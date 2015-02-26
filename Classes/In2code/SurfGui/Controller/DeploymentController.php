<?php
namespace In2code\SurfGui\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use Doctrine\Common\Collections\ArrayCollection;
use In2code\SurfGui\Domain\Factory\Git\Concrete\RepositoryFactory;
use In2code\SurfGui\Domain\Model\Deployment;
use In2code\SurfGui\Domain\Model\Git\GitEntityInterface;
use In2code\SurfGui\Domain\Model\Git\Repository;
use In2code\SurfGui\Domain\Repository\Git\RepositoryRepository;
use In2code\SurfGui\Utility\CliHelper;
use TYPO3\Surf\Domain\Service\DeploymentService;
use In2code\SurfGui\Utility\Shell;
use TYPO3\Fluid\View\StandaloneView;
use TYPO3\Flow\Annotations as Flow;

/**
 * Class DeploymentController
 *
 * @package In2code\SurfGui\Controller
 */
class DeploymentController extends BasicController {

	/**
	 * @var DeploymentService
	 * @Flow\Inject
	 */
	protected $deploymentService;

	/**
	 * @var RepositoryRepository
	 * @Flow\Inject
	 */
	protected $repositoryRepository;

	/**
	 * @var RepositoryFactory
	 * @Flow\Inject
	 */
	protected $repositoryFactory;

	/**
	 * Exception message string
	 */
	const INVALID_DEPLOYMENT = 'The given deployment is not valid';

	/**
	 * Success message after deployment
	 */
	const DEPLOYMENT_SUCCESS = '<span style="background-color: green; color: white; width: 100%; display: block; text-align: center; white-space: nowrap;">SUCCESS</span>';

	/**
	 * Failure message after deployment
	 */
	const DEPLOYMENT_FAILURE = '<span style="background-color: red; color: white; width: 100%; display: block; text-align: center; white-space: nowrap;">FAILURE</span>';

	/**
	 * Reads all Deployments from the default folder.
	 * In2code.SurfGui/Deployment::getDeploymentByName is in the broadest sense a
	 * specific Factory Method to create Deployment Objects from their name
	 *
	 * @return void
	 */
	public function indexAction() {
		$deployments = new ArrayCollection();
		foreach ($this->getDeploymentNames() as $deploymentName) {
			$deployments->add(
				Deployment::getDeploymentByName($deploymentName)
			);
		}
		$this->view->assign('deployments', $deployments);
	}

	/**
	 * @param string $deployment
	 * @param bool $verbose
	 * @param GitEntityInterface $source
	 * @throws \Exception
	 * @return void
	 */
	public function deployAction($deployment, $verbose = FALSE, $source = NULL) {
		if (!$this->isValidDeployment($deployment)) {
			throw new \Exception(self::INVALID_DEPLOYMENT, 1402497907);
		}

		$flowContext = getenv('FLOW_CONTEXT');
		if ($flowContext === FALSE) {
			$flowContext = 'Development';
		}

		$command = 'FLOW_CONTEXT=' . $flowContext . ' ';
		$command .= FLOW_PATH_ROOT . 'flow surf:deploy ' . $deployment;
		if ($verbose) {
			$command .= ' --verbose';
		}
		if ($source) {
			$command .= ' ' . $source->getForArgument();
		}
		$success = FALSE;
		$this->runCommandWithShell($command, function ($line) use (&$success) {
			if (preg_match('/Node ".*" is live!/', $line)) {
				$success = TRUE;
			}
		});
		if ($success) {
			echo self::DEPLOYMENT_SUCCESS;
		} else {
			echo self::DEPLOYMENT_FAILURE;
		}
	}

	/**
	 * @param Repository $repository
	 * @return void
	 */
	public function updateRepositoryAction(Repository $repository) {
		$repository = $this->repositoryFactory->updateRepository($repository);
		$this->repositoryRepository->update($repository);
		$this->persistenceManager->persistAll();
		$this->addFlashMessage('Repository updated');
		$this->redirect('index');
	}

	/**
	 * @param string $deploymentName
	 * @return bool
	 */
	protected function isValidDeployment($deploymentName) {
		return in_array($deploymentName, $this->getDeploymentNames());
	}

	/**
	 * @param string $deployment
	 * @throws \Exception
	 * @return void
	 */
	public function printLogfileAction($deployment) {
		if ($this->isValidDeployment($deployment)) {
			$logFilePathAndFileName = FLOW_PATH_DATA . 'Logs/Surf-' . $deployment . '.log';
			$logFileContent = $this->tailCustom($logFilePathAndFileName,
				(isset($this->settings['logLines']) ? $this->settings['logLines'] : 500));
			$this->view->assign('logFileContent', $logFileContent);
		} else {
			throw new \Exception(self::INVALID_DEPLOYMENT, 1402658894);
		}
	}

	/**
	 * @param $command
	 * @param callable $callback
	 * @return void
	 */
	protected function runCommandWithShell($command, \Closure $callback = NULL) {
		$this->prepareDirectOutput($command);
		$shell = new Shell($command, $this->settings['homePath'], TRUE);
		$shell->run();
		while (($line = $shell->getLine())) {
			if ($callback) {
				$callback($line);
			}
			echo CliHelper::ansiToHtmlString($line);
			$this->flushBuffer();
		}
	}

	/**
	 * @param string $command
	 * @return void
	 */
	protected function prepareDirectOutput($command) {
		$standaloneView = new StandaloneView($this->request);
		$standaloneView->setLayoutRootPath(
			FLOW_PATH_PACKAGES . 'Application/In2code.SurfGui/Resources/Private/Layouts');
		$standaloneView->setTemplatePathAndFilename(
			FLOW_PATH_PACKAGES . 'Application/In2code.SurfGui/Resources/Private/Templates/Standalone/Console.html');
		$standaloneView->assign('command', $command);
		echo $standaloneView->render();

		$this->enableOutputBuffering();
	}

	/**
	 * @return void
	 */
	protected function enableOutputBuffering() {
		if (ob_get_level() == 0) {
			ob_start();
		}
	}

	/**
	 * @return void
	 */
	protected function flushBuffer() {
		ob_flush();
		flush();
	}

	/**
	 * @link https://gist.github.com/lorenzos/1711e81a9162320fde20
	 * Original modified
	 * @param string $filePath
	 * @param int $lines
	 * @return bool|string
	 */
	protected function tailCustom($filePath, $lines = 1) {
		$f = fopen($filePath, 'rb');
		if ($f === FALSE) {
			return FALSE;
		}
		$buffer = ($lines < 10 ? 512 : 4096);
		$buffer = ($lines < 2 ? 64 : $buffer);
		fseek($f, -1, SEEK_END);
		if (fread($f, 1) != "\n") {
			$lines -= 1;
		}
		$output = '';
		while (ftell($f) > 0 && $lines >= 0) {
			$seek = min(ftell($f), $buffer);
			fseek($f, - $seek, SEEK_CUR);
			$output = ($chunk = fread($f, $seek)) . $output;
			fseek($f, - mb_strlen($chunk, '8bit'), SEEK_CUR);
			$lines -= substr_count($chunk, PHP_EOL);
		}
		while ($lines++ < 0) {
			$output = substr($output, strpos($output, PHP_EOL) + 1);
		}
		fclose($f);
		return $output;
	}

	/**
	 * @return array
	 */
	protected function getDeploymentNames() {
		return $this->deploymentService->getDeploymentNames();
	}
}

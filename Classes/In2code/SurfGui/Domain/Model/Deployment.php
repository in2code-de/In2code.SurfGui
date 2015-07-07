<?php
namespace In2code\SurfGui\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use In2code\SurfGui\Domain\Factory\ApplicationFactory;
use In2code\SurfGui\Domain\Factory\Git\Concrete\RepositoryFactory;
use In2code\SurfGui\Domain\Repository\Git\RepositoryRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Surf\Domain\Service\DeploymentService;

/**
 * Class Deployment
 *
 * @package In2code\SurfGui\Domain\Model
 */
class Deployment extends \TYPO3\Surf\Domain\Model\Deployment implements DeploymentInterface {

	const LIVE = 'live';

	/**
	 * @var string
	 */
	protected $comment = '';

	/**
	 * @var string
	 */
	protected $customName = '';

	/**
	 * @var
	 */
	protected $system;

	/**
	 * @var ApplicationFactory
	 * @Flow\Inject
	 */
	protected $applicationFactory;

	/**
	 * @var RepositoryRepository
	 * @Flow\Inject
	 */
	protected $repositoryRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @var RepositoryFactory
	 * @Flow\Inject
	 */
	protected $repositoryFactory;

	/**
	 * If set to TRUE this deployment won't be displayed in the GUI
	 *
	 * @var bool
	 */
	protected $disabledInWeb = FALSE;

	/**
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @param string $comment
	 * @return $this
	 */
	public function setComment($comment) {
		$this->comment = $comment;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCustomName() {
		return $this->customName;
	}

	/**
	 * @param string $customName
	 * @return $this
	 */
	public function setCustomName($customName) {
		$this->customName = $customName;
		return $this;
	}

	/**
	 * @param array $applications
	 * @return $this
	 */
	public function setApplications(array $applications) {
		$this->applications = array();
		/** @var Application $application */
		foreach ($applications as $application) {
			$this->applications[$application->getName()] = $this->convertApplicationToSurfGuiApplication($application);
		}
		return $this;
	}

	/**
	 * @param \TYPO3\Surf\Domain\Model\Application $application
	 * @return $this
	 */
	public function addApplication(\TYPO3\Surf\Domain\Model\Application $application) {
		$application = $this->convertApplicationToSurfGuiApplication($application);

		$repositoryUrl = $application->getOption('repositoryUrl');
		// get the repository, if there is none, make a new one from the repo-url
		$repository = $this->repositoryRepository->findOneByUrl($repositoryUrl);
		if ($repository === NULL) {
			// the factory makes a fresh instance and
			// retrieves all branches an tags for the repository
			$repository = $this->repositoryFactory->makeInstance(array('repositoryUrl' => $repositoryUrl));
			$this->repositoryRepository->add($repository);
			$this->persistenceManager->persistAll();
		}
		$application->setRepository($repository);

		$this->applications[$application->getName()] = $application;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSystem() {
		return $this->system;
	}

	/**
	 * @param mixed $system
	 * @return $this
	 */
	public function setSystem($system) {
		$this->system = $system;
		return $this;
	}

	/**
	 * @param \TYPO3\Surf\Domain\Model\Application $application
	 * @return \In2code\SurfGui\Domain\Model\Application
	 */
	protected function convertApplicationToSurfGuiApplication(\TYPO3\Surf\Domain\Model\Application $application) {
		return $this->applicationFactory->convert($application, '\\In2code\\SurfGui\\Domain\\Model\\Application');
	}

	/**
	 * @param $deploymentName
	 * @return Deployment
	 */
	static public function getDeploymentByName($deploymentName) {
		$deploymentService = new DeploymentService();
		$deploymentPathAndFilename = $deploymentService->getDeploymentsBasePath() . '/' . $deploymentName . '.php';
		$deployment = new Deployment($deploymentName);
		require_once($deploymentPathAndFilename);
		return $deployment;
	}

	/**
	 * @return boolean
	 */
	public function isDisabledInWeb() {
		return $this->disabledInWeb;
	}

	/**
	 * @param boolean $disabledInWeb
	 * @return Deployment
	 */
	public function setDisabledInWeb($disabledInWeb) {
		$this->disabledInWeb = $disabledInWeb;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNameForDisplay() {
		if (!empty($this->customName)) {
			return $this->customName;
		}
		return $this->name;
	}
}

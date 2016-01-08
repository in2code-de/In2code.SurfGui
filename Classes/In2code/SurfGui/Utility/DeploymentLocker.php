<?php
namespace In2code\SurfGui\Utility;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use TYPO3\Flow\Utility\Files;
use TYPO3\Surf\Domain\Model\Deployment;

/**
 * Class DeploymentLocker
 *
 * @package In2code\SurfGui\Utility
 */
class DeploymentLocker {

	/**
	 * @var Deployment
	 */
	protected $deployment = NULL;

	/**
	 * Indicates if the LockFile should be removed after deployment
	 *
	 * @var bool
	 */
	protected $isLockerForRunningDeployment = FALSE;

	/**
	 * @param Deployment $deployment
	 * @return void
	 * @throws \Exception
	 */
	public function checkAndLockDeployment(Deployment $deployment) {
		if ($deployment instanceof Deployment && !$deployment instanceof \In2code\SurfGui\Domain\Model\Deployment) {
			$this->deployment = $deployment;
			if ($this->lockFileExists($deployment)) {
				throw new \Exception('This Deployment ist currently running!', 1421146734);
			}
			$this->createLockFile($deployment);
		}
	}

	/**
	 * Deletes the LockFile if this Instance
	 * of Locker was created for a valid deployment
	 */
	public function __destruct() {
		if ($this->deployment instanceof Deployment && !$this->deployment instanceof \In2code\SurfGui\Domain\Model\Deployment) {
			if ($this->isLockerForRunningDeployment) {
				unlink($this->getLockFilePath() . $this->getLockFileName($this->deployment));
				echo 'LOCK FILE REMOVED' . PHP_EOL;
			}
		}
	}

	/**
	 * @param Deployment $deployment
	 * @return bool
	 */
	protected function lockFileExists(Deployment $deployment) {
		return is_file($this->getLockFilePath() . $this->getLockFileName($deployment));
	}

	/**
	 * @param Deployment $deployment
	 * @return void
	 * @throws \TYPO3\Flow\Utility\Exception
	 */
	public function createLockFile(Deployment $deployment) {
		$this->isLockerForRunningDeployment = TRUE;

		// ensure the lock directory exists
		Files::createDirectoryRecursively($this->getLockFilePath());

		// gather all information for the lock file
		$lockInformation = array(
			'Deployment' => serialize($deployment),
			'Begin' => time(),
		);

		file_put_contents($this->getLockFilePath() . $this->getLockFileName($deployment), json_encode($lockInformation));
		echo 'LOCK FILE CREATED' . PHP_EOL;
	}

	/**
	 * @return string
	 */
	public function getLockFilePath() {
		return FLOW_PATH_DATA . 'Locks/Deployment/';
	}

	/**
	 * @param Deployment $deployment
	 * @return string
	 */
	public function getLockFileName(Deployment $deployment) {
		return str_replace(' ', '_', $deployment->getName());
	}
}

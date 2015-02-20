<?php
namespace In2code\SurfGui\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use In2code\SurfGui\Domain\Model\Git\Repository;

/**
 * Class Application
 *
 * @package In2code\SurfGui\Domain\Model
 */
class Application extends \TYPO3\Surf\Domain\Model\Application {

	/**
	 * @var Repository
	 */
	protected $repository;

	/**
	 * @return Repository
	 */
	public function getRepository() {
		return $this->repository;
	}

	/**
	 * @param Repository $repository
	 * @return $this
	 */
	public function setRepository(Repository $repository) {
		$this->repository = $repository;
		return $this;
	}
}

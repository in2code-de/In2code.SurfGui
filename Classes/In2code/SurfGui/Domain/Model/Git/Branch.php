<?php
namespace In2code\SurfGui\Domain\Model\Git;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * class Branch
 *
 * @Flow\ValueObject
 */
class Branch implements GitEntityInterface {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @param $name
	 * @return Branch
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return 'Branch: ' . $this->name;
	}

	/**
	 * Return the identifying string of the object,
	 * which will be passed to the command line
	 *
	 * @return string
	 */
	public function getForArgument() {
		return '--branch=' . $this->name;
	}
}

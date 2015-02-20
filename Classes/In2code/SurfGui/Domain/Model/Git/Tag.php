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
 * class Tag
 *
 * @Flow\ValueObject
 */
class Tag implements GitEntityInterface {

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @param $version
	 * @return Tag
	 */
	public function __construct($version) {
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return 'Tag: ' . $this->version;
	}

	/**
	 * Return the identifying string of the object,
	 * which will be passed to the command line
	 *
	 * @return string
	 */
	public function getForArgument() {
		return '--tag=' . $this->version;
	}
}

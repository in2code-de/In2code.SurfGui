<?php
namespace In2code\SurfGui\Domain\Model\Git;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

/**
 * Interface GitEntityInterface
 *
 * @package In2code\SurfGui\Domain\Model\Git
 */
interface GitEntityInterface
{
    /**
     * Return the identifying string of the object,
     * which will be passed to the command line
     *
     * @return string
     */
    public function getForArgument();

    /**
     * Returns the identifying attribute
     *
     * @return string
     */
    public function __toString();
}

<?php
namespace In2code\SurfGui\Domain\Repository\Git;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * class RepositoryRepository
 *
 * @Flow\Scope("singleton")
 */
class RepositoryRepository extends Repository
{
    /**
     * I know there is a wildcard findBy* Method using __call()
     * but this implementing the concrete Method improves hinting in any IDE
     * and seems to be more performing
     *
     * @param string $url
     * @return \In2code\SurfGui\Domain\Model\Git\Repository
     */
    public function findOneByUrl($url)
    {
        $query = $this->createQuery();
        return $query->matching($query->equals('url', $url))
                     ->execute()
                     ->getFirst();
    }
}

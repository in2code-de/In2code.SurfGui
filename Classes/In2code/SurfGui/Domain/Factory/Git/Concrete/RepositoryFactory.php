<?php
namespace In2code\SurfGui\Domain\Factory\Git\Concrete;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use Doctrine\Common\Collections\ArrayCollection;
use In2code\SurfGui\Domain\Model\Git\Branch;
use In2code\SurfGui\Domain\Model\Git\Repository;
use In2code\SurfGui\Domain\Model\Git\Tag;
use In2code\SurfGui\Utility\GitHelper;
use TYPO3\Flow\Annotations as Flow;

/**
 * Class RepositoryFactory
 *
 * @package In2code\SurfGui\Domain\Factory\Git\Concrete
 */
class RepositoryFactory {

	/**
	 * @var GitHelper
	 * @Flow\Inject
	 */
	protected $gitHelper;

	/**
	 * @param array $arguments
	 * @return Repository
	 */
	public function makeInstance(array $arguments) {
		if (!isset($arguments['repositoryUrl'])) {
			return NULL;
		}
		$repositoryUrl = $arguments['repositoryUrl'];
		$repository = new Repository($repositoryUrl);

		foreach ($this->gitHelper->getTagsFromRepositoryUrl($repositoryUrl) as $tagInformation) {
			$repository->addTag(new Tag($tagInformation[1], $tagInformation[0]));
		}
		foreach ($this->gitHelper->getBranchesFromRepositoryUrl($repositoryUrl) as $branchInformation) {
			$repository->addBranch(new Branch($branchInformation[1], $branchInformation[0]));
		}

		return $repository;
	}

	/**
	 * @param Repository $repository
	 * @return Repository
	 */
	public function updateRepository(Repository $repository) {
		$repositoryUrl = $repository->getUrl();

		$repository->setTags(new ArrayCollection());
		foreach ($this->gitHelper->getTagsFromRepositoryUrl($repositoryUrl) as $tagInformation) {
			$repository->addTag(new Tag($tagInformation[1], $tagInformation[0]));
		}

		$repository->setBranches(new ArrayCollection());
		foreach ($this->gitHelper->getBranchesFromRepositoryUrl($repositoryUrl) as $branchInformation) {
			$repository->addBranch(new Branch($branchInformation[1], $branchInformation[0]));
		}

		return $repository;
	}
}

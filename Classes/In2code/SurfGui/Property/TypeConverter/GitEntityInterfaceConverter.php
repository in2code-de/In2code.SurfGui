<?php
namespace In2code\SurfGui\Property\TypeConverter;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use In2code\SurfGui\Domain\Repository\Git\BranchRepository;
use In2code\SurfGui\Domain\Repository\Git\TagRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Error;
use TYPO3\Flow\Property\PropertyMappingConfigurationInterface;
use TYPO3\Flow\Property\TypeConverter\AbstractTypeConverter;

/**
 * Class AbstractGitEntityInterfaceConverter
 *
 * @package In2code\SurfGui\Property\TypeConverter
 */
class GitEntityInterfaceConverter extends AbstractTypeConverter
{
    /**
     * @var int
     */
    protected $priority = 100;

    /**
     * @var array
     */
    protected $sourceTypes = array('string');

    /**
     * @var string
     */
    protected $targetType = 'In2code\SurfGui\Domain\Model\Git\GitEntityInterface';

    /**
     * @var BranchRepository
     * @Flow\Inject
     */
    protected $branchRepository;

    /**
     * @var TagRepository
     * @Flow\Inject
     */
    protected $tagRepository;

    /**
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param PropertyMappingConfigurationInterface $configuration
     * @return object|Error
     */
    public function convertFrom(
        $source,
        $targetType,
        array $convertedChildProperties = array(),
        PropertyMappingConfigurationInterface $configuration = null
    ) {
        $gitSource = $this->tagRepository->findByIdentifier($source);
        if (!is_object($gitSource)) {
            $gitSource = $this->branchRepository->findByIdentifier($source);
        }
        if (!is_object($gitSource)) {
            return new Error('Could not find a matching git entity for ' . $source);
        }
        return $gitSource;
    }
}

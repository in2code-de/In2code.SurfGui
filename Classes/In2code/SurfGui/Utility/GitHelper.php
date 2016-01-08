<?php
namespace In2code\SurfGui\Utility;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * class GitHelper
 *
 * @Flow\Scope("singleton")
 */
class GitHelper
{
    const GIT_TAG = 'tags';
    const GIT_BRANCH = 'heads';

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var array
     */
    protected $errorMessages = array(
        128 => 'Could not connect to remote repository. Please assure that this server can connect to the Repository. Maybe add the deployment key to the repository',
    );

    /**
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param string $repositoryUrl
     * @return array
     */
    public function getBranchesFromRepositoryUrl($repositoryUrl)
    {
        return $this->getGitEntitiesFromRepositoryUrl(
            self::GIT_BRANCH,
            $repositoryUrl,
            '/(.*)' . chr(9) . 'refs\/heads\/(.*)/'
        );
    }

    /**
     * @param string $repositoryUrl
     * @return array
     */
    public function getTagsFromRepositoryUrl($repositoryUrl)
    {
        return $this->getGitEntitiesFromRepositoryUrl(
            self::GIT_TAG,
            $repositoryUrl,
            '/(.*)' . chr(9) . 'refs\/tags\/(.*)\^{}/',
            array('*{}')
        );
    }

    /**
     * @param string $type
     * @param string $repositoryUrl
     * @param string $filterRegex
     * @param array $additionalArguments
     * @return array
     */
    protected function getGitEntitiesFromRepositoryUrl(
        $type,
        $repositoryUrl,
        $filterRegex,
        $additionalArguments = array()
    ) {
        $arguments = array(
            'command' => 'ls-remote',
            'options' => array(
                '--' . $type,
            ),
            'arguments' => array(
                $repositoryUrl,
            ),
        );
        $arguments['arguments'] = array_merge($arguments['arguments'], $additionalArguments);
        $result = $this->executeCommandFromArguments(
            $arguments,
            function ($branch) use ($filterRegex) {
                if (preg_match($filterRegex, $branch, $output) === 1 && count($output) === 3) {
                    return array(
                        $output[1],
                        $output[2],
                    );
                }
            }
        );
        return $result;
    }

    /**
     * @param array $arguments
     * @param \Closure $mappingCallback
     * @return array
     * @throws \Exception
     */
    protected function executeCommandFromArguments(array $arguments, \Closure $mappingCallback)
    {
        $command = $this->buildGitCommandWithArguments($arguments);
        try {
            $result = $this->executeCommandWithShell($command);
        } catch (\Exception $exception) {
            if (isset($this->errorMessages[$exception->getCode()])) {
                throw new \Exception($this->errorMessages[$exception->getCode()], 1408009604);
            } else {
                throw $exception;
            }
        }

        if (is_callable($mappingCallback)) {
            return array_map($mappingCallback, $result);
        }
        return $result;
    }

    /**
     * @param array $arguments
     * @return string
     */
    protected function buildGitCommandWithArguments(array $arguments)
    {
        $command = '';
        $command .= $this->settings['gitExecutable'] . ' ';
        $command .= $arguments['command'] . ' ';
        if (isset($arguments['options'])) {
            $command .= implode(' ', $arguments['options']) . ' ';
        }
        if (isset($arguments['arguments'])) {
            $command .= implode(' ', $arguments['arguments']) . ' ';
        }
        return trim($command);
    }

    /**
     * @param string $command
     * @return array
     * @throws \Exception
     */
    protected function executeCommandWithShell($command)
    {
        $output = array();
        $shell = new Shell($command, $this->settings['homePath'], true);
        $shell->run();
        while (($line = $shell->getLine())) {
            $output[] = $line;
        };
        if (($returnInteger = $shell->tearDown()) > 0) {
            throw new \Exception(
                'The command "' . htmlspecialchars($command) . '" did not execute successfully',
                $returnInteger
            );
        }
        return $output;
    }
}

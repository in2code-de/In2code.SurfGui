<?php
namespace In2code\SurfGui\Utility;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

/**
 * Class SurfCliArgument
 *
 * @package In2code\SurfGui\Utility
 */
class SurfCliArgument
{
    /**
     * @return string
     */
    static public function getGitSource()
    {
        if (FLOW_SAPITYPE === 'CLI') {
            $type = 'branch';
            $source = 'master';
            foreach ($_SERVER['argv'] as $commandLineArgument) {
                if (substr($commandLineArgument, 0, 6) === '--tag=') {
                    $source = substr($commandLineArgument, 6);
                    $type = 'tag';
                    break;
                }
                if (substr($commandLineArgument, 0, 7) === '--sha1=') {
                    $source = substr($commandLineArgument, 7);
                    $type = 'sha1';
                    break;
                }
                if (substr($commandLineArgument, 0, 9) === '--branch=') {
                    $source = substr($commandLineArgument, 9);
                    $type = 'branch';
                    break;
                }
            }
            return array(
                $type => $source,
            );
        }
        return false;
    }
}

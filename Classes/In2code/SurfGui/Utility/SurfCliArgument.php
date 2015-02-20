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
class SurfCliArgument {

	/**
	 * @param string $for if there are more than one application
	 *      you can identify the branch by this string
	 * @return string
	 */
	static public function getBranch($for = '') {
		$branch = '';
		if (FLOW_SAPITYPE === 'CLI') {
			if ($for) {
				foreach ($_SERVER['argv'] as $commandLineArgument) {
					if (substr($commandLineArgument, 0, 10 + strlen($for)) === '--branch-' . $for . '=') {
						$branch = substr($commandLineArgument, 9);
					}
				}
			} else {
				foreach ($_SERVER['argv'] as $commandLineArgument) {
					if (substr($commandLineArgument, 0, 9) === '--branch=') {
						$branch = substr($commandLineArgument, 9);
					}
				}
			}
		}
		return $branch;
	}

	/**
	 * @param string $for
	 * @return string
	 */
	static public function getGitSource($for = '') {
		if (FLOW_SAPITYPE === 'CLI') {
			$type = 'branch';
			$source = '';
			foreach ($_SERVER['argv'] as $commandLineArgument) {
				if ($for) {
					if (substr($commandLineArgument, 0, 7 + strlen($for)) === '--tag-' . $for . '=') {
						$source = substr($commandLineArgument, 7 + strlen($for));
						$type = 'tag';
						break;
					}
				} else {
					if (substr($commandLineArgument, 0, 6) === '--tag=') {
						$source = substr($commandLineArgument, 6 + strlen($for));
						$type = 'tag';
						break;
					}
				}
			}
			if ($type === 'branch') {
				$source = self::getBranch($for);
			}
			return array(
				$type => $source
			);
		}
		return FALSE;
	}
}

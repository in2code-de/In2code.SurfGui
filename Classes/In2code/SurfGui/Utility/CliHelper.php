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
 * Class CliHelper
 *
 * @package In2code\SurfGui\Utility
 */
class CliHelper {

	/**
	 * @var array $textStyle
	 */
	static public $textStyle = array(
		'0' => 'font-weight: normal;',
		'1' => 'font-weight: bold;',
		'4' => 'text-decoration: underline;',
		// reverse not supported
		'7' => '',
	);

	/**
	 * @var array $textColors
	 */
	static public $textColors = array(
		'30' => 'color: black;',
		'31' => 'color: red;',
		'32' => 'color: green;',
		'33' => 'color: yellow;',
		'34' => 'color: blue;',
		'35' => 'color: purple;',
		'36' => 'color: cyan;',
		'37' => 'color: white;',
	);

	/**
	 * @var array $backgroundColors
	 */
	static public $backgroundColors = array(
		'40' => 'background-color: black;',
		'41' => 'background-color: red;',
		'42' => 'background-color: green;',
		'43' => 'background-color: yellow;',
		'44' => 'background-color: blue;',
		'45' => 'background-color: magenta;',
		'46' => 'background-color: cyan;',
		'47' => 'background-color: white;',
	);

	static public $outputPattern = '<span style="%s">%s</span>';

	/**
	 * @param string $string
	 * @return string
	 */
	public static function ansiToHtmlString($string) {
		// first part of ansi code style + color
		if (preg_match('/\[(\d+);(\d+)m(.*)\[(0)m/sm', $string, $matches)) {
			$style = self::$textStyle[$matches[1]] . self::$textColors[$matches[2]];
			// if there is a second ansi part it is the background color
			if (preg_match('/\[(\d+);(\d+)m(.*)(?:\[(0)m)?/sm', $matches[3], $innerAnsiTags)) {
				$style = self::$textStyle[$innerAnsiTags[1]] . self::$textColors[$innerAnsiTags[2]];
				// overwrite string without the inner tag
				$matches[3] = $innerAnsiTags[3];
			} elseif (preg_match('/\[(\d+)m(.*)(?:\[(0)m)?/sm', $matches[3], $innerAnsiTags)) {
				$style .= self::$backgroundColors[$innerAnsiTags[1]];
				// overwrite string without the inner tag
				$matches[3] = $innerAnsiTags[2];
			}
			$string = sprintf(self::$outputPattern, $style, $matches[3] . '<br/>');
		}
		return $string;
	}
}

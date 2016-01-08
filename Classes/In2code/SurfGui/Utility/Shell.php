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
 * Class Shell
 *
 * Usage:
 *        $shell = new Shell($command);
 *        $shell->run();
 *        while (($line = $shell->getLine())) {
 *            echo $line;
 *        }
 *
 * @package In2code\SurfGui\Utility
 */
class Shell
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $homePath;

    /**
     * @var resource
     */
    protected $process;

    /**
     * @var bool
     */
    protected $redirectStdErr = false;

    /**
     * @param string $command
     * @param string $homePath
     * @param bool $redirectStdErr
     */
    public function __construct($command, $homePath = '', $redirectStdErr = false)
    {
        $this->command = $command;
        $this->homePath = $homePath;
        $this->redirectStdErr = $redirectStdErr;
    }

    /**
     * @return void
     */
    public function run()
    {
        $additionalArguments = $this->redirectStdErr ? ' 2>&1' : '';
        $homePath = $this->homePath ? 'HOME=' . $this->homePath . ' ' : '';
        $this->process = popen($homePath . $this->command . $additionalArguments, 'r');
    }

    /**
     * Returns a Line from the running Command
     * If all Lines are read, this will return FALSE
     *
     * @return bool|string
     */
    public function getLine()
    {
        if (!feof($this->process)) {
            return fgets($this->process);
        } else {
            $this->tearDown();
        }
        return false;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->tearDown();
    }

    /**
     * @return void
     */
    public function __sleep()
    {
        $this->tearDown();
    }

    /**
     * @return int
     */
    public function tearDown()
    {
        $returnInteger = 1;
        if (is_resource($this->process)) {
            $returnInteger = pclose($this->process);
        }
        return $returnInteger;
    }
}

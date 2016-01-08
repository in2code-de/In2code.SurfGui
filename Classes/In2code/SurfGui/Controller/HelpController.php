<?php
namespace In2code\SurfGui\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * THIS CLASS IS "WORK IN PROGRESS" AND CURRENTLY UNUSED
 *
 * Class HelpController
 *
 * @package In2code\SurfGui\Controller
 */
class HelpController extends BasicController
{
    /**
     * @var string
     */
    protected $documentationPath;

    /**
     * @var string
     */
    protected $languageShortName;

    /**
     * @var string
     */
    protected $languageFolder;

    /**
     * @return HelpController
     */
    public function __construct()
    {
        $this->documentationPath = FLOW_PATH_ROOT . 'Packages/Application/In2code.SurfGui/Documentation/';
        require_once(FLOW_PATH_ROOT
                     . 'Packages/Application/In2code.SurfGui/Resources/Private/Php/php-restructuredtext-0.1/rst.php');
    }

    protected function initializeAction()
    {
        parent::initializeAction();
        $this->languageFolder = $this->i18nService->getConfiguration()
                                                  ->getCurrentLocale()
                                                  ->getLanguage();
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $document = new \ezcDocumentRst();
        $document->loadFile('../tutorial.txt');
        $content = $document->getAsXhtml();
        $this->view->assign('content', $content);
    }

    /**
     * @param string $fileName
     * @return string
     * @throws \Exception
     */
    protected function getLocalizedDocumentationByFilename($fileName)
    {
        $fileName .= '.rst';
        $filePath = $this->documentationPath . $this->languageFolder . '/' . $fileName;
        if (file_exists($filePath)) {
            $fileRstContent = file_get_contents($filePath);
        } else {
            $filePath = $this->documentationPath . 'en/' . $fileName;
            if (file_exists($filePath)) {
                $fileRstContent = file_get_contents($filePath);
            } else {
                throw new \Exception('You requested a Documentation File that does not exist', 1403889278);
            }
        }
        return RST($fileRstContent);
    }
}

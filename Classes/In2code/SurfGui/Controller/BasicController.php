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
use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Flow\Mvc\View\ViewInterface;

/**
 * Class BasicController
 *
 * @package In2code\SurfGui\Controller
 */
class BasicController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $i18nService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Detector
	 */
	protected $i18nDetector;

	/**
	 * @return void
	 */
	protected function initializeAction() {
		parent::initializeAction();
		$acceptLanguageHeader = $this->request->getHttpRequest()->getHeaders()->get('Accept-Language');
		$locale = $this->i18nDetector->detectLocaleFromHttpHeader($acceptLanguageHeader);
		$this->i18nService->getConfiguration()->setCurrentLocale($locale);
	}

	/**
	 * @param \TYPO3\Flow\Mvc\View\ViewInterface $view
	 * @return void
	 */
	protected function initializeView(ViewInterface $view) {
		parent::initializeView($view);
		$view->assign('lang', $this->i18nService->getConfiguration()->getCurrentLocale()->getLanguage());
	}
}

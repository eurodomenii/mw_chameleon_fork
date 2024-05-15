<?php
/**
 * File holding the SkinChameleon1 class
 *
 * This file is part of the MediaWiki skin Chameleon1.
 *
 * @copyright 2013 - 2019, Stephan Gambke
 * @license   GPL-3.0-or-later
 *
 * The Chameleon1 skin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * The Chameleon1 skin is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup Skins
 */

namespace Skins\Chameleon1;

use Bootstrap\BootstrapManager;
use FileFetcher\SimpleFileFetcher;
use MediaWiki\MediaWikiServices;
use OutputPage;
use QuickTemplate;
use ResourceLoader;
use Sanitizer;
use Skins\Chameleon1\Hooks\ResourceLoaderRegisterModules;
use Skins\Chameleon1\Hooks\SetupAfterCache;
use SkinTemplate;
use Hooks;

/**
 * SkinTemplate class for the Chameleon1 skin
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 */
class Chameleon1 extends SkinTemplate {

	public $skinname = 'chameleon1';
	public $stylename = 'chameleon1';
	public $template = '\Skins\Chameleon1\Chameleon1Template';

	private $componentFactory;

	public const HOOK_GET_LAYOUT_XML = 'Chameleon1GetLayoutXml';

	/**
	 * @throws \Exception
	 */
	public static function init() {
		/**
		 * Using callbacks for hook registration
		 *
		 * The hook registry should contain as less knowledge about a process as
		 * necessary therefore a callback is used as Factory/Builder that instantiates
		 * a business / domain object.
		 *
		 * GLOBAL state should be encapsulated by the callback and not leaked into
		 * a instantiated class
		 */

		/**
		 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforeInitialize
		 */
		$GLOBALS[ 'wgHooks' ][ 'SetupAfterCache' ][ ] = function () {
			$setupAfterCache = new SetupAfterCache(
				BootstrapManager::getInstance(),
				$GLOBALS,
				$GLOBALS['wgRequest']
			);

			$setupAfterCache->process();
		};

		$GLOBALS[ 'wgHooks' ][ 'ResourceLoaderRegisterModules' ][ ] = function ( ResourceLoader $rl ) {
			( new ResourceLoaderRegisterModules( $rl, $GLOBALS ) )->process();
		};

		// set default skin layout
		if ( DIRECTORY_SEPARATOR === '/' && $GLOBALS[ 'egChameleon1LayoutFile' ][0] !== '/' ) {
			$GLOBALS[ 'egChameleon1LayoutFile' ] = $GLOBALS[ 'wgStyleDirectory' ] . '/chameleon1/' .
				$GLOBALS[ 'egChameleon1LayoutFile' ];
		}
	}

	/**
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		// Enable responsive behaviour on mobile browsers
		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no' );
	}

	/**
	 * @inheritDoc
	 */
	protected function prepareQuickTemplate() {
		$tpl = parent::prepareQuickTemplate();
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer()->run(
			'Chameleon1SkinTemplateOutputPageBeforeExec',
			[ $this, $tpl ]
		);
		return $tpl;
	}

	/**
	 * @return QuickTemplate
	 * @throws \MWException
	 */
	protected function setupTemplateForOutput() {
		$template = parent::setupTemplateForOutput();

		$this->getComponentFactory()->setSkinTemplate( $template );

		$template->set( 'skin', $this );
		$this->addSkinModulesToOutput();

		return $template;
	}

	public function getComponentFactory(): ComponentFactory {
		if ( !isset( $this->componentFactory ) ) {
			$this->componentFactory = new ComponentFactory(
				$this->getLayoutFilePath(),
				MediaWikiServices::getInstance()->getHookContainer(),
				new SimpleFileFetcher()
			);
		}

		return $this->componentFactory;
	}

	/**
	 * @throws \MWException
	 */
	public function addSkinModulesToOutput() {
		// load Bootstrap scripts
		$output = $this->getOutput();
		$output->addModules( [ 'ext.bootstrap.scripts' ] );
		$output->addModules(
			$this->getComponentFactory()->getRootComponent()->getResourceLoaderModules() );
	}

	/**
	 * @param \Title $title
	 * @return string
	 */
	public function getPageClasses( $title ) {
		$layoutFilePath = $this->getLayoutFilePath();
		$layoutName = Sanitizer::escapeClass( 'layout-' . basename( $layoutFilePath, '.xml' ) );

		// When viewing a page revision with a non-existent namespace, Skin::getPageClasses() will fail.
		if ( !MediaWikiServices::getInstance()->getNamespaceInfo()->exists( $title->getNamespace() ) ) {
			return $layoutName;
		}

		return implode( ' ', [ parent::getPageClasses( $title ), $layoutName ] );
	}

	/**
	 * Template method that can be overridden by subclasses
	 * @return string Path to layout file
	 */
	protected function getLayoutFilePath() {
		return $GLOBALS[ 'egChameleon1LayoutFile' ];
	}

	/**
	 * Template method that can be overridden by subclasses
	 * @return string Path to theme file
	 */
	protected function getThemeFilePath() {
		return $GLOBALS[ 'egChameleon1ThemeFile' ];
	}
}

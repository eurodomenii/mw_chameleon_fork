<?php
/**
 * File containing the BeforeInitialize class
 *
 * This file is part of the MediaWiki skin Chameleon2.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
 * @license   GPL-3.0-or-later
 *
 * The Chameleon2 skin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * The Chameleon2 skin is distributed in the hope that it will be useful, but
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

namespace Skins\Chameleon2\Hooks;

use Bootstrap\BootstrapManager2;
use MediaWiki\MediaWikiServices;
use RuntimeException;
use Skins\Chameleon2\Chameleon2;

/**
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SetupAfterCache
 *
 * @since 1.0
 *
 * @author mwjames
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 */
class SetupAfterCache {

	private BootstrapManager2 $bootstrapManager;
	private array $configuration;
	private \WebRequest $request;

	public function __construct( BootstrapManager2 $bootstrapManager, array $configuration, \WebRequest $request ) {
		$this->bootstrapManager = $bootstrapManager;
		$this->configuration = $configuration;
		$this->request = $request;
	}

	public function process(): self {
		$this->setInstallPaths();
		$this->addLateSettings();
		$this->registerCommonBootstrapModules();
		$this->registerExternalScssModules();
		$this->registerExternalStyleVariables();

		return $this;
	}

	/**
	 * Set local and remote base path of the Chameleon2 skin
	 */
	protected function setInstallPaths(): void {
		$GLOBALS[ 'chameleon2LocalPath' ] = $this->configuration['wgStyleDirectory'] . '/chameleon2';
		$GLOBALS[ 'chameleon2RemotePath' ] = $this->configuration['wgStylePath'] . '/chameleon2';
	}

	protected function addLateSettings() {
		$this->registerSkinWithMW();
		$this->addChameleon2ToVisualEditorSupportedSkins();
		//vanhack eliminare scripturi sticky js duplicate, oricum nu am nevoie de ele.
		//$this->addResourceModules();
		$this->setLayoutFile();
	}

	protected function registerCommonBootstrapModules(): void {
		$this->bootstrapManager->addAllBootstrapModules();

		if ( !empty( $this->configuration[ 'egChameleon2ThemeFile' ] ) ) {
			$this->bootstrapManager->addStyleFile(
				$this->configuration[ 'egChameleon2ThemeFile' ],
				'beforeVariables'
			);
		}

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon2LocalPath' ] . '/resources/styles/_variables.scss',
			'variables'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon2LocalPath' ] . '/resources/fontawesome/scss/fontawesome.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon2LocalPath' ] . 	'/resources/fontawesome/scss/fa-solid.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon2LocalPath' ] . '/resources/fontawesome/scss/fa-regular.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon2LocalPath' ] . '/resources/fontawesome/scss/fa-brands.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon2LocalPath' ] . '/resources/styles/chameleon2.scss'
		);

		$this->bootstrapManager->setScssVariable(
			'fa-font-path',
			$GLOBALS[ 'chameleon2RemotePath' ] . '/resources/fontawesome/webfonts'
		);
	}

	protected function registerExternalScssModules() {
		if ( $this->hasConfigurationOfTypeArray( 'egChameleon2ExternalStyleModules' ) ) {
			foreach ( $this->configuration[ 'egChameleon2ExternalStyleModules' ] as $localFile => $position ) {
				$config = $this->matchAssociativeElement( $localFile, $position );
				$config[0] = $this->isReadableFile( $config[0] );

				$this->bootstrapManager->addStyleFile( ...$config );
			}
		}
	}

	protected function registerExternalStyleVariables() {
		if ( $this->hasConfigurationOfTypeArray( 'egChameleon2ExternalStyleVariables' ) ) {

			foreach ( $this->configuration[ 'egChameleon2ExternalStyleVariables' ] as $key => $value ) {
				$this->bootstrapManager->setScssVariable( $key, $value );
			}
		}
	}

	/**
	 * @param string $id
	 */
	private function hasConfiguration( $id ): bool {
		return isset( $this->configuration[ $id ] );
	}

	/**
	 * @param string $id
	 */
	private function hasConfigurationOfTypeArray( $id ): bool {
		return $this->hasConfiguration( $id ) && is_array( $this->configuration[ $id ] );
	}

	/**
	 * @param mixed $localFile
	 * @param mixed $position
	 */
	private function matchAssociativeElement( $localFile, $position ): array {
		if ( is_int( $localFile ) ) {
			return [ $position ];
		}

		return [ $localFile, $position ];
	}

	/**
	 * @param string $file
	 * @return string
	 */
	private function isReadableFile( $file ) {
		if ( is_readable( $file ) ) {
			return $file;
		}

		throw new RuntimeException( "Expected an accessible {$file} file" );
	}

	protected function addChameleon2ToVisualEditorSupportedSkins(): void {
		// if Visual Editor is installed and there is a setting to enable or disable it
		if ( $this->hasConfiguration( 'wgVisualEditorSupportedSkins' ) &&
			$this->hasConfiguration( 'egChameleon2EnableVisualEditor' ) ) {

			// if VE should be enabled
			if ( $this->configuration[ 'egChameleon2EnableVisualEditor' ] === true ) {

				// if Chameleon2 is not yet in the list of VE-enabled skins
				if ( !in_array( 'chameleon2', $this->configuration[ 'wgVisualEditorSupportedSkins' ] ) ) {
					$GLOBALS[ 'wgVisualEditorSupportedSkins' ][] = 'chameleon2';
				}

			} else {
				// remove all entries of Chameleon2 from the list of VE-enabled skins
				$GLOBALS[ 'wgVisualEditorSupportedSkins' ] = array_diff(
					$this->configuration[ 'wgVisualEditorSupportedSkins' ],
					[ 'chameleon2' ]
				);
			}
		}
	}

	protected function addResourceModules(): void {
		$GLOBALS[ 'wgResourceModules' ][ 'skin.chameleon2.sticky' ] = [
			'localBasePath'  => $GLOBALS[ 'chameleon2LocalPath' ] . '/resources/js',
			'remoteBasePath' => $GLOBALS[ 'chameleon2RemotePath' ] . '/resources/js',
			'group'          => 'skin.chameleon2',
			'skinScripts'    =>
				[ 'chameleon2' => [ 'hc-sticky/hc-sticky.js', 'Components/Modifications/sticky.js' ] ]
		];

		$GLOBALS[ 'wgResourceModules' ][ 'skin.chameleon2.toc' ] = [
			'localBasePath'  => $GLOBALS[ 'chameleon2LocalPath' ] . '/resources',
			'remoteBasePath' => $GLOBALS[ 'chameleon2RemotePath' ] . '/resources',
			'group'          => 'skin.chameleon2',
			'skinScripts'    => [ 'chameleon2' => [ 'js/Components/toc.js' ] ]
		];
	}

	protected function setLayoutFile(): void {
		$layout = $this->request->getVal( 'uselayout' );

		if ( $layout !== null &&
			$this->hasConfigurationOfTypeArray( 'egChameleon2AvailableLayoutFiles' ) &&
			array_key_exists( $layout, $this->configuration[ 'egChameleon2AvailableLayoutFiles' ] ) ) {

			$GLOBALS[ 'egChameleon2LayoutFile' ] = $this->configuration[ 'egChameleon2AvailableLayoutFiles' ][ $layout ];
		}
	}

	protected function registerSkinWithMW() {
		MediaWikiServices::getInstance()->getSkinFactory()->register( 'chameleon2', 'Chameleon2',
			function () {
				$styles = [
					'mediawiki.ui.button',
					'skins.chameleon2',
					'zzz.ext.bootstrap2.styles',
				];

				if ( $this->configuration[ 'egChameleon2EnableExternalLinkIcons' ] === true &&
					version_compare( $this->configuration['wgVersion'], '1.39', '<' ) ) {
					array_unshift( $styles, 'mediawiki.skinning.content.externallinks' );
				}

				return new Chameleon2( [
					'name' => 'chameleon2',
					'styles' => $styles,
					'bodyOnly' => version_compare( $this->configuration['wgVersion'], '1.39', '>=' ),
				] );
			} );
	}

}

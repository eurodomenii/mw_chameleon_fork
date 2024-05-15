<?php
/**
 * File containing the BeforeInitialize class
 *
 * This file is part of the MediaWiki skin Chameleon1.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
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

namespace Skins\Chameleon1\Hooks;

use Bootstrap\BootstrapManager;
use MediaWiki\MediaWikiServices;
use RuntimeException;
use Skins\Chameleon1\Chameleon1;

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

	private BootstrapManager $bootstrapManager;
	private array $configuration;
	private \WebRequest $request;

	public function __construct( BootstrapManager $bootstrapManager, array $configuration, \WebRequest $request ) {
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
	 * Set local and remote base path of the Chameleon1 skin
	 */
	protected function setInstallPaths(): void {
		$GLOBALS[ 'chameleon1LocalPath' ] = $this->configuration['wgStyleDirectory'] . '/chameleon1';
		$GLOBALS[ 'chameleon1RemotePath' ] = $this->configuration['wgStylePath'] . '/chameleon1';
	}

	protected function addLateSettings() {
		$this->registerSkinWithMW();
		$this->addChameleon1ToVisualEditorSupportedSkins();
		$this->addResourceModules();
		$this->setLayoutFile();
	}

	protected function registerCommonBootstrapModules(): void {
		$this->bootstrapManager->addAllBootstrapModules();

		if ( !empty( $this->configuration[ 'egChameleon1ThemeFile' ] ) ) {
			$this->bootstrapManager->addStyleFile(
				$this->configuration[ 'egChameleon1ThemeFile' ],
				'beforeVariables'
			);
		}

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon1LocalPath' ] . '/resources/styles/_variables.scss',
			'variables'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon1LocalPath' ] . '/resources/fontawesome/scss/fontawesome.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon1LocalPath' ] . 	'/resources/fontawesome/scss/fa-solid.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon1LocalPath' ] . '/resources/fontawesome/scss/fa-regular.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon1LocalPath' ] . '/resources/fontawesome/scss/fa-brands.scss'
		);

		$this->bootstrapManager->addStyleFile(
			$GLOBALS[ 'chameleon1LocalPath' ] . '/resources/styles/chameleon1.scss'
		);

		$this->bootstrapManager->setScssVariable(
			'fa-font-path',
			$GLOBALS[ 'chameleon1RemotePath' ] . '/resources/fontawesome/webfonts'
		);
	}

	protected function registerExternalScssModules() {
		if ( $this->hasConfigurationOfTypeArray( 'egChameleon1ExternalStyleModules' ) ) {
			foreach ( $this->configuration[ 'egChameleon1ExternalStyleModules' ] as $localFile => $position ) {
				$config = $this->matchAssociativeElement( $localFile, $position );
				$config[0] = $this->isReadableFile( $config[0] );

				$this->bootstrapManager->addStyleFile( ...$config );
			}
		}
	}

	protected function registerExternalStyleVariables() {
		if ( $this->hasConfigurationOfTypeArray( 'egChameleon1ExternalStyleVariables' ) ) {

			foreach ( $this->configuration[ 'egChameleon1ExternalStyleVariables' ] as $key => $value ) {
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

	protected function addChameleon1ToVisualEditorSupportedSkins(): void {
		// if Visual Editor is installed and there is a setting to enable or disable it
		if ( $this->hasConfiguration( 'wgVisualEditorSupportedSkins' ) &&
			$this->hasConfiguration( 'egChameleon1EnableVisualEditor' ) ) {

			// if VE should be enabled
			if ( $this->configuration[ 'egChameleon1EnableVisualEditor' ] === true ) {

				// if Chameleon1 is not yet in the list of VE-enabled skins
				if ( !in_array( 'chameleon1', $this->configuration[ 'wgVisualEditorSupportedSkins' ] ) ) {
					$GLOBALS[ 'wgVisualEditorSupportedSkins' ][] = 'chameleon1';
				}

			} else {
				// remove all entries of Chameleon1 from the list of VE-enabled skins
				$GLOBALS[ 'wgVisualEditorSupportedSkins' ] = array_diff(
					$this->configuration[ 'wgVisualEditorSupportedSkins' ],
					[ 'chameleon1' ]
				);
			}
		}
	}

	protected function addResourceModules(): void {
		$GLOBALS[ 'wgResourceModules' ][ 'skin.chameleon1.sticky' ] = [
			'localBasePath'  => $GLOBALS[ 'chameleon1LocalPath' ] . '/resources/js',
			'remoteBasePath' => $GLOBALS[ 'chameleon1RemotePath' ] . '/resources/js',
			'group'          => 'skin.chameleon1',
			'skinScripts'    =>
				[ 'chameleon1' => [ 'hc-sticky/hc-sticky.js', 'Components/Modifications/sticky.js' ] ]
		];

		$GLOBALS[ 'wgResourceModules' ][ 'skin.chameleon1.toc' ] = [
			'localBasePath'  => $GLOBALS[ 'chameleon1LocalPath' ] . '/resources',
			'remoteBasePath' => $GLOBALS[ 'chameleon1RemotePath' ] . '/resources',
			'group'          => 'skin.chameleon1',
			'skinScripts'    => [ 'chameleon1' => [ 'js/Components/toc.js' ] ]
		];
	}

	protected function setLayoutFile(): void {
		$layout = $this->request->getVal( 'uselayout' );

		if ( $layout !== null &&
			$this->hasConfigurationOfTypeArray( 'egChameleon1AvailableLayoutFiles' ) &&
			array_key_exists( $layout, $this->configuration[ 'egChameleon1AvailableLayoutFiles' ] ) ) {

			$GLOBALS[ 'egChameleon1LayoutFile' ] = $this->configuration[ 'egChameleon1AvailableLayoutFiles' ][ $layout ];
		}
	}

	protected function registerSkinWithMW() {
		MediaWikiServices::getInstance()->getSkinFactory()->register( 'chameleon1', 'Chameleon1',
			function () {
				$styles = [
					'mediawiki.ui.button',
					'skins.chameleon1',
					'zzz.ext.bootstrap.styles',
				];

				if ( $this->configuration[ 'egChameleon1EnableExternalLinkIcons' ] === true &&
					version_compare( $this->configuration['wgVersion'], '1.39', '<' ) ) {
					array_unshift( $styles, 'mediawiki.skinning.content.externallinks' );
				}

				return new Chameleon1( [
					'name' => 'chameleon1',
					'styles' => $styles,
					'bodyOnly' => version_compare( $this->configuration['wgVersion'], '1.39', '>=' ),
				] );
			} );
	}

}

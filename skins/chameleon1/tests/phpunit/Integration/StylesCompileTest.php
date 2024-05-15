<?php
/**
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

namespace Skins\Chameleon1\Tests\Integration;

use Bootstrap\BootstrapManager1;
use CSSMin;
use HashBagOStuff;
use SCSS\ResourceLoaderSCSSModule;
use Skins\Chameleon1\Hooks\SetupAfterCache;

/**
 * @coversNothing
 *
 * @group skins-chameleon1
 * @group skins-chameleon1-integration
 * @group mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.1
 * @ingroup Skins
 * @ingroup Test
 */
class StylesCompileTest extends \PHPUnit\Framework\TestCase {

	public function testStylesCompile() {
		$request = $this->getMockBuilder( '\WebRequest' )
			->disableOriginalConstructor()
			->getMock();

		$setupAfterCache = new SetupAfterCache(
			BootstrapManager1::getInstance(),
			$GLOBALS,
			$request
		);

		$setupAfterCache->process();

		$resourceLoaderContext = $this->getMockBuilder( '\ResourceLoaderContext' )
			->disableOriginalConstructor()
			->getMock();

		$module =
			new ResourceLoaderSCSSModule( $GLOBALS[ 'wgResourceModules' ][ 'ext.bootstrap1.styles' ] );
		$module->setCache( new HashBagOStuff() );

		$styles = $module->getStyles( $resourceLoaderContext );
		$css = CSSMin::minify( $styles[ 'all' ] );

		$this->assertNotEquals( '', $css );
	}

}

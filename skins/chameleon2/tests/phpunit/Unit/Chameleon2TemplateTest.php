<?php
/**
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

namespace Skins\Chameleon2\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Skins\Chameleon2\Chameleon2;
use Skins\Chameleon2\Chameleon2Template;

/**
 * @uses \Skins\Chameleon2\Chameleon2Template
 *
 * @group skins-chameleon2
 * @group skins-chameleon2-unit
 * @group mediawiki-databaseless
 *
 * @license   GPL-3.0-or-later
 * @since 1.0
 *
 * @author mwjames
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class Chameleon2TemplateTest extends TestCase {

	// This is to ensure that the original value is cached since we are unable
	// to inject the setting during testing
	protected $egChameleon2LayoutFile = null;
	protected $egChameleon2ThemeFile = null;

	protected function setUp(): void {
		parent::setUp();

		$this->egChameleon2LayoutFile = $GLOBALS['egChameleon2LayoutFile'];
		$this->egChameleon2ThemeFile = $GLOBALS['egChameleon2ThemeFile'];
	}

	protected function tearDown(): void {
		$GLOBALS['egChameleon2LayoutFile'] = $this->egChameleon2LayoutFile;
		$GLOBALS['egChameleon2ThemeFile'] = $this->egChameleon2ThemeFile;

		parent::tearDown();
	}

	/**
	 * @covers \Skins\Chameleon2\Chameleon2Template
	 */
	public function testCanConstruct() {
		$this->assertInstanceOf(
			'\Skins\Chameleon2\Chameleon2Template',
			new Chameleon2Template()
		);
	}

	/**
	 * @covers \Skins\Chameleon2\Chameleon2Template
	 */
	public function testInaccessibleLayoutFileThrowsExeception() {
		$this->expectException( 'RuntimeException' );

		$GLOBALS['egChameleon2LayoutFile'] = 'setInaccessibleLayoutFile';

		$skin = new Chameleon2();

		$instance = new Chameleon2Template;
		$instance->set( 'skin', $skin );
		$instance->execute();
	}

}

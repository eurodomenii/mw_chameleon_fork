<?php
/**
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

namespace Skins\Chameleon1\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Skins\Chameleon1\Chameleon1;
use Skins\Chameleon1\Chameleon1Template;

/**
 * @uses \Skins\Chameleon1\Chameleon1Template
 *
 * @group skins-chameleon1
 * @group skins-chameleon1-unit
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
class Chameleon1TemplateTest extends TestCase {

	// This is to ensure that the original value is cached since we are unable
	// to inject the setting during testing
	protected $egChameleon1LayoutFile = null;
	protected $egChameleon1ThemeFile = null;

	protected function setUp(): void {
		parent::setUp();

		$this->egChameleon1LayoutFile = $GLOBALS['egChameleon1LayoutFile'];
		$this->egChameleon1ThemeFile = $GLOBALS['egChameleon1ThemeFile'];
	}

	protected function tearDown(): void {
		$GLOBALS['egChameleon1LayoutFile'] = $this->egChameleon1LayoutFile;
		$GLOBALS['egChameleon1ThemeFile'] = $this->egChameleon1ThemeFile;

		parent::tearDown();
	}

	/**
	 * @covers \Skins\Chameleon1\Chameleon1Template
	 */
	public function testCanConstruct() {
		$this->assertInstanceOf(
			'\Skins\Chameleon1\Chameleon1Template',
			new Chameleon1Template()
		);
	}

	/**
	 * @covers \Skins\Chameleon1\Chameleon1Template
	 */
	public function testInaccessibleLayoutFileThrowsExeception() {
		$this->expectException( 'RuntimeException' );

		$GLOBALS['egChameleon1LayoutFile'] = 'setInaccessibleLayoutFile';

		$skin = new Chameleon1();

		$instance = new Chameleon1Template;
		$instance->set( 'skin', $skin );
		$instance->execute();
	}

}

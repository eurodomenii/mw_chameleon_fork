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

namespace Skins\Chameleon2\Tests\Unit\Components;

use Skins\Chameleon2\Components\NavbarHorizontal;

/**
 * @coversDefaultClass \Skins\Chameleon2\Components\NavbarHorizontal
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon2
 * @group   mediawiki-databaseless
 *
 * @author mwjames
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class NavbarHorizontalTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Chameleon2\Components\NavbarHorizontal';

	/**
	 * @covers ::getHtml
	 * @requires PHP < 8.1
	 */
	public function testGetHtml_containsNavElement() {
		$element = $this->getMockBuilder( '\DOMElement' )
			->disableOriginalConstructor()
			->getMock();

		$message = $this->getMockBuilder( '\Message' )
			->disableOriginalConstructor()
			->getMock();

		$chameleon2Template = $this->getMockBuilder( '\Skins\Chameleon2\Chameleon2Template' )
			->disableOriginalConstructor()
			->getMock();

		$chameleon2Template->expects( $this->any() )
			->method( 'getMsg' )
			->will( $this->returnValue( $message ) );

		$instance = new NavbarHorizontal(
			$chameleon2Template,
			$element
		);

		$matcher = [ 'tag' => 'nav' ];
		$this->assertTag( $matcher, $instance->getHtml() );
	}

}

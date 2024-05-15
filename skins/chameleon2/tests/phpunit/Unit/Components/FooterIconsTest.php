<?php
/**
 * This file is part of the MediaWiki skin Chameleon2.
 *
 * @copyright 2013 - 2019, Stephan Gambke
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

/**
 * @coversDefaultClass \Skins\Chameleon2\Components\FooterIcons
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon2
 * @group   mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class FooterIconsTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Chameleon2\Components\FooterIcons';

	/**
	 * @covers ::getHtml
	 */
	public function testGetHtml() {
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$skin = $chameleon2Template->getSkin();

		$skin->expects( $this->exactly( 4 ) )
			->method( 'makeFooterIcon' )
			->withConsecutive(
				[ $this->equalTo( 'icon1' ) ],
				[ $this->equalTo( 'icon2' ) ],
				[ $this->equalTo( 'icon3' ) ],
				[ $this->equalTo( 'icon4' ) ]
			)
			->willReturn( $this->returnValue( 'SomeHTML' ) );

		$chameleon2Template->expects( $this->any() )
			->method( 'getFooterIconsWithImage' )
			->will( $this->returnValue( [
				'block1' => [ 'icon1', 'icon2' ],
				'block2' => [ 'icon3', 'icon4' ],
			] ) );

		$instance = new $this->classUnderTest( $chameleon2Template );

		$this->assertValidHTML( $instance->getHtml() );
	}

}

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

namespace Skins\Chameleon1\Tests\Unit\Components;

/**
 * @coversDefaultClass \Skins\Chameleon1\Components\FooterPlaces
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon1
 * @group   mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class FooterPlacesTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Chameleon1\Components\FooterPlaces';

	/**
	 * @covers ::getHtml
	 */
	public function testGetHtml_GetsAllKeys() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$chameleon1Template->expects( $this->exactly( 3 ) )
			->method( 'get' )
			->withConsecutive(
				[ $this->equalTo( 'privacy' ), $this->equalTo( null ) ],
				[ $this->equalTo( 'about' ), $this->equalTo( null ) ],
				[ $this->equalTo( 'disclaimer' ), $this->equalTo( null ) ]
			)
			->willReturn( $this->returnValue( 'SomeHTML' ) );

		$instance = new $this->classUnderTest( $chameleon1Template );

		$instance->getHtml();
	}
}

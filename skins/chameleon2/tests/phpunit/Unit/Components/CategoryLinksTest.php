<?php
/**
 * This file is part of the MediaWiki skin Chameleon2.
 *
 * @copyright 2022, Morne Alberts
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
 * @coversDefaultClass \Skins\Chameleon2\Components\CategoryLinks
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon2
 * @group   mediawiki-databaseless
 *
 * @author Morne Alberts
 * @since 4.0
 * @ingroup Skins
 * @ingroup Test
 */
class CategoryLinksTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Chameleon2\Components\CategoryLinks';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_CategoryDisplayed( $domElement ) {
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();
		$instance = new $this->classUnderTest( $chameleon2Template, $domElement );
		$this->assertStringContainsString( 'SomeCategory', $instance->getHtml() );
	}

}

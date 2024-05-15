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

use Skins\Chameleon1\Components\Html;

/**
 * @coversDefaultClass \Skins\Chameleon1\Components\Html
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
class HtmlTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Chameleon1\Components\Html';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 *
	 * @param \DOMElement $domElement
	 */
	public function testGetHtml_OnSyntheticLayoutXml( \DOMElement $domElement ) {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$expected = '';

		foreach ( $domElement->childNodes as $child ) {
			$expected .= $domElement->ownerDocument->saveHTML( $child );
		}

		$instance = new Html( $chameleon1Template, $domElement );
		$actual = $instance->getHtml();

		$this->assertEquals( $expected, $actual );
	}
}

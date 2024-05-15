<?php
/**
 * This file is part of the MediaWiki skin Chameleon1.
 *
 * @copyright 2022, gesinn-it-wam
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

use Skins\Chameleon1\Chameleon1Template;
use Skins\Chameleon1\Components\ContentBody;

/**
 * @coversDefaultClass \Skins\Chameleon1\Components\ContentBody
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon1
 * @group   mediawiki-databaseless
 *
 * @author gesinn-it-wam
 * @since 4.2
 * @ingroup Skins
 * @ingroup Test
 */
class ContentBodyTest extends GenericComponentTestCase {

	protected $classUnderTest = ContentBody::class;

	/**
	 * @covers ::getHtml
	 * @throws \MWException
	 */
	public function testGetHtml_BodytextDisplayed() {
		$chameleon1Template = $this->createStub( Chameleon1Template::class );
		$chameleon1Template->method( 'get' )->willReturnMap([['bodytext', null, 'SomeBodytext']]);
		$instance = new $this->classUnderTest( $chameleon1Template, null );

		$html = $instance->getHtml();

		$this->assertStringContainsString( 'SomeBodytext', $html );
	}

}

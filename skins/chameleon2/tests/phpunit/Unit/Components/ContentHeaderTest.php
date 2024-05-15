<?php
/**
 * This file is part of the MediaWiki skin Chameleon2.
 *
 * @copyright 2022, gesinn-it-wam
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

use Message;
use Skins\Chameleon2\Chameleon2Template;
use Skins\Chameleon2\Components\ContentHeader;

/**
 * @coversDefaultClass \Skins\Chameleon2\Components\ContentHeader
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon2
 * @group   mediawiki-databaseless
 *
 * @author gesinn-it-wam
 * @since 4.2
 * @ingroup Skins
 * @ingroup Test
 */
class ContentHeaderTest extends GenericComponentTestCase {

	protected $classUnderTest = ContentHeader::class;

	/**
	 * @covers ::getHtml
	 * @throws \MWException
	 */
	public function testGetHtml_TitleDisplayed() {
		$chameleon2Template = $this->createStub( Chameleon2Template::class );
		$chameleon2Template->method( 'get' )->willReturnMap([['title', null, 'SomeTitle']]);
		$chameleon2Template->method( 'getMsg' )->willReturn(new Message(''));
		$instance = new $this->classUnderTest( $chameleon2Template, null );

		$html = $instance->getHtml();

		$this->assertStringContainsString( 'SomeTitle', $html );
	}

}

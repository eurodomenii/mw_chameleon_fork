<?php
/**
 * This file is part of the MediaWiki skin Chameleon1.
 *
 * @copyright 2013 - 2014, Stephan Gambke, mwjames
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

use Message;
use Skins\Chameleon1\Chameleon1Template;

/**
 * @coversDefaultClass \Skins\Chameleon1\Components\MainContent
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon1
 * @group   mediawiki-databaseless
 *
 * @author  mwjames
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class MainContentTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Chameleon1\Components\MainContent';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_OnEmptyDataProperty( $domElement ) {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$chameleon1Template->data = [
			'subtitle'         => '',
			'undelete'         => '',
			'printfooter'      => '',
			'dataAfterContent' => ''
		];

		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$this->assertIsString( $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @throws \MWException
	 */
	public function testGetHtml_AllSubComponentsDisplayed() {
		$chameleon1Template = $this->createChameleon1TemplateStub();
		$instance = new $this->classUnderTest( $chameleon1Template, null );

		$html = $instance->getHtml();

		$this->assertStringContainsString( 'SomeTitle', $html );
		$this->assertStringContainsString( 'SomeBodytext', $html );
		$this->assertStringContainsString( 'SomeIndicatorId', $html );
		$this->assertStringContainsString( 'SomeIndicatorContent', $html );
		$this->assertStringContainsString( 'SomeCategory', $html );
	}

	/**
	 * @covers ::getHtml
	 * @throws \MWException
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_AllSubComponentsHidden( $domElement ) {
		$chameleon1Template = $this->createChameleon1TemplateStub();
		$domElement->setAttribute('hideContentHeader', 'yes');
		$domElement->setAttribute('hideContentBody', 'yes');
		$domElement->setAttribute('hideIndicators', 'yes');
		$domElement->setAttribute('hideCatLinks', 'yes');
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$html = $instance->getHtml();

		$this->assertStringNotContainsString( 'SomeTitle', $html );
		$this->assertStringNotContainsString( 'SomeBodytext', $html );
		$this->assertStringNotContainsString( 'SomeIndicatorId', $html );
		$this->assertStringNotContainsString( 'SomeIndicatorContent', $html );
		$this->assertStringNotContainsString( 'SomeCategory', $html );
	}

	/**
	 * @return \PHPUnit\Framework\MockObject\Stub|Chameleon1Template
	 */
	private function createChameleon1TemplateStub() {
		$chameleon1Template = $this->createStub( Chameleon1Template::class );
		$chameleon1Template->method( 'get' )->willReturnMap( [
			[ 'title', null, 'SomeTitle' ],
			[ 'bodytext', null, 'SomeBodytext' ],
			[ 'indicators', null, [ 'SomeIndicatorId', 'SomeIndicatorContent' ] ],
			[ 'catlinks', null, 'SomeCategory' ],
		] );
		$chameleon1Template->method( 'getMsg' )->willReturn( new Message( '' ) );

		return $chameleon1Template;
	}

}

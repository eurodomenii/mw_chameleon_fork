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

namespace Skins\Chameleon1\Tests\Unit\Components\NavbarHorizontal;

use Skins\Chameleon1\Components\Component;
use Skins\Chameleon1\Tests\Unit\Components\GenericComponentTestCase;
use Skins\Chameleon1\Tests\Util\MockupFactory;

/**
 * @coversDefaultClass \Skins\Chameleon1\Components\NavbarHorizontal\PersonalTools
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon1
 * @group   mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.6
 * @ingroup Skins
 * @ingroup Test
 */
class PersonalToolsTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Chameleon1\Components\NavbarHorizontal\PersonalTools';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_LoggedInUserHasNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();
		$chameleon1Template->data = [ 'newtalk' => 'foo' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertTag( [ 'class' => 'pt-mytalk' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_LoggedInUserHasNoNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();
		$chameleon1Template->data = [ 'newtalk' => '' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertNotTag( [ 'class' => 'pt-mytalk' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_LoggedOutUserHasNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', false );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();
		$chameleon1Template->data = [ 'newtalk' => 'foo' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertTag( [ 'class' => 'pt-anontalk' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_LoggedOutUserHasNoNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', false );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();
		$chameleon1Template->data = [ 'newtalk' => '' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertNotTag( [ 'class' => 'pt-anontalk' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoDefault( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();
		$chameleon1Template->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				// Icons are rendered without link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'] ],
				[ 'foo', [ 'id' => 'pt-foo'], [ 'tag' => 'div' , 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'tag' => 'div' , 'link-class' => 'pt-bar' ] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$instance->getHtml();
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoIcons( $domElement ) {
		$domElement->setAttribute( 'showEcho', 'icons' );
		$factory = MockupFactory::makeFactory( $this );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();
		$chameleon1Template->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				// Icons are rendered without link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'] ],
				[ 'foo', [ 'id' => 'pt-foo'], [ 'tag' => 'div' , 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'tag' => 'div' , 'link-class' => 'pt-bar' ] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$instance->getHtml();
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoLinks( $domElement ) {
		$domElement->setAttribute( 'showEcho', 'links' );
		$factory = MockupFactory::makeFactory( $this );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();
		$chameleon1Template->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				[ 'foo', [ 'id' => 'pt-foo'], [ 'tag' => 'div' , 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'tag' => 'div' , 'link-class' => 'pt-bar' ] ],
				// Links are rendered with link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'],
					[ 'tag' => 'div' , 'link-class' => 'pt-notifications-alert' ] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'],
					[ 'tag' => 'div' , 'link-class' => 'pt-notifications-notice' ] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$instance->getHtml();
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowUserNameNo( $domElement ) {
		$domElement->setAttribute( 'showUserName', 'no' );
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$factory->set( 'UserRealName', 'John Doe' );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$html = $instance->getHtml();

		$this->assertStringNotContainsString( 'FooUser', $html );
		$this->assertStringNotContainsString( 'John Doe', $html );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowUserNameYesWithoutRealName( $domElement ) {
		$domElement->setAttribute( 'showUserName', 'yes' );
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$html = $instance->getHtml();

		$this->assertStringContainsString( 'FooUser', $html );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowUserNameYesWithRealName( $domElement ) {
		$domElement->setAttribute( 'showUserName', 'yes' );
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$factory->set( 'UserRealName', 'John Doe' );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$html = $instance->getHtml();

		$this->assertStringNotContainsString( 'FooUser', $html );
		$this->assertStringContainsString( 'John Doe', $html );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowUserNameNone( $domElement ) {
		$domElement->setAttribute( 'showUserName', 'none' );
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$factory->set( 'UserRealName', 'John Doe' );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$html = $instance->getHtml();

		$this->assertStringNotContainsString( 'FooUser', $html );
		$this->assertStringNotContainsString( 'John Doe', $html );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowUserNameUsernameOnly( $domElement ) {
		$domElement->setAttribute( 'showUserName', 'username-only' );
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$factory->set( 'UserRealName', 'John Doe' );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$html = $instance->getHtml();

		$this->assertStringContainsString( 'FooUser', $html );
		$this->assertStringNotContainsString( 'John Doe', $html );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowUserNameTryRealNameWithoutRealName( $domElement ) {
		$domElement->setAttribute( 'showUserName', 'try-realname' );
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$html = $instance->getHtml();

		$this->assertStringContainsString( 'FooUser', $html );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowUserNameTryRealNameWithRealName( $domElement ) {
		$domElement->setAttribute( 'showUserName', 'try-realname' );
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsRegistered', true );
		$factory->set( 'UserRealName', 'John Doe' );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );
		$html = $instance->getHtml();

		$this->assertStringNotContainsString( 'FooUser', $html );
		$this->assertStringContainsString( 'John Doe', $html );
	}

}

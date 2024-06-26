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
 * @coversDefaultClass \Skins\Chameleon1\Components\Component
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon1
 * @group   mediawiki-databaseless
 *
 * @author  Stephan Gambke
 * @since   1.0
 * @ingroup Test
 */
class ComponentTest extends \PHPUnit\Framework\TestCase {

	protected $classUnderTest = '\Skins\Chameleon1\Components\Component';

	/**
	 * @covers ::__construct
	 */
	public function testCanConstruct() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertInstanceOf(
			$this->classUnderTest,
			$instance
		);

		$this->assertSame( 0, $instance->getIndent() );
		$this->assertNull( $instance->getDomElement() );
	}

	/**
	 * @covers ::__construct
	 * @requires PHP < 8.1
	 */
	public function testCanConstruct_withClassAttribute() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$domElement = $this->getMockBuilder( '\DOMElement' )
			->disableOriginalConstructor()
			->getMock();

		$domElement->expects( $this->atLeastOnce() )
			->method( 'getAttribute' )
			->will( $this->returnValueMap( [ [ 'class', 'someClass' ] ] ) );

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template,
			$domElement ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertInstanceOf(
			$this->classUnderTest,
			$instance
		);
	}

	/**
	 * @covers ::getHtml
	 */
	public function testGetHtml() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertValidHTML( $instance->getHtml() );
	}

	/**
	 * @covers ::getSkinTemplate
	 */
	public function testGetSkinTemplate() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertEquals(
			$chameleon1Template,
			$instance->getSkinTemplate()
		);
	}

	/**
	 * @covers ::getIndent
	 */
	public function testGetIndent() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template, null,
			42 ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertEquals(
			42,
			$instance->getIndent()
		);
	}

	/**
	 * @covers ::indent
	 */
	public function testIndent() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template, null,
			42 ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$reflection = new \ReflectionClass( get_class( $instance ) );
		$method = $reflection->getMethod( 'indent' );
		$method->setAccessible( true );

		$this->assertEquals(
			"\n" . str_repeat( "\t", 43 ),
			$method->invokeArgs( $instance, [ 1 ] )
		);
	}

	/**
	 * @covers ::getDomElement
	 * @requires PHP < 8.1
	 */
	public function testGetDomElement() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$domElement = $this->getMockBuilder( '\DOMElement' )
			->disableOriginalConstructor()
			->getMock();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template,
			$domElement ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertEquals(
			$domElement,
			$instance->getDomElement()
		);
	}

	/**
	 * @covers ::getClassString
	 */
	public function testGetClassString_WithoutSetting() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertIsString( $instance->getClassString() );
	}

	/**
	 * @covers ::setClasses
	 * @covers ::getClassString
	 * @dataProvider setClassesProvider
	 */
	public function testSetClasses( $input, $expected ) {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$instance->setClasses( $input );

		$this->assertEquals( $expected, $instance->getClassString() );
	}

	/**
	 * @covers ::setClasses
	 */
	public function testSetClasses_WithInvalidParameter() {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->expectException( \MWException::class );
		// use bool instead of string
		$instance->setClasses( true );
	}

	/**
	 * @covers ::addClasses
	 * @covers ::getClassString
	 * @covers ::transformClassesToArray
	 * @dataProvider addClassesProvider
	 */
	public function testAddClasses( $input1, $input2, $combined ) {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$instance->setClasses( $input1 );
		$instance->addClasses( $input2 );

		$this->assertEquals( $combined, $instance->getClassString() );
	}

	/**
	 * @covers ::removeClasses
	 * @covers ::getClassString
	 * @dataProvider removeClassesProvider
	 */
	public function testRemoveClasses( $combined, $toRemove, $remainder ) {
		$chameleon1Template = $this->getChameleon1SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon1Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$instance->setClasses( $combined );
		$instance->removeClasses( $toRemove );

		$this->assertEquals( $remainder, $instance->getClassString() );
	}

	public function setClassesProvider() {
		return [
			[ null, '' ],

			[ '', '' ],
			[ [], '' ],

			[ 'foo bar baz', 'foo bar baz' ],
			[ [ 'foo', 'bar', 'baz', ], 'foo bar baz' ],
		];
	}

	public function addClassesProvider() {
		return [
			[ 'foo bar', null, 'foo bar' ],

			[ 'foo bar', '', 'foo bar' ],
			[ 'foo bar', [], 'foo bar' ],

			[ 'foo bar', 'baz', 'foo bar baz' ],
			[ 'foo bar', [ 'baz' ], 'foo bar baz' ],

			[ 'foo bar', 'baz quok', 'foo bar baz quok' ],
			[ 'foo bar', [ 'baz', 'quok' ], 'foo bar baz quok' ],
		];
	}

	public function removeClassesProvider() {
		return [
			[ 'foo bar', null, 'foo bar' ],

			[ 'foo bar', '', 'foo bar' ],
			[ 'foo bar', [], 'foo bar' ],

			[ 'foo bar baz', 'bar', 'foo baz' ],
			[ 'foo bar baz', [ 'baz' ], 'foo bar' ],

			[ 'foo bar baz quok', 'foo baz', 'bar quok' ],
			[ 'foo bar baz quok', [ 'bar', 'baz' ], 'foo quok' ],
		];
	}

	protected function getChameleon1SkinTemplateStub() {
		return $this->getMockBuilder( '\Skins\Chameleon1\Chameleon1Template' )
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * Asserts that $actual is a valid HTML fragment
	 *
	 * @todo Currently only asserts that $actual is a string. Need to parse and validate,
	 *
	 * @param        $actual
	 * @param string $message
	 */
	public function assertValidHTML( $actual, $message = '' ) {
		$this->assertIsString( $actual, $message );
	}

}

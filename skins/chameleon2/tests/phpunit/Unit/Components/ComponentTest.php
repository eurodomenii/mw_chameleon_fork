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
 * @coversDefaultClass \Skins\Chameleon2\Components\Component
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-chameleon2
 * @group   mediawiki-databaseless
 *
 * @author  Stephan Gambke
 * @since   1.0
 * @ingroup Test
 */
class ComponentTest extends \PHPUnit\Framework\TestCase {

	protected $classUnderTest = '\Skins\Chameleon2\Components\Component';

	/**
	 * @covers ::__construct
	 */
	public function testCanConstruct() {
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template ] );
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$domElement = $this->getMockBuilder( '\DOMElement' )
			->disableOriginalConstructor()
			->getMock();

		$domElement->expects( $this->atLeastOnce() )
			->method( 'getAttribute' )
			->will( $this->returnValueMap( [ [ 'class', 'someClass' ] ] ) );

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template,
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertValidHTML( $instance->getHtml() );
	}

	/**
	 * @covers ::getSkinTemplate
	 */
	public function testGetSkinTemplate() {
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template ] );
		$instance->expects( $this->any() )
			->method( 'getHtml' )
			->will( $this->returnValue( 'SomeHtml' ) );

		$this->assertEquals(
			$chameleon2Template,
			$instance->getSkinTemplate()
		);
	}

	/**
	 * @covers ::getIndent
	 */
	public function testGetIndent() {
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template, null,
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template, null,
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$domElement = $this->getMockBuilder( '\DOMElement' )
			->disableOriginalConstructor()
			->getMock();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template,
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template ] );
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template ] );
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template ] );
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template ] );
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
		$chameleon2Template = $this->getChameleon2SkinTemplateStub();

		$instance = $this->getMockForAbstractClass( $this->classUnderTest, [ $chameleon2Template ] );
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

	protected function getChameleon2SkinTemplateStub() {
		return $this->getMockBuilder( '\Skins\Chameleon2\Chameleon2Template' )
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

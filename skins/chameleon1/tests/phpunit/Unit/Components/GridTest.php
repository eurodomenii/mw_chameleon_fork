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

use DOMElement;
use Skins\Chameleon1\Components\Component;
use Skins\Chameleon1\Tests\Util\MockupFactory;

/**
 * @coversDefaultClass \Skins\Chameleon1\Components\Grid
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
class GridTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Chameleon1\Components\Grid';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_DefaultModeIsFixed( DomElement $domElement ): void {
		$factory = MockupFactory::makeFactory( $this );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertTag( [ 'class' => 'container' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ModeIsFixed( DomElement $domElement ): void {
		$domElement->setAttribute( 'mode', 'fixedwidth' );
		$factory = MockupFactory::makeFactory( $this );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertTag( [ 'class' => 'container' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ModeIsFluid( DomElement $domElement ): void {
		$domElement->setAttribute( 'mode', 'fluid' );
		$factory = MockupFactory::makeFactory( $this );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertTag( [ 'class' => 'container-fluid' ], $instance->getHtml() );
	}

	public function breakpointProvider(): iterable {
		$domElement = $this->domElementProviderFromSyntheticLayoutFiles()[0][0];
		yield 'sm' => [ $domElement, 'sm' ];
		yield 'md' => [ $domElement, 'md' ];
		yield 'lg' => [ $domElement, 'lg' ];
		yield 'xl' => [ $domElement, 'xl' ];
		yield 'xxl' => [ $domElement, 'xxl' ];
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider breakpointProvider
	 */
	public function testGetHtml_ModeIsBreakpoint( DomElement $domElement, string $breakpoint ): void {
		$domElement->setAttribute( 'mode', $breakpoint );
		$factory = MockupFactory::makeFactory( $this );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertTag( [ 'class' => "container-$breakpoint" ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ModeIsInvalid( DomElement $domElement ): void {
		$domElement->setAttribute( 'mode', 'invalid' );
		$factory = MockupFactory::makeFactory( $this );
		$chameleon1Template = $factory->getChameleon1SkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $chameleon1Template, $domElement );

		$this->assertTag( [ 'class' => 'container' ], $instance->getHtml() );
	}

}

<?php

declare( strict_types = 1 );

namespace Skins\Chameleon1\Tests\Unit;

use FileFetcher\InMemoryFileFetcher;
use Skins\Chameleon1\Chameleon1;
use Skins\Chameleon1\ComponentFactory;

/**
 * @covers \Skins\Chameleon1\ComponentFactory
 */
class ComponentFactoryTest extends \MediaWikiIntegrationTestCase {

	/**
	 * Refactor idea: inject a LayoutXmlSource into ComponentFactory,
	 * so the latter does not have details about how to obtain the XML.
	 */
	public function testGetLayoutXml(): void {
		$componentFactory = new ComponentFactory(
			'TestLayout.xml',
			$this->createHookContainer( [
				Chameleon1::HOOK_GET_LAYOUT_XML => function( string &$xml ) {
					$xml .= ' Hi from hook.';
				}
			] ),
			new InMemoryFileFetcher( [
				'TestLayout.xml' => 'Hi from file.'
			] )
		);

		$this->assertSame(
			'Hi from file. Hi from hook.',
			$componentFactory->getLayoutXml()
		);
	}

}

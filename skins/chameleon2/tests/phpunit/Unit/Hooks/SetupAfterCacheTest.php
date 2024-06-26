<?php
/**
 * This file is part of the MediaWiki skin Chameleon2.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
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

namespace Skins\Chameleon2\Tests\Unit\Hooks;

use Bootstrap\BootstrapManager2;
use PHPUnit\Framework\TestCase;
use Skins\Chameleon2\Hooks\SetupAfterCache;
use WebRequest;

/**
 * @coversDefaultClass \Skins\Chameleon2\Hooks\SetupAfterCache
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group skins-chameleon2
 * @group skins-chameleon2-unit
 * @group mediawiki-databaseless
 *
 * @author mwjames
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class SetupAfterCacheTest extends TestCase {

	protected $dummyExternalModule = null;

	protected function setUp(): void {
		parent::setUp();

		$this->dummyExternalModule = __DIR__ . '/../../Fixture/externalmodule.scss';
	}

	/**
	 * @covers ::__construct
	 */
	public function testCanConstruct() {
		$bootstrapManager = $this->getMockBuilder( BootstrapManager2::class )
			->disableOriginalConstructor()
			->getMock();

		$configuration = [];

		$request = $this->getMockBuilder( '\WebRequest' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			SetupAfterCache::class,
			new SetupAfterCache( $bootstrapManager, $configuration, $request )
		);
	}

	/**
	 * @covers ::process
	 * @covers ::registerCommonBootstrapModules
	 * @covers ::registerExternalScssModules
	 */
	public function testProcessWithValidExternalModuleWithoutScssVariables() {
		$bootstrapManager = $this->createMock( BootstrapManager::class );

		$bootstrapManager->expects( $this->exactly( 9 ) )
			->method( 'addStyleFile' )
			->withConsecutive(
				[ $this->anything() ],
				[ $this->anything() ],
				[ $this->anything() ],
				[ $this->anything() ],
				[ $this->anything() ],
				[ $this->anything() ],
				[ $this->anything() ],
				[ $this->equalTo( $this->dummyExternalModule ) ],
				[ $this->equalTo( $this->dummyExternalModule ),	$this->equalTo( 'somePositionWeDontCheck' ) ]
			);

		$bootstrapManager->expects( $this->once() )
			->method( 'setScssVariable' )
			->with(
				$this->equalTo( 'fa-font-path' ),
				$this->anything()
			);

		$mixedExternalStyleModules = [
			$this->dummyExternalModule,
			$this->dummyExternalModule => 'somePositionWeDontCheck'
		];

		$configuration = [
			'egChameleon2ExternalStyleModules' => $mixedExternalStyleModules,
			'egChameleon2ThemeFile'            => $this->dummyExternalModule,
			'IP'                              => 'notTestingIP',
			'wgScriptPath'                    => 'notTestingwgScriptPath',
			'wgStyleDirectory'                => 'notTestingwgStyleDirectory',
			'wgStylePath'                     => 'notTestingwgStylePath',
		];

		$instance = new SetupAfterCache(
			$bootstrapManager,
			$configuration,
			$this->createMock( WebRequest::class )
		);

		$instance->process();
	}

	///**
	// * @covers ::process
	// * @covers ::registerExternalScssModules
	// */
	//public function testProcessWithInvalidExternalModuleThrowsException() {
	//
	//	$bootstrapManager = $this->getMockBuilder( BootstrapManager::class )
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$bootstrapManager->expects( $this->atLeastOnce() )
	//		->method( 'addStyleFile' );
	//
	//	$externalStyleModules = [
	//		__DIR__ . '/../../Util/Fixture/' . 'externalFileDoesNotExist.scss'
	//	];
	//
	//	$configuration = [
	//		'egChameleon2ExternalStyleModules' => $externalStyleModules,
	//		'IP'                              => 'notTestingIP',
	//		'wgScriptPath'                    => 'notTestingwgScriptPath',
	//		'wgStyleDirectory'                => 'notTestingwgStyleDirectory',
	//		'wgStylePath'                     => 'notTestingwgStylePath'
	//	];
	//
	//	$request = $this->getMockBuilder('\WebRequest')
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$instance = new SetupAfterCache(
	//		$bootstrapManager,
	//		$configuration,
	//		$request
	//	);
	//
	//	$this->expectException( 'RuntimeException' );
	//
	//	$instance->process();
	//}
	//
	///**
	// * @covers ::process
	// * @covers ::registerExternalStyleVariables
	// */
	//public function testProcessWithScssVariables() {
	//
	//	$bootstrapManager = $this->getMockBuilder( BootstrapManager::class )
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$bootstrapManager->expects( $this->once() )
	//		->method( 'addStyleFile' );
	//
	//	$bootstrapManager->expects( $this->once() )
	//		->method( 'setScssVariable' )
	//		->with(
	//			$this->equalTo( 'foo' ),
	//			$this->equalTo( '999px' ) );
	//
	//	$externalStyleVariables = [
	//		'foo' => '999px'
	//	];
	//
	//	$configuration = [
	//		'egChameleon2ExternalStyleVariables'=> $externalStyleVariables,
	//		'IP'                               => 'notTestingIP',
	//		'wgScriptPath'                     => 'notTestingwgScriptPath',
	//		'wgStyleDirectory'                 => 'notTestingwgStyleDirectory',
	//		'wgStylePath'                      => 'notTestingwgStylePath'
	//	];
	//
	//	$request = $this->getMockBuilder('\WebRequest')
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$instance = new SetupAfterCache(
	//		$bootstrapManager,
	//		$configuration,
	//		$request
	//	);
	//
	//	$instance->process();
	//}
	//
	///**
	// * @covers ::process
	// * @covers ::registerExternalLessVariables
	// *
	// * @dataProvider processWithRequestedLayoutFileProvider
	// */
	//public function testProcessWithRequestedLayoutFile( $availableLayoutFiles, $defaultLayoutFile,
	//	$requestedLayout, $expectedLayoutfile ) {
	//
	//	$bootstrapManager = $this->getMockBuilder( BootstrapManager::class )
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$configuration = [
	//		'egChameleon2AvailableLayoutFiles'  => $availableLayoutFiles,
	//		'egChameleon2LayoutFile'            => $defaultLayoutFile,
	//		'IP'                               => 'notTestingIP',
	//		'wgScriptPath'                     => 'notTestingwgScriptPath',
	//		'wgStyleDirectory'                 => 'notTestingwgStyleDirectory',
	//		'wgStylePath'                      => 'notTestingwgStylePath'
	//	];
	//
	//	$request = $this->getMockBuilder('\WebRequest')
	//		->disableOriginalConstructor()
	//		->getMock();
	//
	//	$request->expects( $this->once() )
	//		->method( 'getVal' )
	//		->will( $this->returnValue( $requestedLayout ) );
	//
	//	$instance = new SetupAfterCache(
	//		$bootstrapManager,
	//		$configuration,
	//		$request
	//	);
	//
	//	$instance->process();
	//
	//	$this->assertEquals(
	//		$expectedLayoutfile,
	//		$configuration['egChameleon2LayoutFile']
	//	);
	//}

	/**
	 * @return array
	 */
	public function processWithRequestedLayoutFileProvider() {
		$provider = [];

		// no layout files available => keep default layout file
		$provider[] = [
			null,
			'standard.xml',
			'someOtherLayout',
			'standard.xml'
		];

		// no specific layout requested => keep default layout file
		$provider[] = [
			[
				'layout1' => 'layout1.xml',
				'layout2' => 'layout2.xml',
			],
			'standard.xml',
			null,
			'standard.xml'
		];

		// requested layout not available => keep default layout file
		$provider[] = [
			[
				'layout1' => 'layout1.xml',
				'layout2' => 'layout2.xml',
			],
			'standard.xml',
			'someOtherLayout',
			'standard.xml'
		];

		// requested layout available => return requested layout file
		$provider[] = [
			[
				'layout1' => 'layout1.xml',
				'layout2' => 'layout2.xml',
			],
			'standard.xml',
			'layout1',
			'layout1.xml'
		];

		return $provider;
	}

	/**
	 * Provides test data for the lateSettings test
	 */
	public function lateSettingsProvider() {
		$provider = [];

		$provider[ ] = [
			[],
			[]
		];

		$provider[ ] = [
			[
				'wgVisualEditorSupportedSkins' => [],
			],
			[
				'wgVisualEditorSupportedSkins' => [],
			]
		];

		$provider[ ] = [
			[
				'egChameleon2EnableVisualEditor' => true,
			],
			[
				'egChameleon2EnableVisualEditor' => true,
			]
		];

		$provider[ ] = [
			[
				'egChameleon2EnableVisualEditor' => true,
				'wgVisualEditorSupportedSkins'  => [ 'foo' ],
			],
			[
				'egChameleon2EnableVisualEditor' => true,
				'wgVisualEditorSupportedSkins'  => [ 'foo', 'chameleon2' ],
			]
		];

		$provider[ ] = [
			[
				'egChameleon2EnableVisualEditor' => true,
				'wgVisualEditorSupportedSkins'  => [ 'foo', 'chameleon2' ],
			],
			[
				'egChameleon2EnableVisualEditor' => true,
				'wgVisualEditorSupportedSkins'  => [ 'foo', 'chameleon2' ],
			]
		];

		$provider[ ] = [
			[
				'egChameleon2EnableVisualEditor' => false,
				'wgVisualEditorSupportedSkins'  => [ 'chameleon2', 'foo' => 'chameleon2', 'foo' ],
			],
			[
				'egChameleon2EnableVisualEditor' => false,
				'wgVisualEditorSupportedSkins'  => [ 1 => 'foo' ],
			]
		];

		return $provider;
	}

	/**
	 * Provides test data for the adjustConfiguration test
	 */
	public function adjustConfigurationProvider() {
		$provider = [];

		$provider[ ] = [
			[
				'key1' => 'value1',
				'key2' => 'value2',
			],
			[
				'key2' => 'value2changed',
				'key3' => 'value3changed',
			],
			[
				'key1' => 'value1',
				'key2' => 'value2changed',
				'key3' => 'value3changed',
			]
		];

		return $provider;
	}

}

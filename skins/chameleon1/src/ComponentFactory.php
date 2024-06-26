<?php
/**
 * File containing the ComponentFactory class
 *
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
 * @ingroup   Skins
 */

namespace Skins\Chameleon1;

use DOMDocument;
use DOMElement;
use FileFetcher\FileFetcher;
use MediaWiki\HookContainer\HookContainer;
use MWException;
use QuickTemplate;
use Skins\Chameleon1\Components\Component;
use Skins\Chameleon1\Components\Container;
use Skins\Chameleon1\Components\Structure;

/**
 * @author  Stephan Gambke
 * @since   1.0
 * @ingroup Skins
 */
class ComponentFactory {

	// the root component of the page; should be of type Container
	private ?Structure $rootComponent = null;

	private string $layoutFileName;

	private ?QuickTemplate $skinTemplate;

	private HookContainer $hookContainer;
	private FileFetcher $fileFetcher;

	private const NAMESPACE_HIERARCHY = 'Skins\\Chameleon1\\Components';

	public function __construct( string $layoutFileName, HookContainer $hookContainer, FileFetcher $fileFetcher ) {
		$this->setLayoutFileName( $layoutFileName );
		$this->hookContainer = $hookContainer;
		$this->fileFetcher = $fileFetcher;
	}

	/**
	 * @throws MWException
	 */
	public function getRootComponent(): Structure {
		if ( $this->rootComponent === null ) {

			$document = $this->getDomDocument();

			$roots = $document->getElementsByTagName( 'structure' );

			if ( $roots->length > 0 ) {

				$element = $roots->item( 0 );
				if ( $element instanceof DOMElement ) {
					$this->rootComponent = $this->getComponent( $element );
				}

			} else {
				// TODO: catch other errors, e.g. malformed XML
				throw new MWException( sprintf( '%s: XML description is missing an element: structure.',
					$this->layoutFileName ) );
			}
		}

		return $this->rootComponent;
	}

	private function getDomDocument(): DOMDocument {
		$document = new DOMDocument();

		$document->loadXML( $this->getLayoutXml() );
		$document->normalizeDocument();

		return $document;
	}

	public function getLayoutXml(): string {
		$xml = $this->fileFetcher->fetchFile( $this->layoutFileName );

		$this->hookContainer->run(
			Chameleon1::HOOK_GET_LAYOUT_XML,
			[ &$xml ]
		);

		return $xml;
	}

	private function setLayoutFileName( string $fileName ) {
		$this->layoutFileName = $this->sanitizeFileName( $fileName );
	}

	/**
	 * @return Container
	 * @throws MWException
	 */
	public function getComponent( DOMElement $description, int $indent = 0, string $htmlClassAttribute = '' ) {
		$className = $this->getComponentClassName( $description );
		$component = new $className( $this->getSkinTemplate(), $description, $indent,
			$htmlClassAttribute );

		$children = $description->childNodes;

		foreach ( $children as $child ) {
			if ( $child instanceof DOMElement && strtolower( $child->nodeName ) === 'modification' ) {
				$component = $this->getModifiedComponent( $child, $component );
			}
		}

		return $component;
	}

	/**
	 * @throws MWException
	 * @since 1.1
	 */
	protected function getComponentClassName( DOMElement $description ): string {
		$className = $this->mapDescriptionToClassName( $description );

		if ( !class_exists( $className ) ||
			!is_subclass_of( $className, self::NAMESPACE_HIERARCHY . '\\Component' ) ) {
			throw new MWException( sprintf( '%s (line %d): Invalid component type: %s.',
				$this->layoutFileName, $description->getLineNo(), $description->getAttribute( 'type' ) ) );
		}

		return $className;
	}

	/**
	 * @throws MWException
	 */
	protected function mapDescriptionToClassName( DOMElement $description ): string {
		$nodeName = strtolower( $description->nodeName );

		$mapOfComponentsToClassNames = [
			'structure' => 'Structure',
			'grid' => 'Grid',
			'row' => 'Row',
			'cell' => 'Cell',
			'modification' => 'Silent',
		];

		if ( array_key_exists( $nodeName, $mapOfComponentsToClassNames ) ) {
			return self::NAMESPACE_HIERARCHY . '\\' . $mapOfComponentsToClassNames[ $nodeName ];
		}

		if ( $nodeName === 'component' ) {
			return $this->mapComponentDescriptionToClassName( $description );
		}

		throw new MWException( sprintf( '%s (line %d): XML element not allowed here: %s.',
			$this->layoutFileName, $description->getLineNo(), $description->nodeName ) );
	}

	public function getSkinTemplate(): ?QuickTemplate {
		return $this->skinTemplate;
	}

	public function setSkinTemplate( QuickTemplate $skinTemplate ) {
		$this->skinTemplate = $skinTemplate;
	}

	/**
	 * @return mixed
	 * @throws MWException
	 */
	protected function getModifiedComponent( DOMElement $description, Component $component ) {
		if ( !$description->hasAttribute( 'type' ) ) {
			throw new MWException(
				sprintf( '%s (line %d): Modification element missing an attribute: type.',
					$this->layoutFileName, $description->getLineNo() ) );
		}

		$className = 'Skins\\Chameleon1\\Components\\Modifications\\' .
			$description->getAttribute( 'type' );

		if ( !class_exists( $className ) || !is_subclass_of( $className,
			'Skins\\Chameleon1\\Components\\Modifications\\Modification' ) ) {
			throw new MWException(
				sprintf( '%s (line %d): Invalid modification type: %s.',
					$this->layoutFileName, $description->getLineNo(), $description->getAttribute( 'type' ) ) );
		}

		return new $className( $component, $description );
	}

	private function sanitizeFileName( string $fileName ): string {
		return str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $fileName );
	}

	protected function mapComponentDescriptionToClassName( DOMElement $description ): string {
		if ( $description->hasAttribute( 'type' ) ) {
			$className = $description->getAttribute( 'type' );
			$parent = $description->parentNode;

			if ( $parent instanceof DOMElement && $parent->hasAttribute( 'type' ) ) {
				$fullClassName = implode(
					'\\',
					[
						self::NAMESPACE_HIERARCHY,
						$parent->getAttribute( 'type' ),
						$className
					]
				);

				if ( class_exists( $fullClassName ) ) {
					return $fullClassName;
				}
			}

			$chameleon1ClassName = implode( '\\', [ self::NAMESPACE_HIERARCHY, $className ] );
			if ( class_exists( $chameleon1ClassName ) ) {
				return $chameleon1ClassName;
			}

			return $className;

		}

		return self::NAMESPACE_HIERARCHY . '\\Container';
	}
}

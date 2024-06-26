<?php
/**
 * This file is part of the MediaWiki skin Chameleon1.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
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

namespace Skins\Chameleon1\Tests\Util;

use DOMDocument;
use DOMElement;

use RuntimeException;

// @codingStandardsIgnoreStart
/**
 * @group skins-chameleon1
 * @group mediawiki-databaseless
 *
 * @author mwjames
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
// @codingStandardsIgnoreEnd
class DocumentElementFinder {

	protected $file = null;
	protected $document = null;

	/**
	 * @since 1.0
	 *
	 * @param string $file
	 */
	public function __construct( $file ) {
		$this->file = $file;
	}

	/**
	 * @since 1.0
	 *
	 * @param string $type
	 *
	 * @return DOMElement|null
	 */
	public function getComponentByTypeAttribute( $type ) {
		$elements = $this->getComponentsByTypeAttribute( $type );

		if ( count( $elements ) > 0 ) {
			return array_shift( $elements );
		}

		return null;
	}

	/**
	 * @since 1.0
	 *
	 * @param string $type
	 *
	 * @return DOMElement[]
	 */
	public function getComponentsByTypeAttribute( $type ) {
		$elements = [];

		$elementList = $this->getDocument()->getElementsByTagName( strtolower( $type ) );
		foreach ( $elementList as $element ) {
			$elements[] = $element;
		}

		$elementList = $this->getDocument()->getElementsByTagName( '*' );
		foreach ( $elementList as $element ) {
			if ( $element instanceof DOMElement && $element->hasAttribute( 'type' ) &&
				$element->getAttribute( 'type' ) === $type ) {
				$elements[] = $element;
			}
		}

		return $elements;
	}

	/**
	 * @return DOMDocument
	 */
	protected function getDocument() {
		if ( $this->document !== null ) {
			return $this->document;
		}

		$file = str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $this->file );

		if ( !is_readable( $file ) ) {
			throw new RuntimeException( "Expected an accessible {$file} path" );
		}

		$document = new DOMDocument;
		$document->validateOnParse = true;

		if ( !$document->load( $file ) ) {
			throw new RuntimeException( "Unable to load {$file} file" );
		}

		$document->normalizeDocument();

		return $document;
	}

}

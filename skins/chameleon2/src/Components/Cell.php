<?php
/**
 * File holding the Cell class
 *
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

namespace Skins\Chameleon2\Components;

use Skins\Chameleon2\Chameleon2Template;

/**
 * The Cell class.
 *
 * @ingroup Skins
 */
class Cell extends Container {

	/**
	 * @param Chameleon2Template $template
	 * @param \DOMElement|null $domElement
	 * @param int $indent
	 */
	public function __construct( Chameleon2Template $template, \DOMElement $domElement = null,
		$indent = 0 ) {

		parent::__construct( $template, $domElement, $indent );

		$this->addClasses( "col" );
	}

}

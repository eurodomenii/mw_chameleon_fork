<?php
/**
 * File holding the Cell class
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
 * @ingroup Skins
 */

namespace Skins\Chameleon1\Components;

use Skins\Chameleon1\Chameleon1Template;

/**
 * The Cell class.
 *
 * @ingroup Skins
 */
class Cell extends Container {

	/**
	 * @param Chameleon1Template $template
	 * @param \DOMElement|null $domElement
	 * @param int $indent
	 */
	public function __construct( Chameleon1Template $template, \DOMElement $domElement = null,
		$indent = 0 ) {

		parent::__construct( $template, $domElement, $indent );

		$this->addClasses( "col" );
	}

}

<?php
/**
 * File containing the HideFor class
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

namespace Skins\Chameleon1\Components\Modifications;

use Skins\Chameleon1\Components\Silent;
use Skins\Chameleon1\PermissionsHelper;

/**
 * HideFor class
 *
 * @author Stephan Gambke
 * @since 1.1
 * @ingroup Skins
 */
class HideFor extends Modification {

	private $permissionsHelper;

	/**
	 * This method checks if the restriction is applicable and if necessary
	 * replaces the decorated component by a Silent component
	 *
	 * @throws \MWException
	 */
	protected function applyModification() {
		if ( $this->isHidden() ) {

			$component = $this->getComponent();
			$this->setComponent( new Silent( $component->getSkinTemplate(), $component->getDomElement(),
				$component->getIndent() ) );

		}
	}

	/**
	 * @return bool
	 * @throws \MWException
	 */
	private function isHidden() {
		$permissionsHelper = $this->getPermissionsHelper();
		return $permissionsHelper->userHasGroup( 'group' ) &&
			$permissionsHelper->userHasPermission( 'permission' ) &&
			$permissionsHelper->pageIsInNamespace( 'namespace' );
	}

	/**
	 * @return PermissionsHelper
	 */
	private function getPermissionsHelper() {
		if ( $this->permissionsHelper === null ) {
			$this->permissionsHelper = new PermissionsHelper( $this->getSkinTemplate()->getSkin(),
				$this->getDomElementOfModification(), true );
		}

		return $this->permissionsHelper;
	}
}

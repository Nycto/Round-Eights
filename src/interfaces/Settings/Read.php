<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of Round Eights.
 *
 * Round Eights is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * Round Eights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with Round Eights. If not, see <http://www.RoundEights.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Settings
 */

namespace r8\iface\Settings;

/**
 * Settings objects that provide a readable interface
 */
interface Read
{

    /**
     * Returns a the value of a setting
     *
     * @param String $group The higher level group in which to look for the key
     * @param String $key The key to pull
     * @return Mixed
     */
    public function get ( $group, $key );

    /**
     * Returns whether a setting exists
     *
     * @param String $group The higher level group in which to look for the key
     * @param String $key TThe key to look up
     * @return Boolean
     */
    public function exists ( $group, $key );

    /**
     * Returns all the values from a group as a Key/Value list
     *
     * @param String $group The higher level group to pull
     * @return Array
     */
    public function getGroup ( $group );

}

?>
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
 * @package Finder
 */

namespace r8;

/**
 * The base File Finder interface
 */
class Finder implements \r8\iface\Finder
{

    /**
     * Constructor...
     *
     * @param \r8\iface\Finder $finder The modifier to use
     */
    public function __construct ( \r8\iface\Finder $finder )
    {

    }

    /**
     * Attempts to find a file given a relative path
     *
     * @param String $file The relative path of the file being looked for
     * @return String|NULL Returns the path of the found file, relative to the
     *      given base. Returns NULL if the file could not be found
     */
    public function find ( $file )
    {

    }

}

?>
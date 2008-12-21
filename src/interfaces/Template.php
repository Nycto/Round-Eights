<?php
/**
 * Core Template Class
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package FileFinder
 */

namespace cPHP\iface;

/**
 * Interface to allow classes to be used as templates
 */
interface Template
{

    /**
     * Outputs the rendered template to the client
     *
     * @return object Returns a self reference
     */
    public function display ();

    /**
     * Returns the rendered template as a string
     *
     * @return String
     */
    public function render ();

    /**
     * Returns the rendered template as a string
     *
     * @return String
     */
    public function __toString ();

}

?>
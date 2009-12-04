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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Input
 */

namespace r8\Input;

/**
 * Provides an Input wrapper for an array reference
 */
class Reference implements \r8\iface\Input
{

    /**
     * The array being wrapped
     *
     * @var Array
     */
    private $data;

    /**
     * Constructor...
     *
     * @param Array $data The array being wrapped
     */
    public function __construct ( array &$data )
    {
        $this->data =& $data;
    }

    /**
     * Returns the value of an input value
     *
     * @param String $key The value to retrieve
     * @return mixed Returns the cached value
     */
    public function get ( $key )
    {
        return isset( $this->data[$key] ) ? $this->data[$key] : NULL;
    }

    /**
     * Returns whether an input value has been set
     *
     * @param String $key The key for the value
     * @return Boolean
     */
    public function exists ( $key )
    {
        return isset( $this->data[$key] );
    }

    /**
     * Returns the input data as an array
     *
     * @return Array
     */
    public function toArray ()
    {
        return $this->data;
    }

}

?>
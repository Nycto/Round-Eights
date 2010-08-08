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
 * @package Exception
 */

namespace r8;

/**
 * The base exception class
 */
class Exception extends \Exception
{

    /**
     * The name of the exception
     */
    const TITLE = "General Exception";

    /**
     * A description of this Exception
     */
    const DESCRIPTION = "General Errors";

    /**
     * Stores specific exception data. Each item has a label and a value
     *
     * @var Array
     */
    private $data = array();

    /**
     * Returns the backtrace trace details for a given offset
     *
     * @param integer $offset The offset of the trace
     * @param integer $wrapFlag The offset wrapping mode to use
     * @return array A list of the backtrace details at the given offset
     */
    public function getTraceByOffset ($offset, $wrapFlag = \r8\num\OFFSET_RESTRICT)
    {
        $trace = $this->getTrace();
        if (count($trace) <= 0)
            return NULL;

        return \r8\Backgtrace\Event::from(
            \r8\ary\offset($trace, $offset, $wrapFlag)
        );
    }

    /**
     * Adds a piece of data to this instance
     *
     * @param String $label The label of this data
     * @param mixed $data The actual data
     * @return \r8\Exception Returns a self reference
     */
    public function addData ( $label, $value )
    {
        $this->data[ trim((string) $label) ] = $value;
        return $this;
    }

    /**
     * Returns the data list for the current instance
     *
     * @return Array
     */
    public function getData ()
    {
        return $this->data;
    }

    /**
     * Returns the value of a specific piece of data
     *
     * @param String $label The label to fetch
     * @return mixed Returns the value. If it the requested data isn't set, NULL is returned
     */
    public function getDataValue ( $label )
    {
        $label = trim((string) $label);

        if ( array_key_exists($label, $this->data) )
            return $this->data[ $label ];
        else
            return NULL;
    }

    /**
     * Return a string that briefly describes this exception
     *
     * @return String
     */
    public function getDescription ()
    {
        return static::TITLE ." (". static::DESCRIPTION .")";
    }

    /**
     * Returns a string about this exception
     *
     * @return String
     */
    public function __toString ()
    {
        if ( \r8\Env::request()->isCLI() )
            $formatter = new \r8\Error\Formatter\Text( \r8\Env::request() );
        else
            $formatter = new \r8\Error\Formatter\HTML( \r8\Env::request() );

        return $formatter->format( $this );
    }

}

?>
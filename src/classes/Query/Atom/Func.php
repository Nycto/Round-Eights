<?php
/**
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
 * @package Query
 */

namespace cPHP\Query\Atom;

/**
 * Represents a func name in a SQL query
 */
class Func extends \cPHP\Query\Atom
{

    /**
     * The function name
     *
     * @var String
     */
    private $func;

    /**
     * The list of arguments for the function
     *
     * @var array This is a list of \cPHP\iface\Query\Atom objects
     */
    private $args = array();

    /**
     * Constructor...
     *
     * @param String $func The function name
     * @param \cPHP\iface\Query\Atom $args... Any arguments to pass to the function
     */
    public function __construct ( $func )
    {
        $func = \cPHP\str\stripW( $func, \cPHP\str\ALLOW_UNDERSCORES );
        $func = strtoupper( $func );

        if ( empty($func) )
            throw new \cPHP\Exception\Argument( 0, "Function Name", "Must not be empty" );

        $this->func = $func;

        if ( func_num_args() > 1 )
            $this->setArgs( func_get_args() );
    }

    /**
     * Returns the Field the column will be compared to
     *
     * @return mixed
     */
    public function getFunc ()
    {
        return $this->func;
    }

    /**
     * Returns the argument list
     *
     * @return array An array of \cPHP\iface\Query\Atom objects
     */
    public function getArgs ()
    {
        return $this->args;
    }

    /**
     * Adds an argument to the end of argument list
     *
     * @param \cPHP\iface\Query\Atom $arg The argument to add
     * @return \cPHP\Query\Atom\Func Returns a self reference
     */
    public function addArg ( \cPHP\iface\Query\Atom $arg )
    {
        $this->args[] = $arg;
        return $this;
    }

    /**
     * Sets the list of arguments to the values contained in an array
     *
     * @param array $args An array of \cPHP\iface\Query\Atom objects
     * @return \cPHP\Query\Atom\Func Returns a self reference
     */
    public function setArgs ( array $args )
    {
        $this->args = array();

        foreach ( $args AS $arg )
        {
            if ( $arg instanceof \cPHP\iface\Query\Atom )
                $this->args[] = $arg;
        }

        return $this;
    }

    /**
     * Returns the SQL this atom represents
     *
     * @param \cPHP\iface\DB\Link $link The database connection this atom
     * 		is being created against. This is being passed in for escaping
     * 		purposes
     * @return String
     */
    public function toAtomSQL( \cPHP\iface\DB\Link $link )
    {
        $args = array();

        foreach( $this->args AS $arg )
        {
            $args[] = $arg->toAtomSQL( $link );
        }

        return $this->func ."(". implode(", ", $args) .")";
    }

}

?>
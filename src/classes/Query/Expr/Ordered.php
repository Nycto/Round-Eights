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

namespace cPHP\Query\Expr;

/**
 * An ordered field expression
 */
class Ordered implements \cPHP\iface\Query\Ordered
{

    /**
     * The atom being sorted
     *
     * @var \cPHP\iface\Query\Atom
     */
    private $atom;

    /**
     * The order in which this atom should be sorted
     *
     * @var String
     */
    private $order;

    /**
     * Instantiates a new instance of this object from a string
     *
     * @param String $string The string to parse into an object
     * @return \cPHP\Query\Expr\Ordered
     */
    static public function fromString ( $string )
    {
        $string = \cPHP\strval( $string );

        preg_match('/^(.*?)(\bASC|\bDESC)?$/i', $string, $matches);

        return new self(
            \cPHP\Query\Atom\Field::fromString( $matches[1] ),
            isset($matches[2]) ? $matches[2] : null
        );
    }

    /**
     * Constructor...
     *
     * @param \cPHP\iface\Query\Atom $atom The atom being ordered
     * @param String $order The order in which this atom should be sorted
     */
    public function __construct ( \cPHP\iface\Query\Atom $atom, $order = null )
    {
        $this->atom = $atom;
        $this->setOrder( $order );
    }

    /**
     * Returns the Atom being ordered
     *
     * @return \cPHP\iface\Query\Atom
     */
    public function getAtom ()
    {
        return $this->atom;
    }

    /**
     * Returns the Order the atom will be sorted in
     *
     * @return String|NULL Returns NULL if the order hasn't been specified
     */
    public function getOrder ()
    {
        return $this->order;
    }

    /**
     * Sets the order in which this atom should be sorted
     *
     * @param String $order The order of the atom
     * @return \cPHP\Query\Expr\Ordered Returns a self reference
     */
    public function setOrder ( $order )
    {
        if ( \is_null($order) ) {
            $this->order = null;
        }

        else if ( \is_bool($order) || \is_int($order) || \is_float($order) ) {
            $this->order = $order ? "ASC" : "DESC";
        }

        else {
            $order = strtoupper( \cPHP\str\stripW( $order ) );

            if ( $order == "ASC" || $order == "DESC" )
                $this->order = $order;
            else
                $this->order = null;
        }

        return $this;
    }

    /**
     * Returns the SQL string for this expression
     *
     * @param \cPHP\iface\DB\Link $link The database connection this atom
     * 		is being created against. This is being passed in for escaping
     * 		purposes
     * @return String
     */
    public function toOrderedSQL( \cPHP\iface\DB\Link $link )
    {
        return $this->atom->toAtomSQL( $link )
            .( $this->order ? " ". $this->order : "" );
    }

}

 ?>
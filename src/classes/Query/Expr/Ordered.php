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
 * @package Query
 */

namespace r8\Query\Expr;

/**
 * An ordered field expression
 */
class Ordered implements \r8\iface\Query\Ordered
{

    /**
     * The atom being sorted
     *
     * @var \r8\iface\Query\Atom
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
     * @return \r8\Query\Expr\Ordered
     */
    static public function fromString ( $string )
    {
        $string = (string) $string;

        preg_match('/^(.*?)(\bASC|\bDESC)?$/i', $string, $matches);

        return new self(
            \r8\Query\Atom\Field::fromString( $matches[1] ),
            isset($matches[2]) ? $matches[2] : null
        );
    }

    /**
     * Constructor...
     *
     * @param \r8\iface\Query\Atom $atom The atom being ordered
     * @param String $order The order in which this atom should be sorted
     */
    public function __construct ( \r8\iface\Query\Atom $atom, $order = null )
    {
        $this->atom = $atom;
        $this->setOrder( $order );
    }

    /**
     * Returns the Atom being ordered
     *
     * @return \r8\iface\Query\Atom
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
     * @return \r8\Query\Expr\Ordered Returns a self reference
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
            $order = strtoupper( \r8\str\stripW( $order ) );

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
     * @param \r8\iface\DB\Link $link The database connection this atom
     *      is being created against. This is being passed in for escaping
     *      purposes
     * @return String
     */
    public function toOrderedSQL( \r8\iface\DB\Link $link )
    {
        return $this->atom->toAtomSQL( $link )
            .( $this->order ? " ". $this->order : "" );
    }

}

 ?>
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
 * @package Query
 */

namespace r8\Query\Expr;

/**
 * An aliased field expression
 */
class Aliased implements \r8\iface\Query\Selectable
{

    /**
     * The atom that will be selected
     *
     * @var \r8\iface\Query\Atom
     */
    private $atom;

    /**
     * The alias to select this field as, if any
     *
     * @var String
     */
    private $alias;

    /**
     * Instantiates a new instance of this object from a string
     *
     * @param String $string The string to parse into an object
     * @return \r8\Query\Expr\Aliased
     */
    static public function fromString ( $string )
    {
        list( $string, $alias ) = \r8\Query::parseSQLAlias( $string );

        $atom = \r8\Query\Atom\Field::fromString( $string );

        return new self( $atom, $alias );
    }

    /**
     * Constructor...
     *
     * @param \r8\iface\Query\Atom $atom The atom to select
     * @param String $alias The field name alias, if any
     */
    public function __construct ( \r8\iface\Query\Atom $atom, $alias = null )
    {
        $this->atom = $atom;
        $this->setAlias( $alias );
    }

    /**
     * Returns the Atom being aliased
     *
     * @return \r8\iface\Query\Atom
     */
    public function getAtom ()
    {
        return $this->atom;
    }

    /**
     * Returns the Alias of this select field
     *
     * @return String|NULL Returns Null if there is no alias
     */
    public function getAlias ()
    {
        return $this->alias;
    }

    /**
     * Sets the alias of this field
     *
     * @param String $alias The field name alias
     * @return \r8\Query\Expr\Select Returns a self reference
     */
    public function setAlias ( $alias )
    {
        $alias = \r8\str\stripW( $alias, \r8\str\ALLOW_UNDERSCORES );

        $this->alias = empty($alias) ? NULL : $alias;

        return $this;
    }

    /**
     * Returns the SQL string for this expression
     *
     * @param \r8\iface\DB\Link $link The database connection this atom
     * 		is being created against. This is being passed in for escaping
     * 		purposes
     * @return String
     */
    public function toSelectSQL( \r8\iface\DB\Link $link )
    {
        return $this->atom->toAtomSQL( $link )
            .( $this->alias ? " AS ". $this->alias : "" );
    }

}

 ?>
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
 * An aliased field expression
 */
class Aliased
{

    /**
     * The atom that will be selected
     *
     * @var \cPHP\iface\Query\Atom
     */
    private $atom;

    /**
     * The alias to select this field as, if any
     *
     * @var String
     */
    private $alias;

    /**
     * Constructor...
     *
     * @param \cPHP\iface\Query\Atom $atom The atom to select
     * @param String $alias The field name alias, if any
     */
    public function __construct ( \cPHP\iface\Query\Atom $atom, $alias = null )
    {
        $this->atom = $atom;
        $this->setAlias( $alias );
    }

    /**
     * Returns the Atom being aliased
     *
     * @return \cPHP\iface\Query\Atom
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
     * @return \cPHP\Query\Expr\Select Returns a self reference
     */
    public function setAlias ( $alias )
    {
        $alias = \cPHP\str\stripW( $alias, \cPHP\str\ALLOW_UNDERSCORES );

        $this->alias = empty($alias) ? NULL : $alias;

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
    public function toAliasedSQL( \cPHP\iface\DB\Link $link )
    {
        return $this->atom->toAtomSQL( $link )
            .( $this->alias ? " AS ". $this->alias : "" );
    }

}

 ?>
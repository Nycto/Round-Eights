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

namespace r8;

/**
 * A helper class for Query objects
 */
class Query
{

    /**
     * Parses a SQL field, string, or database name into an array of it's parts
     *
     * @param String $name The SQL string to parse
     * @return array Returns an array of the normalized parts. Each element
     */
    static public function parseSQLName ( $name )
    {
        $name = (string) $name;

        // If it doesn't contain a 'dot', then things are simple
        if ( !\r8\str\contains( ".", $name ) ) {
            $result = array( $name );
        }

        // If it has a dot, but no back ticks, take an easy out
        else if ( !\r8\str\contains( "`", $name ) ) {
            $result = explode( ".", $name );
        }

        // Otherwise, we need to do some parsing. Ugh.
        else {
            $parser = new \r8\Quoter;
            $parsed = $parser->clearQuotes()->setQuote("`")->parse( $name );
            $result = $parsed->setIncludeQuoted( FALSE )->explode(".");
        }

        foreach ( $result AS $key => $part ) {
            $result[ $key ] = trim( trim( trim( $part ), "`" ) );
        }

        return array_filter( $result );
    }

    /**
     * Parses a string into a SQL expression and it's alias
     *
     * @param String $name The SQL string to parse
     * @return array Returns an array where the first element is the
     * 		SQL expression and the second is the alias
     */
    static public function parseSQLAlias ( $string )
    {
        $string = (string) $string;

        // If there is no obvious alias, take an easy out
        if ( !\r8\str\contains(" AS ", $string) ) {
            $alias = null;
        }

        // If it doesn't contain any backtics, there is no need to parse
        else if ( !\r8\str\contains("`", $string) ) {
            list( $string, $alias ) = explode( " AS ", $string, 2 );
            $alias = trim( $alias );
        }

        // Otherwise, we need to parse within the context of the backtics
        else {
            $parser = new \r8\Quoter;
            list( $string, $alias ) = $parser->clearQuotes()
                ->setQuote("`")
                ->parse( $string )
                ->setIncludeQuoted( FALSE )
                ->explode(" AS ");
            $alias = trim( $alias );
        }

        $string = trim( $string );
        if ( \r8\IsEmpty($string) )
            $string = null;

        $alias = \r8\str\stripW( $alias );
        if ( \r8\IsEmpty($alias) )
            $alias = null;

        return array( $string, $alias );
    }

    /**
     * Inline instantiation method for Select objects
     *
     * @return \r8\Query\Select
     */
    static public function select ()
    {
        return new \r8\Query\Select;
    }

}

?>
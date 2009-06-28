<?php
/**
 * Function Currying
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
 * @package Curry
 */

namespace cPHP;

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
        $name = \cPHP\strval( $name );

        // If it doesn't contain a 'dot', then things are simple
        if ( !\cPHP\str\contains( ".", $name ) ) {
            $result = array( $name );
        }

        // If it has a dot, but no back ticks, take an easy out
        else if ( !\cPHP\str\contains( "`", $name ) ) {
            $result = explode( ".", $name );
        }

        // Otherwise, we need to do some parsing. Ugh.
        else {
            $parser = new \cPHP\Quoter;
            $parsed = $parser->clearQuotes()->setQuote("`")->parse( $name );
            $result = $parsed->setIncludeQuoted( FALSE )->explode(".");
        }

        foreach ( $result AS $key => $part ) {
            $result[ $key ] = trim( trim( trim( $part ), "`" ) );
        }

        return $result;
    }

}

?>
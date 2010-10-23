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
 * @package Words
 */

namespace r8;

/**
 * A set of helper functions for manipulating english words
 */
class Words
{

    /**
     * A list of irregular plurals
     *
     * @var Array
     */
    static private $irregular = array(
        // Irregular plurals
        'person' => 'people', 'man' => 'men', 'child' => 'children',
        'ox' => 'oxen', 'staff' => 'staves', 'mouse' => 'mice',
        'louse' => 'lice', 'index' => 'indices', 'matrix' => 'matrices',
        'vertex' => 'vertices', 'octopus' => 'octopi', 'virus' => 'viri',

        // Plural == Singular
        'deer' => 'deer', 'moose' => 'moose', 'sheep' => 'sheep',
        'bison' => 'bison', 'salmon' => 'salmon', 'pike' => 'pike',
        'trout' => 'trout', 'fish' => 'fish', 'swine ' => 'swine',
        'equipment' => 'equipment', 'information' => 'information',
        'rice' => 'rice', 'money' => 'money', 'species' => 'species',
        'series' => 'series',
    );

    /**
     * Makes a word plural
     *
     * @param String $string The value to pluralize
     * @param Integer $count If the pluralization should only be done based on
     *      a number, pass it here
     */
    static public function pluralize ( $string, $count = 2 )
    {
        $string = trim( (string) $string );

        if ( $count == 1 || \r8\isEmpty( $string ) )
            return $string;

        $word = strtolower( $string );

        if ( isset( self::$irregular[$word] ) ) {
            if ( ctype_upper($string) )
                return strtoupper( self::$irregular[$word] );
            else if ( ctype_upper($string[0]) )
                return ucfirst( self::$irregular[$word] );
            else
                return self::$irregular[$word];
        }

        // Grab the final characters
        $lastOne = substr($word, -1);
        $lastTwo = substr($word, -2);
        $lastThree = substr($word, -3);

        // Represents how many letters should be pulled off the end of the word
        $trim = 0;

        switch ( $lastOne )
        {
            case "s":
                if ( $lastTwo == "is" ) {
                    $trim = -2;
                    $end = "es";
                }
                else {
                    $end = "es";
                }
                break;

            case "z":
            case "x":
                $end = "es";
                break;

            case "y":
                if (
                    $lastThree != "quy"
                    && in_array($lastTwo, array('ay', 'ey', 'oy', 'uy'))
                ) {
                    $end = "s";
                }
                else {
                    $trim = -1;
                    $end = "ies";
                }
                break;

            case "f":
                if ( $lastTwo == "ff" ) {
                    $end = "s";
                }
                else {
                    $trim = -1;
                    $end = "ves";
                }
                break;

            case "o":
                $end = "es";
                break;

            default:
                if ( $lastThree == "ium" ) {
                    $end = "a";
                    $trim = -2;
                }
                else if ( in_array($lastTwo, array('ch', 'ss', 'sh')) ) {
                    $end = "es";
                }
                else {
                    $end = "s";
                }
                break;
        }

        if ( ctype_upper( substr($string, -1) ) )
            $end = strtoupper($end);

        return $trim == 0
            ? $string . $end
            : substr($string, 0, $trim) . $end;
    }

    /**
     * Makes a word singular
     *
     * @param String $string The word to make singular
     */
    static public function singularize ( $string )
    {
        $string = trim( (string) $string );

        if ( \r8\isEmpty( $string ) )
            return $string;

        $word = strtolower( $string );

        $irregular = array_search( $word, self::$irregular );
        if ( $irregular !== FALSE ) {
            if ( ctype_upper($string) )
                return strtoupper($irregular);
            else if ( ctype_upper($string[0]) )
                return ucfirst($irregular);
            else
                return $irregular;
        }

        // Grab the final characters
        $lastTwo = substr($word, -2);
        $lastThree = substr($word, -3);
        $lastFour = substr($word, -4);
        $lastFive = substr($word, -5);

        // Represents how many letters should be pulled off the end of the word
        $trim = -1;
        $end = "";

        if ( $lastThree == "ies" ) {
            $trim = -3;
            $end = "y";
        }
        else if ( $lastThree == "ses" ) {
            if ( $lastFour == "sses" || $lastFour == "ases" )
                $trim = -2;
            else if ( $lastFour == "uses" && $lastFive != "ouses" )
                $trim = -2;
            else
                $trim = -1;
        }
        else if ( $lastThree == "ves" ) {
            if ( in_array($lastFour, array("aves", "eves", "ives", "oves", "uves")) ) {
                $trim = -1;
            }
            else {
                $trim = -3;
                $end = "f";
            }
        }
        else if ( $lastTwo == "es" ) {
            if ( $lastThree == "fes" )
                $trim = -1;
            else
                $trim = -2;
        }
        else if ( $lastTwo == "ia" ) {
            $trim = -1;
            $end = "um";
        }

        if ( ctype_upper( substr($string, -1) ) )
            $end = strtoupper($end);

        return $trim == 0
            ? $string . $end
            : substr($string, 0, $trim) . $end;
    }

    /**
     * Translate a number to a string with a suffix
     *
     * For example, 1 becomes "1st", 2 becomes "2nd"
     *
     * @param Integer $integer The number to convert to an ordinal
     * @return String Returns a string version of the given integer,
     *      with a suffix tacked on
     */
    static public function ordinal ($integer)
    {
        $integer = (string) (int) $integer;

        if ( \r8\num\between( abs( substr($integer, -2) ), 11, 13, TRUE))
            return $integer ."th";

        switch( substr($integer, -1) ) {
            case "1":
                return $integer ."st";
            case "2":
                return $integer ."nd";
            case "3":
                return $integer ."rd";
            default:
                return $integer . "th";
        }
    }

}


<?php
/**
 * String functions
 *
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
 * @package strings
 */

namespace r8\str;

/**
 * Function constants
 */
const ALLOW_FALSE = 1;
const ALLOW_BLANK = 2;
const ALLOW_SPACES = 4;
const ALLOW_UNDERSCORES = 8;
const ALLOW_NEWLINES = 16;
const ALLOW_TABS = 32;
const ALLOW_DASHES = 64;
const ALLOW_ASCII = 128;

/**
 * Translate a number to a string with a suffix
 *
 * For example, 1 becomes "1st", 2 becomes "2nd"
 *
 * @param Integer $integer The number to convert to an ordinal
 * @return String Returns a string version of the given integer, with a suffix tacked on
 */
function int2Ordinal ($integer)
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

/**
 * Returns true if $needle is in haystack, false if it isn't
 *
 * @param String $needle The string you are trying to find
 * @param String $haystack The string you are searching for the needle in
 * @param Boolean $ignoreCase Whether the search should be case sensitive
 * @return Boolean Returns whether the needle exists in the haystack
 */
function contains($needle, $haystack, $ignoreCase = TRUE)
{
    if ($ignoreCase)
        return ( stripos( (string) $haystack, (string) $needle ) === FALSE) ? FALSE : TRUE;
    else
        return ( strpos( (string) $haystack, (string) $needle ) === FALSE) ? FALSE : TRUE;
}

/**
 * Finds every occurance of the given needle in the haystack and returns their offsets
 *
 * @param String $needle The string being searched for
 * @param String $haystack The string that you trying to find the needle in
 * @param Boolean $ignoreCase Whether the search should be case sensitive
 * @return object Returns an array containing the found offsets. If the needle is not contained in the haystack, an empty array is returned
 */
function offsets ($needle, $haystack, $ignoreCase = TRUE)
{
    $ignoreCase = (bool) $ignoreCase;
    $needle = (string) $needle;
    $haystack = (string) $haystack;

    if (empty($needle))
        throw new \r8\Exception\Argument(0, 'needle', 'Must not be empty');

    if (!\r8\str\contains($needle, $haystack, $ignoreCase))
        return array();

    $count = $ignoreCase ? \r8\str\substr_icount($haystack, $needle) : substr_count($haystack, $needle);

    $found = array();

    $offset = 0;

    $length = strlen($needle);

    for ($i = 0; $i < $count; $i++) {
        $found[] =  $ignoreCase ? stripos( $haystack, $needle, $offset ) : strpos( $haystack, $needle, $offset );
        $offset = end( $found ) + $length;
    }

    return $found;
}

/**
 * Get the position of the nth needle in the haystack
 *
 * @param String $needle The string being searched for
 * @param String $haystack The string that you trying to find the needle in
 * @param Boolean $ignoreCase Whether the search should be case sensitive
 * @param Integer $wrapFlag How to handle offset wrapping when the offset, per {@link calcWrapFlag()}.
 * @return Integer Returns the offsets, from 0, of the needle in the haystack
 */
function npos ($needle, $haystack, $offset, $ignoreCase = TRUE, $wrapFlag = \r8\ary\OFFSET_RESTRICT)
{
    $found = \r8\str\offsets($needle, $haystack, $ignoreCase);

    if (count($found) <= 0)
        return FALSE;
    else
        return \r8\ary\offset($found, $offset, $wrapFlag);
}

/**
 * Changes any all caps words to upper case the first letter
 *
 * @param String $string The string to convert
 * @return String Returns the corrected version of the string
 */
function unshout ($string)
{

    return preg_replace_callback(
            '/\b(\w)(\w*[A-Z]\w*)\b/',
            function ( $match ) {
                    return $match[1] . strtolower($match[2]);
                },
            (string) $string
        );

}

/**
 * Removes everything but letters and numbers from a string
 *
 * This method is named after the regular expression control character used to
 * represent alpha-numeric characters
 *
 * @param String $string The value being processed
 * @param String $allow Any additional characters to allow
 * @return String Returns the stripped string
 */
function stripW ($string, $allow = "")
{
    $allow = (string) $allow;

    if ( $allow !== "" )
        $allow = preg_quote($allow);

    return preg_replace( "/[^a-z0-9". $allow ."]/i", "", (string) $string );
}

/**
 * Strips all non-printable characters from a string
 *
 * @param String $string The value being processed
 * @return String Returns the stripped string
 */
function stripNoPrint ($string)
{
    return preg_replace( '/[^\x20-\x7E]/', '', (string) $string );
}

/**
 * Removes repetitions from a string
 *
 * @param String $string The string to strip of repeats
 * @param String|Array $repeated The repeated string to remove. If it is an array, it will remove multiple duplicates at once
 * @param Boolean $ignoreCase Whether the replace should be case sensitive
 * @return String Returns the string with the repeated values removed
 */
function stripRepeats ($string, $repeated, $ignoreCase = TRUE)
{
    if (is_array($repeated) ) {

        $repeated = \r8\ary\flatten($repeated);
        $repeated = \r8\ary\stringize($repeated);

        foreach( $repeated AS $key => $value ) {
            $repeated[ $key ] = preg_quote($value, "/");
        }
        $repeated = implode("|", $repeated);
    }
    else {
        $repeated = preg_quote((string) $repeated, '/');
    }

    if ( \r8\isEmpty( $repeated, \r8\ALLOW_SPACES ) )
        throw new \r8\Exception\Argument(1, 'Repeated', 'Must not be empty');

    return preg_replace(
            '/('. $repeated .')\1+/'. ($ignoreCase?'i':''),
            '\1',
            $string
        );

}

/**
 * Collapses long words down with elipses
 *
 * @param String $string The string to scan for long words
 * @param Integer $maxLength The maximum length a word can be
 * @param Integer|Boolean $trimTo How short to make the long words
 * @param String $glue The delimiter to replace the removed characters with
 * @return String Returns the string with all the long words replaced
 */
function truncateWords ( $string, $maxLength, $trimTo = FALSE, $glue = '...' )
{

    $string = (string) $string;
    if ( \r8\isEmpty($string) )
        return '';

    $glue = (string) $glue;

    // Maxlength must be at least 1
    $maxLength = max( $maxLength, 1 );

    // If they didn't define a trimTo, then default it to 2/3 the max length
    if ( \r8\isVague($trimTo) || (int) $trimTo <= 0 )
        $trimTo = ceil($maxLength * (2 / 3));

    // The trimTo length can't be greater than the max length
    // The trimTo must be at least the length of the glue + characters on each side
    $trimTo = max($trimTo, strlen($glue) + 2);

    // We now double check to make sure the maxlength is at least equal to the trimTo
    $maxLength = max( $maxLength, $trimTo );


    $first = ceil( ( $trimTo - strlen( $glue ) ) / 2);
    $third = $trimTo - strlen( $glue ) - $first;
    $second = $maxLength - $trimTo + strlen( $glue );

    return preg_replace(
            '/\b(\w{'. $first .'})\w{'. $second .',}(\w{'. $third .'})\b/i',
            '\1'. $glue .'\2',
            $string
        );

}

/**
 * Removes quoted text from a string.
 *
 * To define your own quotes, simply add them as arguments. For a more advanced
 * Quotation interface, see the Quoted Class.
 *
 * @param String $string The string to remove the quoted values from
 * @param String $quotes Strings that should be treated as quotes
 * @return String Returns the string with all quoted segments removed
 */
function stripQuoted ( $string, $quotes = array( "'", '"' ) )
{

    $string = (string) $string;

    $quotes = (array) $quotes;
    $quotes = \r8\ary\flatten( $quotes );
    $quotes = \array_map( 'trim', $quotes );
    $quotes = \array_unique($quotes);
    $quotes = \r8\ary\compact($quotes);

    $quoteString = array_map(
            r8( new \r8\Curry\Call("preg_quote") )->setRight("/")->setLimit(1),
            $quotes
        );
    $quoteString = implode("|", $quoteString);

    $split = preg_split(
            '/(?<!\\\\)(?:\\\\\\\\)*('
                . $quoteString
                .')/i',
            $string,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

    $curQuote = NULL;
    $output = "";

    foreach ($split AS $key => $part) {

        if ( is_null($curQuote) && in_array($part, $quotes) )
            $curQuote = $part;

        else if (is_null($curQuote))
            $output .= $part;

        else if ( in_array($part, $quotes) )
            $curQuote = null;

    }

    return $output;

}

/**
 * Case insensitive version of substr_count
 *
 * @param String $haystack The string to look for the needle in
 * @param String $needle The string being searched for
 * @param Integer $offset The offset to start searching from
 * @param Integer|Boolean $length If set, the number of characters to search starting at the offset
 * @return Integer Returns the number of times the needle appears in the haystack
 */
function substr_icount ( $haystack, $needle, $offset = 0, $length = FALSE )
{
    $haystack = strtolower((string) $haystack);
    $needle = strtolower((string) $needle);

    if (!is_int($length))
        return substr_count( $haystack, $needle, $offset );
    else
        return substr_count( $haystack, $needle, $offset, $length );
}

/**
 * Tells you if a string starts with a value
 *
 * @param String $string The string we are testing the start of
 * @param String $string The value being compared to the front of the first argument
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @return Boolean Returns whether the given string starts with the head
 */
function startsWith ($string, $head, $ignoreCase = TRUE)
{
    $head = (string) $head;

    if ( $ignoreCase )
        return strcasecmp( substr( (string) $string, 0, strlen($head) ), $head ) == 0;
    else
        return substr( (string) $string, 0, strlen($head) ) === $head;
}

/**
 * Tells you if a string ends with a value
 *
 * @param String $string The string we are testing the end of
 * @param String $string The value being compared to the end of the first argument
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @return Boolean Returns whether the given string ends with the tail
 */
function endsWith ($string, $tail, $ignoreCase = TRUE)
{
    $tail = (string) $tail;

    if ( $ignoreCase )
        return strcasecmp( substr( (string) $string, 0 - strlen($tail) ), $tail ) == 0;
    else
        return substr( (string) $string, 0 - strlen($tail) ) === $tail;
}
/**
 * Adds a tail to a string if it doesn't already exist
 *
 * @param String $string The base string
 * @param String $tail The string to add to the end of the base string, if it isn't there already
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @param String Returns the string with the new "suffix"
 */
function tail ($string, $tail, $ignoreCase = TRUE)
{
    $string = (string) $string;
    $tail = (string) $tail;

    // not isEmpty because it's okay if it is filled with spaces
    if (empty($tail))
        return $string;

    return !\r8\str\endsWith($string, $tail, $ignoreCase) ? $string.$tail : $string;
}

/**
 * Removes a tail from a string if it exists
 *
 * @param String $string The base string
 * @param String $tail The string to remove from the end of the base string, if it is there
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @param String Returns the string without its tail
 */
function stripTail ($string, $tail, $ignoreCase = TRUE)
{
    $string = (string) $string;
    $tail = (string) $tail;

    if ( !\r8\str\endsWith($string, $tail, $ignoreCase))
        return $string;

    return substr($string, 0, 0 - strlen($tail)) ?: "";
}

/**
 * Adds a head to a string if it doesn't already exist
 *
 * @param String $string The base string
 * @param String $head The string to add to the beginning of the base string, if it isn't there already
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @return String Returns the string with its head on
 */
function head ($string, $head, $ignoreCase = TRUE)
{
    $string = (string) $string;
    $head = (string) $head;

    // not isEmpty because it's okay if it is filled with spaces
    if (empty($head))
        return $string;

    return !\r8\str\startsWith($string, $head, $ignoreCase)?$head . $string:$string;
}

/**
 * Removes a head from a string if it exists
 *
 * @param String $string The base string
 * @param String $head The string to remove from the beginning of the base string, if it is there
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @return String Returns the decapitated string
 */
function stripHead ($string, $head, $ignoreCase = TRUE)
{
    $string = (string) $string;
    $head = (string) $head;

    if ( !\r8\str\startsWith($string, $head, $ignoreCase) )
        return $string;

    return substr($string, strlen( $head )) ?: "";
}

/**
 * Combines two strings and adds a separator between them.
 *
 * Detects if the separator already exists at the tail or head of either string
 * so that it isn't repeated.
 *
 * @param String $string1 The first part of the resulting string
 * @param String $string2 The last part of the resulting string
 * @param String $glue The glue to put between the two strings
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @return String Returns the strings combined, with the glue in the middle
 */
function weld ($string1, $string2, $glue, $ignoreCase = TRUE)
{
    $string1 = (string) $string1;
    $string2 = (string) $string2;
    $glue = (string) $glue;

    if ( \r8\isVague($glue, \r8\str\ALLOW_SPACES))
        return $string1 . $string2;

    return \r8\str\stripTail($string1, $glue, $ignoreCase)
        .$glue
        .\r8\str\stripHead($string2, $glue, $ignoreCase);

}

/**
 * Splits a string at the given offsets
 *
 * Breaks are done right before the given offset
 *
 * @param String $string The string being split
 * @param Integer $offsets... The list of offsets where the string should be split
 * @return Array Returns an array of the segmented string
 */
function partition ($string, $offsets)
{
    $string = (string) $string;

    if (strlen($string) <= 0)
        return array();

    $offsets = func_get_args();
    array_shift($offsets);
    $offsets = \r8\ary\flatten( $offsets );
    $offsets = \array_map( "intval", $offsets );
    $offsets = \array_unique( $offsets );
    sort( $offsets );

    $out = array();

    $last = 0;

    foreach ($offsets AS $break) {
        if ($break > 0 && $break < strlen($string)) {
            $out[] = substr($string, $last, $break - $last);
            $last = $break;
        }
    }

    $out[] = substr($string, $last);

    return $out;
}

/**
 * Adds a wrapper to the string compare functions to allow a case sensitivity toggle
 *
 * @param String $string The first string
 * @param String $string The second string
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 */
function compare ($string1, $string2, $ignoreCase = TRUE)
{
    return $ignoreCase ?
        strcasecmp( (string) $string1, (string) $string2 ) :
        strcmp( (string) $string1, (string) $string2 );
}

/**
 * Ensures that the given string is at the beginning and end of a string
 *
 * @param String $string The string being enclosed
 * @param String $enclose The value to be appended and prepended to the string, it it doesn't already exist
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @return String Returns the string with the head and tail on it
 */
function enclose ($string, $enclose, $ignoreCase = TRUE)
{
    return \r8\str\tail(
            \r8\str\head($string, $enclose, $ignoreCase),
            $enclose,
            $ignoreCase
        );
}

/**
 * Truncates a string down to the maximum length
 *
 * This operates by taking a chunk from the start and end of the string and
 * replacing everything between with a delimiter
 *
 * @param String $string The string to shorten
 * @param Integer $maxLength The maximum length the string can be
 * @param String $delimiter The string to replace the center section with
 * @return String Returns the shortened version of the string
 */
function truncate ($string, $maxLength, $delimiter = '...')
{

    $string = (string) $string;
    $delimiter = (string) $delimiter;

    // The maxLength must be at LEAST the length of the delimiter, plus a character on both sides
    $maxLength = max( (int) $maxLength, strlen($delimiter) + 2 );

    if (strlen( $string ) <= $maxLength)
        return $string;

    $firstLength = ceil( ($maxLength - strlen($delimiter)) / 2 );

    return substr( $string, 0, $firstLength )
        .$delimiter
        .substr( $string, strlen( $string ) - ( $maxLength - strlen($delimiter) - $firstLength ) );
}

/**
 * Makes the last word in a string plural
 *
 * @param String $string The value to str::pluralize
 * @param Integer $count If the pluralization should only be done based on a number, pass it here
 */
function pluralize ( $string, $count = 2 )
{
    $string = (string) $string;

    if ( \r8\isEmpty( trim($string) ) )
        throw new \r8\Exception\Argument(0, "String", "Must not be empty");

    if ( $count == 1 )
        return $string;

    $origLen = strlen($string);

    $string = rtrim($string);

    $lastChar = substr($string, -1);

    if ( $lastChar === "y" ) {
        $string = substr($string, 0, -1) . "ies";
        $origLen += 2;
    }
    else if ( $lastChar === "Y" ) {
        $string = substr($string, 0, -1) . "IES";
        $origLen += 2;
    }
    else if ( $lastChar === strtoupper($lastChar) ) {
        $string = $string . "S";
        $origLen += 1;
    }
    else {
        $string = $string . "s";
        $origLen += 1;
    }

    return $string . str_repeat(" ", $origLen - strlen($string));
}

?>
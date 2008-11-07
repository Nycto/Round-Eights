<?php
/**
 * String functions
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package strings
 */

namespace cPHP::str;

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
    $integer = strval(intval($integer));

    if ( ::cPHP::num::between( abs( substr($integer, -2) ), 11, 13, TRUE))
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
        return ( stripos( strVal($haystack), strVal($needle) ) === FALSE) ? FALSE : TRUE;
    else
        return ( strpos( strVal($haystack), strVal($needle) ) === FALSE) ? FALSE : TRUE;
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
    $ignoreCase = ::cPHP::boolVal($ignoreCase);
    $needle = ::cPHP::strVal($needle);
    $haystack = ::cPHP::strVal($haystack);

    if (empty($needle))
        throw new ::cPHP::Exception::Argument(0, 'needle', 'Must not be empty');

    if (!::cPHP::str::contains($needle, $haystack, $ignoreCase))
        return new cPHP::Ary;

    $count = $ignoreCase ? ::cPHP::str::substr_icount($haystack, $needle) : substr_count($haystack, $needle);

    $found = array();

    $offset = 0;

    $length = strlen($needle);

    for ($i = 0; $i < $count; $i++) {
        $found[] =  $ignoreCase ? stripos( $haystack, $needle, $offset ) : strpos( $haystack, $needle, $offset );
        $offset = end( $found ) + $length;
    }

    return new cPHP::Ary( $found );
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
function npos ($needle, $haystack, $offset, $ignoreCase = TRUE, $wrapFlag = cPHP::Ary::OFFSET_RESTRICT)
{
    $found = ::cPHP::str::offsets($needle, $haystack, $ignoreCase);

    if (count($found) <= 0)
        return FALSE;
    else
        return $found->offset($offset, $wrapFlag);
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
            strVal( $string )
        );

}

/**
 * Removes everything but letters and numbers from a string
 *
 * named after the regular expression used
 *
 * @param String $string The value being processed
 * @param Integer $flags Allows you to adjust how str::stripW handles different characters. Allowed flags are:
 *  - ALLOW_TABS
 *  - ALLOW_NEWLINES
 *  - ALLOW_SPACES
 *  - ALLOW_UNDERSCORES
 *  - ALLOW_DASHES
 * @return String Returns the stripped string
 */
function stripW ($string, $flags = 0)
{

    $flags = max( intval( ::cPHP::reduce($flags)), 0 );

    return preg_replace(
            "/[^a-z0-9"
            .($flags & ::cPHP::str::ALLOW_TABS?"\t":"")
            .($flags & ::cPHP::str::ALLOW_NEWLINES?"\n\r":"")
            .($flags & ::cPHP::str::ALLOW_SPACES?" ":"")
            .($flags & ::cPHP::str::ALLOW_UNDERSCORES?"_":"")
            .($flags & ::cPHP::str::ALLOW_DASHES?'\-':"")
            ."]/i",
            NULL,
            $string
        );
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
        $repeated = ::cPHP::Ary::create( $repeated )->flatten()->collect("strval");
        foreach( $repeated AS $key => $value ) {
            $repeated[ $key ] = preg_quote($value, "/");
        }
        $repeated = $repeated->implode("|");
    }
    else {
        $repeated = preg_quote(strval($repeated), '/');
    }

    if ( ::cPHP::isEmpty( $repeated ) )
        throw new ::cPHP::Exception::Argument(1, 'Repeated', 'Must not be empty');

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

    $string = strval($string);
    if ( ::cPHP::isEmpty($string) )
        return '';

    $glue = strval( $glue );

    // Maxlength must be at least 1
    $maxLength = max( $maxLength, 1 );

    // If they didn't define a trimTo, then default it to 2/3 the max length
    if ( ::cPHP::isVague($trimTo) || intval($trimTo) <= 0 )
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

    $string = strval($string);

    $quotes = new cPHP::Ary( $quotes );
    $quotes = $quotes->flatten()->collect("trim")->unique()->compact();

    $split = preg_split(
            '/(?<!\\\\)(?:\\\\\\\\)*('
                . $quotes->collect(
                        cPHP::Curry::Call::Create("preg_quote")->setRight("/")->setLimit(1)
                    )->implode("|")
                .')/i',
            $string,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

    $curQuote = NULL;
    $output = "";

    foreach ($split AS $key => $part) {

        if ( is_null($curQuote) && $quotes->contains($part) )
            $curQuote = $part;

        else if (is_null($curQuote))
            $output .= $part;

        else if ( $quotes->contains($part) )
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
    $haystack = strtolower(strVal($haystack));
    $needle = strtolower(strVal($needle));

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
    $string = strval($string);
    $head = strval($head);

    // Not isEmpty because it's okay if it is filled with spaces.
    // True is returned because all strings start with an empty character.
    if (empty($head))
        return TRUE;

    $stringHead = substr($string, 0, strlen($head));

    return ::cPHP::str::compare($stringHead, $head, $ignoreCase) == 0?TRUE:FALSE;

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

    $string = strval($string);
    $tail = strval($tail);

    // not isEmpty because it's okay if it is filled with spaces
    // all strings end with an empty character...
    if (empty($tail))
        return TRUE;

    $stringTail = substr($string, 0 - strlen($tail));

    return ::cPHP::str::compare($stringTail, $tail, $ignoreCase) == 0?TRUE:FALSE;

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
    $string = strval($string);
    $tail = strval($tail);

    // not isEmpty because it's okay if it is filled with spaces
    if (empty($tail))
        return $string;

    return !::cPHP::str::endsWith($string, $tail, $ignoreCase) ? $string.$tail : $string;
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
    $string = strval($string);
    $tail = strval($tail);

    // not isEmpty because it's okay if it is filled with spaces
    if (empty($tail))
        return $string;

    if ( ::cPHP::str::endsWith($string, $tail, $ignoreCase))
        $string = substr($string, 0, 0 - strlen($tail));

    if ($string === FALSE)
        $string = "";

    return $string;
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
    $string = strval($string);
    $head = strval($head);

    // not isEmpty because it's okay if it is filled with spaces
    if (empty($head))
        return $string;

    return !::cPHP::str::startsWith($string, $head, $ignoreCase)?$head . $string:$string;
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
    $string = ::cPHP::strval( ::cPHP::reduce($string) );
    $head = ::cPHP::strval( ::cPHP::reduce($head) );

    // not isEmpty because it's okay if it is filled with spaces
    if (empty($head))
        return $string;

    if (::cPHP::str::startsWith($string, $head, $ignoreCase))
        $string = substr($string, strlen($head));

    if ($string === FALSE)
        $string = "";

    return $string;
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
    $string1 = ::cPHP::strval($string1);
    $string2 = ::cPHP::strval($string2);
    $glue = ::cPHP::strval($glue);

    if ( ::cPHP::isVague($glue, ::cPHP::str::ALLOW_SPACES))
        return $string1 . $string2;

    return ::cPHP::str::stripTail($string1, $glue, $ignoreCase)
        .$glue
        .::cPHP::str::stripHead($string2, $glue, $ignoreCase);

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
    $string = ::cPHP::strval($string);

    if (strlen($string) <= 0)
        return new cPHP::Ary;

    $offsets = func_get_args();
    array_shift($offsets);
    $offsets = cPHP::Ary::Create( $offsets )->flatten()->collect("intval")->unique()->sort();

    $out = new cPHP::Ary;

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
        strcasecmp( ::cPHP::strval($string1), ::cPHP::strval($string2) ) :
        strcmp( ::cPHP::strval($string1), ::cPHP::strval($string2) );
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
    return ::cPHP::str::tail(
            ::cPHP::str::head($string, $enclose, $ignoreCase),
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

    $string = strval($string);
    $delimiter = strval($delimiter);

    // The maxLength must be at LEAST the length of the delimiter, plus a character on both sides
    $maxLength = max( intval($maxLength), strlen($delimiter) + 2 );

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
    $string = ::cPHP::strval($string);

    if ( ::cPHP::isEmpty( trim($string) ) )
        throw new ::cPHP::Exception::Argument(0, "String", "Must not be empty");

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
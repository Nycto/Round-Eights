<?php
/**
 * String functions
 *
 * @package strings
 */

namespace cPHP;

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

    if (between( abs( substr($integer, -2) ), 11, 13, TRUE))
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
function strContains($needle, $haystack, $ignoreCase = TRUE)
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
function strOffsets ($needle, $haystack, $ignoreCase = TRUE)
{
    $ignoreCase = boolVal($ignoreCase);
    $needle = strVal($needle);
    $haystack = strVal($haystack);

    if (empty($needle))
        throw new ::cPHP::Exception::Data::Argument(0, 'needle', 'Must not be empty');

    if (!strContains($needle, $haystack, $ignoreCase))
        return new cPHP::Ary;

    $count = $ignoreCase ? substr_icount($haystack, $needle) : substr_count($haystack, $needle);

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
function strnpos ($needle, $haystack, $offset, $ignoreCase = TRUE, $wrapFlag = cPHP::Ary::OFFSET_RESTRICT)
{
    $found = strOffsets($needle, $haystack, $ignoreCase);

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
 * Removes everything but letters, numbers and spaces from a string
 *
 * named after the regular expression used
 *
 * @param String $string The value being processed
 * @param Integer $flags Allows you to adjust how stripW handles different characters. Allowed flags are:
 *  - ALLOW_TABS
 *  - ALLOW_NEWLINES
 *  - ALLOW_SPACES
 *  - ALLOW_UNDERSCORES
 * @return String Returns the stripped string
 */
function stripW ($string, $flags = 0)
{

    $flags = max( intval(reduce($flags)), 0 );

    return preg_replace(
            "/[^a-z0-9"
            .($flags & ALLOW_TABS?"\t":"")
            .($flags & ALLOW_NEWLINES?"\n\r":"")
            .($flags & ALLOW_SPACES?" ":"")
            .($flags & ALLOW_UNDERSCORES?"_":"")
            .($flags & ALLOW_DASHES?'\-':"")
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

    if ( is_empty( $repeated ) )
        throw new ::cPHP::Exception::Data::Argument(1, 'Repeated', 'Must not be empty');

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
    if (is_empty($string))
        return '';

    $glue = strval( $glue );

    // Maxlength must be at least 1
    $maxLength = max( $maxLength, 1 );

    // If they didn't define a trimTo, then default it to 2/3 the max length
    if (is_vague($trimTo) || intval($trimTo) <= 0)
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

    // Not is_empty because it's okay if it is filled with spaces.
    // True is returned because all strings start with an empty character.
    if (empty($head))
        return TRUE;

    $stringHead = substr($string, 0, strlen($head));

    return strCompare($stringHead, $head, $ignoreCase) == 0?TRUE:FALSE;

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

    // not is_empty because it's okay if it is filled with spaces
    // all strings end with an empty character...
    if (empty($tail))
        return TRUE;

    $stringTail = substr($string, 0 - strlen($tail));

    return strCompare($stringTail, $tail, $ignoreCase) == 0?TRUE:FALSE;

}
/**
 * Adds a tail to a string if it doesn't already exist
 *
 * @param String $string The base string
 * @param String $tail The string to add to the end of the base string, if it isn't there already
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @param String Returns the string with the new "suffix"
 */
function strTail ($string, $tail, $ignoreCase = TRUE)
{
    $string = strval($string);
    $tail = strval($tail);

    // not is_empty because it's okay if it is filled with spaces
    if (empty($tail))
        return $string;

    return !endsWith($string, $tail, $ignoreCase) ? $string.$tail : $string;
}

/**
 * Removes a tail from a string if it exists
 *
 * @param String $string The base string
 * @param String $tail The string to remove from the end of the base string, if it is there
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @param String Returns the string without its tail
 */
function strStripTail ($string, $tail, $ignoreCase = TRUE)
{
    $string = strval($string);
    $tail = strval($tail);

    // not is_empty because it's okay if it is filled with spaces
    if (empty($tail))
        return $string;

    if (endsWith($string, $tail, $ignoreCase))
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
function strHead ($string, $head, $ignoreCase = TRUE)
{
    $string = strval($string);
    $head = strval($head);

    // not is_empty because it's okay if it is filled with spaces
    if (empty($head))
        return $string;

    return !startsWith($string, $head, $ignoreCase)?$head . $string:$string;
}

/**
 * Removes a head from a string if it exists
 *
 * @param String $string The base string
 * @param String $head The string to remove from the beginning of the base string, if it is there
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @return String Returns the decapitated string
 */
function strStripHead ($string, $head, $ignoreCase = TRUE)
{
    $string = strval(reduce($string));
    $head = strval(reduce($head));

    // not is_empty because it's okay if it is filled with spaces
    if (empty($head))
        return $string;

    if (startsWith($string, $head, $ignoreCase))
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
function strWeld ($string1, $string2, $glue, $ignoreCase = TRUE)
{
    $string1 = strval($string1);
    $string2 = strval($string2);
    $glue = strval($glue);

    if (is_vague($glue, ALLOW_SPACES))
        return $string1 . $string2;

    return strStripTail($string1, $glue, $ignoreCase) . $glue . strStripHead($string2, $glue, $ignoreCase);

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
function strPartition ($string, $offsets)
{
    $string = strval($string);

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
function strCompare ($string1, $string2, $ignoreCase = TRUE)
{
    return $ignoreCase ?
        strcasecmp( strval($string1), strval($string2) ) :
        strcmp( strval($string1), strval($string2) );
}

/**
 * Ensures that the given string is at the beginning and end of a string
 *
 * @param String $string The string being enclosed
 * @param String $enclose The value to be appended and prepended to the string, it it doesn't already exist
 * @param Boolean $ignoreCase Whether the comparison should be case sensitive
 * @return String Returns the string with the head and tail on it
 */
function strEnclose ($string, $enclose, $ignoreCase = TRUE)
{
    return strTail(strHead($string, $enclose, $ignoreCase), $enclose, $ignoreCase);
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
function strTruncate ($string, $maxLength, $delimiter = '...')
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
 * @param String $string The value to pluralize
 * @param Integer $count If the pluralization should only be done based on a number, pass it here
 */
function pluralize ( $string, $count = 2 )
{
    $string = strval($string);

    if ( is_empty( trim($string) ) )
        throw new ::cPHP::Exception::Data::Argument(0, "String", "Must not be empty");

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
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
 * @package Query Parser
 */

namespace cPHP;

/**
 * Parses a URL query string into an array
 *
 * This has a few advantages over the parser built into PHP. Most obviously,
 * you can customize the delimiters. It also you to more accurate parse key
 * names because it doesn't require them to be valid PHP variable names
 */
class QueryParser
{

    /**
     * The delimiter used to separate each key/value pair
     *
     * @var String
     */
    private $outerDelim = "&";

    /**
     * The delimiter used to separate the key from the value within each pair
     *
     * @var String
     */
    private $innerDelim = "=";

    /**
     * The string that marks the beginning of the URL query
     *
     * @var String
     */
    private $startDelim = "?";

    /**
     * The string marking the end of the URL query
     *
     * @var String
     */
    private $endDelim = "#";

    /**
     * The regular expression used to find multi-dimensional keys within the key
     *
     * @var String
     */
    private $subRegEx = '/\[(.*?)\]/';

    /**
     * The filter to apply to the keys before returning them
     *
     * @var \cPHP\iface\Filter
     */
    private $keyFilter;

    /**
     * The filter to apply to the values before returning them
     *
     * @var \cPHP\iface\Filter
     */
    private $valueFilter;

    /**
     * Constructor...
     */
    public function __construct ()
    {
        $filter = new \cPHP\Curry\Call('urldecode');

        $this->keyFilter = $filter;
        $this->valueFilter = $filter;
    }

    /**
     * Returns the delimiter used to separate each key/value pair
     *
     * @return String
     */
    public function getOuterDelim ()
    {
        return $this->outerDelim;
    }

    /**
     * Sets the delimiter used to separate each key/value pair
     *
     * @param String $delim The new delimiter
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function setOuterDelim ( $delim )
    {
        $delim = \cPHP\strval($delim);

        if ( \cPHP\isEmpty($delim, \cPHP\ALLOW_SPACES) )
            throw new \cPHP\Exception\Argument(0, "Outer Delimiter", "Must not be empty");

        $this->outerDelim = $delim;

        return $this;
    }

    /**
     * Returns the delimiter used to separate the key and value within a pair
     *
     * @return String
     */
    public function getInnerDelim ()
    {
        return $this->innerDelim;
    }

    /**
     * Returns the delimiter used to separate the key and value within a pair
     *
     * @param String $delim The new delimiter
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function setInnerDelim ( $delim )
    {
        $delim = \cPHP\strval($delim);

        if ( \cPHP\isEmpty($delim, \cPHP\ALLOW_SPACES) )
            throw new \cPHP\Exception\Argument(0, "Inner Delimiter", "Must not be empty");

        $this->innerDelim = $delim;

        return $this;
    }

    /**
     * Returns the delimiter used to separate the key and value within a pair
     *
     * @return String
     */
    public function getStartDelim ()
    {
        return $this->startDelim;
    }

    /**
     * Sets the delimiter used to separate the key and value within a pair
     *
     * @param String $delim The new delimiter
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function setStartDelim ( $delim )
    {
        $delim = \cPHP\strval($delim);

        if ( \cPHP\isEmpty($delim, \cPHP\ALLOW_SPACES) )
            $delim = null;

        $this->startDelim = $delim;

        return $this;
    }

    /**
     * Returns whether a starting delimiter has been set
     *
     * @return Boolean
     */
    public function startDelimExists ()
    {
        return isset( $this->startDelim );
    }

    /**
     * Clears the starting delimiter from this instance
     *
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function clearStartDelim ()
    {
        $this->startDelim = null;
        return $this;
    }

    /**
     * Returns the delimiter used to separate the key and value within a pair
     *
     * @return String
     */
    public function getEndDelim ()
    {
        return $this->endDelim;
    }

    /**
     * Sets the delimiter used to separate the key and value within a pair
     *
     * @param String $delim The new delimiter
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function setEndDelim ( $delim )
    {
        $delim = \cPHP\strval($delim);

        if ( \cPHP\isEmpty($delim, \cPHP\ALLOW_SPACES) )
            $delim = null;

        $this->endDelim = $delim;

        return $this;
    }

    /**
     * Returns whether a ending delimiter has been set
     *
     * @return Boolean
     */
    public function endDelimExists ()
    {
        return isset( $this->endDelim );
    }

    /**
     * Clears the ending delimiter from this instance
     *
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function clearEndDelim ()
    {
        $this->endDelim = null;
        return $this;
    }

    /**
     * Returns the reglar expression used to extract sub-keys from the keys
     *
     * @return String
     */
    public function getSubRegEx ()
    {
        return $this->subRegEx;
    }

    /**
     * Sets the reglar expression used to extract sub-keys from the keys
     *
     * @param String $regex The new regular expression
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function setSubRegEx ( $regex )
    {
        $regex = \cPHP\strval($regex);

        if ( \cPHP\isEmpty($regex) )
            throw new \cPHP\Exception\Argument(0, "Sub-Key Reg Ex", "Must not be empty");

        $this->subRegEx = $regex;

        return $this;
    }

    /**
     * Returns the filter that will be applied to the keys
     *
     * @return \cPHP\iface\Filter
     */
    public function getKeyFilter ()
    {
        return $this->keyFilter;
    }

    /**
     * Sets the filter that will be applied to the keys
     *
     * @param \cPHP\iface\Filter $filter The new Filter
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function setKeyFilter ( \cPHP\iface\Filter $filter )
    {
        $this->keyFilter = $filter;
        return $this;
    }

    /**
     * Returns the filter that will be applied to the values
     *
     * @return \cPHP\iface\Filter
     */
    public function getValueFilter ()
    {
        return $this->valueFilter;
    }

    /**
     * Sets the filter that will be applied to the values
     *
     * @param \cPHP\iface\Filter $filter The new Filter
     * @return \cPHP\QueryParser Returns a self reference
     */
    public function setValueFilter ( \cPHP\iface\Filter $filter )
    {
        $this->valueFilter = $filter;
        return $this;
    }

    /**
     * Takes a key string and parses it into any multi-dimensional parts it has
     *
     * @param String $key The key being parsed
     * @return Array Returns a list of the sub-keys
     */
    private function parseKey ( $key )
    {
        $found = preg_match_all(
                $this->subRegEx,
                $key,
                $matches,
                PREG_OFFSET_CAPTURE
            );

        // If no sub-keys were found, just return the original key
        if ( !$found )
            return array($key);

        // Grab the value of the key up to the position of the first match
        if ( $matches[0][0][1] > 0 )
            $result = array( substr($key, 0, $matches[0][0][1]) );
        else
            $result = array();

        if ( !isset($matches[1]) ) {
            $err = new \cPHP\Exception\Interaction("Sub-Key RegEx did not return a sub-pattern");
            $err->addData("Sub-Key RegEx", $this->subRegEx);
            throw $err;
        }

        // Loop through the matched sub-patterns
        foreach ( $matches[1] AS $subKey ) {

            if ( \cPHP\isEmpty($subKey[0], \cPHP\ALLOW_SPACES) )
                $result[] = null;
            else
                $result[] = $subKey[0];
        }

        return $result;
    }

    /**
     * Parses a query string into an array
     *
     * @param String $query The query string to parser
     * @return Array Returns the parsed string as an array
     */
    public function parse ( $query )
    {
        $query = \cPHP\strval($query);

        // Grab everything after the starting delimiter
        if ( \cPHP\str\contains($this->startDelim, $query) )
            $query = substr($query, strpos($query, $this->startDelim) + 1);

        // Cut off everything after the ending delimiter
        if ( \cPHP\str\contains($this->endDelim, $query) )
            $query = substr($query, 0, strpos($query, $this->endDelim) );

        // Split the query into its pairs
        $query = explode($this->outerDelim, $query);

        $result = array();

        // Loop through each pair
        foreach ($query AS $pair) {

            // Skip over empty pairs
            if ( \cPHP\isEmpty($pair) )
                continue;

            // split the pair up into its key and value
            if ( \cPHP\str\contains($this->innerDelim, $pair) )
                list( $key, $value ) = explode($this->innerDelim, $pair, 2);
            else
                list( $key, $value ) = array( $pair, "" );

            // if the key is empty, do nothing with it
            if ( \cPHP\isEmpty( $key, \cPHP\ALLOW_SPACES ) )
                continue;

            // Apply the filters to the key and value
            $key = $this->keyFilter->filter( $key );
            $value = $this->valueFilter->filter( $value );

            // parse the list of keys into an array
            $key = $this->parseKey( $key );

            // Add the branch to the result array
            \cPHP\ary\branch( $result, $value, $key );
        }

        return $result;
    }

}

?>
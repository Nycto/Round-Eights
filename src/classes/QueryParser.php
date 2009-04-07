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
    private $subRegEx = '/\[([^\]])\]/';

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
     * Returns the delimiter used to separate each key/value pair
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
     * Returns the delimiter used to separate the key and value within a pair
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
     * Returns the delimiter used to separate the key and value within a pair
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

}

?>
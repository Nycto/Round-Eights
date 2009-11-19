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
 * @package Quoter
 */

namespace r8\Quoter;

/**
 * Representation a collection of parsed string sections
 */
class Parsed
{

    /**
     * The sections represented by this instance
     */
    private $sections = array();

    /**
     * Whether the iterator functionality should include the quoted objects
     */
    private $quoted = TRUE;

    /**
     * Whether the iterator functionality should include the unquoted objects
     */
    private $unquoted = TRUE;

    /**
     * Returns a list of all the sections in this instance
     *
     * @return Array Returns an array of \r8\Quoter\Parsed objects
     */
    public function getSections ()
    {
        return $this->sections;
    }

    /**
     * Adds a new section to the end of this list
     *
     * @param Object $section The section being added
     * @return Object Returns a self reference
     */
    public function addSection( \r8\Quoter\Section $section )
    {
        $this->sections[] = $section;
        return $this;
    }

    /**
     * Converts all the contained sections to strings and concatenates them
     *
     * @return String
     */
    public function __toString ()
    {
        $result = "";
        foreach ( $this->sections AS $section ) {
            $result .= $section->__toString();
        }
        return $result;
    }

    /**
     * Returns whether the quoted values will included in the advanced functionality
     *
     * @return Boolean
     */
    public function getIncludeQuoted ()
    {
        return $this->quoted;
    }

    /**
     * Sets whether the quoted values should be included in the advanced functionality
     *
     * @return Object returns a self reference
     */
    public function setIncludeQuoted ( $setting )
    {
        $this->quoted = $setting ? TRUE : FALSE;
        return $this;
    }

    /**
     * Returns whether the unquoted values will included in the advanced functionality
     *
     * @return Boolean
     */
    public function getIncludeUnquoted ()
    {
        return $this->unquoted;
    }

    /**
     * Sets whether the unquoted values should be included in the advanced functionality
     *
     * @return Object returns a self reference
     */
    public function setIncludeUnquoted ( $setting )
    {
        $this->unquoted = $setting ? TRUE : FALSE;
        return $this;
    }

    /**
     * Splits the string based on a separator and returns the resulting sections
     *
     * This will only explode on the separators found in the selected section.
     *
     * @param String $separator The separator to split the string on
     * @return Array Returns a list containing the string sections
     */
    public function explode ( $separator )
    {
        $result = array("");

        foreach ( $this->sections AS $section ) {

            if ( ( $section->isQuoted() && $this->quoted ) || ( !$section->isQuoted() && $this->unquoted ) )
                $exploded = explode( $separator, $section->__toString() );
            else
                $exploded = array( $section->__toString() );

            array_push(
                    $result,
                    array_pop( $result ) . array_shift( $exploded )
                );

            $result = array_merge( $result, $exploded );

        }

        return $result;
    }

    /**
     * Applies a filter to the selected sections
     *
     * @param Object $filter The filter to apply
     * @return Object Returns a self reference
     */
    public function filter ( \r8\iface\Filter $filter )
    {
        if ( !$this->quoted && !$this->unquoted )
            return $this;

        foreach ( $this->sections AS $section ) {

            if ( ( $section->isQuoted() && $this->quoted ) || ( !$section->isQuoted() && $this->unquoted ) ) {

                $section->setContent(
                        $filter->filter( $section->getContent() )
                    );

            }

        }

        return $this;
    }

}

?>
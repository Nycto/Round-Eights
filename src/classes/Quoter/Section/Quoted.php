<?php
/**
 * Quote parsing result class
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Quoter
 */

namespace h2o\Quoter\Section;

/**
 * Representation of a quoted section of the parsed string
 */
class Quoted extends \h2o\Quoter\Section
{

    /**
     * The string quote that opened
     */
    protected $openQuote;

    /**
     * The string that closed the section
     */
    protected $closeQuote;

    /**
     * Constructor...
     *
     * @param String $content The string content of this section
     * @param String $openQuote The open quote
     * @param String $closeQuote The quote that closed this section
     */
    public function __construct( $content, $openQuote, $closeQuote )
    {
        parent::__construct( $content );

        $this->setOpenQuote( $openQuote )
            ->setCloseQuote( $closeQuote );
    }

    /**
     * Returns whether the current section is quoted
     *
     * @return Boolean
     */
    public function isQuoted ()
    {
        return true;
    }

    /**
     * Returns the open quote string
     *
     * @return String|null Returns null if there is no open quote set
     */
    public function getOpenQuote ()
    {
        return $this->openQuote;
    }

    /**
     * Sets the open quote
     *
     * @param String $quote The new open quote
     * @return Object Returns a self reference
     */
    public function setOpenQuote ( $quote )
    {
        $this->openQuote = is_null( $quote ) ? null : \h2o\strval( $quote );
        return $this;
    }

    /**
     * Unsets the open quote from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearOpenQuote ()
    {
        $this->openQuote = null;
        return $this;
    }

    /**
     * Returns whether this instance has an open quote
     *
     * @return Boolean
     */
    public function openQuoteExists ()
    {
        return isset($this->openQuote);
    }

    /**
     * Returns the close quote string
     *
     * @return String|null Returns null if there is no close quote set
     */
    public function getCloseQuote ()
    {
        return $this->closeQuote;
    }

    /**
     * Sets the close quote
     *
     * @param String $quote The new close quote
     * @return Object Returns a self reference
     */
    public function setCloseQuote ( $quote )
    {
        $this->closeQuote = is_null( $quote ) ? null : \h2o\strval( $quote );
        return $this;
    }

    /**
     * Unsets the close quote from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearCloseQuote ()
    {
        $this->closeQuote = null;
        return $this;
    }

    /**
     * Returns whether this instance has an close quote
     *
     * @return Boolean
     */
    public function closeQuoteExists ()
    {
        return isset($this->closeQuote);
    }

    /**
     * Returns the value of this instance
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->getOpenQuote() . $this->getContent() . $this->getCloseQuote();
    }
}

?>
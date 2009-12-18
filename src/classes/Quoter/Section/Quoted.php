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
 * @package Quoter
 */

namespace r8\Quoter\Section;

/**
 * Representation of a quoted section of the parsed string
 */
class Quoted extends \r8\Quoter\Section
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
        $this->openQuote = is_null( $quote ) ? null : \r8\strval( $quote );
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
        $this->closeQuote = is_null( $quote ) ? null : \r8\strval( $quote );
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
<?php
/**
 * Email sender
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
 * @package Mail
 */

namespace cPHP;

/**
 * Handles sending a piece of mail
 */
class Mail
{

    /**
     * The maximum number of characters a single line can contain
     */
    const LINE_LENGTH = 750;

    /**
     * The end of line character to use
     */
    const EOL = "\n";

    /**
     * The email address being sent to
     */
    protected $to;

    /**
     * The actual name of the person being sent to
     */
    protected $toName;

    /**
     * The email address this message will be sent from
     */
    protected $from;

    /**
     * The actual name of the person this message was sent from
     */
    protected $fromName;

    /**
     * The subject of the message
     */
    protected $subject;

    /**
     * Any email addresses to cc the message to
     */
    protected $cc = array();

    /**
     * The raw text of the message
     */
    protected $text;

    /**
     * The HTML of the message
     */
    protected $html;

    /**
     * Returns the name this email will be sent from
     *
     * @return NULL|String Returns NULL if no from name is set
     */
    public function getFromName ()
    {
        return $this->fromName;
    }

    /**
     * Set the label for the "from" field
     *
     * @param String $name The label being sent from
     * @return Object Returns a self reference
     */
    public function setFromName ( $name )
    {
        $name = trim( \cPHP\str\stripW( $name, \cPHP\str\ALLOW_ASCII ) );

        $this->fromName = \cPHP\isEmpty($name) ? NULL : $name;

        return $this;
    }

    /**
     * Returns whether a from label has been set
     *
     * @return Boolean
     */
    public function fromNameExists ()
    {
        return isset( $this->fromName );
    }

    /**
     * Clears the from label from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearFromName ()
    {
        $this->fromName = null;
        return $this;
    }

    /**
     * Returns the e-mail address the message will be sent from
     *
     * @return NULL|String Returns Null if this property has not been set. Otherwise,
     *      it returns a string
     */
    public function getFrom ()
    {
        return $this->from;
    }

    /**
     * Set the email address this email will sent from
     *
     * @param String $email The email address
     * @param String $name The label attached to this address
     * @return Object Returns a self reference
     */
    public function setFrom ( $email, $name = FALSE )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        $this->from = $email;

        if ( !\cPHP\isVague( $name ) )
            $this->setFromName( $name );

        return $this;
    }

    /**
     * Returns whether a from address has been set
     *
     * @return Boolean
     */
    public function fromExists ()
    {
        return isset( $this->from );
    }

    /**
     * Clears the "from" address from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearFrom ()
    {
        $this->from = null;
        return $this;
    }

    /**
     * Returns the name this email will be sent to
     *
     * @return NULL|String Returns NULL if no to name is set
     */
    public function getToName ()
    {
        return $this->toName;
    }

    /**
     * Set the label for the "to" field
     *
     * @param String $name The label being sent to
     * @return Object Returns a self reference
     */
    public function setToName ( $name )
    {
        $name = trim( \cPHP\str\stripW( $name, \cPHP\str\ALLOW_ASCII ) );

        $this->toName = \cPHP\isEmpty($name) ? NULL : $name;

        return $this;
    }

    /**
     * Returns whether a to label has been set
     *
     * @return Boolean
     */
    public function toNameExists ()
    {
        return isset( $this->toName );
    }

    /**
     * Clears the to label to this instance
     *
     * @return Object Returns a self reference
     */
    public function clearToName ()
    {
        $this->toName = null;
        return $this;
    }

    /**
     * Returns the e-mail address the message will be sent to
     *
     * @return NULL|String Returns Null if this property has not been set. Otherwise,
     *      it returns a string
     */
    public function getTo ()
    {
        return $this->to;
    }

    /**
     * Set the email address this email will sent to
     *
     * @param String $email The email address
     * @param String $name The label attached to this address
     * @return Object Returns a self reference
     */
    public function setTo ( $email, $name = FALSE )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        $this->to = $email;

        if ( !\cPHP\isVague( $name ) )
            $this->setToName( $name );

        return $this;
    }

    /**
     * Returns whether a to address has been set
     *
     * @return Boolean
     */
    public function toExists ()
    {
        return isset( $this->to );
    }

    /**
     * Clears the "to" address to this instance
     *
     * @return Object Returns a self reference
     */
    public function clearTo ()
    {
        $this->to = null;
        return $this;
    }

}

?>
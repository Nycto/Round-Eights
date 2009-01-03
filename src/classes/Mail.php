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
     * The email address this message will be sent from
     */
    private $from;

    /**
     * The actual name of the person this message was sent from
     */
    private $fromName;

    /**
     * The list of addresses to send to
     */
    private $to = array();

    /**
     * Any email addresses to cc the message to
     */
    private $cc = array();

    /**
     * Any email addresses to blind carbon copy the message to
     */
    private $bcc = array();

    /**
     * The subject of the message
     */
    private $subject;

    /**
     * The raw text of the message
     */
    private $text;

    /**
     * The HTML of the message
     */
    private $html;

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
     * Returns the list of primary addresses that this email will be sent to
     *
     * @return Object Returns a cPHP\Ary object. This is actually an array of
     *      arrays. The first dimension enumerates the different addresses
     *      that will be sent to. The second dimension represents an individual
     *      address. It has two keys: email and name.
     */
    public function getTo ()
    {
        return new \cPHP\Ary( $this->to );
    }

    /**
     * Adds an address this e-mail should be sent to
     *
     * @param String $email The actual email address
     * @param String $name The label for that e-mail address
     * @return Object Returns a self reference
     */
    public function addTo ( $email, $name = FALSE )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        if ( !\cPHP\isVague( $name ) ) {
            $name = trim( \cPHP\str\stripW( $name, \cPHP\str\ALLOW_ASCII ) );
            $name = \cPHP\isEmpty($name) ? NULL : $name;
        }

        $to = array(
                "email" => $email,
                "name" => $name
            );

        $this->to[$email] = $to;

        return $this;
    }

    /**
     * Clears all the addresses from the to field
     *
     * @return Object Returns a self reference
     */
    public function clearTo ()
    {
        $this->to = array();
        return $this;
    }

    /**
     * Returns the subject of this email
     *
     * @return NULL|String Returns NULL if no subject is set
     */
    public function getSubject ()
    {
        return $this->subject;
    }

    /**
     * Set the subject of the email
     *
     * @param String $subject The email subject
     * @return Object Returns a self reference
     */
    public function setSubject ( $subject )
    {
        // Strip out any new lines or tabs
        $subject = str_replace( array("\r\n", "\r", "\n", "\t"), " ", $subject );

        $subject = trim( \cPHP\str\stripW( $subject, \cPHP\str\ALLOW_ASCII ) );

        $this->subject = \cPHP\isEmpty($subject) ? NULL : $subject;

        return $this;
    }

    /**
     * Returns whether a subject has been set
     *
     * @return Boolean
     */
    public function subjectExists ()
    {
        return isset( $this->subject );
    }

    /**
     * Clears the subject
     *
     * @return Object Returns a self reference
     */
    public function clearSubject ()
    {
        $this->subject = null;
        return $this;
    }

    /**
     * Returns the raw text portion of this email
     *
     * @return NULL|String Returns NULL if no text is set
     */
    public function getText ()
    {
        return $this->text;
    }

    /**
     * Set the raw text portion of the email.
     *
     * If you want to send HTML content, use the setHTML method.
     *
     * If both HTML and text content are set, they will both be sent. Users
     * without HTMl enabled will see the text content.
     *
     * @param String $text The email text
     * @return Object Returns a self reference
     */
    public function setText ( $text )
    {
        $text = trim( \cPHP\strval( $text ) );

        $this->text = \cPHP\isEmpty($text) ? NULL : $text;

        return $this;
    }

    /**
     * Returns whether a text has been set
     *
     * @return Boolean
     */
    public function textExists ()
    {
        return isset( $this->text );
    }

    /**
     * Clears the text
     *
     * @return Object Returns a self reference
     */
    public function clearText ()
    {
        $this->text = null;
        return $this;
    }

    /**
     * Returns the html portion of this email
     *
     * @return NULL|String Returns NULL if no html is set
     */
    public function getHTML ()
    {
        return $this->html;
    }

    /**
     * Set the html portion of the email.
     *
     * If you want to send raw text content, use the setText method.
     *
     * If both HTML and text content are set, they will both be sent. Users
     * without HTML enabled will see the text content.
     *
     * @param String $html The html
     * @return Object Returns a self reference
     */
    public function setHTML ( $html )
    {
        $html = trim( \cPHP\strval( $html ) );

        $this->html = \cPHP\isEmpty($html) ? NULL : $html;

        return $this;
    }

    /**
     * Returns whether any html has been set
     *
     * @return Boolean
     */
    public function htmlExists ()
    {
        return isset( $this->html );
    }

    /**
     * Clears the html
     *
     * @return Object Returns a self reference
     */
    public function clearHTML ()
    {
        $this->html = null;
        return $this;
    }

}

?>
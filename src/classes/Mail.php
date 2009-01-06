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
     * Any custom headers to send
     *
     * The key is the name of the header, the value is the value for the header
     */
    private $headers = array();

    /**
     * The message ID used to identify this message
     */
    private $messageID;

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
     * Strips any invalid characters from a header name string.
     *
     * According to RFC 2822 (http://tools.ietf.org/html/rfc2822), header
     * field names can only contain ascii characters >= 33 and <= 126, except
     * the colon character.
     *
     * @param String $header The header label to strip down
     * @return String
     */
    static public function stripHeaderName ( $header )
    {
        // Convert it to a string
        $header = \cPHP\strval( $header );

        // Remove any non-printable ascii characters
        $header = preg_replace('/[^\x21-\x7E]/', '', $header);

        // Strip out the colons
        $header = str_replace(':', '', $header);

        return $header;
    }

    /**
     * Strips any invalid characters from a header value
     *
     * @param String $value The header label to strip down
     * @return String
     */
    static public function stripHeaderValue ( $value )
    {
        // Alright... so this function isn't really RFC compliant, but it will
        // suffice until a more extensive version can be written

        // Convert it to a string
        $value = \cPHP\strval($value);

        // Remove any non-printable ascii characters, except for \r and \n
        $value = preg_replace( '/[^\x20-\x7E\r\n]/', '', $value );

        // Replace any line returns and following spaces with folding compatible eols
        $value = preg_replace( '/[\r\n][\s]*/', self::EOL ."\t", $value );

        $value = trim($value);

        return $value;
    }

    /**
     * Creates a new mail instance
     *
     * @return Object Returns a new mail instance
     */
    static public function create ()
    {
        return new self;
    }

    /**
     * Constructor...
     *
     * This will load the default email address for the sender from the
     * 'sendmail_from' php.ini directive.
     */
    public function __construct ()
    {
        // Get the default source e-mail address from the php.ini file
        $default = ini_get('sendmail_from');

        $default = \cPHP\Filter::Email()->filter( $default );
        if ( \cPHP\Validator::Email()->isValid( $default ) )
            $this->setFrom( $default );
    }

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
     * Returns whether a given e-mail address has been registered in the 'to' list
     *
     * @param String $email The actual email address
     * @return Boolean
     */
    public function toExists( $email )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        return isset( $this->to[ $email ] );
    }

    /**
     * Removes an e-mail address from the To list
     *
     * @param String $email The email address to remove
     * @return Object Returns a self reference
     */
    public function removeTo ( $email )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        if ( isset($this->to[ $email ]) )
            unset( $this->to[ $email ] );

        return $this;
    }

    /**
     * Returns the list of primary addresses that this email will be CCd to
     *
     * @return Object Returns a cPHP\Ary object. This is actually an array of
     *      arrays. The first dimension enumerates the different addresses
     *      that will be CCd. The second dimension represents an individual
     *      address. It has two keys: email and name.
     */
    public function getCC ()
    {
        return new \cPHP\Ary( $this->cc );
    }

    /**
     * Adds an address this e-mail should be sent cc
     *
     * @param String $email The actual email address
     * @param String $name The label for that e-mail address
     * @return Object Returns a self reference
     */
    public function addCC ( $email, $name = FALSE )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        if ( !\cPHP\isVague( $name ) ) {
            $name = trim( \cPHP\str\stripW( $name, \cPHP\str\ALLOW_ASCII ) );
            $name = \cPHP\isEmpty($name) ? NULL : $name;
        }

        $cc = array(
                "email" => $email,
                "name" => $name
            );

        $this->cc[$email] = $cc;

        return $this;
    }

    /**
     * Clears all the addresses from the cc field
     *
     * @return Object Returns a self reference
     */
    public function clearCC ()
    {
        $this->cc = array();
        return $this;
    }

    /**
     * Returns whether a given e-mail address has been registered in the 'cc' list
     *
     * @param String $email The actual email address
     * @return Boolean
     */
    public function ccExists ( $email )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        return isset( $this->cc[ $email ] );
    }

    /**
     * Removes an e-mail address from the CC list
     *
     * @param String $email The email address to remove
     * @return Object Returns a self reference
     */
    public function removeCC ( $email )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        if ( isset($this->cc[ $email ]) )
            unset( $this->cc[ $email ] );

        return $this;
    }

    /**
     * Returns the list of primary addresses that this email will be BCCd to
     *
     * @return Object Returns a cPHP\Ary object. This is actually an array of
     *      arrays. The first dimension enumerates the different addresses
     *      that will be BCCd. The second dimension represents an individual
     *      address. It has two keys: email and name.
     */
    public function getBCC ()
    {
        return new \cPHP\Ary( $this->bcc );
    }

    /**
     * Adds an address this e-mail should be sent bcc
     *
     * @param String $email The actual email address
     * @param String $name The label for that e-mail address
     * @return Object Returns a self reference
     */
    public function addBCC ( $email, $name = FALSE )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        if ( !\cPHP\isVague( $name ) ) {
            $name = trim( \cPHP\str\stripW( $name, \cPHP\str\ALLOW_ASCII ) );
            $name = \cPHP\isEmpty($name) ? NULL : $name;
        }

        $bcc = array(
                "email" => $email,
                "name" => $name
            );

        $this->bcc[$email] = $bcc;

        return $this;
    }

    /**
     * Clears all the addresses from the bcc field
     *
     * @return Object Returns a self reference
     */
    public function clearBCC ()
    {
        $this->bcc = array();
        return $this;
    }

    /**
     * Returns whether a given e-mail address has been registered in the 'bcc' list
     *
     * @param String $email The actual email address
     * @return Boolean
     */
    public function bccExists ( $email )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        return isset( $this->bcc[ $email ] );
    }

    /**
     * Removes an e-mail address from the BCC list
     *
     * @param String $email The email address to remove
     * @return Object Returns a self reference
     */
    public function removeBCC ( $email )
    {
        $email = \cPHP\Filter::Email()->filter( $email );
        \cPHP\Validator::Email()->ensure( $email );

        if ( isset($this->bcc[ $email ]) )
            unset( $this->bcc[ $email ] );

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
     * Returns the message ID of this email
     *
     * @return NULL|String Returns NULL if no message ID is set
     */
    public function getMessageID ()
    {
        return $this->messageID;
    }

    /**
     * Set the message ID of the email
     *
     * @param String $messageID The email message ID
     * @return Object Returns a self reference
     */
    public function setMessageID ( $messageID )
    {
        // Strip out any new lines or tabs
        $messageID = str_replace( array("\r\n", "\r", "\n", "\t"), " ", $messageID );

        $messageID = trim( \cPHP\str\stripW( $messageID, \cPHP\str\ALLOW_ASCII ) );

        $this->messageID = \cPHP\isEmpty($messageID) ? NULL : $messageID;

        return $this;
    }

    /**
     * Returns whether a messageID has been set
     *
     * @return Boolean
     */
    public function messageIDExists ()
    {
        return isset( $this->messageID );
    }

    /**
     * Clears the messageID
     *
     * @return Object Returns a self reference
     */
    public function clearMessageID ()
    {
        $this->messageID = null;
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

    /**
     * Returns the list of custom headers loaded in this e-mail
     *
     * @return Object Returns a cPHP object where the key is the name of the
     *      header, and the array value is the value of the header
     */
    public function getCustomHeaders ()
    {
        return new \cPHP\Ary( $this->headers );
    }

    /**
     * Adds a custom header to send along with this e-mail
     *
     * @param String $header The name of the header
     * @param String $value The value of the header
     * @return Object Returns a self reference
     */
    public function addCustomHeader ( $header, $value )
    {
        $header = self::stripHeaderName( $header );

        if ( \cPHP\isEmpty($header) )
            throw new \cPHP\Exception\Argument( 0, 'Header Name', 'Must not be empty' );

        $this->headers[ $header ] = self::stripHeaderValue( $value );

        return $this;
    }

    /**
     * Returns whether a specific header has been set
     *
     * @param String $header The header to test
     * @return Boolean
     */
    public function customHeaderExists ( $header )
    {
        $header = self::stripHeaderName( $header );

        if ( \cPHP\isEmpty($header) )
            throw new \cPHP\Exception\Argument( 0, 'Header Name', 'Must not be empty' );

        return isset( $this->headers[$header] );
    }

    /**
     * Clears a custom header from this instance
     *
     * @param String $header The header to remove
     * @return Object Returns a self reference
     */
    public function removeCustomHeader ( $header )
    {
        $header = self::stripHeaderName( $header );

        if ( \cPHP\isEmpty($header) )
            throw new \cPHP\Exception\Argument( 0, 'Header Name', 'Must not be empty' );

        if ( isset( $this->headers[$header] ) )
            unset( $this->headers[$header] );

        return $this;
    }

    /**
     * Clears all the custom headers set in this instance
     *
     * @return Object Returns a self reference
     */
    public function clearCustomHeaders ()
    {
        $this->headers = array();
        return $this;
    }

}

?>
<?php
/**
 * E-Mail detail class
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
 * @package Mail
 */

namespace h2o;

/**
 * Collects the details of an e-mail
 */
class Mail
{

    /**
     * The default transport class for sending a piece of mail
     *
     * @var Object A \h2o\Mail\Transport instance
     */
    static private $defaultTransport;

    /**
     * If set, this is the specific transport to use for this message
     *
     * If this value isn't set, the default transport will be used
     *
     * @var Object A \h2o\Mail\Transport instance
     */
    private $transport;

    /**
     * The email address this message will be sent from
     *
     * @var String
     */
    private $from;

    /**
     * The actual name of the person this message was sent from
     *
     * @var String
     */
    private $fromName;

    /**
     * The list of addresses to send to
     *
     * @var Array
     */
    private $to = array();

    /**
     * Any email addresses to cc the message to
     *
     * @var Array
     */
    private $cc = array();

    /**
     * Any email addresses to blind carbon copy the message to
     *
     * @var Array
     */
    private $bcc = array();

    /**
     * Any custom headers to send
     *
     * The key is the name of the header, the value is the value for the header
     *
     * @var Array
     */
    private $headers = array();

    /**
     * The message ID used to identify this message
     *
     * @var String
     */
    private $messageID;

    /**
     * The subject of the message
     *
     * @var String
     */
    private $subject;

    /**
     * The raw text of the message
     *
     * @var String
     */
    private $text;

    /**
     * The HTML of the message
     *
     * @var String
     */
    private $html;

    /**
     * The generated boundary
     *
     * @var String
     */
    private $boundary;

    /**
     * Sets the default transport to send mail with
     *
     * @param Object $transport A \h2o\Mail\Transport object
     * @return null
     */
    static public function setDefaultTransport ( \h2o\Mail\Transport $transport )
    {
        self::$defaultTransport = $transport;
    }

    /**
     * Returns the default transport that will be used to send mail
     *
     * If no default transport has been sent, this will return an instance of
     * \h2o\Mail\Transport\Mail
     *
     * @return Object A \h2o\Mail\Transport object
     */
    static public function getDefaultTransport ()
    {
        if ( !(self::$defaultTransport instanceof \h2o\Mail\Transport ) )
            self::$defaultTransport = new \h2o\Mail\Transport\Mail;

        return self::$defaultTransport;
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

        $default = \h2o\Filter::Email()->filter( $default );
        if ( \h2o\Validator::Email()->isValid( $default ) )
            $this->setFrom( $default );
    }

    /**
     * Returns the transport that will be used to send this message.
     *
     * If no specific transport has been set for this message, the default
     * transport will be used
     *
     * @return Object Returns a \h2o\Transport instance
     */
    public function getTransport ()
    {
        if ( isset($this->transport) )
            return $this->transport;
        else
            return self::getDefaultTransport();
    }

    /**
     * Sets the transport to send this specific piece of mail with
     *
     * @param Object $transport A \h2o\Mail\Transport object
     * @return \h2o\Mail Returns a self reference
     */
    public function setTransport ( \h2o\Mail\Transport $transport )
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * Clears the specific transport from this instance. This will cause the
     * default transport to be used
     *
     * @return \h2o\Mail Returns a self reference
     */
    public function clearTransport ()
    {
        $this->transport = null;
        return $this;
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
     * @return \h2o\Mail Returns a self reference
     */
    public function setFromName ( $name )
    {
        $name = trim( \h2o\str\stripW( $name, \h2o\str\ALLOW_ASCII ) );

        $this->fromName = \h2o\isEmpty($name) ? NULL : $name;

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
     * @return \h2o\Mail Returns a self reference
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
     * @return \h2o\Mail Returns a self reference
     */
    public function setFrom ( $email, $name = FALSE )
    {
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        $this->from = $email;

        if ( !\h2o\isVague( $name ) )
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
     * @return \h2o\Mail Returns a self reference
     */
    public function clearFrom ()
    {
        $this->from = null;
        return $this;
    }

    /**
     * Returns the list of primary addresses that this email will be sent to
     *
     * @return Array This is actually an array of arrays. The first dimension
     *      enumerates the different addresses that will be sent to. The second
     *      dimension represents an individual address. It has two keys: email and name.
     */
    public function getTo ()
    {
        return $this->to;
    }

    /**
     * Adds an address this e-mail should be sent to
     *
     * @param String $email The actual email address
     * @param String $name The label for that e-mail address
     * @return \h2o\Mail Returns a self reference
     */
    public function addTo ( $email, $name = FALSE )
    {
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        if ( !\h2o\isVague( $name ) ) {
            $name = trim( \h2o\str\stripW( $name, \h2o\str\ALLOW_ASCII ) );
            $name = \h2o\isEmpty($name) ? NULL : $name;
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
     * @return \h2o\Mail Returns a self reference
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
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        return isset( $this->to[ $email ] );
    }

    /**
     * Returns whether there are any "to" addresses in this instance
     *
     * @return Boolean
     */
    public function hasTos ()
    {
        return !empty( $this->to );
    }

    /**
     * Removes an e-mail address from the To list
     *
     * @param String $email The email address to remove
     * @return \h2o\Mail Returns a self reference
     */
    public function removeTo ( $email )
    {
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        if ( isset($this->to[ $email ]) )
            unset( $this->to[ $email ] );

        return $this;
    }

    /**
     * Returns the list of primary addresses that this email will be CCd to
     *
     * @return Array This is actually an array of arrays. The first dimension
     *      enumerates the different addresses that will be CCdThe second
     *      dimension represents an individual address. It has two keys: email and name.
     */
    public function getCC ()
    {
        return $this->cc;
    }

    /**
     * Adds an address this e-mail should be sent cc
     *
     * @param String $email The actual email address
     * @param String $name The label for that e-mail address
     * @return \h2o\Mail Returns a self reference
     */
    public function addCC ( $email, $name = FALSE )
    {
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        if ( !\h2o\isVague( $name ) ) {
            $name = trim( \h2o\str\stripW( $name, \h2o\str\ALLOW_ASCII ) );
            $name = \h2o\isEmpty($name) ? NULL : $name;
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
     * @return \h2o\Mail Returns a self reference
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
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        return isset( $this->cc[ $email ] );
    }

    /**
     * Returns whether there are any "CC" addresses in this instance
     *
     * @return Boolean
     */
    public function hasCCs ()
    {
        return !empty( $this->cc );
    }

    /**
     * Removes an e-mail address from the CC list
     *
     * @param String $email The email address to remove
     * @return \h2o\Mail Returns a self reference
     */
    public function removeCC ( $email )
    {
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        if ( isset($this->cc[ $email ]) )
            unset( $this->cc[ $email ] );

        return $this;
    }

    /**
     * Returns the list of primary addresses that this email will be BCCd to
     *
     * @return Array This is actually an array of arrays. The first dimension
     *      enumerates the different addresses that will be BCCd. The second
     *      dimension represents an individual address. It has two keys: email and name.
     */
    public function getBCC ()
    {
        return $this->bcc;
    }

    /**
     * Adds an address this e-mail should be sent bcc
     *
     * @param String $email The actual email address
     * @param String $name The label for that e-mail address
     * @return \h2o\Mail Returns a self reference
     */
    public function addBCC ( $email, $name = FALSE )
    {
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        if ( !\h2o\isVague( $name ) ) {
            $name = trim( \h2o\str\stripW( $name, \h2o\str\ALLOW_ASCII ) );
            $name = \h2o\isEmpty($name) ? NULL : $name;
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
     * @return \h2o\Mail Returns a self reference
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
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

        return isset( $this->bcc[ $email ] );
    }

    /**
     * Returns whether there are any "BCC" addresses in this instance
     *
     * @return Boolean
     */
    public function hasBCCs ()
    {
        return !empty( $this->bcc );
    }

    /**
     * Removes an e-mail address from the BCC list
     *
     * @param String $email The email address to remove
     * @return \h2o\Mail Returns a self reference
     */
    public function removeBCC ( $email )
    {
        $email = \h2o\Filter::Email()->filter( $email );
        \h2o\Validator::Email()->ensure( $email );

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
     * @return \h2o\Mail Returns a self reference
     */
    public function setSubject ( $subject )
    {
        // Strip out any new lines or tabs
        $subject = str_replace( array("\r\n", "\r", "\n", "\t"), " ", $subject );

        $subject = trim( \h2o\str\stripW( $subject, \h2o\str\ALLOW_ASCII ) );

        $this->subject = \h2o\isEmpty($subject) ? NULL : $subject;

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
     * @return \h2o\Mail Returns a self reference
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
     * @return \h2o\Mail Returns a self reference
     */
    public function setMessageID ( $messageID )
    {
        // Strip out any new lines or tabs
        $messageID = str_replace( array("\r\n", "\r", "\n", "\t"), " ", $messageID );

        $messageID = trim( \h2o\str\stripW( $messageID, \h2o\str\ALLOW_ASCII ) );

        $this->messageID = \h2o\isEmpty($messageID) ? NULL : $messageID;

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
     * @return \h2o\Mail Returns a self reference
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
     * @return \h2o\Mail Returns a self reference
     */
    public function setText ( $text )
    {
        $text = trim( \h2o\strval( $text ) );

        $this->text = \h2o\isEmpty($text) ? NULL : $text;

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
     * @return \h2o\Mail Returns a self reference
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
     * @return \h2o\Mail Returns a self reference
     */
    public function setHTML ( $html )
    {
        $html = trim( \h2o\strval( $html ) );

        $this->html = \h2o\isEmpty($html) ? NULL : $html;

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
     * @return \h2o\Mail Returns a self reference
     */
    public function clearHTML ()
    {
        $this->html = null;
        return $this;
    }

    /**
     * Returns the list of custom headers loaded in this e-mail
     *
     * @return Array Returns an array where the key is the name of the
     *      header, and the array value is the value of the header
     */
    public function getCustomHeaders ()
    {
        return $this->headers;
    }

    /**
     * Adds a custom header to send along with this e-mail
     *
     * @param String $header The name of the header
     * @param String $value The value of the header
     * @return \h2o\Mail Returns a self reference
     */
    public function addCustomHeader ( $header, $value )
    {
        $header = \h2o\Transform\MIME::stripHeaderName( $header );

        if ( \h2o\isEmpty($header) )
            throw new \h2o\Exception\Argument( 0, 'Header Name', 'Must not be empty' );

        $this->headers[ $header ] = \h2o\strval( $value );

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
        $header = \h2o\Transform\MIME::stripHeaderName( $header );

        if ( \h2o\isEmpty($header) )
            throw new \h2o\Exception\Argument( 0, 'Header Name', 'Must not be empty' );

        return isset( $this->headers[$header] );
    }

    /**
     * Clears a custom header from this instance
     *
     * @param String $header The header to remove
     * @return \h2o\Mail Returns a self reference
     */
    public function removeCustomHeader ( $header )
    {
        $header = \h2o\Transform\MIME::stripHeaderName( $header );

        if ( \h2o\isEmpty($header) )
            throw new \h2o\Exception\Argument( 0, 'Header Name', 'Must not be empty' );

        if ( isset( $this->headers[$header] ) )
            unset( $this->headers[$header] );

        return $this;
    }

    /**
     * Clears all the custom headers set in this instance
     *
     * @return \h2o\Mail Returns a self reference
     */
    public function clearCustomHeaders ()
    {
        $this->headers = array();
        return $this;
    }

    /**
     * Analyzes the html and text body and returns a boundary string that doesn't
     * exist in either.
     *
     * This will return the same boundary every time it is called, unless the text
     * or HTML is changed to include the boundary
     *
     * @return String A thirty character long string
     */
    public function getBoundary ()
    {
        // If the boundary has been generated and the string and HTML don't contain
        // it, then there is no need to create another
        while ( !isset($this->boundary)
                || \h2o\str\contains($this->boundary, $this->text)
                || \h2o\str\contains($this->boundary, $this->html) ) {

            // The string "=_" can never appear in the quoted printable character
            // encoding format (or base64, for that matter), which ensures that
            // the generated boundary won't show up in any encoded data
            $this->boundary =
                "=_"
                .substr(md5(
                    microtime() . time()
                ), 0, 26)
                ."_=";
        }

        return $this->boundary;
    }

    /**
     * Sends this piece of mail
     *
     * @return \h2o\Mail Returns a self reference
     */
    public function send ()
    {
        $this->getTransport()->send( $this );
        return $this;
    }

}

?>
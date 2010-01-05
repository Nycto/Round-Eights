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
 * @package Mail
 */

namespace r8\Mail;

/**
 * A helper class for formatting various parts of a piece of mail
 */
class Formatter
{

    /**
     * The maximum number of characters a single line can contain
     */
    const LINE_LENGTH = 76;

    /**
     * The end of line character to use
     */
    const EOL = "\r\n";

    /**
     * Returns an e-mail address formatted as such: Name <addr@host.com>
     *
     * @param String $email The e-mail address
     * @param String $name The name of the person associated with the address
     * @return String The well formatted address line
     */
    static public function formatAddress ($email, $name = NULL)
    {
        $email = r8(new \r8\Filter\Email)->filter( $email );

        if ( !\r8\isVague($name) )
            $name = trim( \r8\str\stripNoPrint( $name ) );

        if ( \r8\isVague($name) )
            return "<". $email .">";
        else
            return '"'. addslashes($name) .'" <'. $email .'>';
    }

    /**
     * Takes an array of addresses and names and creates a formatted header string
     *
     * @param Array $list The list of headers to format
     * @return String
     */
    private function getAddressList ( array $list )
    {
        $result = array();

        foreach ( $list AS $elem ) {
            $result[] = self::formatAddress( $elem['email'], $elem['name'] );
        }

        return implode(", ", $result);
    }

    /**
     * Returns the header content string of the list of addresses being sent to
     *
     * @param \r8\Mail $mail The piece of mail whose "to" fields are being formatted
     * @return String
     */
    public function getToString ( \r8\Mail $mail )
    {
        return $this->getAddressList( $mail->getTo() );
    }

    /**
     * Returns the header content string of the list of CC addresses
     *
     * @param \r8\Mail $mail The piece of mail whose "cc" fields are being formatted
     * @return String
     */
    public function getCCString ( \r8\Mail $mail )
    {
        return $this->getAddressList( $mail->getCC() );
    }

    /**
     * Returns the header content string of the list of BCC addresses
     *
     * @param \r8\Mail $mail The piece of mail whose to fields are being formatted
     * @return String
     */
    public function getBCCString ( \r8\Mail $mail )
    {
        return $this->getAddressList( $mail->getBCC() );
    }

    /**
     * Returns an array of headers that will be sent with this message
     *
     * @param \r8\Mail $mail The piece of mail whose headers should be returned
     * @return Array The key is the header name, the value is the value of
     *      the header
     */
    public function getHeaderList ( \r8\Mail $mail )
    {
        $result = array();

        if ( $mail->fromExists() )
            $result['From'] = self::formatAddress( $mail->getFrom(), $mail->getFromName() );

        if ( $mail->hasTos() )
            $result['To'] = $this->getToString( $mail );

        if ( $mail->hasCCs() )
            $result['CC'] = $this->getCCString( $mail );

        if ( $mail->hasBCCs() )
            $result['BCC'] = $this->getBCCString( $mail );

        if ( $mail->subjectExists() )
            $result['Subject'] = $mail->getSubject();

        $result['Date'] = date('r');

        $result["MIME-Version"] = "1.0";

        if ( $mail->messageIDExists() )
            $result['Message-ID'] = '<'. $mail->getMessageID() .'>';

        if ( $mail->htmlExists() && $mail->textExists() ) {
            $result['Content-Type'] = "multipart/alternative;\nboundary='". $mail->getBoundary() ."'";
        }
        else if ( $mail->htmlExists() ) {
            $result['Content-Type'] = 'text/html; charset="ISO-8859-1"';
            $result["Content-Transfer-Encoding"] = "7bit";
        }
        else {
            $result['Content-Type'] = 'text/plain; charset="ISO-8859-1"';
            $result["Content-Transfer-Encoding"] = "7bit";
        }

        return $result;
    }

    /**
     * Returns an the string of headers that will be sent with this message
     *
     * @param \r8\Mail $mail The piece of mail whose headers should be returned
     * @return String A MIME formatted header string
     */
    public function getHeaderString ( \r8\Mail $mail )
    {
        $mime = new \r8\Transform\MIME\Auto;
        $mime->setEOL( self::EOL );
        $mime->setLineLength( self::LINE_LENGTH );

        $headers = $this->getHeaderList( $mail );

        $result = array();

        foreach ( $headers AS $name => $value ) {
            $mime->setHeader($name);
            $result[] = $mime->to( $value );
        }

        return implode( self::EOL, $result );
    }

    /**
     * Prepares the a chunk of message content for sending
     *
     * This does things like fix line endings and wrap the text to the propper
     * length. It will be applied to both the text and html content of a message
     *
     * @param String $body The content string being sent in the email
     * @return String Returns the prepared string
     */
    public function prepareContent ( $body )
    {
        // Fix the line endings
        $body = preg_replace('/\r\n|\r|\n/', "\r\n", $body);

        // Wrap the content
        $body = wordwrap($body, self::LINE_LENGTH, self::EOL, TRUE);

        // Replace any periods that appear on their own line
        $body = preg_replace( '/^(\s*)\.(\s*)$/m', '\1..\2', $body );

        return $body;
    }

    /**
     * Returns the body string for an HTML
     *
     * @param \r8\Mail $mail The piece of mail whose body will be returned
     * @return String A formatted body email string
     */
    public function getBody ( \r8\Mail $mail )
    {
        // If both the text and HTML are set...
        if ( $mail->htmlExists() && $mail->textExists() ) {

            $mime = new \r8\Transform\MIME\Auto;
            $mime->setEOL( self::EOL );
            $mime->setLineLength( self::LINE_LENGTH );

            $boundary = $mail->getBoundary();

            return
                "--". $boundary . self::EOL
                .$mime->setHeader("Content-Type")->to('text/plain; charset="ISO-8859-1"') . self::EOL
                .$mime->setHeader("Content-Transfer-Encoding")->to('7bit') . self::EOL

                .self::EOL

                .$this->prepareContent( $mail->getText() )

                .self::EOL
                .self::EOL

                ."--". $boundary . self::EOL
                .$mime->setHeader("Content-Type")->to('text/html; charset="ISO-8859-1"') . self::EOL
                .$mime->setHeader("Content-Transfer-Encoding")->to('7bit') . self::EOL

                .self::EOL

                .$this->prepareContent( $mail->getHTML() )

                .self::EOL
                .self::EOL

                ."--". $boundary ."--". self::EOL;

        }

        // If just the HTML is set
        else if ( $mail->htmlExists() ) {
            return $this->prepareContent( $mail->getHTML() );
        }

        // If just the text was set
        else {
            return $this->prepareContent( $mail->getText() );
        }
    }

}

?>
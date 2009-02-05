<?php
/**
 * Formats and sends a piece of mail
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

namespace cPHP\Mail;

/**
 * The base class that for objects which handle the actual sending of an email.
 */
abstract class Transport
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
     * Returns an e-mail address formatted as such: Name <addr@host.com>
     *
     * @param String $email The e-mail address
     * @param String $name The name of the person associated with the address
     * @return String The well formatted address line
     */
    static public function formatAddress ($email, $name = NULL)
    {
        $email = \cPHP\Filter::Email()->filter( $email );

        if ( !\cPHP\isVague($name) )
            $name = trim( \cPHP\str\stripW( $name, \cPHP\str\ALLOW_ASCII ) );

        if ( \cPHP\isVague($name) )
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
    private function getAddressList ( \cPHP\Ary $list )
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
     * @param Object $mail The cPHP\Mail object whose to fields are being formatted
     * @return String
     */
    public function getToString ( \cPHP\Mail $mail )
    {
        return $this->getAddressList( $mail->getTo() );
    }

    /**
     * Returns an array of headers that will be sent with this message
     *
     * @param Object $mail The cPHP\Mail object whose headers should be returned
     * @return Array The key is the header name, the value is the value of
     *      the header
     */
    public function getHeaderList ( \cPHP\Mail $mail )
    {
        $result = array();

        if ( $mail->fromExists() )
            $result['From'] = self::formatAddress( $mail->getFrom(), $mail->getFromName() );



        $result["MIME-Version"] = "1.0";

        if ( $mail->messageIDExists() )
            $result['Message-ID'] = '<'. $mail->getMessageID() .'>';

        if ( $mail->htmlExists() && $mail->textExists() ) {
            $result['Content-Type'] = "multipart/alternative;\nboundary='". $mail->getBoundary ."'";
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

}

?>
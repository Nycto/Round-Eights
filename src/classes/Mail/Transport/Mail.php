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
 * @package Mail
 */

namespace r8\Mail\Transport;

/**
 * Formats and sends a piece of mail using the built in PHP mail function
 */
class Mail extends \r8\Mail\Transport
{

    /**
     * The formatter to use for constructing the message parts
     *
     * @var \r8\Mail\Formatter
     */
    private $formatter;

    /**
     * Constructor...
     *
     * @param \r8\Mail\Formatter $formatter The formatter to use for
     * 		constructing the message parts
     */
    public function __construct ( \r8\Mail\Formatter $formatter )
    {
        $this->formatter = $formatter;
    }

    /**
     * Internal method that simply wraps the mail function. This takes the same
     * parameters and passes them directly through.
     *
     * This function exists for testing purposes. It can be easily mocked to
     * ensure the mail function is being called as expected
     *
     * @param String $to Who the mail is being sent to
     * @param String $subject The subject of the email
     * @param String $message The content of the email
     * @param String $headers Any additional headers to send
     * @return Boolean Returns whether the send was succesful
     */
    protected function rawMail ( $to, $subject, $message, $headers )
    {
        return @mail( $to, $subject, $message, $headers );
    }

    /**
     * Internal function that actually sends a piece of mail using this transport.
     *
     * This method is called indirectly via the send method. Use that method
     * if you want to send a piece of mail
     *
     * @param \r8\Mail $mail The mail to send
     * @return Null
     */
    protected function internalSend ( \r8\Mail $mail )
    {
        $result = $this->rawMail(
                $this->formatter->getToString( $mail ),
                $mail->getSubject(),
                $this->formatter->getBody( $mail ),
                $this->formatter->getHeaderString( $mail )
            );

        if ( !$result ) {
            $err = new \r8\Exception\Interaction(
                    "An error occured while sending mail"
                );

            $phpError = error_get_last();
            if ( is_array($phpError) )
                $err->addData('Error', $phpError['message']);

            $err->addData('To', $this->formatter->getToString( $mail ));
            $err->addData('Subject', $mail->getSubject());

            if ( $mail->messageIDExists() )
                $err->addData('MessageID', $mail->getMessageID());

            throw $err;
        }
    }

}

?>
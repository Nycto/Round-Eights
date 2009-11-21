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

namespace r8\Mail;

/**
 * The base class that for objects which handle the actual sending of an email.
 */
abstract class Transport
{

    /**
     * Internal function that actually sends a piece of mail using this transport.
     *
     * This method is called indirectly via the send method. Use that method
     * if you want to send a piece of mail
     *
     * @param \r8\Mail $mail The mail to send
     * @return Null
     */
    abstract protected function internalSend ( \r8\Mail $mail );

    /**
     * Method for sending a piece of mail using this transport.
     *
     * @param \r8\Mail $mail The mail to send
     * @return \r8\Mail\Transport Returns a self reference
     */
    public function send ( \r8\Mail $mail )
    {
        if ( !$mail->fromExists() )
            throw new \r8\Exception\Variable('From Address', '"From" Address must be set to send an email');

        if ( !$mail->hasTos() )
            throw new \r8\Exception\Variable('To Address', '"To" Address must be set to send an email');

        $this->internalSend( $mail );

        return $this;
    }

}

?>
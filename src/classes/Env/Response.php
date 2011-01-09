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
 * @package Env
 */

namespace r8\Env;

/**
 * Manages the response that will be sent to the client
 */
class Response implements \r8\iface\Env\Response
{

    /**
     * @see \r8\iface\Env\Response::headersSent
     */
    public function headersSent ()
    {
        return headers_sent();
    }

    /**
     * @see \r8\iface\Env\Response::setRawHeader
     */
    public function setRawHeader ( $header )
    {
        // @codeCoverageIgnoreStart
        $file = null;
        $line = null;

        if ( headers_sent($file, $line) )
            throw new \r8\Env\HeadersSent($file, $line);

        header( (string) $header );

        return $this;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @see \r8\iface\Env\Response::setHeader
     */
    public function setHeader ( $header, $value )
    {
        $this->setRawHeader(sprintf(
            "%s: %s",
            $header,
            $value
        ));

        return $this;
    }

    /**
     * @see \r8\iface\Env\Response::setResponseCode
     */
    public function setResponseCode ( $code, $message )
    {
        $this->setRawHeader(sprintf(
            "HTTP/1.0 %d %s",
            $code,
            preg_replace('/[^a-z0-9 ]/i', '', $message)
        ));

        return $this;
    }

}

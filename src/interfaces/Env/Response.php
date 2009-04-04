<?php
/**
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
 * @package Env
 */

namespace cPHP\iface\Env;

/**
 * Defines an interface for sending a response to the client
 */
interface Response
{

    /**
     * Returns whether the headers have been sent to the client
     *
     * @return Boolean
     */
    public function headersSent ();

    /**
     * Sends a header back to the client
     *
     * @param String $header The header string to send
     * @return cPHP\iface\Env\Response Returns a self reference
     */
    public function sendHeader ( $header );

}

?>
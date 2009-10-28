<?php
/**
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
 * @package Soap
 */

namespace h2o\Soap;

/**
 * Delegates a Soap request to the appropriate registered message processors
 */
class Server
{

    /**
     * The processor for handling soap messages
     *
     * @var \h2o\Soap\Server\Messages
     */
    private $messages;

    /**
     * The processor for handling soap headers
     *
     * @var \h2o\Soap\Server\Headers
     */
    private $headers;

    /**
     * Constructor...
     */
    public function __construct (
        \h2o\Soap\Server\Messages $message = null,
        \h2o\Soap\Server\Headers $header = null
    ) {
        $this->messages = empty($message) ? new \h2o\Soap\Server\Messages : $message;
        $this->headers = empty($header) ? new \h2o\Soap\Server\Headers : $header;
    }

    /**
     * Returns the list of registered messages
     *
     * @return \h2o\Soap\Server\Messages
     */
    public function getMessages ()
    {
        return $this->messages;
    }

    /**
     * Returns the Headers registered for processing
     *
     * @return \h2o\Soap\Server\Headers
     */
    public function getHeaders ()
    {
        return $this->headers;
    }

    /**
     * Processes a soap request through this server
     *
     * @param \h2o\Soap\Parser $parser The soap message to process
     * @return \h2o\iface\XMLBuilder Returns the builder needed to construct
     * 		the response
     */
    public function process ( \h2o\Soap\Parser $parser )
    {

    }

}

?>
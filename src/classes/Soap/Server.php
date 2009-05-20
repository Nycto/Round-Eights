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
 * @package Soap
 */

namespace cPHP\Soap;

/**
 * Delegates a Soap request to the appropriate registered command
 */
class Server
{

    /**
     * The list of registered commands
     *
     * @var array
     */
    private $commands = array();

    /**
     * Returns the list of registered command
     *
     * @return array Returns an array of \cPHP\iface\Soap\Command objects
     */
    public function getCommandList ()
    {
        return $this->commands;
    }

    /**
     * Registers a new command
     *
     * @param String $title The name of the command this object will handle
     * @param \cPHP\iface\Soap\Command $command The handler to invoke when
     * 		this command is encountered
     * @return \cPHP\Soap\Server Returns a self reference
     */
    public function registerCommand ( $title, \cPHP\iface\Soap\Command $command )
    {
        $title = \cPHP\str\stripW( $title );

        if ( \cPHP\isEmpty($title) )
            throw new \cPHP\Exception\Argument(0, "Command Title", "Must not be empty");

        $this->commands[ $title ] = $command;

        return $this;
    }

}

?>
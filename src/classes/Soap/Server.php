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
    private $operations = array();

    /**
     * The namespace the command elements will be members of
     *
     * @var String
     */
    private $namespace;

    /**
     * Constructor...
     *
     * @param String $namespace The namespace the command elements will
     * 		be members of
     */
    public function __construct ( $namespace )
    {
        $this->namespace = trim( \cPHP\strval( $namespace ) );
    }

    /**
     * Returns the list of registered operations
     *
     * @return array Returns an array of \cPHP\iface\Soap\Operation objects
     */
    public function getOperations ()
    {
        return $this->operations;
    }

    /**
     * Registers a new command
     *
     * @param String $title The name of the operation this object will handle
     * @param \cPHP\iface\Soap\Operation $operation The handler to invoke when
     * 		this command is encountered
     * @return \cPHP\Soap\Server Returns a self reference
     */
    public function register ( $title, \cPHP\iface\Soap\Operation $operation )
    {
        $title = \cPHP\str\stripW( $title );

        if ( \cPHP\isEmpty($title) )
            throw new \cPHP\Exception\Argument(0, "Command Title", "Must not be empty");

        $this->operations[ $title ] = $operation;

        return $this;
    }

    /**
     * Returns the Soap command element from a given DOM Document
     *
     * @throws \cPHP\Exception\Interrupt\Soap This is thrown if any error
     * 		is encountered while parsing the documet
     * @param DOMDocument $doc The document to parse as a soap request
     * @return DOMElement The command element
     */
    private function getOperationElem ( \DOMDocument $doc )
    {
        if ( !$doc->hasChildNodes() ) {
            throw new \cPHP\Exception\Interrupt\Soap(
            	"Empty XML Document",
                1000
            );
        }

        $xpath = new \DOMXPath( $doc );
        $xpath->registerNamespace("soap", "http://www.w3.org/2001/12/soap-envelope");
        $xpath->registerNamespace("cmd", $this->namespace);

        // Look for the soap envelope
        if ( $xpath->evaluate("count(/soap:Envelope)") == 0 ) {
            throw new \cPHP\Exception\Interrupt\Soap(
            	"Could not find soap envelope",
                1001
            );
        }

        // I couldn't resist this variable name.
        $bodyCount = $xpath->evaluate("count(/soap:Envelope/soap:Body)");

        // Look for the soap body
        if ( $bodyCount == 0 ) {
            throw new \cPHP\Exception\Interrupt\Soap(
            	"Could not find soap body",
                1002
            );
        }

        // Ensure there aren't multiple soap bodies
        if ( $bodyCount > 1 ) {
            throw new \cPHP\Exception\Interrupt\Soap(
            	"Multiple soap body elements found",
                1003
            );
        }

        $cmdCount = $xpath->evaluate("count(/soap:Envelope/soap:Body/cmd:*)");

        // Look for the soap command tag
        if ( $cmdCount == 0 ) {
            throw new \cPHP\Exception\Interrupt\Soap(
            	"Could not find soap operation element",
                1004
            );
        }

        // Ensure there aren't multiple commands
        if ( $cmdCount > 1 ) {
            throw new \cPHP\Exception\Interrupt\Soap(
            	"Multiple soap operation elements found",
                1005
            );
        }

        // Pull the command node
        $cmd = $xpath->query("/soap:Envelope/soap:Body/cmd:*");

        return $cmd->item(0);
    }

    /**
     * Processes a DOMDocument as an soap request
     *
     * In the event of an error, a Soap Fault builder will be returned.
     *
     * @param \DOMDocument $doc The document to process
     * @return \cPHP\iface\XMLBuilder Returns the builder needed to construct
     * 		the response
     */
    public function process ( \DOMDocument $doc )
    {
        try {

            // Extract the soap operation element
            $cmd = $this->getOperationElem( $doc );

            $tag = $cmd->tagName;

            // If the tag is namespaced, just grab the local part
            if ( \cPHP\str\contains(":", $tag) )
                $tag = \cPHP\ary\last( explode(":", $tag) );

            if ( !isset($this->operations[ $tag ]) ) {
                throw new \cPHP\Exception\Interrupt\Soap(
                        "Invalid soap operation",
                        1006
                    );
            }

            return $this->operations[ $tag ]->getResponseBuilder( $doc, $cmd );

        }
        catch ( \cPHP\Exception\Interrupt\Soap $err ) {
            return new \cPHP\XMLBuilder\Soap\Fault(
                    $err->getCode(),
                    $err->getMessage()
                );
        }
    }

}

?>
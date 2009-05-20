<?php
/**
 * Unit Test File
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
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_soap_server extends PHPUnit_Framework_TestCase
{

    public function testRegister ()
    {
        $soap = new \cPHP\Soap\Server;
        $this->assertSame( array(), $soap->getCommandList() );

        $cmd = $this->getMock('\cPHP\iface\Soap\Command');
        $this->assertSame( $soap, $soap->registerCommand("one", $cmd) );
        $this->assertSame( array("one" => $cmd), $soap->getCommandList() );

        $cmd2 = $this->getMock('\cPHP\iface\Soap\Command');
        $this->assertSame( $soap, $soap->registerCommand("two", $cmd2) );
        $this->assertSame(
                array("one" => $cmd, "two" => $cmd2),
                $soap->getCommandList()
            );

        $this->assertSame( $soap, $soap->registerCommand("one", $cmd2) );
        $this->assertSame(
                array("one" => $cmd2, "two" => $cmd2),
                $soap->getCommandList()
            );
    }

    public function testRegister_err ()
    {
        $soap = new \cPHP\Soap\Server;
        $this->assertSame( array(), $soap->getCommandList() );

        $cmd = $this->getMock('\cPHP\iface\Soap\Command');

        try {
            $soap->registerCommand("  ", $cmd);
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

}

?>
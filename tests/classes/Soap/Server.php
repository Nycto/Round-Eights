<?php
/**
 * Unit Test File
 *
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
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_soap_server extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Defaults ()
    {
        $server = new \h2o\Soap\Server;

        $this->assertThat(
            $server->getHeaders(),
            $this->isInstanceOf("h2o\Soap\Server\Headers")
        );

        $this->assertThat(
            $server->getMessages(),
            $this->isInstanceOf("h2o\Soap\Server\Messages")
        );
    }

    public function testConstruct_Injected ()
    {
        $headers = new \h2o\Soap\Server\Headers;
        $messages = new \h2o\Soap\Server\Messages;

        $server = new \h2o\Soap\Server( $messages, $headers );

        $this->assertSame( $headers, $server->getHeaders() );
        $this->assertSame( $messages, $server->getMessages() );
    }

    public function testAddRole ()
    {
        $headers = $this->getMock('h2o\Soap\Server\Headers');
        $headers->expects( $this->once() )
            ->method( "addRole" )
            ->with( $this->equalTo("test:uri") );

        $messages = new \h2o\Soap\Server\Messages;

        $server = new \h2o\Soap\Server( $messages, $headers );

        $this->assertSame( $server, $server->addRole( "test:uri" ) );

    }

    public function testAddHeader ()
    {
        $head = $this->getMock('h2o\iface\Soap\Header');

        $headers = $this->getMock('h2o\Soap\Server\Headers');
        $headers->expects( $this->once() )
            ->method( "addHeader" )
            ->with(
                $this->equalTo( "test:uri" ),
                $this->equalTo( "tag" ),
                $this->equalTo( $head )
            );

        $messages = new \h2o\Soap\Server\Messages;

        $server = new \h2o\Soap\Server( $messages, $headers );

        $this->assertSame(
            $server,
            $server->addHeader( "test:uri", "tag", $head )
        );
    }

    public function testProcess ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>
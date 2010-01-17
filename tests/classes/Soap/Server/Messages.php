<?php
/**
 * Unit Test File
 *
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
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_Soap_Server_Messages extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test Message object
     *
     * @return \r8\Soap\Node\Message
     */
    public function getTestMessage ( $tag, $namespace )
    {
        $message = $this->getMock('r8\Soap\Node\Message', array(), array(), '', FALSE);
        $message->expects( $this->any() )
            ->method( "getTag" )
            ->will( $this->returnValue($tag) );
        $message->expects( $this->any() )
            ->method( "getNamespace" )
            ->will( $this->returnValue($namespace) );
        return $message;
    }

    /**
     * Returns a test processor
     *
     * @return \r8\iface\Soap\Message
     */
    public function getTestProcessor ( $result = NULL )
    {
        $hdr = $this->getMock('r8\iface\Soap\Message', array(), array(), '', FALSE);

        if ( empty($result) ) {
            $hdr->expects( $this->never() )
                ->method( "process" );
        }
        else {
            $hdr->expects( $this->any() )
                ->method( "process" )
                ->will( $this->returnValue($result) );
        }

        return $hdr;
    }

    public function testAddMessage ()
    {
        $soap = new \r8\Soap\Server\Messages;
        $this->assertSame( array(), $soap->getMessages() );

        $cmd = $this->getMock('\r8\iface\Soap\Message');
        $this->assertSame( $soap, $soap->addMessage("test:uri", "one", $cmd) );
        $this->assertSame(
            array( "test:uri" => array("one" => $cmd) ),
            $soap->getMessages()
        );

        $cmd2 = $this->getMock('\r8\iface\Soap\Message');
        $this->assertSame( $soap, $soap->addMessage("test:uri", "two", $cmd2) );
        $this->assertSame(
            array( "test:uri" => array("one" => $cmd, "two" => $cmd2) ),
            $soap->getMessages()
        );

        $this->assertSame( $soap, $soap->addMessage("other:uri", "one", $cmd2) );
        $this->assertSame(
            array(
                "test:uri" => array("one" => $cmd, "two" => $cmd2 ),
                "other:uri" => array("one" => $cmd2 )
            ),
            $soap->getMessages()
        );
    }

    public function testAddMessage_err ()
    {
        $soap = new \r8\Soap\Server\Messages;

        try {
            $soap->addMessage("  ", "test", $this->getMock('\r8\iface\Soap\Message'));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            $soap->addMessage("uri", " ", $this->getMock('\r8\iface\Soap\Message'));
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testProcess_empty ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);
        $parser->expects( $this->once() )
            ->method( "getMessages" )
            ->will( $this->returnValue(array()) );

        $soap = new \r8\Soap\Server\Messages;

        $result = $soap->process( $parser );

        $this->assertThat( $result, $this->isInstanceOf("r8\XMLBuilder\Series") );

        $this->assertFalse( $result->hasChildren() );
    }

    public function testProcess_complete ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);
        $parser->expects( $this->once() )
            ->method( "getMessages" )
            ->will( $this->returnValue(array(
                $this->getTestMessage( 'none', 'uri:test', TRUE, "uri:role" ),
                $this->getTestMessage( 'one', 'uri:test', TRUE ),
                $this->getTestMessage( 'two', 'uri:test' )
            )) );

        $soap = new \r8\Soap\Server\Messages;

        $one = $this->getMock('r8\iface\XMLBuilder');
        $two = $this->getMock('r8\iface\XMLBuilder');

        $soap->addMessage( "uri:test", "one", $this->getTestProcessor( $one ) );
        $soap->addMessage( "other:uri", "one", $this->getTestProcessor() );
        $soap->addMessage( "uri:test", "two", $this->getTestProcessor( $two ) );

        $result = $soap->process( $parser );

        $this->assertThat( $result, $this->isInstanceOf("r8\XMLBuilder\Series") );
        $this->assertSame( array($one, $two), $result->getChildren() );
    }

}

?>
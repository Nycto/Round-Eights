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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Soap_Server extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Defaults ()
    {
        $server = new \r8\Soap\Server;

        $this->assertThat(
            $server->getHeaders(),
            $this->isInstanceOf("r8\Soap\Server\Headers")
        );

        $this->assertThat(
            $server->getMessages(),
            $this->isInstanceOf("r8\Soap\Server\Messages")
        );
    }

    public function testConstruct_Injected ()
    {
        $headers = new \r8\Soap\Server\Headers;
        $messages = new \r8\Soap\Server\Messages;

        $server = new \r8\Soap\Server( $messages, $headers );

        $this->assertSame( $headers, $server->getHeaders() );
        $this->assertSame( $messages, $server->getMessages() );
    }

    public function testAddRole ()
    {
        $headers = $this->getMock('r8\Soap\Server\Headers');
        $headers->expects( $this->once() )
            ->method( "addRole" )
            ->with( $this->equalTo("test:uri") );

        $messages = new \r8\Soap\Server\Messages;

        $server = new \r8\Soap\Server( $messages, $headers );

        $this->assertSame( $server, $server->addRole( "test:uri" ) );

    }

    public function testAddHeader ()
    {
        $head = $this->getMock('r8\iface\Soap\Header');

        $headers = $this->getMock('r8\Soap\Server\Headers');
        $headers->expects( $this->once() )
            ->method( "addHeader" )
            ->with(
                $this->equalTo( "test:uri" ),
                $this->equalTo( "tag" ),
                $this->equalTo( $head )
            );

        $messages = new \r8\Soap\Server\Messages;

        $server = new \r8\Soap\Server( $messages, $headers );

        $this->assertSame(
            $server,
            $server->addHeader( "test:uri", "tag", $head )
        );
    }

    public function testAddMessage ()
    {
        $msg = $this->getMock('r8\iface\Soap\Message');

        $messages = $this->getMock('r8\Soap\Server\Messages');
        $messages->expects( $this->once() )
            ->method( "addMessage" )
            ->with(
                $this->equalTo( "test:uri" ),
                $this->equalTo( "tag" ),
                $this->equalTo( $msg )
            );

        $server = new \r8\Soap\Server( $messages );

        $this->assertSame(
            $server,
            $server->addMessage( "test:uri", "tag", $msg )
        );
    }

    public function testProcess_HeaderFault ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);

        $headers = $this->getMock('r8\Soap\Server\Headers');
        $headers->expects( $this->once() )
            ->method( "process" )
            ->with( $this->equalTo($parser) )
            ->will( $this->throwException(
                new \r8\Soap\Fault( "Fault" )
            ) );

        $messages = $this->getMock('r8\Soap\Server\Messages');
        $messages->expects( $this->never() )->method( "process" );

        $server = new \r8\Soap\Server( $messages, $headers );

        $result = $server->process( $parser );

        $this->assertThat( $result, $this->isInstanceOf("r8\XMLBuilder\Soap\Envelope") );


        $doc = new DOMDocument;
        $doc->appendChild( $result->buildNode( $doc ) );
        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
            .'<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'
                .'<soap:Body>'
                    .'<soap:Fault><soap:Code><soap:Value>Sender</soap:Value></soap:Code><soap:Reason><soap:Text>Fault</soap:Text></soap:Reason></soap:Fault>'
                .'</soap:Body>'
            .'</soap:Envelope>' ."\n",
            $doc->saveXML()
        );
    }

    public function testProcess_BodyFault ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);

        $headers = $this->getMock('r8\Soap\Server\Headers');
        $headers->expects( $this->once() )
            ->method( "process" )
            ->with( $this->equalTo($parser) )
            ->will( $this->returnValue(NULL) );

        $messages = $this->getMock('r8\Soap\Server\Messages');
        $messages->expects( $this->once() )
            ->method( "process" )
            ->with( $this->equalTo($parser) )
            ->will( $this->throwException(
                new \r8\Soap\Fault( "Fault" )
            ) );

        $server = new \r8\Soap\Server( $messages, $headers );

        $result = $server->process( $parser );

        $this->assertThat( $result, $this->isInstanceOf("r8\XMLBuilder\Soap\Envelope") );


        $doc = new DOMDocument;
        $doc->appendChild( $result->buildNode( $doc ) );
        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
            .'<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'
                .'<soap:Body>'
                    .'<soap:Fault><soap:Code><soap:Value>Sender</soap:Value></soap:Code><soap:Reason><soap:Text>Fault</soap:Text></soap:Reason></soap:Fault>'
                .'</soap:Body>'
            .'</soap:Envelope>' ."\n",
            $doc->saveXML()
        );
    }

    public function testProcess_Success ()
    {
        $parser = $this->getMock('r8\Soap\Parser', array(), array(), '', FALSE);

        $headers = $this->getMock('r8\Soap\Server\Headers');
        $headers->expects( $this->once() )
            ->method( "process" )
            ->with( $this->equalTo($parser) )
            ->will( $this->returnValue(
                new \r8\XMLBuilder\Node("Heading")
            ) );

        $messages = $this->getMock('r8\Soap\Server\Messages');
        $messages->expects( $this->once() )
            ->method( "process" )
            ->with( $this->equalTo($parser) )
            ->will( $this->returnValue(
                new \r8\XMLBuilder\Node("Content")
            ) );

        $server = new \r8\Soap\Server( $messages, $headers );

        $result = $server->process( $parser );

        $this->assertThat( $result, $this->isInstanceOf("r8\XMLBuilder\Soap\Envelope") );


        $doc = new DOMDocument;
        $doc->appendChild( $result->buildNode( $doc ) );
        $this->assertSame(
            '<?xml version="1.0"?>' ."\n"
            .'<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'
                .'<soap:Header><Heading/></soap:Header>'
                .'<soap:Body><Content/></soap:Body>'
            .'</soap:Envelope>' ."\n",
            $doc->saveXML()
        );
    }

    public function testProcessStream ()
    {
        $result = $this->getMock('r8\XMLBuilder\Soap\Envelope', array(), array(), '', FALSE);

        $server = $this->getMock('r8\Soap\Server', array('process'));
        $server->expects( $this->once() )
            ->method( "process" )
            ->with( $this->isInstanceOf('r8\Soap\Parser') )
            ->will( $this->returnValue( $result ) );

        $stream = $this->getMock('r8\iface\Stream\In');
        $stream->expects( $this->once() )
            ->method( "readAll" )
            ->will( $this->returnValue("<Request/>") );

        $this->assertSame( $result, $server->processStream($stream) );

    }

}

?>

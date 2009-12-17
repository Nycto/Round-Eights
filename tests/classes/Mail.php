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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_Mail extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test transport object
     *
     * @return \r8\Mail\Transport
     */
    public function getTestTransport ()
    {
        return $this->getMock('\r8\Mail\Transport', array('internalSend'));
    }

    public function testTransportAccessors ()
    {
        $default = $this->getTestTransport();

        $mail = new \r8\Mail( $default );
        $this->assertSame( $default, $mail->getTransport() );

        $transport = $this->getTestTransport();
        $this->assertSame( $mail, $mail->setTransport($transport) );
        $this->assertSame( $transport, $mail->getTransport() );
    }

    public function testFromNameAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );

        $this->assertFalse( $mail->fromNameExists() );
        $this->assertNull( $mail->getFromName() );

        $this->assertSame( $mail, $mail->setFromName("John Doe") );
        $this->assertTrue( $mail->fromNameExists() );
        $this->assertSame( "John Doe", $mail->getFromName() );

        $this->assertSame( $mail, $mail->clearFromName() );
        $this->assertFalse( $mail->fromNameExists() );
        $this->assertNull( $mail->getFromName() );

        $this->assertSame( $mail, $mail->setFromName( "Name". chr(1) ) );
        $this->assertTrue( $mail->fromNameExists() );
        $this->assertSame( "Name", $mail->getFromName() );

        $this->assertSame( $mail, $mail->setFromName("  ") );
        $this->assertFalse( $mail->fromNameExists() );
        $this->assertNull( $mail->getFromName() );
    }

    public function testFromAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );

        $this->assertFalse( $mail->fromExists() );
        $this->assertNull( $mail->getFrom() );
        $this->assertFalse( $mail->fromNameExists() );
        $this->assertNull( $mail->getFromName() );

        $this->assertSame( $mail, $mail->setFrom("test@example.com") );
        $this->assertTrue( $mail->fromExists() );
        $this->assertSame( "test@example.com", $mail->getFrom() );
        $this->assertFalse( $mail->fromNameExists() );
        $this->assertNull( $mail->getFromName() );

        $this->assertSame( $mail, $mail->clearFrom() );
        $this->assertFalse( $mail->fromExists() );
        $this->assertNull( $mail->getFrom() );
        $this->assertFalse( $mail->fromNameExists() );
        $this->assertNull( $mail->getFromName() );

        $this->assertSame( $mail, $mail->setFrom( "Name". chr(220) ."@Example.net", "Label" ) );
        $this->assertTrue( $mail->fromExists() );
        $this->assertSame( "Name@Example.net", $mail->getFrom() );
        $this->assertTrue( $mail->fromNameExists() );
        $this->assertSame( "Label", $mail->getFromName() );

        $this->assertSame( $mail, $mail->setFrom("test@example.com") );
        $this->assertTrue( $mail->fromExists() );
        $this->assertSame( "test@example.com", $mail->getFrom() );
        $this->assertTrue( $mail->fromNameExists() );
        $this->assertSame( "Label", $mail->getFromName() );

        try {
            $this->assertSame( $mail, $mail->setFrom("  ") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Email Address must not be empty", $err->getMessage() );
        }
    }

    public function testToAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );
        $this->assertEquals(
                array(),
                $mail->getTo()
            );
        $this->assertFalse( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );
        $this->assertFalse( $mail->hasTos() );


        $this->assertSame( $mail, $mail->addTo('addr@example.org') );
        $this->assertEquals(
                array( 'addr@example.org' => array('email' => 'addr@example.org', 'name' => null) ),
                $mail->getTo()
            );
        $this->assertTrue( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );
        $this->assertTrue( $mail->hasTos() );


        $this->assertSame( $mail, $mail->addTo('addr@example.org', 'Label') );
        $this->assertEquals(
                array( 'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label') ),
                $mail->getTo()
            );
        $this->assertTrue( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );
        $this->assertTrue( $mail->hasTos() );


        $this->assertSame( $mail, $mail->addTo('test@example.net', 'Name') );
        $this->assertEquals(
                array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label'),
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    ),
                $mail->getTo()
            );
        $this->assertTrue( $mail->toExists('addr@example.org') );
        $this->assertTrue( $mail->toExists('test@example.net') );
        $this->assertTrue( $mail->hasTos() );


        $this->assertSame( $mail, $mail->removeTo('addr@example.org') );
        $this->assertEquals(
                array(
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    ),
                $mail->getTo()
            );
        $this->assertFalse( $mail->toExists('addr@example.org') );
        $this->assertTrue( $mail->toExists('test@example.net') );
        $this->assertTrue( $mail->hasTos() );


        $this->assertSame( $mail, $mail->clearTo() );
        $this->assertEquals( array(), $mail->getTo() );
        $this->assertFalse( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );
        $this->assertFalse( $mail->hasTos() );


        try {
            $this->assertSame( $mail, $mail->addTo("  ") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Email Address must not be empty", $err->getMessage() );
        }


        $this->assertSame( $mail, $mail->clearTo() );
        $this->assertEquals( array(), $mail->getTo() );
        $this->assertFalse( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );
        $this->assertFalse( $mail->hasTos() );
    }

    public function testCCAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );
        $this->assertEquals( array(), $mail->getCC() );
        $this->assertFalse( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );
        $this->assertFalse( $mail->hasCCs() );


        $this->assertSame( $mail, $mail->addCC('addr@example.org') );
        $this->assertEquals(
                array( 'addr@example.org' => array('email' => 'addr@example.org', 'name' => null) ),
                $mail->getCC()
            );
        $this->assertTrue( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );
        $this->assertTrue( $mail->hasCCs() );


        $this->assertSame( $mail, $mail->addCC('addr@example.org', 'Label') );
        $this->assertEquals(
                array( 'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label') ),
                $mail->getCC()
            );
        $this->assertTrue( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );
        $this->assertTrue( $mail->hasCCs() );


        $this->assertSame( $mail, $mail->addCC('test@example.net', 'Name') );
        $this->assertEquals(
                array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label'),
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    ),
                $mail->getCC()
            );
        $this->assertTrue( $mail->ccExists('addr@example.org') );
        $this->assertTrue( $mail->ccExists('test@example.net') );
        $this->assertTrue( $mail->hasCCs() );


        $this->assertSame( $mail, $mail->removeCC('addr@example.org') );
        $this->assertEquals(
                array(
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    ),
                $mail->getCC()
            );
        $this->assertFalse( $mail->ccExists('addr@example.org') );
        $this->assertTrue( $mail->ccExists('test@example.net') );
        $this->assertTrue( $mail->hasCCs() );


        $this->assertSame( $mail, $mail->clearCC() );
        $this->assertEquals( array(), $mail->getCC() );
        $this->assertFalse( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );
        $this->assertFalse( $mail->hasCCs() );


        try {
            $this->assertSame( $mail, $mail->addCC("  ") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Email Address must not be empty", $err->getMessage() );
        }


        $this->assertSame( $mail, $mail->clearCC() );
        $this->assertEquals( array(), $mail->getCC() );
        $this->assertFalse( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );
        $this->assertFalse( $mail->hasCCs() );
    }

    public function testBCCAbccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );
        $this->assertEquals( array(), $mail->getBCC() );
        $this->assertFalse( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );
        $this->assertFalse( $mail->hasBCCs() );


        $this->assertSame( $mail, $mail->addBCC('addr@example.org') );
        $this->assertEquals(
                array( 'addr@example.org' => array('email' => 'addr@example.org', 'name' => null) ),
                $mail->getBCC()
            );
        $this->assertTrue( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );
        $this->assertTrue( $mail->hasBCCs() );


        $this->assertSame( $mail, $mail->addBCC('addr@example.org', 'Label') );
        $this->assertEquals(
                array( 'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label') ),
                $mail->getBCC()
            );
        $this->assertTrue( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );
        $this->assertTrue( $mail->hasBCCs() );


        $this->assertSame( $mail, $mail->addBCC('test@example.net', 'Name') );
        $this->assertEquals(
                array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label'),
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    ),
                $mail->getBCC()
            );
        $this->assertTrue( $mail->bccExists('addr@example.org') );
        $this->assertTrue( $mail->bccExists('test@example.net') );
        $this->assertTrue( $mail->hasBCCs() );


        $this->assertSame( $mail, $mail->removeBCC('addr@example.org') );
        $this->assertEquals(
                array( 'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name') ),
                $mail->getBCC()
            );
        $this->assertFalse( $mail->bccExists('addr@example.org') );
        $this->assertTrue( $mail->bccExists('test@example.net') );
        $this->assertTrue( $mail->hasBCCs() );


        $this->assertSame( $mail, $mail->clearBCC() );
        $this->assertEquals( array(), $mail->getBCC() );
        $this->assertFalse( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );
        $this->assertFalse( $mail->hasBCCs() );


        try {
            $this->assertSame( $mail, $mail->addBCC("  ") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Email Address must not be empty", $err->getMessage() );
        }


        $this->assertSame( $mail, $mail->clearBCC() );
        $this->assertEquals( array(), $mail->getBCC() );
        $this->assertFalse( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );
        $this->assertFalse( $mail->hasBCCs() );
    }

    public function testSubjectAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );

        $this->assertFalse( $mail->subjectExists() );
        $this->assertNull( $mail->getSubject() );

        $this->assertSame( $mail, $mail->setSubject("Hey there") );
        $this->assertTrue( $mail->subjectExists() );
        $this->assertSame( "Hey there", $mail->getSubject() );

        $this->assertSame( $mail, $mail->clearSubject() );
        $this->assertFalse( $mail->subjectExists() );
        $this->assertNull( $mail->getSubject() );

        $this->assertSame( $mail, $mail->setSubject( "Some\nkind of". chr(1)." thing" ) );
        $this->assertTrue( $mail->subjectExists() );
        $this->assertSame( "Some kind of thing", $mail->getSubject() );

        $this->assertSame( $mail, $mail->setSubject("  ") );
        $this->assertFalse( $mail->subjectExists() );
        $this->assertNull( $mail->getSubject() );
    }

    public function testMessageIDAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );

        $this->assertFalse( $mail->messageIDExists() );
        $this->assertNull( $mail->getMessageID() );

        $this->assertSame( $mail, $mail->setMessageID("ABC@132434.xyz") );
        $this->assertTrue( $mail->messageIDExists() );
        $this->assertSame( "ABC@132434.xyz", $mail->getMessageID() );

        $this->assertSame( $mail, $mail->clearMessageID() );
        $this->assertFalse( $mail->messageIDExists() );
        $this->assertNull( $mail->getMessageID() );

        $this->assertSame( $mail, $mail->setMessageID( "Invalid\nChars". chr(1)." In Here" ) );
        $this->assertTrue( $mail->messageIDExists() );
        $this->assertSame( "Invalid Chars In Here", $mail->getMessageID() );

        $this->assertSame( $mail, $mail->setMessageID("  ") );
        $this->assertFalse( $mail->messageIDExists() );
        $this->assertNull( $mail->getMessageID() );
    }

    public function testTextAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );

        $this->assertFalse( $mail->textExists() );
        $this->assertNull( $mail->getText() );

        $this->assertSame( $mail, $mail->setText("Hey there") );
        $this->assertTrue( $mail->textExists() );
        $this->assertSame( "Hey there", $mail->getText() );

        $this->assertSame( $mail, $mail->clearText() );
        $this->assertFalse( $mail->textExists() );
        $this->assertNull( $mail->getText() );

        $this->assertSame( $mail, $mail->setText( "Some kind of content" ) );
        $this->assertTrue( $mail->textExists() );
        $this->assertSame( "Some kind of content", $mail->getText() );

        $this->assertSame( $mail, $mail->setText("  ") );
        $this->assertFalse( $mail->textExists() );
        $this->assertNull( $mail->getText() );
    }

    public function testHTMLAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );

        $this->assertFalse( $mail->htmlExists() );
        $this->assertNull( $mail->getHTML() );

        $this->assertSame( $mail, $mail->setHTML("Hey there") );
        $this->assertTrue( $mail->htmlExists() );
        $this->assertSame( "Hey there", $mail->getHTML() );

        $this->assertSame( $mail, $mail->clearHTML() );
        $this->assertFalse( $mail->htmlExists() );
        $this->assertNull( $mail->getHTML() );

        $this->assertSame( $mail, $mail->setHTML( "<p>Some kind of content</p>" ) );
        $this->assertTrue( $mail->htmlExists() );
        $this->assertSame( "<p>Some kind of content</p>", $mail->getHTML() );

        $this->assertSame( $mail, $mail->setHTML("  ") );
        $this->assertFalse( $mail->htmlExists() );
        $this->assertNull( $mail->getHTML() );
    }

    public function testConstruct_emptyIni ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \r8\Mail( $this->getTestTransport() );
        $this->assertNull( $mail->getFrom() );
    }

    public function testConstruct_invalidIni ()
    {
        $this->iniSet('sendmail_from', 'example');

        $mail = new \r8\Mail( $this->getTestTransport() );
        $this->assertNull( $mail->getFrom() );
    }

    public function testConstruct_validIni ()
    {
        $this->iniSet('sendmail_from', 'test@example.net');

        $mail = new \r8\Mail( $this->getTestTransport() );
        $this->assertSame( 'test@example.net', $mail->getFrom() );
    }

    public function testAddCustomHeader ()
    {
        $mail = new \r8\Mail( $this->getTestTransport() );
        $this->assertEquals( array(), $mail->getCustomHeaders() );

        $this->assertSame( $mail, $mail->addCustomHeader('X-Test', 'Example Header') );
        $this->assertEquals(
                array(
                        'X-Test' => 'Example Header'
                    ),
                $mail->getCustomHeaders()
            );


        $this->assertSame( $mail, $mail->addCustomHeader('X-Test', Null) );
        $this->assertEquals(
                array(
                        'X-Test' => ''
                    ),
                $mail->getCustomHeaders()
            );


        $this->assertSame( $mail, $mail->addCustomHeader('In-Reply-To', 'abcxyz') );
        $this->assertEquals(
                array(
                        'X-Test' => '',
                        'In-Reply-To' => 'abcxyz'
                    ),
                $mail->getCustomHeaders()
            );
    }

    public function testAddCustomHeader_error ()
    {
        $mail = new \r8\Mail( $this->getTestTransport() );

        try {
            $mail->addCustomHeader( '', 'Value' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testCustomHeaderExists ()
    {
        $mail = new \r8\Mail( $this->getTestTransport() );
        $this->assertFalse( $mail->customHeaderExists('X-Test') );

        $mail->addCustomHeader('X-Test', 'value');
        $this->assertTrue( $mail->customHeaderExists('X-Test') );
        $this->assertFalse( $mail->customHeaderExists('X-Other') );

        try {
            $mail->customHeaderExists( '' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testRemoveCustomHeader ()
    {
        $mail = new \r8\Mail( $this->getTestTransport() );

        $mail->addCustomHeader('X-Test', 'value');
        $this->assertTrue( $mail->customHeaderExists('X-Test') );

        $this->assertSame( $mail, $mail->removeCustomHeader('X-Test') );
        $this->assertFalse( $mail->customHeaderExists('X-Test') );

        $this->assertSame( $mail, $mail->removeCustomHeader('X-Test') );
        $this->assertFalse( $mail->customHeaderExists('X-Test') );

        try {
            $mail->removeCustomHeader( '', 'Value' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testClearCustomHeaders ()
    {
        $mail = new \r8\Mail( $this->getTestTransport() );

        $mail->addCustomHeader('X-Test', 'value');
        $mail->addCustomHeader('X-Other', 'value');
        $this->assertTrue( $mail->customHeaderExists('X-Test') );
        $this->assertTrue( $mail->customHeaderExists('X-Other') );

        $this->assertSame( $mail, $mail->clearCustomHeaders() );
        $this->assertEquals( array(), $mail->getCustomHeaders() );
    }

    public function testGetBoundary ()
    {
        $mail = new \r8\Mail( $this->getTestTransport() );

        $boundary = $mail->getBoundary();

        $this->assertType( 'string', $boundary );
        $this->assertEquals( 30, strlen($boundary) );
        $this->assertRegExp( '/^\=\_[0-9a-zA-Z]{26}\_\=$/', $boundary );

        // subsequent calls should be the same
        $this->assertSame( $boundary, $mail->getBoundary() );
        $this->assertSame( $boundary, $mail->getBoundary() );
        $this->assertSame( $boundary, $mail->getBoundary() );

        // Putting the boundary in the text should result in a change of the boundary
        $mail->setText('string '. $boundary .' more data');

        $newBoundary = $mail->getBoundary();
        $this->assertNotSame( $newBoundary, $boundary );

        $this->assertSame( $newBoundary, $mail->getBoundary() );
        $this->assertSame( $newBoundary, $mail->getBoundary() );
        $this->assertSame( $newBoundary, $mail->getBoundary() );
    }

    public function testSend ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend', 'send'));

        $mail = new \r8\Mail( $this->getTestTransport() );
        $mail->setTransport( $transport );

        $transport->expects( $this->once() )
            ->method('send')
            ->with( $this->equalTo($mail) );

        $this->assertSame( $mail, $mail->send() );
    }

}

?>
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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_mail extends PHPUnit_Framework_TestCase
{

    public function testStripHeaderName ()
    {
        $chars = implode("", array_map( 'chr', range(1, 255) ));
        $this->assertSame(
                '!"#$%&\'()*+,-./0123456789;<=>?@ABCDEFGHIJKLMNOP'
                .'QRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
                \cPHP\Mail::stripHeaderName( $chars )
            );
    }

    public function testStripHeaderValue ()
    {
        $chars = implode("", array_map( 'chr', range(1, 255) ));
        $this->assertSame(
                '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOP'
                .'QRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
                \cPHP\Mail::stripHeaderValue( $chars )
            );

        $this->assertSame(
                "Break\n\tString",
                \cPHP\Mail::stripHeaderValue( "Break\nString" )
            );

        $this->assertSame(
                "Break\n\tString",
                \cPHP\Mail::stripHeaderValue( "Break\rString" )
            );

        $this->assertSame(
                "Break\n\tString",
                \cPHP\Mail::stripHeaderValue( "Break\r\nString" )
            );

        $this->assertSame(
                "Break\n\tString",
                \cPHP\Mail::stripHeaderValue( "Break\n\tString" )
            );

        $this->assertSame(
                "Break\n\tString",
                \cPHP\Mail::stripHeaderValue( "Break\n  \tString" )
            );

        $this->assertSame(
                "Break\n\tString",
                \cPHP\Mail::stripHeaderValue( "Break\n\n\n\r\n\r\r\rString" )
            );

        $this->assertSame(
                "String",
                \cPHP\Mail::stripHeaderValue( "   String   " )
            );

        $this->assertSame(
                "String",
                \cPHP\Mail::stripHeaderValue( "\nString\n\r" )
            );

    }

    public function testFromNameAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \cPHP\Mail;

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

        $mail = new \cPHP\Mail;

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
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame( "Email Address must contain an 'at' (@) symbol", $err->getMessage() );
        }
    }

    public function testToAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \cPHP\Mail;
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getTo()
            );
        $this->assertFalse( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );


        $this->assertSame( $mail, $mail->addTo('addr@example.org') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => null)
                    )),
                $mail->getTo()
            );
        $this->assertTrue( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );


        $this->assertSame( $mail, $mail->addTo('addr@example.org', 'Label') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label')
                    )),
                $mail->getTo()
            );
        $this->assertTrue( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );


        $this->assertSame( $mail, $mail->addTo('test@example.net', 'Name') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label'),
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    )),
                $mail->getTo()
            );
        $this->assertTrue( $mail->toExists('addr@example.org') );
        $this->assertTrue( $mail->toExists('test@example.net') );


        $this->assertSame( $mail, $mail->removeTo('addr@example.org') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    )),
                $mail->getTo()
            );
        $this->assertFalse( $mail->toExists('addr@example.org') );
        $this->assertTrue( $mail->toExists('test@example.net') );


        $this->assertSame( $mail, $mail->clearTo() );
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getTo()
            );
        $this->assertFalse( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );


        try {
            $this->assertSame( $mail, $mail->addTo("  ") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame( "Email Address must contain an 'at' (@) symbol", $err->getMessage() );
        }


        $this->assertSame( $mail, $mail->clearTo() );
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getTo()
            );
        $this->assertFalse( $mail->toExists('addr@example.org') );
        $this->assertFalse( $mail->toExists('test@example.net') );
    }

    public function testCCAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \cPHP\Mail;
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getCC()
            );
        $this->assertFalse( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );


        $this->assertSame( $mail, $mail->addCC('addr@example.org') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => null)
                    )),
                $mail->getCC()
            );
        $this->assertTrue( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );


        $this->assertSame( $mail, $mail->addCC('addr@example.org', 'Label') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label')
                    )),
                $mail->getCC()
            );
        $this->assertTrue( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );


        $this->assertSame( $mail, $mail->addCC('test@example.net', 'Name') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label'),
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    )),
                $mail->getCC()
            );
        $this->assertTrue( $mail->ccExists('addr@example.org') );
        $this->assertTrue( $mail->ccExists('test@example.net') );


        $this->assertSame( $mail, $mail->removeCC('addr@example.org') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    )),
                $mail->getCC()
            );
        $this->assertFalse( $mail->ccExists('addr@example.org') );
        $this->assertTrue( $mail->ccExists('test@example.net') );


        $this->assertSame( $mail, $mail->clearCC() );
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getCC()
            );
        $this->assertFalse( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );


        try {
            $this->assertSame( $mail, $mail->addCC("  ") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame( "Email Address must contain an 'at' (@) symbol", $err->getMessage() );
        }


        $this->assertSame( $mail, $mail->clearCC() );
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getCC()
            );
        $this->assertFalse( $mail->ccExists('addr@example.org') );
        $this->assertFalse( $mail->ccExists('test@example.net') );
    }

    public function testBCCAbccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \cPHP\Mail;
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getBCC()
            );
        $this->assertFalse( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );


        $this->assertSame( $mail, $mail->addBCC('addr@example.org') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => null)
                    )),
                $mail->getBCC()
            );
        $this->assertTrue( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );


        $this->assertSame( $mail, $mail->addBCC('addr@example.org', 'Label') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label')
                    )),
                $mail->getBCC()
            );
        $this->assertTrue( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );


        $this->assertSame( $mail, $mail->addBCC('test@example.net', 'Name') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'addr@example.org' => array('email' => 'addr@example.org', 'name' => 'Label'),
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    )),
                $mail->getBCC()
            );
        $this->assertTrue( $mail->bccExists('addr@example.org') );
        $this->assertTrue( $mail->bccExists('test@example.net') );


        $this->assertSame( $mail, $mail->removeBCC('addr@example.org') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'test@example.net' => array('email' => 'test@example.net', 'name' => 'Name')
                    )),
                $mail->getBCC()
            );
        $this->assertFalse( $mail->bccExists('addr@example.org') );
        $this->assertTrue( $mail->bccExists('test@example.net') );


        $this->assertSame( $mail, $mail->clearBCC() );
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getBCC()
            );
        $this->assertFalse( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );


        try {
            $this->assertSame( $mail, $mail->addBCC("  ") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame( "Email Address must contain an 'at' (@) symbol", $err->getMessage() );
        }


        $this->assertSame( $mail, $mail->clearBCC() );
        $this->assertEquals(
                new \cPHP\Ary(array()),
                $mail->getBCC()
            );
        $this->assertFalse( $mail->bccExists('addr@example.org') );
        $this->assertFalse( $mail->bccExists('test@example.net') );
    }

    public function testSubjectAccessors ()
    {
        $this->iniSet('sendmail_from', '');

        $mail = new \cPHP\Mail;

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

        $mail = new \cPHP\Mail;

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

        $mail = new \cPHP\Mail;

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

        $mail = new \cPHP\Mail;

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

        $mail = new \cPHP\Mail;
        $this->assertNull( $mail->getFrom() );
    }

    public function testConstruct_invalidIni ()
    {
        $this->iniSet('sendmail_from', 'example');

        $mail = new \cPHP\Mail;
        $this->assertNull( $mail->getFrom() );
    }

    public function testConstruct_validIni ()
    {
        $this->iniSet('sendmail_from', 'test@example.net');

        $mail = new \cPHP\Mail;
        $this->assertSame( 'test@example.net', $mail->getFrom() );
    }

    public function testCreate ()
    {
        $this->assertThat(
                \cPHP\Mail::create(),
                $this->isInstanceOf('cPHP\Mail')
            );
    }

    public function testAddCustomHeader ()
    {
        $mail = new \cPHP\Mail;
        $this->assertEquals(
                new \cPHP\Ary,
                $mail->getCustomHeaders()
            );


        $this->assertSame( $mail, $mail->addCustomHeader('X-Test', 'Example Header') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'X-Test' => 'Example Header'
                    )),
                $mail->getCustomHeaders()
            );


        $this->assertSame( $mail, $mail->addCustomHeader('X-Test', Null) );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'X-Test' => ''
                    )),
                $mail->getCustomHeaders()
            );


        $this->assertSame( $mail, $mail->addCustomHeader('In-Reply-To', 'abcxyz') );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        'X-Test' => '',
                        'In-Reply-To' => 'abcxyz'
                    )),
                $mail->getCustomHeaders()
            );
    }

    public function testAddCustomHeader_charTest ()
    {
        $mail = new \cPHP\Mail;

        $chars = implode("", array_map( 'chr', range(1, 255) ));
        $this->assertSame( $mail, $mail->addCustomHeader( $chars, $chars ) );

        $headers = $mail->getCustomHeaders();
        $this->assertThat( $headers, $this->isInstanceOf('cPHP\Ary') );
        $this->assertSame( 1, count($headers) );

        $this->assertSame(
                "!\"#$%&'()*+,-./0123456789;<=>?@ABCDEFGHIJKLMNOPQ"
                ."RSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~",
                $headers->keys()->pop( TRUE )
            );

        $this->assertSame(
                "!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQR"
                ."STUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~",
                $headers->pop( TRUE )
            );
    }

    public function testAddCustomHeader_error ()
    {
        $mail = new \cPHP\Mail;

        try {
            $mail->addCustomHeader( '', 'Value' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testCustomHeaderExists ()
    {
        $mail = new \cPHP\Mail;
        $this->assertFalse( $mail->customHeaderExists('X-Test') );

        $mail->addCustomHeader('X-Test', 'value');
        $this->assertTrue( $mail->customHeaderExists('X-Test') );
        $this->assertFalse( $mail->customHeaderExists('X-Other') );

        try {
            $mail->customHeaderExists( '' );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testRemoveCustomHeader ()
    {
        $mail = new \cPHP\Mail;

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
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testClearCustomHeaders ()
    {
        $mail = new \cPHP\Mail;

        $mail->addCustomHeader('X-Test', 'value');
        $mail->addCustomHeader('X-Other', 'value');
        $this->assertTrue( $mail->customHeaderExists('X-Test') );
        $this->assertTrue( $mail->customHeaderExists('X-Other') );

        $this->assertSame( $mail, $mail->clearCustomHeaders() );
        $this->assertEquals( new \cPHP\Ary, $mail->getCustomHeaders() );
    }

}

?>
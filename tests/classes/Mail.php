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

    public function testFromNameAccessors ()
    {
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

    public function testToNameAccessors ()
    {
        $mail = new \cPHP\Mail;

        $this->assertFalse( $mail->toNameExists() );
        $this->assertNull( $mail->getToName() );

        $this->assertSame( $mail, $mail->setToName("John Doe") );
        $this->assertTrue( $mail->toNameExists() );
        $this->assertSame( "John Doe", $mail->getToName() );

        $this->assertSame( $mail, $mail->clearToName() );
        $this->assertFalse( $mail->toNameExists() );
        $this->assertNull( $mail->getToName() );

        $this->assertSame( $mail, $mail->setToName( "Name". chr(1) ) );
        $this->assertTrue( $mail->toNameExists() );
        $this->assertSame( "Name", $mail->getToName() );

        $this->assertSame( $mail, $mail->setToName("  ") );
        $this->assertFalse( $mail->toNameExists() );
        $this->assertNull( $mail->getToName() );
    }

    public function testToAccessors ()
    {
        $mail = new \cPHP\Mail;

        $this->assertFalse( $mail->toExists() );
        $this->assertNull( $mail->getTo() );
        $this->assertFalse( $mail->toNameExists() );
        $this->assertNull( $mail->getToName() );

        $this->assertSame( $mail, $mail->setTo("test@example.com") );
        $this->assertTrue( $mail->toExists() );
        $this->assertSame( "test@example.com", $mail->getTo() );
        $this->assertFalse( $mail->toNameExists() );
        $this->assertNull( $mail->getToName() );

        $this->assertSame( $mail, $mail->clearTo() );
        $this->assertFalse( $mail->toExists() );
        $this->assertNull( $mail->getTo() );
        $this->assertFalse( $mail->toNameExists() );
        $this->assertNull( $mail->getToName() );

        $this->assertSame( $mail, $mail->setTo( "Name". chr(220) ."@Example.net", "Label" ) );
        $this->assertTrue( $mail->toExists() );
        $this->assertSame( "Name@Example.net", $mail->getTo() );
        $this->assertTrue( $mail->toNameExists() );
        $this->assertSame( "Label", $mail->getToName() );

        $this->assertSame( $mail, $mail->setTo("test@example.com") );
        $this->assertTrue( $mail->toExists() );
        $this->assertSame( "test@example.com", $mail->getTo() );
        $this->assertTrue( $mail->toNameExists() );
        $this->assertSame( "Label", $mail->getToName() );

        try {
            $this->assertSame( $mail, $mail->setTo("  ") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame( "Email Address must contain an 'at' (@) symbol", $err->getMessage() );
        }
    }

    public function testSubjectAccessors ()
    {
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

    public function testTextAccessors ()
    {
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

}

?>
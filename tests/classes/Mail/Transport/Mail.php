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
class classes_Mail_Transport_Mail extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $transport = new \r8\Mail\Transport\Mail;
        $this->assertThat(
            $transport->getFormatter(),
            $this->isInstanceOf('\r8\Mail\Formatter')
        );
    }

    public function testSend_success ()
    {
        $format = $this->getMock('\r8\Mail\Formatter');


        $transport = $this->getMock('r8\Mail\Transport\Mail', array('rawMail'), array($format));
        $transport->expects( $this->once() )
            ->method("rawMail")
            ->with(
                $this->equalTo('"Destination" <dest@example.com>'),
                $this->equalTo("Some Test Email"),
                $this->equalTo("This is the text"),
                $this->equalTo('From: "Test Acct" <tester@example.net>')
            )
            ->will( $this->returnValue(TRUE) );


        $mail = new \r8\Mail( $transport );
        $mail->setFrom("tester@example.net", "Test Acct")
            ->addTo("dest@example.com", "Destination")
            ->setSubject("Some Test Email")
            ->setText("This is the text");


        $format->expects( $this->once() )
            ->method( "getToString" )
            ->with( $this->equalTo($mail) )
            ->will( $this->returnValue( '"Destination" <dest@example.com>' ) );
        $format->expects( $this->once() )
            ->method( "getBody" )
            ->with( $this->equalTo($mail) )
            ->will( $this->returnValue( 'This is the text' ) );
        $format->expects( $this->once() )
            ->method( "getHeaderString" )
            ->with( $this->equalTo($mail) )
            ->will( $this->returnValue( 'From: "Test Acct" <tester@example.net>' ) );


        $transport->send( $mail );
    }

    public function testSend_fail ()
    {
        $format = $this->getMock('\r8\Mail\Formatter');


        $transport = $this->getMock('r8\Mail\Transport\Mail', array('rawMail'), array($format));
        $transport->expects( $this->once() )
            ->method("rawMail")
            ->with(
                $this->equalTo('"Destination" <dest@example.com>'),
                $this->equalTo("Some Test Email"),
                $this->equalTo("This is the text"),
                $this->equalTo('From: "Test Acct" <tester@example.net>')
            )
            ->will( $this->returnValue(FALSE) );


        $mail = new \r8\Mail( $transport );
        $mail->setFrom("tester@example.net", "Test Acct")
            ->addTo("dest@example.com", "Destination")
            ->setSubject("Some Test Email")
            ->setText("This is the text");


        $format->expects( $this->exactly(2) )
            ->method( "getToString" )
            ->with( $this->equalTo($mail) )
            ->will( $this->returnValue( '"Destination" <dest@example.com>' ) );
        $format->expects( $this->once() )
            ->method( "getBody" )
            ->with( $this->equalTo($mail) )
            ->will( $this->returnValue( 'This is the text' ) );
        $format->expects( $this->once() )
            ->method( "getHeaderString" )
            ->with( $this->equalTo($mail) )
            ->will( $this->returnValue( 'From: "Test Acct" <tester@example.net>' ) );


        try {
            $transport->send( $mail );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame( "An error occured while sending mail", $err->getMessage() );
        }
    }

}

?>
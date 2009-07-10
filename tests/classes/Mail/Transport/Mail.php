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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_mail_transport_mail extends PHPUnit_Framework_TestCase
{

    public function testSend_success ()
    {
        $transport = $this->getMock('h2o\Mail\Transport\Mail', array('rawMail'));

        $transport->expects( $this->once() )
            ->method("rawMail")
            ->with(
                $this->equalTo('"Destination" <dest@example.com>'),
                $this->equalTo("Some Test Email"),
                $this->equalTo("This is the text"),
                $this->logicalAnd(
                    $this->stringContains('From: "Test Acct" <tester@example.net>'),
                    $this->stringContains('To: "Destination" <dest@example.com>'),
                    $this->stringContains('Subject: Some Test Email'),
                    $this->stringContains('Date: '),
                    $this->stringContains('MIME-Version: 1.0'),
                    $this->stringContains('Content-Type: text/plain; charset="ISO-8859-1"'),
                    $this->stringContains('Content-Transfer-Encoding: 7bit')
                )
            )
            ->will( $this->returnValue(TRUE) );

        $mail = new \h2o\Mail;
        $mail->setFrom("tester@example.net", "Test Acct")
            ->addTo("dest@example.com", "Destination")
            ->setSubject("Some Test Email")
            ->setText("This is the text");

        $transport->send( $mail );
    }

    public function testSend_fail ()
    {
        $transport = $this->getMock('h2o\Mail\Transport\Mail', array('rawMail'));

        $transport->expects( $this->once() )
            ->method("rawMail")
            ->with(
                $this->equalTo('"Destination" <dest@example.com>'),
                $this->equalTo("Some Test Email"),
                $this->equalTo("This is the text"),
                $this->logicalAnd(
                    $this->stringContains('From: "Test Acct" <tester@example.net>'),
                    $this->stringContains('To: "Destination" <dest@example.com>'),
                    $this->stringContains('Subject: Some Test Email'),
                    $this->stringContains('Date: '),
                    $this->stringContains('MIME-Version: 1.0'),
                    $this->stringContains('Content-Type: text/plain; charset="ISO-8859-1"'),
                    $this->stringContains('Content-Transfer-Encoding: 7bit')
                )
            )
            ->will( $this->returnValue(FALSE) );

        $mail = new \h2o\Mail;
        $mail->setFrom("tester@example.net", "Test Acct")
            ->addTo("dest@example.com", "Destination")
            ->setSubject("Some Test Email")
            ->setText("This is the text");

        try {
            $transport->send( $mail );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Interaction $err ) {
            $this->assertSame( "An error occured while sending mail", $err->getMessage() );
        }
    }

}

?>
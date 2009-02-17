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
class classes_mail_transport extends PHPUnit_Framework_TestCase
{

    public function testFormatAddress ()
    {
        $this->assertSame(
                "<test@example.com>",
                \cPHP\Mail\Transport::formatAddress("test@example.com")
            );

        $this->assertSame(
                '"Lug MightyChunk" <test@example.com>',
                \cPHP\Mail\Transport::formatAddress("test@example.com", "Lug MightyChunk")
            );

        $this->assertSame(
                '<test@example.com>',
                \cPHP\Mail\Transport::formatAddress("test@example.com", chr(5))
            );

        $this->assertSame(
                '"Lug \"MightyChunk\"" <test@example.com>',
                \cPHP\Mail\Transport::formatAddress("test@example.com", 'Lug "MightyChunk"')
            );
    }

    public function testGetToString ()
    {
        $mail = new \cPHP\Mail;
        $transport = $this->getMock('cPHP\Mail\Transport');
        $this->assertSame( "", $transport->getToString($mail) );

        $mail->addTo("test@example.com");
        $this->assertSame(
                "<test@example.com>",
                $transport->getToString($mail)
            );

        $mail->addTo("other@example.net", "Jack Snap");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>",
                $transport->getToString($mail)
            );

        $mail->addTo("another@example.org", "Crackle Pop");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>, "
                ."\"Crackle Pop\" <another@example.org>",
                $transport->getToString($mail)
            );
    }

    public function testGetBCCString ()
    {
        $mail = new \cPHP\Mail;
        $transport = $this->getMock('cPHP\Mail\Transport');
        $this->assertSame( "", $transport->getBCCString($mail) );

        $mail->addBCC("test@example.com");
        $this->assertSame(
                "<test@example.com>",
                $transport->getBCCString($mail)
            );

        $mail->addBCC("other@example.net", "Jack Snap");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>",
                $transport->getBCCString($mail)
            );

        $mail->addBCC("another@example.org", "Crackle Pop");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>, "
                ."\"Crackle Pop\" <another@example.org>",
                $transport->getBCCString($mail)
            );
    }

    public function testGetHeaderList_sparse ()
    {
        $mail = new \cPHP\Mail;

        $transport = $this->getMock('cPHP\Mail\Transport');

        $headers = $transport->getHeaderList( $mail );
        $this->assertArrayHasKey("Date", $headers);
        $this->assertRegExp(
                '/^\w{3}\, \d{2} \w{3} \d{4} \d{2}\:\d{2}\:\d{2} [-+]\d{4}$/i',
                $headers['Date']
            );
        unset( $headers['Date'] );

        $this->assertSame(
                array(
                        "MIME-Version" => "1.0",
                        "Content-Type" => 'text/plain; charset="ISO-8859-1"',
                        "Content-Transfer-Encoding" => "7bit"
                    ),
                $headers
            );
    }

    public function testGetHeaderList_full ()
    {
        $mail = new \cPHP\Mail;
        $mail->setFrom("from@example.com", "Jack Test")
            ->addTo("other@example.net", "Jack Snap")
            ->addTo("another@example.org", "Crackle Pop")
            ->addCC("cc@example.edu", "Veal SteakFace")
            ->addCC("devnull@example.org")
            ->addBCC("bcc@example.com")
            ->setSubject("This is a test")
            ->setMessageID("abc123");

        $transport = $this->getMock('cPHP\Mail\Transport');

        $headers = $transport->getHeaderList( $mail );
        $this->assertArrayHasKey("Date", $headers);
        $this->assertRegExp(
                '/^\w{3}\, \d{2} \w{3} \d{4} \d{2}\:\d{2}\:\d{2} [-+]\d{4}$/i',
                $headers['Date']
            );
        unset( $headers['Date'] );

        $this->assertSame(
                array(
                        'From' => '"Jack Test" <from@example.com>',
                        'To' => '"Jack Snap" <other@example.net>, "Crackle Pop" <another@example.org>',
                        'CC' => '"Veal SteakFace" <cc@example.edu>, <devnull@example.org>',
                        'BCC' => '<bcc@example.com>',
                        'Subject' => 'This is a test',
                        "MIME-Version" => "1.0",
                        'Message-ID' => '<abc123>',
                        "Content-Type" => 'text/plain; charset="ISO-8859-1"',
                        "Content-Transfer-Encoding" => "7bit"
                    ),
                $headers
            );
    }

    public function testGetHeaderList_textOnly ()
    {
        $mail = new \cPHP\Mail;
        $mail->setText("This is some content");

        $transport = $this->getMock('cPHP\Mail\Transport');

        $headers = $transport->getHeaderList( $mail );
        $this->assertArrayHasKey("Date", $headers);
        $this->assertRegExp(
                '/^\w{3}\, \d{2} \w{3} \d{4} \d{2}\:\d{2}\:\d{2} [-+]\d{4}$/i',
                $headers['Date']
            );
        unset( $headers['Date'] );

        $this->assertSame(
                array(
                        "MIME-Version" => "1.0",
                        "Content-Type" => 'text/plain; charset="ISO-8859-1"',
                        "Content-Transfer-Encoding" => "7bit"
                    ),
                $headers
            );
    }

    public function testGetHeaderList_htmlOnly ()
    {
        $mail = new \cPHP\Mail;
        $mail->setHTML("This is some content");

        $transport = $this->getMock('cPHP\Mail\Transport');

        $headers = $transport->getHeaderList( $mail );
        $this->assertArrayHasKey("Date", $headers);
        $this->assertRegExp(
                '/^\w{3}\, \d{2} \w{3} \d{4} \d{2}\:\d{2}\:\d{2} [-+]\d{4}$/i',
                $headers['Date']
            );
        unset( $headers['Date'] );

        $this->assertSame(
                array(
                        "MIME-Version" => "1.0",
                        "Content-Type" => 'text/html; charset="ISO-8859-1"',
                        "Content-Transfer-Encoding" => "7bit"
                    ),
                $headers
            );
    }

    public function testGetHeaderList_multipart ()
    {
        $mail = new \cPHP\Mail;
        $mail->setHTML("This is some content");
        $mail->setText("This is some content");

        $transport = $this->getMock('cPHP\Mail\Transport');

        $headers = $transport->getHeaderList( $mail );
        $this->assertArrayHasKey("Date", $headers);
        $this->assertRegExp(
                '/^\w{3}\, \d{2} \w{3} \d{4} \d{2}\:\d{2}\:\d{2} [-+]\d{4}$/i',
                $headers['Date']
            );
        unset( $headers['Date'] );

        $this->assertArrayHasKey("Content-Type", $headers);
        $this->assertRegExp(
                "/^multipart\/alternative;\\sboundary='=_[\\w=]+'$/i",
                $headers['Content-Type']
            );
        unset( $headers['Content-Type'] );

        $this->assertSame(
                array(
                        "MIME-Version" => "1.0",
                    ),
                $headers
            );
    }

    public function testGetHeaderString_sparse ()
    {
        $mail = new \cPHP\Mail;

        $transport = $this->getMock('cPHP\Mail\Transport');

        $headers = $transport->getHeaderString( $mail );
        $this->assertType('string', $headers);

        $this->assertRegExp(
                '/Date: \w{3}\, \d{2} \w{3} \d{4} \d{2}\:\d{2}\:\d{2} [-+]\d{4}/i',
                $headers
            );

        $this->assertContains("MIME-Version: 1.0", $headers);
        $this->assertContains('Content-Type: text/plain; charset="ISO-8859-1"', $headers);
        $this->assertContains("Content-Transfer-Encoding: 7bit", $headers);
    }

    public function testGetHeaderString_full ()
    {
        $mail = new \cPHP\Mail;
        $mail->setFrom("from@example.com", "Jack Test")
            ->addTo("other@example.net", "Jack Snap")
            ->addTo("another@example.org", "Crackle Pop")
            ->addCC("cc@example.edu", "Veal SteakFace")
            ->addCC("devnull@example.org")
            ->addBCC("bcc@example.com")
            ->setSubject("This is a test")
            ->setMessageID("abc123");

        $transport = $this->getMock('cPHP\Mail\Transport');

        $headers = $transport->getHeaderString( $mail );
        $this->assertType('string', $headers);

        $this->assertRegExp(
                '/Date: \w{3}\, \d{2} \w{3} \d{4} \d{2}\:\d{2}\:\d{2} [-+]\d{4}/i',
                $headers
            );

        $this->assertContains('From: "Jack Test" <from@example.com>', $headers);
        $this->assertContains('To: "Jack Snap" <other@example.net>, "Crackle Pop" <another@example.org>', $headers);
        $this->assertContains('CC: "Veal SteakFace" <cc@example.edu>, <devnull@example.org>', $headers);
        $this->assertContains('BCC: <bcc@example.com>', $headers);
        $this->assertContains('Subject: This is a test', $headers);
        $this->assertContains('MIME-Version: 1.0', $headers);
        $this->assertContains('Message-ID: <abc123>', $headers);
        $this->assertContains('Content-Type: text/plain; charset="ISO-8859-1"', $headers);
        $this->assertContains('Content-Transfer-Encoding: 7bit', $headers);
    }

    public function testPrepareText ()
    {
        $transport = $this->getMock('cPHP\Mail\Transport');

        $this->assertSame(
                "This string should not be changed",
                $transport->prepareText("This string should not be changed")
            );

        $this->assertSame( "start\r\nend", $transport->prepareText("start\nend") );
        $this->assertSame( "start\r\nend", $transport->prepareText("start\rend") );
        $this->assertSame( "start\r\nend", $transport->prepareText("start\r\nend") );

        $this->assertSame(
                "A long string that will need to be wrapped because it exceeds the line\r\n"
                ."length limit.",
                $transport->prepareText(
                        "A long string that will need to be wrapped because it exceeds the line length limit."
                    )
            );

        $this->assertSame(
                "fix\r\n..\r\nthe dots",
                $transport->prepareText("fix\r\n.\r\nthe dots")
            );

        $this->assertSame(
                "fix\r\n..\r\nthe dots",
                $transport->prepareText("fix\r\n.\r\nthe dots")
            );
    }

    public function testPrepareHTMl ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testGetBody ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>
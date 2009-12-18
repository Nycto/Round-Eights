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
class classes_Mail_Formatter extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test piece of mail
     *
     * @return \r8\Mail
     */
    public function getTestMail ()
    {
        return new \r8\Mail( $this->getMock('\r8\Mail\Transport', array('internalSend')) );
    }

    public function testFormatAddress ()
    {
        $this->assertSame(
                "<test@example.com>",
                \r8\Mail\Formatter::formatAddress("test@example.com")
            );

        $this->assertSame(
                '"Lug MightyChunk" <test@example.com>',
                \r8\Mail\Formatter::formatAddress("test@example.com", "Lug MightyChunk")
            );

        $this->assertSame(
                '<test@example.com>',
                \r8\Mail\Formatter::formatAddress("test@example.com", chr(5))
            );

        $this->assertSame(
                '"Lug \"MightyChunk\"" <test@example.com>',
                \r8\Mail\Formatter::formatAddress("test@example.com", 'Lug "MightyChunk"')
            );
    }

    public function testGetToString ()
    {
        $mail = $this->getTestMail();

        $format = new \r8\Mail\Formatter;

        $this->assertSame( "", $format->getToString($mail) );

        $mail->addTo("test@example.com");
        $this->assertSame(
                "<test@example.com>",
                $format->getToString($mail)
            );

        $mail->addTo("other@example.net", "Jack Snap");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>",
                $format->getToString($mail)
            );

        $mail->addTo("another@example.org", "Crackle Pop");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>, "
                ."\"Crackle Pop\" <another@example.org>",
                $format->getToString($mail)
            );
    }

    public function testGetCCString ()
    {
        $mail = $this->getTestMail();

        $format = new \r8\Mail\Formatter;


        $this->assertSame( "", $format->getCCString($mail) );

        $mail->addCC("test@example.com");
        $this->assertSame(
                "<test@example.com>",
                $format->getCCString($mail)
            );

        $mail->addCC("other@example.net", "Jack Snap");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>",
                $format->getCCString($mail)
            );

        $mail->addCC("another@example.org", "Crackle Pop");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>, "
                ."\"Crackle Pop\" <another@example.org>",
                $format->getCCString($mail)
            );
    }

    public function testGetBCCString ()
    {
        $mail = $this->getTestMail();

        $format = new \r8\Mail\Formatter;


        $this->assertSame( "", $format->getBCCString($mail) );

        $mail->addBCC("test@example.com");
        $this->assertSame(
                "<test@example.com>",
                $format->getBCCString($mail)
            );

        $mail->addBCC("other@example.net", "Jack Snap");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>",
                $format->getBCCString($mail)
            );

        $mail->addBCC("another@example.org", "Crackle Pop");
        $this->assertSame(
                "<test@example.com>, \"Jack Snap\" <other@example.net>, "
                ."\"Crackle Pop\" <another@example.org>",
                $format->getBCCString($mail)
            );
    }

    public function testGetHeaderList_sparse ()
    {
        $mail = $this->getTestMail();

        $format = new \r8\Mail\Formatter;

        $headers = $format->getHeaderList( $mail );
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
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();
        $mail->setFrom("from@example.com", "Jack Test")
            ->addTo("other@example.net", "Jack Snap")
            ->addTo("another@example.org", "Crackle Pop")
            ->addCC("cc@example.edu", "Veal SteakFace")
            ->addCC("devnull@example.org")
            ->addBCC("bcc@example.com")
            ->setSubject("This is a test")
            ->setMessageID("abc123");


        $headers = $format->getHeaderList( $mail );
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
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();
        $mail->setText("This is some content");


        $headers = $format->getHeaderList( $mail );
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
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();
        $mail->setHTML("This is some content");

        $headers = $format->getHeaderList( $mail );
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
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();
        $mail->setHTML("This is some content");
        $mail->setText("This is some content");


        $headers = $format->getHeaderList( $mail );
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
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();

        $headers = $format->getHeaderString( $mail );
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
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();
        $mail->setFrom("from@example.com", "Jack Test")
            ->addTo("other@example.net", "Jack Snap")
            ->addTo("another@example.org", "Crackle Pop")
            ->addCC("cc@example.edu", "Veal SteakFace")
            ->addCC("devnull@example.org")
            ->addBCC("bcc@example.com")
            ->setSubject("This is a test")
            ->setMessageID("abc123");

        $headers = $format->getHeaderString( $mail );
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

    public function testPrepareContent ()
    {
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();

        $this->assertSame(
                "This string should not be changed",
                $format->prepareContent("This string should not be changed")
            );

        $this->assertSame( "start\r\nend", $format->prepareContent("start\nend") );
        $this->assertSame( "start\r\nend", $format->prepareContent("start\rend") );
        $this->assertSame( "start\r\nend", $format->prepareContent("start\r\nend") );

        $this->assertSame(
                "A long string that will need to be wrapped because it exceeds the line\r\n"
                ."length limit.",
                $format->prepareContent(
                        "A long string that will need to be wrapped because it exceeds the line length limit."
                    )
            );

        $this->assertSame(
                "fix\r\n..\r\nthe dots",
                $format->prepareContent("fix\r\n.\r\nthe dots")
            );

        $this->assertSame(
                "fix\r\n..\r\nthe dots",
                $format->prepareContent("fix\r\n.\r\nthe dots")
            );
    }

    public function testGetBody_text ()
    {
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();

        $this->assertSame(
                "",
                $format->getBody( $mail )
            );

        $mail->setText("This is a chunk of text");

        $this->assertSame(
                "This is a chunk of text",
                $format->getBody( $mail )
            );
    }

    public function testGetBody_html ()
    {
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();

        $mail->setHTML("<h1>This is a chunk of text</h1>");

        $this->assertSame(
                "<h1>This is a chunk of text</h1>",
                $format->getBody( $mail )
            );
    }

    public function testGetBody_multi ()
    {
        $format = new \r8\Mail\Formatter;

        $mail = $this->getTestMail();

        $mail->setHTML("<h1>This is a chunk of text</h1>");
        $mail->setText("This is a chunk of text");

        $boundary = $mail->getBoundary();

        $this->assertSame(
                "--". $boundary ."\r\n"
                ."Content-Type: text/plain; charset=\"ISO-8859-1\"\r\n"
                ."Content-Transfer-Encoding: 7bit\r\n"
                ."\r\n"
                ."This is a chunk of text\r\n"
                ."\r\n"
                ."--". $boundary ."\r\n"
                ."Content-Type: text/html; charset=\"ISO-8859-1\"\r\n"
                ."Content-Transfer-Encoding: 7bit\r\n"
                ."\r\n"
                ."<h1>This is a chunk of text</h1>\r\n"
                ."\r\n"
                ."--". $boundary ."--\r\n",
                $format->getBody( $mail )
            );
    }

}

?>
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
                \r8\Mail\Transport::formatAddress("test@example.com")
            );

        $this->assertSame(
                '"Lug MightyChunk" <test@example.com>',
                \r8\Mail\Transport::formatAddress("test@example.com", "Lug MightyChunk")
            );

        $this->assertSame(
                '<test@example.com>',
                \r8\Mail\Transport::formatAddress("test@example.com", chr(5))
            );

        $this->assertSame(
                '"Lug \"MightyChunk\"" <test@example.com>',
                \r8\Mail\Transport::formatAddress("test@example.com", 'Lug "MightyChunk"')
            );
    }

    public function testGetToString ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );

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
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));
        $mail = new \r8\Mail( $transport );


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
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );

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
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );
        $mail->setFrom("from@example.com", "Jack Test")
            ->addTo("other@example.net", "Jack Snap")
            ->addTo("another@example.org", "Crackle Pop")
            ->addCC("cc@example.edu", "Veal SteakFace")
            ->addCC("devnull@example.org")
            ->addBCC("bcc@example.com")
            ->setSubject("This is a test")
            ->setMessageID("abc123");


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
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );
        $mail->setText("This is some content");


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
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );
        $mail->setHTML("This is some content");

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
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );
        $mail->setHTML("This is some content");
        $mail->setText("This is some content");


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
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));
        $mail = new \r8\Mail( $transport );

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
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );
        $mail->setFrom("from@example.com", "Jack Test")
            ->addTo("other@example.net", "Jack Snap")
            ->addTo("another@example.org", "Crackle Pop")
            ->addCC("cc@example.edu", "Veal SteakFace")
            ->addCC("devnull@example.org")
            ->addBCC("bcc@example.com")
            ->setSubject("This is a test")
            ->setMessageID("abc123");

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

    public function testPrepareContent ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $this->assertSame(
                "This string should not be changed",
                $transport->prepareContent("This string should not be changed")
            );

        $this->assertSame( "start\r\nend", $transport->prepareContent("start\nend") );
        $this->assertSame( "start\r\nend", $transport->prepareContent("start\rend") );
        $this->assertSame( "start\r\nend", $transport->prepareContent("start\r\nend") );

        $this->assertSame(
                "A long string that will need to be wrapped because it exceeds the line\r\n"
                ."length limit.",
                $transport->prepareContent(
                        "A long string that will need to be wrapped because it exceeds the line length limit."
                    )
            );

        $this->assertSame(
                "fix\r\n..\r\nthe dots",
                $transport->prepareContent("fix\r\n.\r\nthe dots")
            );

        $this->assertSame(
                "fix\r\n..\r\nthe dots",
                $transport->prepareContent("fix\r\n.\r\nthe dots")
            );
    }

    public function testGetBody_text ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));
        $mail = new \r8\Mail( $transport );

        $this->assertSame(
                "",
                $transport->getBody( $mail )
            );

        $mail->setText("This is a chunk of text");

        $this->assertSame(
                "This is a chunk of text",
                $transport->getBody( $mail )
            );
    }

    public function testGetBody_html ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));
        $mail = new \r8\Mail( $transport );

        $mail->setHTML("<h1>This is a chunk of text</h1>");

        $this->assertSame(
                "<h1>This is a chunk of text</h1>",
                $transport->getBody( $mail )
            );
    }

    public function testGetBody_multi ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));
        $mail = new \r8\Mail( $transport );

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
                $transport->getBody( $mail )
            );
    }

    public function testSend_incomplete ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));
        $mail = new \r8\Mail( $transport );

        try {
            $transport->send( $mail );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Variable $err ) {
            $this->assertSame( '"From" Address must be set to send an email', $err->getMessage() );
        }

        $mail->setFrom('test@example.com');

        try {
            $transport->send( $mail );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Variable $err ) {
            $this->assertSame( '"To" Address must be set to send an email', $err->getMessage() );
        }
    }

    public function testSend_valid ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );
        $mail->setFrom('test@example.com');
        $mail->addTo('destination@example.com');

        $transport->expects( $this->once() )
            ->method( 'internalSend' )
            ->with( $this->equalTo($mail) );

        $this->assertSame( $transport, $transport->send($mail) );
    }

}

?>
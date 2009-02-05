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

    public function testGetHeaderList ()
    {
        $this->markTestIncomplete("To be written");
        $mail = new \cPHP\Mail;

        $transport = $this->getMock('cPHP\Mail\Transport');

        $this->assertSame(
                array(),
                $transport->getHeaderList( $mail )
            );
    }

}

?>
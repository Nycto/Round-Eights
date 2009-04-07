<?php
/**
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
class classes_queryparser extends PHPUnit_Framework_TestCase
{

    public function testOuterDelimAccessors ()
    {
        $parser = new \cPHP\QueryParser;

        $this->assertSame( "&", $parser->getOuterDelim() );

        $this->assertSame( $parser, $parser->setOuterDelim(";=;") );
        $this->assertSame( ";=;", $parser->getOuterDelim() );

        $this->assertSame( $parser, $parser->setOuterDelim(" ") );
        $this->assertSame( " ", $parser->getOuterDelim() );

        try {
            $parser->setOuterDelim("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        $this->assertSame( " ", $parser->getOuterDelim() );
    }

    public function testInnerDelimAccessors ()
    {
        $parser = new \cPHP\QueryParser;

        $this->assertSame( "=", $parser->getInnerDelim() );

        $this->assertSame( $parser, $parser->setInnerDelim(";=;") );
        $this->assertSame( ";=;", $parser->getInnerDelim() );

        $this->assertSame( $parser, $parser->setInnerDelim(" ") );
        $this->assertSame( " ", $parser->getInnerDelim() );

        try {
            $parser->setInnerDelim("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        $this->assertSame( " ", $parser->getInnerDelim() );
    }

    public function testStartDelimAccessors ()
    {
        $parser = new \cPHP\QueryParser;

        $this->assertSame( "?", $parser->getStartDelim() );
        $this->assertTrue( $parser->startDelimExists() );

        $this->assertSame( $parser, $parser->setStartDelim(";=;") );
        $this->assertSame( ";=;", $parser->getStartDelim() );
        $this->assertTrue( $parser->startDelimExists() );

        $this->assertSame( $parser, $parser->setStartDelim("") );
        $this->assertNull( $parser->getStartDelim() );
        $this->assertFalse( $parser->startDelimExists() );

        $this->assertSame( $parser, $parser->setStartDelim(" ") );
        $this->assertSame( " ", $parser->getStartDelim() );
        $this->assertTrue( $parser->startDelimExists() );

        $this->assertSame( $parser, $parser->clearStartDelim() );
        $this->assertNull( $parser->getStartDelim() );
        $this->assertFalse( $parser->startDelimExists() );
    }

    public function testEndDelimAccessors ()
    {
        $parser = new \cPHP\QueryParser;

        $this->assertSame( "#", $parser->getEndDelim() );
        $this->assertTrue( $parser->endDelimExists() );

        $this->assertSame( $parser, $parser->setEndDelim(";=;") );
        $this->assertSame( ";=;", $parser->getEndDelim() );
        $this->assertTrue( $parser->endDelimExists() );

        $this->assertSame( $parser, $parser->setEndDelim("") );
        $this->assertNull( $parser->getEndDelim() );
        $this->assertFalse( $parser->endDelimExists() );

        $this->assertSame( $parser, $parser->setEndDelim(" ") );
        $this->assertSame( " ", $parser->getEndDelim() );
        $this->assertTrue( $parser->endDelimExists() );

        $this->assertSame( $parser, $parser->clearEndDelim() );
        $this->assertNull( $parser->getEndDelim() );
        $this->assertFalse( $parser->endDelimExists() );
    }

}

?>
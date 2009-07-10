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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class functions_strings extends PHPUnit_Framework_TestCase
{

    public function testInt2Ordinal ()
    {
        $this->assertEquals( "1st", \h2o\str\int2Ordinal(1) );
        $this->assertEquals( "2nd", \h2o\str\int2Ordinal(2) );
        $this->assertEquals( "3rd", \h2o\str\int2Ordinal(3) );
        $this->assertEquals( "4th", \h2o\str\int2Ordinal(4) );
        $this->assertEquals( "5th", \h2o\str\int2Ordinal(5) );
        $this->assertEquals( "6th", \h2o\str\int2Ordinal(6) );
        $this->assertEquals( "7th", \h2o\str\int2Ordinal(7) );
        $this->assertEquals( "8th", \h2o\str\int2Ordinal(8) );
        $this->assertEquals( "9th", \h2o\str\int2Ordinal(9) );
        $this->assertEquals( "10th", \h2o\str\int2Ordinal(10) );

        $this->assertEquals( "11th", \h2o\str\int2Ordinal(11) );
        $this->assertEquals( "12th", \h2o\str\int2Ordinal(12) );
        $this->assertEquals( "13th", \h2o\str\int2Ordinal(13) );
        $this->assertEquals( "14th", \h2o\str\int2Ordinal(14) );
        $this->assertEquals( "15th", \h2o\str\int2Ordinal(15) );
        $this->assertEquals( "16th", \h2o\str\int2Ordinal(16) );
        $this->assertEquals( "17th", \h2o\str\int2Ordinal(17) );
        $this->assertEquals( "18th", \h2o\str\int2Ordinal(18) );
        $this->assertEquals( "19th", \h2o\str\int2Ordinal(19) );
        $this->assertEquals( "20th", \h2o\str\int2Ordinal(20) );

        $this->assertEquals( "21st", \h2o\str\int2Ordinal(21) );
        $this->assertEquals( "22nd", \h2o\str\int2Ordinal(22) );
        $this->assertEquals( "23rd", \h2o\str\int2Ordinal(23) );
        $this->assertEquals( "24th", \h2o\str\int2Ordinal(24) );
        $this->assertEquals( "25th", \h2o\str\int2Ordinal(25) );
        $this->assertEquals( "30th", \h2o\str\int2Ordinal(30) );

        $this->assertEquals( "-1st", \h2o\str\int2Ordinal(-1) );
        $this->assertEquals( "-2nd", \h2o\str\int2Ordinal(-2) );
        $this->assertEquals( "-3rd", \h2o\str\int2Ordinal(-3) );
        $this->assertEquals( "-4th", \h2o\str\int2Ordinal(-4) );
        $this->assertEquals( "-5th", \h2o\str\int2Ordinal(-5) );
        $this->assertEquals( "-9th", \h2o\str\int2Ordinal(-9) );
        $this->assertEquals( "-10th", \h2o\str\int2Ordinal(-10) );
    }

    public function testContains ()
    {
        $this->assertTrue( \h2o\str\contains(' In ', 'Check In this string') );
        $this->assertFalse( \h2o\str\contains('not in', 'Check In this string') );

        $this->assertTrue( \h2o\str\contains(' In ', 'Check In this string', TRUE) );
        $this->assertFalse( \h2o\str\contains('not in', 'Check In this string', TRUE) );

        $this->assertTrue( \h2o\str\contains(' in ', 'Check In this string', TRUE) );
        $this->assertFalse( \h2o\str\contains('not in', 'Check In this string', TRUE) );

        $this->assertTrue( \h2o\str\contains(' In ', 'Check In this string', FALSE) );
        $this->assertFalse( \h2o\str\contains('not in', 'Check In this string', FALSE) );

        $this->assertFalse( \h2o\str\contains(' in ', 'Check In this string', FALSE) );
        $this->assertFalse( \h2o\str\contains('not in', 'Check In this string', FALSE) );
    }

    public function testOffsets ()
    {

        $this->assertSame(
                array(0, 8, 52, 60, 70),
                \h2o\str\offsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string' )
            );

        $this->assertEquals(
                array(0, 8, 52, 60, 70),
                \h2o\str\offsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', TRUE )
            );

        $this->assertEquals(
                array(8, 52, 70),
                \h2o\str\offsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )
            );

        $this->assertEquals(
                array(0, 60),
                \h2o\str\offsets( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )
            );

        $this->assertEquals(
                array(),
                \h2o\str\offsets( 'Not in Haystack', 'Stringy string with multiple occurances of the word string, Stringity string' )
            );

        $this->assertEquals(
                array(),
                \h2o\str\offsets( 'Multiple', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )
            );

    }

    public function testOffsetsException ()
    {
        $this->setExpectedException('\h2o\Exception\Argument');
        \h2o\str\offsets( '', 'Stringy string with multiple occurances of the word string, Stringity string' );
    }

    public function testNPos ()
    {

        $this->assertEquals(
                0,
                \h2o\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 0 )
            );

        $this->assertEquals(
                52,
                \h2o\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 2 )
            );

        $this->assertEquals(
                70,
                \h2o\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -1 )
            );

        $this->assertEquals(
                52,
                \h2o\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -3 )
            );

        $this->assertEquals(
                8,
                \h2o\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 0, FALSE )
            );

        $this->assertEquals(
                52,
                \h2o\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 1, FALSE )
            );

        $this->assertEquals(
                70,
                \h2o\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -1, FALSE )
            );

        $this->assertEquals(
                0,
                \h2o\str\npos( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', 0, FALSE )
            );

        $this->assertEquals(
                60,
                \h2o\str\npos( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', -1, FALSE )
            );

    }

    public function testUnshout ()
    {

        $this->assertEquals(
            'This is A String with Some odd Capitals',
            \h2o\str\unshout( "This is A STRING wiTH SoMe odd CAPITALs" )
        );

    }

    public function testStripW ()
    {
        $this->assertEquals(
                "123abc",
                \h2o\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc")
            );
        $this->assertEquals(
                "  1 23 a bc",
                \h2o\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \h2o\str\ALLOW_SPACES)
            );
        $this->assertEquals(
                "  1_ 23 a bc",
                \h2o\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \h2o\str\ALLOW_SPACES | \h2o\str\ALLOW_UNDERSCORES)
            );
        $this->assertEquals(
                "12\n3ab\rc",
                \h2o\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \h2o\str\ALLOW_NEWLINES)
            );
        $this->assertEquals(
                "12\n3ab\rc",
                \h2o\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \h2o\str\ALLOW_NEWLINES)
            );
        $this->assertEquals(
                "123\tabc",
                \h2o\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \h2o\str\ALLOW_TABS)
            );
        $this->assertEquals(
                "123-abc",
                \h2o\str\stripW("  !@#^1^%_ 2\n3\t <->?a )))b\rc", \h2o\str\ALLOW_DASHES)
            );

        $chars = implode("", array_map( 'chr', range(0, 255) ));
        $this->assertSame(
                " !\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ["
                ."\\]^_`abcdefghijklmnopqrstuvwxyz{|}~",
                \h2o\str\stripW($chars, \h2o\str\ALLOW_ASCII)
            );
    }

    public function testStripRepeats ()
    {
        $this->assertEquals(
                "start T tytyty yyyy end",
                \h2o\str\stripRepeats('start TTT ttytyty yyyy end', 't')
            );

        $this->assertEquals(
                "start TTT tty yyyy end",
                \h2o\str\stripRepeats('start TTT ttytyty yyyy end', 'ty')
            );

        $this->assertEquals(
                "start TTT tytyty yyyy end",
                \h2o\str\stripRepeats('start TTT ttytyty yyyy end', 't', FALSE)
            );

        $this->assertEquals(
                "start T ttytyty yyyy end",
                \h2o\str\stripRepeats('start TTT ttytyty yyyy end', 'T', FALSE)
            );

        $this->assertEquals(
                "start T tyty yyyy end",
                \h2o\str\stripRepeats('start TTT ttytyty yyyy end', array( array('t'), 'ty'))
            );
    }

    public function testStripRepeats_Exception ()
    {
        $this->setExpectedException('\h2o\Exception\Argument');
        \h2o\str\stripRepeats( 'Stringy string with multiple occurances of the word string, Stringity string', '' );
    }

    public function testTruncateWords ()
    {
        $this->assertEquals(
                "string with so...ds that ne...ed down",
                \h2o\str\truncateWords( "string with someverylongwords that needtobetrimmed down", 10)
            );

        $this->assertEquals(
                "string with so...s that ne...d down",
                \h2o\str\truncateWords( "string with someverylongwords that needtobetrimmed down", 10, 6)
            );

        $this->assertEquals(
                "string with so..ds that ne..ed down",
                \h2o\str\truncateWords( "string with someverylongwords that needtobetrimmed down", 10, 6, '..')
            );

        $this->assertEquals(
                "string with someverylongwords that needtobetrimmed down",
                \h2o\str\truncateWords( "string with someverylongwords that needtobetrimmed down", 20 )
            );
    }

    public function testStripQuoted ()
    {
        $this->assertEquals(
                "This  with quotes",
                \h2o\str\stripQuoted( "This 'is a string' with\"\" quotes" )
            );

        $this->assertEquals(
                "This  withot",
                \h2o\str\stripQuoted( "This /is a string& with/ qu&ot&es", array('/', '&') )
            );
    }

    public function testSubstr_icount ()
    {

        $this->assertEquals(
                2,
                \h2o\str\substr_icount( 'This Is A Test', 'is' )
            );

        $this->assertEquals(
                2,
                \h2o\str\substr_icount( 'This Is A Test', 'Is' )
            );

        $this->assertEquals(
                2,
                \h2o\str\substr_icount( 'This Is A Test', 'IS' )
            );

        $this->assertEquals(
                1,
                \h2o\str\substr_icount( 'This Is A Test', 'is', 3 )
            );

        $this->assertEquals(
                0,
                \h2o\str\substr_icount( 'This Is A Test', 'is', 3, 3 )
            );
    }

    public function testStartsWith ()
    {
        $this->assertTrue( \h2o\str\startsWith('string with content', 'string') );
        $this->assertTrue( \h2o\str\startsWith('string with content', 'String') );

        $this->assertTrue( \h2o\str\startsWith('string with content', 'string', TRUE) );
        $this->assertTrue( \h2o\str\startsWith('string with content', 'String', TRUE) );

        $this->assertTrue( \h2o\str\startsWith('string with content', 'string', FALSE) );

        $this->assertFalse( \h2o\str\startsWith('string with content', 'String', FALSE) );
        $this->assertFalse( \h2o\str\startsWith('string with content', 'strn', FALSE) );
    }

    public function testEndsWith ()
    {
        $this->assertTrue( \h2o\str\endsWith('string with content', 'content') );
        $this->assertTrue( \h2o\str\endsWith('string with content', 'Content') );

        $this->assertTrue( \h2o\str\endsWith('string with content', 'content', TRUE) );
        $this->assertTrue( \h2o\str\endsWith('string with content', 'Content', TRUE) );

        $this->assertTrue( \h2o\str\endsWith('string with content', 'content', FALSE) );

        $this->assertFalse( \h2o\str\endsWith('string with content', 'Content', FALSE) );
        $this->assertFalse( \h2o\str\endsWith('string with content', 'contnt', FALSE) );
    }

    public function testTail ()
    {
        $this->assertEquals(
                'stringtail',
                \h2o\str\tail('string', 'tail')
            );

        $this->assertEquals(
                'stringtail',
                \h2o\str\tail('stringtail', 'tail')
            );

        $this->assertEquals(
                'stringtail',
                \h2o\str\tail('string', 'tail', TRUE)
            );

        $this->assertEquals(
                'stringtail',
                \h2o\str\tail('stringtail', 'tail', TRUE)
            );

        $this->assertEquals(
                'stringtail',
                \h2o\str\tail('string', 'tail', FALSE)
            );

        $this->assertEquals(
                'stringtail',
                \h2o\str\tail('stringtail', 'tail', FALSE)
            );

        $this->assertEquals(
                'stringtailTail',
                \h2o\str\tail('stringtail', 'Tail', FALSE)
            );

        $this->assertEquals(
                'stringTail',
                \h2o\str\tail('stringTail', 'Tail', FALSE)
            );
    }

    public function testStripTail ()
    {
        $this->assertEquals(
                'string with cont',
                \h2o\str\stripTail( "string with content", "ent" )
            );

        $this->assertEquals(
                'string with cont',
                \h2o\str\stripTail( "string with cont", "ent" )
            );

        $this->assertEquals(
                'string with cont',
                \h2o\str\stripTail( "string with content", "ent", TRUE )
            );

        $this->assertEquals(
                'string with cont',
                \h2o\str\stripTail( "string with content", "ENT", TRUE )
            );

        $this->assertEquals(
                'string with cont',
                \h2o\str\stripTail( "string with cont", "ent", TRUE)
            );

        $this->assertEquals(
                'string with cont',
                \h2o\str\stripTail( "string with content", "ent", FALSE )
            );

        $this->assertEquals(
                'string with content',
                \h2o\str\stripTail( "string with content", "Ent", FALSE )
            );

        $this->assertEquals(
                'string with cont',
                \h2o\str\stripTail( "string with cont", "ent", FALSE)
            );

        $this->assertEquals(
                '',
                \h2o\str\stripTail( "string", "string", TRUE )
            );

        $this->assertEquals(
                'string',
                \h2o\str\stripTail( "string", "" )
            );
    }

    public function testHead ()
    {
        $this->assertEquals(
                'headstring',
                \h2o\str\head('string', 'head')
            );

        $this->assertEquals(
                'headstring',
                \h2o\str\head('headstring', 'head')
            );

        $this->assertEquals(
                'headstring',
                \h2o\str\head('string', 'head', TRUE)
            );

        $this->assertEquals(
                'headstring',
                \h2o\str\head('headstring', 'head', TRUE)
            );

        $this->assertEquals(
                'headstring',
                \h2o\str\head('string', 'head', FALSE)
            );

        $this->assertEquals(
                'headstring',
                \h2o\str\head('headstring', 'head', FALSE)
            );

        $this->assertEquals(
                'Headheadstring',
                \h2o\str\head('headstring', 'Head', FALSE)
            );

        $this->assertEquals(
                'Headstring',
                \h2o\str\head('Headstring', 'Head', FALSE)
            );
    }

    public function testStripHead ()
    {
        $this->assertEquals(
                'ing with content',
                \h2o\str\stripHead( "ing with content", "str" )
            );

        $this->assertEquals(
                'ing with content',
                \h2o\str\stripHead( "ing with content", "str" )
            );

        $this->assertEquals(
                'ing with content',
                \h2o\str\stripHead( "ing with content", "str", TRUE )
            );

        $this->assertEquals(
                'ing with content',
                \h2o\str\stripHead( "string with content", "STR", TRUE )
            );

        $this->assertEquals(
                'ing with content',
                \h2o\str\stripHead( "ing with content", "str", TRUE)
            );

        $this->assertEquals(
                'ing with content',
                \h2o\str\stripHead( "string with content", "str", FALSE )
            );

        $this->assertEquals(
                'string with content',
                \h2o\str\stripHead( "string with content", "Str", FALSE )
            );

        $this->assertEquals(
                'ing with content',
                \h2o\str\stripHead( "ing with content", "str", FALSE)
            );

        $this->assertEquals(
                '',
                \h2o\str\stripHead( "string", "string", TRUE )
            );

        $this->assertEquals(
                'string',
                \h2o\str\stripHead( "string", "" )
            );
    }

    public function testWeld ()
    {
        $this->assertEquals(
                '/dir/file',
                \h2o\str\weld('/dir', 'file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                \h2o\str\weld('/dir/', 'file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                \h2o\str\weld('/dir', '/file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                \h2o\str\weld('/dir/', '/file', '/')
            );

        $this->assertEquals(
                'onEleven',
                \h2o\str\weld('one', 'eleven', 'E')
            );

        $this->assertEquals(
                'onEleven',
                \h2o\str\weld('one', 'eleven', 'E', TRUE)
            );

        $this->assertEquals(
                'oneEeleven',
                \h2o\str\weld('one', 'eleven', 'E', FALSE)
            );
    }

    public function testPartition ()
    {
        $this->assertSame( array(), \h2o\str\partition("", 5, 10, 12) );

        $this->assertSame(
                array( "This ", "is a ", "st", "ring to split" ),
                \h2o\str\partition("This is a string to split", 5, 10, 12)
            );


        $this->assertSame(
                array( "This ", "is a ", "st", "ring to split" ),
                \h2o\str\partition("This is a string to split", array( 5, 10 ), 12)
            );

        $this->assertSame(
                array( "This ", "is a ", "st", "ring to split" ),
                \h2o\str\partition("This is a string to split", -10, 5, 10, 12)
            );

        $this->assertSame(
                array( "This ", "is a ", "st", "ring to split" ),
                \h2o\str\partition("This is a string to split", 0, 5, 10, 12)
            );

        $this->assertSame(
                array( "This ", "is a ", "st", "ring to split" ),
                \h2o\str\partition("This is a string to split", 12, 10, 5)
            );

        $this->assertSame(
                array( "This ", "is a ", "st", "ring to split" ),
                \h2o\str\partition("This is a string to split", 5, 10, 12, 10)
            );

        $this->assertSame(
                array( "This ", "is a ", "st", "ring to split" ),
                \h2o\str\partition( "This is a string to split", array( 5, -10 ), 12, 10, 12, 10, 50 )
            );

        $this->assertSame(
                array( "T", "his is a string to spli", "t" ),
                \h2o\str\partition( "This is a string to split", 0, 1, 24, 25 )
            );
    }

    public function testCompare ()
    {
        $this->assertEquals(
                0,
                \h2o\str\compare('Test', 'test', TRUE)
            );

        $this->assertEquals(
                -6,
                \h2o\str\compare('Not The Same', 'test', TRUE)
            );

        $this->assertEquals(
                16,
                \h2o\str\compare('test', 'Different Than', TRUE)
            );

        $this->assertEquals(
                0,
                \h2o\str\compare('The Same', 'The Same', FALSE)
            );

        $this->assertEquals(
                -1,
                \h2o\str\compare('Test', 'test', FALSE)
            );

        $this->assertEquals(
                1,
                \h2o\str\compare('Casesensitive', 'CaseSensitivE', FALSE)
            );
    }

    public function testEnclose ()
    {

        $this->assertEquals(
                "wrap data wrap",
                \h2o\str\enclose( " data ", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                \h2o\str\enclose( "wrap data ", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                \h2o\str\enclose( " data wrap", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                \h2o\str\enclose( "wrap data wrap", "wrap" )
            );



        $this->assertEquals(
                "Wrap data Wrap",
                \h2o\str\enclose( " data ", "Wrap", TRUE )
            );

        $this->assertEquals(
                "wrap data Wrap",
                \h2o\str\enclose( "wrap data ", "Wrap", TRUE )
            );

        $this->assertEquals(
                "Wrap data wrap",
                \h2o\str\enclose( " data wrap", "Wrap", TRUE )
            );

        $this->assertEquals(
                "wrap data wrap",
                \h2o\str\enclose( "wrap data wrap", "Wrap", TRUE )
            );



        $this->assertEquals(
                "Wrap data Wrap",
                \h2o\str\enclose( " data ", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrapwrap data Wrap",
                \h2o\str\enclose( "wrap data ", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrap data wrapWrap",
                \h2o\str\enclose( " data wrap", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrapwrap data wrapWrap",
                \h2o\str\enclose( "wrap data wrap", "Wrap", FALSE )
            );
    }

    public function testTruncate ()
    {
        $this->assertEquals (
                "Not long enough",
                \h2o\str\truncate ( "Not long enough", 30 )
            );

        $this->assertEquals (
                "too long ...own good",
                \h2o\str\truncate ( "too long for it's own good", 20 )
            );

        $this->assertEquals (
                "too long -- own good",
                \h2o\str\truncate ( "too long for it's own good", 20, "--" )
            );
    }

    public function testPluralize ()
    {
        $this->assertEquals( "tests", \h2o\str\pluralize("test") );
        $this->assertEquals( "   tests   ", \h2o\str\pluralize("   test   ") );

        $this->assertEquals( "tries", \h2o\str\pluralize("try") );
        $this->assertEquals( "   tries   ", \h2o\str\pluralize("   try   ") );

        $this->assertEquals( "TESTS", \h2o\str\pluralize("TEST") );
        $this->assertEquals( "TRIES", \h2o\str\pluralize("TRY") );

        $this->assertEquals( "test", \h2o\str\pluralize("test", 1) );
        $this->assertEquals( "try", \h2o\str\pluralize("try", 1) );

        $this->assertEquals( "tests", \h2o\str\pluralize("test", 5) );
        $this->assertEquals( "tries", \h2o\str\pluralize("try", 5) );
    }

    public function testPluralizeException ()
    {
        $this->setExpectedException('\h2o\Exception\Argument');
        \h2o\str\pluralize( '' );
    }

}

?>
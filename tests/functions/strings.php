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
class functions_strings extends PHPUnit_Framework_TestCase
{

    public function testInt2Ordinal ()
    {
        $this->assertEquals( "1st", \cPHP\str\int2Ordinal(1) );
        $this->assertEquals( "2nd", \cPHP\str\int2Ordinal(2) );
        $this->assertEquals( "3rd", \cPHP\str\int2Ordinal(3) );
        $this->assertEquals( "4th", \cPHP\str\int2Ordinal(4) );
        $this->assertEquals( "5th", \cPHP\str\int2Ordinal(5) );
        $this->assertEquals( "6th", \cPHP\str\int2Ordinal(6) );
        $this->assertEquals( "7th", \cPHP\str\int2Ordinal(7) );
        $this->assertEquals( "8th", \cPHP\str\int2Ordinal(8) );
        $this->assertEquals( "9th", \cPHP\str\int2Ordinal(9) );
        $this->assertEquals( "10th", \cPHP\str\int2Ordinal(10) );

        $this->assertEquals( "11th", \cPHP\str\int2Ordinal(11) );
        $this->assertEquals( "12th", \cPHP\str\int2Ordinal(12) );
        $this->assertEquals( "13th", \cPHP\str\int2Ordinal(13) );
        $this->assertEquals( "14th", \cPHP\str\int2Ordinal(14) );
        $this->assertEquals( "15th", \cPHP\str\int2Ordinal(15) );
        $this->assertEquals( "16th", \cPHP\str\int2Ordinal(16) );
        $this->assertEquals( "17th", \cPHP\str\int2Ordinal(17) );
        $this->assertEquals( "18th", \cPHP\str\int2Ordinal(18) );
        $this->assertEquals( "19th", \cPHP\str\int2Ordinal(19) );
        $this->assertEquals( "20th", \cPHP\str\int2Ordinal(20) );

        $this->assertEquals( "21st", \cPHP\str\int2Ordinal(21) );
        $this->assertEquals( "22nd", \cPHP\str\int2Ordinal(22) );
        $this->assertEquals( "23rd", \cPHP\str\int2Ordinal(23) );
        $this->assertEquals( "24th", \cPHP\str\int2Ordinal(24) );
        $this->assertEquals( "25th", \cPHP\str\int2Ordinal(25) );
        $this->assertEquals( "30th", \cPHP\str\int2Ordinal(30) );

        $this->assertEquals( "-1st", \cPHP\str\int2Ordinal(-1) );
        $this->assertEquals( "-2nd", \cPHP\str\int2Ordinal(-2) );
        $this->assertEquals( "-3rd", \cPHP\str\int2Ordinal(-3) );
        $this->assertEquals( "-4th", \cPHP\str\int2Ordinal(-4) );
        $this->assertEquals( "-5th", \cPHP\str\int2Ordinal(-5) );
        $this->assertEquals( "-9th", \cPHP\str\int2Ordinal(-9) );
        $this->assertEquals( "-10th", \cPHP\str\int2Ordinal(-10) );
    }

    public function testContains ()
    {
        $this->assertTrue( \cPHP\str\contains(' In ', 'Check In this string') );
        $this->assertFalse( \cPHP\str\contains('not in', 'Check In this string') );

        $this->assertTrue( \cPHP\str\contains(' In ', 'Check In this string', TRUE) );
        $this->assertFalse( \cPHP\str\contains('not in', 'Check In this string', TRUE) );

        $this->assertTrue( \cPHP\str\contains(' in ', 'Check In this string', TRUE) );
        $this->assertFalse( \cPHP\str\contains('not in', 'Check In this string', TRUE) );

        $this->assertTrue( \cPHP\str\contains(' In ', 'Check In this string', FALSE) );
        $this->assertFalse( \cPHP\str\contains('not in', 'Check In this string', FALSE) );

        $this->assertFalse( \cPHP\str\contains(' in ', 'Check In this string', FALSE) );
        $this->assertFalse( \cPHP\str\contains('not in', 'Check In this string', FALSE) );
    }

    public function testOffsets ()
    {

        $this->assertSame(
                array(0, 8, 52, 60, 70),
                \cPHP\str\offsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string' )->get()
            );

        $this->assertEquals(
                array(0, 8, 52, 60, 70),
                \cPHP\str\offsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', TRUE )->get()
            );

        $this->assertEquals(
                array(8, 52, 70),
                \cPHP\str\offsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )->get()
            );

        $this->assertEquals(
                array(0, 60),
                \cPHP\str\offsets( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )->get()
            );

        $this->assertEquals(
                array(),
                \cPHP\str\offsets( 'Not in Haystack', 'Stringy string with multiple occurances of the word string, Stringity string' )->get()
            );

        $this->assertEquals(
                array(),
                \cPHP\str\offsets( 'Multiple', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )->get()
            );

    }

    public function testOffsetsException ()
    {
        $this->setExpectedException('\cPHP\Exception\Argument');
        \cPHP\str\offsets( '', 'Stringy string with multiple occurances of the word string, Stringity string' );
    }

    public function testNPos ()
    {

        $this->assertEquals(
                0,
                \cPHP\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 0 )
            );

        $this->assertEquals(
                52,
                \cPHP\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 2 )
            );

        $this->assertEquals(
                70,
                \cPHP\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -1 )
            );

        $this->assertEquals(
                52,
                \cPHP\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -3 )
            );

        $this->assertEquals(
                8,
                \cPHP\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 0, FALSE )
            );

        $this->assertEquals(
                52,
                \cPHP\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 1, FALSE )
            );

        $this->assertEquals(
                70,
                \cPHP\str\npos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -1, FALSE )
            );

        $this->assertEquals(
                0,
                \cPHP\str\npos( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', 0, FALSE )
            );

        $this->assertEquals(
                60,
                \cPHP\str\npos( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', -1, FALSE )
            );

    }

    public function testUnshout ()
    {

        $this->assertEquals(
            'This is A String with Some odd Capitals',
            \cPHP\str\unshout( "This is A STRING wiTH SoMe odd CAPITALs" )
        );

    }

    public function testStripW ()
    {
        $this->assertEquals(
                "123abc",
                \cPHP\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc")
            );
        $this->assertEquals(
                "  1 23 a bc",
                \cPHP\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \cPHP\str\ALLOW_SPACES)
            );
        $this->assertEquals(
                "  1_ 23 a bc",
                \cPHP\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \cPHP\str\ALLOW_SPACES | \cPHP\str\ALLOW_UNDERSCORES)
            );
        $this->assertEquals(
                "12\n3ab\rc",
                \cPHP\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \cPHP\str\ALLOW_NEWLINES)
            );
        $this->assertEquals(
                "12\n3ab\rc",
                \cPHP\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \cPHP\str\ALLOW_NEWLINES)
            );
        $this->assertEquals(
                "123\tabc",
                \cPHP\str\stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", \cPHP\str\ALLOW_TABS)
            );
        $this->assertEquals(
                "123-abc",
                \cPHP\str\stripW("  !@#^1^%_ 2\n3\t <->?a )))b\rc", \cPHP\str\ALLOW_DASHES)
            );
    }

    public function testStripRepeats ()
    {
        $this->assertEquals(
                "start T tytyty yyyy end",
                \cPHP\str\stripRepeats('start TTT ttytyty yyyy end', 't')
            );

        $this->assertEquals(
                "start TTT tty yyyy end",
                \cPHP\str\stripRepeats('start TTT ttytyty yyyy end', 'ty')
            );

        $this->assertEquals(
                "start TTT tytyty yyyy end",
                \cPHP\str\stripRepeats('start TTT ttytyty yyyy end', 't', FALSE)
            );

        $this->assertEquals(
                "start T ttytyty yyyy end",
                \cPHP\str\stripRepeats('start TTT ttytyty yyyy end', 'T', FALSE)
            );

        $this->assertEquals(
                "start T tyty yyyy end",
                \cPHP\str\stripRepeats('start TTT ttytyty yyyy end', array( array('t'), 'ty'))
            );
    }

    public function testStripRepeats_Exception ()
    {
        $this->setExpectedException('\cPHP\Exception\Argument');
        \cPHP\str\stripRepeats( 'Stringy string with multiple occurances of the word string, Stringity string', '' );
    }

    public function testTruncateWords ()
    {
        $this->assertEquals(
                "string with so...ds that ne...ed down",
                \cPHP\str\truncateWords( "string with someverylongwords that needtobetrimmed down", 10)
            );

        $this->assertEquals(
                "string with so...s that ne...d down",
                \cPHP\str\truncateWords( "string with someverylongwords that needtobetrimmed down", 10, 6)
            );

        $this->assertEquals(
                "string with so..ds that ne..ed down",
                \cPHP\str\truncateWords( "string with someverylongwords that needtobetrimmed down", 10, 6, '..')
            );

        $this->assertEquals(
                "string with someverylongwords that needtobetrimmed down",
                \cPHP\str\truncateWords( "string with someverylongwords that needtobetrimmed down", 20 )
            );
    }

    public function testStripQuoted ()
    {
        $this->assertEquals(
                "This  with quotes",
                \cPHP\str\stripQuoted( "This 'is a string' with\"\" quotes" )
            );

        $this->assertEquals(
                "This  withot",
                \cPHP\str\stripQuoted( "This /is a string& with/ qu&ot&es", array('/', '&') )
            );
    }

    public function testSubstr_icount ()
    {

        $this->assertEquals(
                2,
                \cPHP\str\substr_icount( 'This Is A Test', 'is' )
            );

        $this->assertEquals(
                2,
                \cPHP\str\substr_icount( 'This Is A Test', 'Is' )
            );

        $this->assertEquals(
                2,
                \cPHP\str\substr_icount( 'This Is A Test', 'IS' )
            );

        $this->assertEquals(
                1,
                \cPHP\str\substr_icount( 'This Is A Test', 'is', 3 )
            );

        $this->assertEquals(
                0,
                \cPHP\str\substr_icount( 'This Is A Test', 'is', 3, 3 )
            );
    }

    public function testStartsWith ()
    {
        $this->assertTrue( \cPHP\str\startsWith('string with content', 'string') );
        $this->assertTrue( \cPHP\str\startsWith('string with content', 'String') );

        $this->assertTrue( \cPHP\str\startsWith('string with content', 'string', TRUE) );
        $this->assertTrue( \cPHP\str\startsWith('string with content', 'String', TRUE) );

        $this->assertTrue( \cPHP\str\startsWith('string with content', 'string', FALSE) );

        $this->assertFalse( \cPHP\str\startsWith('string with content', 'String', FALSE) );
        $this->assertFalse( \cPHP\str\startsWith('string with content', 'strn', FALSE) );
    }

    public function testEndsWith ()
    {
        $this->assertTrue( \cPHP\str\endsWith('string with content', 'content') );
        $this->assertTrue( \cPHP\str\endsWith('string with content', 'Content') );

        $this->assertTrue( \cPHP\str\endsWith('string with content', 'content', TRUE) );
        $this->assertTrue( \cPHP\str\endsWith('string with content', 'Content', TRUE) );

        $this->assertTrue( \cPHP\str\endsWith('string with content', 'content', FALSE) );

        $this->assertFalse( \cPHP\str\endsWith('string with content', 'Content', FALSE) );
        $this->assertFalse( \cPHP\str\endsWith('string with content', 'contnt', FALSE) );
    }

    public function testTail ()
    {
        $this->assertEquals(
                'stringtail',
                \cPHP\str\tail('string', 'tail')
            );

        $this->assertEquals(
                'stringtail',
                \cPHP\str\tail('stringtail', 'tail')
            );

        $this->assertEquals(
                'stringtail',
                \cPHP\str\tail('string', 'tail', TRUE)
            );

        $this->assertEquals(
                'stringtail',
                \cPHP\str\tail('stringtail', 'tail', TRUE)
            );

        $this->assertEquals(
                'stringtail',
                \cPHP\str\tail('string', 'tail', FALSE)
            );

        $this->assertEquals(
                'stringtail',
                \cPHP\str\tail('stringtail', 'tail', FALSE)
            );

        $this->assertEquals(
                'stringtailTail',
                \cPHP\str\tail('stringtail', 'Tail', FALSE)
            );

        $this->assertEquals(
                'stringTail',
                \cPHP\str\tail('stringTail', 'Tail', FALSE)
            );
    }

    public function testStripTail ()
    {
        $this->assertEquals(
                'string with cont',
                \cPHP\str\stripTail( "string with content", "ent" )
            );

        $this->assertEquals(
                'string with cont',
                \cPHP\str\stripTail( "string with cont", "ent" )
            );

        $this->assertEquals(
                'string with cont',
                \cPHP\str\stripTail( "string with content", "ent", TRUE )
            );

        $this->assertEquals(
                'string with cont',
                \cPHP\str\stripTail( "string with content", "ENT", TRUE )
            );

        $this->assertEquals(
                'string with cont',
                \cPHP\str\stripTail( "string with cont", "ent", TRUE)
            );

        $this->assertEquals(
                'string with cont',
                \cPHP\str\stripTail( "string with content", "ent", FALSE )
            );

        $this->assertEquals(
                'string with content',
                \cPHP\str\stripTail( "string with content", "Ent", FALSE )
            );

        $this->assertEquals(
                'string with cont',
                \cPHP\str\stripTail( "string with cont", "ent", FALSE)
            );

        $this->assertEquals(
                '',
                \cPHP\str\stripTail( "string", "string", TRUE )
            );

        $this->assertEquals(
                'string',
                \cPHP\str\stripTail( "string", "" )
            );
    }

    public function testHead ()
    {
        $this->assertEquals(
                'headstring',
                \cPHP\str\head('string', 'head')
            );

        $this->assertEquals(
                'headstring',
                \cPHP\str\head('headstring', 'head')
            );

        $this->assertEquals(
                'headstring',
                \cPHP\str\head('string', 'head', TRUE)
            );

        $this->assertEquals(
                'headstring',
                \cPHP\str\head('headstring', 'head', TRUE)
            );

        $this->assertEquals(
                'headstring',
                \cPHP\str\head('string', 'head', FALSE)
            );

        $this->assertEquals(
                'headstring',
                \cPHP\str\head('headstring', 'head', FALSE)
            );

        $this->assertEquals(
                'Headheadstring',
                \cPHP\str\head('headstring', 'Head', FALSE)
            );

        $this->assertEquals(
                'Headstring',
                \cPHP\str\head('Headstring', 'Head', FALSE)
            );
    }

    public function testStripHead ()
    {
        $this->assertEquals(
                'ing with content',
                \cPHP\str\stripHead( "ing with content", "str" )
            );

        $this->assertEquals(
                'ing with content',
                \cPHP\str\stripHead( "ing with content", "str" )
            );

        $this->assertEquals(
                'ing with content',
                \cPHP\str\stripHead( "ing with content", "str", TRUE )
            );

        $this->assertEquals(
                'ing with content',
                \cPHP\str\stripHead( "string with content", "STR", TRUE )
            );

        $this->assertEquals(
                'ing with content',
                \cPHP\str\stripHead( "ing with content", "str", TRUE)
            );

        $this->assertEquals(
                'ing with content',
                \cPHP\str\stripHead( "string with content", "str", FALSE )
            );

        $this->assertEquals(
                'string with content',
                \cPHP\str\stripHead( "string with content", "Str", FALSE )
            );

        $this->assertEquals(
                'ing with content',
                \cPHP\str\stripHead( "ing with content", "str", FALSE)
            );

        $this->assertEquals(
                '',
                \cPHP\str\stripHead( "string", "string", TRUE )
            );

        $this->assertEquals(
                'string',
                \cPHP\str\stripHead( "string", "" )
            );
    }

    public function testWeld ()
    {
        $this->assertEquals(
                '/dir/file',
                \cPHP\str\weld('/dir', 'file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                \cPHP\str\weld('/dir/', 'file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                \cPHP\str\weld('/dir', '/file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                \cPHP\str\weld('/dir/', '/file', '/')
            );

        $this->assertEquals(
                'onEleven',
                \cPHP\str\weld('one', 'eleven', 'E')
            );

        $this->assertEquals(
                'onEleven',
                \cPHP\str\weld('one', 'eleven', 'E', TRUE)
            );

        $this->assertEquals(
                'oneEeleven',
                \cPHP\str\weld('one', 'eleven', 'E', FALSE)
            );
    }

    public function testPartition ()
    {
        $result = \cPHP\str\partition("", 5, 10, 12);
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( array(), $result->get() );


        $result = \cPHP\str\partition("This is a string to split", 5, 10, 12);
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( array( "This ", "is a ", "st", "ring to split" ), $result->get() );


        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                \cPHP\str\partition("This is a string to split", array( 5, 10 ), 12)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                \cPHP\str\partition("This is a string to split", -10, 5, 10, 12)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                \cPHP\str\partition("This is a string to split", 0, 5, 10, 12)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                \cPHP\str\partition("This is a string to split", 12, 10, 5)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                \cPHP\str\partition("This is a string to split", 5, 10, 12, 10)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                \cPHP\str\partition( "This is a string to split", array( 5, -10 ), 12, 10, 12, 10, 50 )->get()
            );

        $this->assertEquals(
                array( "T", "his is a string to spli", "t" ),
                \cPHP\str\partition( "This is a string to split", 0, 1, 24, 25 )->get()
            );
    }

    public function testCompare ()
    {
        $this->assertEquals(
                0,
                \cPHP\str\compare('Test', 'test', TRUE)
            );

        $this->assertEquals(
                -6,
                \cPHP\str\compare('Not The Same', 'test', TRUE)
            );

        $this->assertEquals(
                16,
                \cPHP\str\compare('test', 'Different Than', TRUE)
            );

        $this->assertEquals(
                0,
                \cPHP\str\compare('The Same', 'The Same', FALSE)
            );

        $this->assertEquals(
                -1,
                \cPHP\str\compare('Test', 'test', FALSE)
            );

        $this->assertEquals(
                1,
                \cPHP\str\compare('Casesensitive', 'CaseSensitivE', FALSE)
            );
    }

    public function testEnclose ()
    {

        $this->assertEquals(
                "wrap data wrap",
                \cPHP\str\enclose( " data ", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                \cPHP\str\enclose( "wrap data ", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                \cPHP\str\enclose( " data wrap", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                \cPHP\str\enclose( "wrap data wrap", "wrap" )
            );



        $this->assertEquals(
                "Wrap data Wrap",
                \cPHP\str\enclose( " data ", "Wrap", TRUE )
            );

        $this->assertEquals(
                "wrap data Wrap",
                \cPHP\str\enclose( "wrap data ", "Wrap", TRUE )
            );

        $this->assertEquals(
                "Wrap data wrap",
                \cPHP\str\enclose( " data wrap", "Wrap", TRUE )
            );

        $this->assertEquals(
                "wrap data wrap",
                \cPHP\str\enclose( "wrap data wrap", "Wrap", TRUE )
            );



        $this->assertEquals(
                "Wrap data Wrap",
                \cPHP\str\enclose( " data ", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrapwrap data Wrap",
                \cPHP\str\enclose( "wrap data ", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrap data wrapWrap",
                \cPHP\str\enclose( " data wrap", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrapwrap data wrapWrap",
                \cPHP\str\enclose( "wrap data wrap", "Wrap", FALSE )
            );
    }

    public function testTruncate ()
    {
        $this->assertEquals (
                "Not long enough",
                \cPHP\str\truncate ( "Not long enough", 30 )
            );

        $this->assertEquals (
                "too long ...own good",
                \cPHP\str\truncate ( "too long for it's own good", 20 )
            );

        $this->assertEquals (
                "too long -- own good",
                \cPHP\str\truncate ( "too long for it's own good", 20, "--" )
            );
    }

    public function testPluralize ()
    {
        $this->assertEquals( "tests", \cPHP\str\pluralize("test") );
        $this->assertEquals( "   tests   ", \cPHP\str\pluralize("   test   ") );

        $this->assertEquals( "tries", \cPHP\str\pluralize("try") );
        $this->assertEquals( "   tries   ", \cPHP\str\pluralize("   try   ") );

        $this->assertEquals( "TESTS", \cPHP\str\pluralize("TEST") );
        $this->assertEquals( "TRIES", \cPHP\str\pluralize("TRY") );

        $this->assertEquals( "test", \cPHP\str\pluralize("test", 1) );
        $this->assertEquals( "try", \cPHP\str\pluralize("try", 1) );

        $this->assertEquals( "tests", \cPHP\str\pluralize("test", 5) );
        $this->assertEquals( "tries", \cPHP\str\pluralize("try", 5) );
    }

    public function testPluralizeException ()
    {
        $this->setExpectedException('\cPHP\Exception\Argument');
        \cPHP\str\pluralize( '' );
    }

}

?>
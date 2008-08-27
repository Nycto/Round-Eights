<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * test suite
 */
class functions_strings
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('commonPHP Exception Class');
        $suite->addTestSuite( 'general' );
        $suite->addTestSuite( 'functions_strings_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class functions_strings_tests extends PHPUnit_Framework_TestCase
{

    public function testInt2Ordinal ()
    {
        $this->assertEquals( "1st", cPHP::int2Ordinal(1) );
        $this->assertEquals( "2nd", cPHP::int2Ordinal(2) );
        $this->assertEquals( "3rd", cPHP::int2Ordinal(3) );
        $this->assertEquals( "4th", cPHP::int2Ordinal(4) );
        $this->assertEquals( "5th", cPHP::int2Ordinal(5) );
        $this->assertEquals( "6th", cPHP::int2Ordinal(6) );
        $this->assertEquals( "7th", cPHP::int2Ordinal(7) );
        $this->assertEquals( "8th", cPHP::int2Ordinal(8) );
        $this->assertEquals( "9th", cPHP::int2Ordinal(9) );
        $this->assertEquals( "10th", cPHP::int2Ordinal(10) );

        $this->assertEquals( "11th", cPHP::int2Ordinal(11) );
        $this->assertEquals( "12th", cPHP::int2Ordinal(12) );
        $this->assertEquals( "13th", cPHP::int2Ordinal(13) );
        $this->assertEquals( "14th", cPHP::int2Ordinal(14) );
        $this->assertEquals( "15th", cPHP::int2Ordinal(15) );
        $this->assertEquals( "16th", cPHP::int2Ordinal(16) );
        $this->assertEquals( "17th", cPHP::int2Ordinal(17) );
        $this->assertEquals( "18th", cPHP::int2Ordinal(18) );
        $this->assertEquals( "19th", cPHP::int2Ordinal(19) );
        $this->assertEquals( "20th", cPHP::int2Ordinal(20) );

        $this->assertEquals( "21st", cPHP::int2Ordinal(21) );
        $this->assertEquals( "22nd", cPHP::int2Ordinal(22) );
        $this->assertEquals( "23rd", cPHP::int2Ordinal(23) );
        $this->assertEquals( "24th", cPHP::int2Ordinal(24) );
        $this->assertEquals( "25th", cPHP::int2Ordinal(25) );
        $this->assertEquals( "30th", cPHP::int2Ordinal(30) );

        $this->assertEquals( "-1st", cPHP::int2Ordinal(-1) );
        $this->assertEquals( "-2nd", cPHP::int2Ordinal(-2) );
        $this->assertEquals( "-3rd", cPHP::int2Ordinal(-3) );
        $this->assertEquals( "-4th", cPHP::int2Ordinal(-4) );
        $this->assertEquals( "-5th", cPHP::int2Ordinal(-5) );
        $this->assertEquals( "-9th", cPHP::int2Ordinal(-9) );
        $this->assertEquals( "-10th", cPHP::int2Ordinal(-10) );
    }

    public function testStrContains ()
    {
        $this->assertTrue( cPHP::strContains(' In ', 'Check In this string') );
        $this->assertFalse( cPHP::strContains('not in', 'Check In this string') );

        $this->assertTrue( cPHP::strContains(' In ', 'Check In this string', TRUE) );
        $this->assertFalse( cPHP::strContains('not in', 'Check In this string', TRUE) );

        $this->assertTrue( cPHP::strContains(' in ', 'Check In this string', TRUE) );
        $this->assertFalse( cPHP::strContains('not in', 'Check In this string', TRUE) );

        $this->assertTrue( cPHP::strContains(' In ', 'Check In this string', FALSE) );
        $this->assertFalse( cPHP::strContains('not in', 'Check In this string', FALSE) );

        $this->assertFalse( cPHP::strContains(' in ', 'Check In this string', FALSE) );
        $this->assertFalse( cPHP::strContains('not in', 'Check In this string', FALSE) );
    }

    public function testStrOffsets ()
    {
        
        $this->assertSame(
                array(0, 8, 52, 60, 70),
                cPHP::strOffsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string' )->get()
            );

        $this->assertEquals(
                array(0, 8, 52, 60, 70),
                cPHP::strOffsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', TRUE )->get()
            );

        $this->assertEquals(
                array(8, 52, 70),
                cPHP::strOffsets( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )->get()
            );

        $this->assertEquals(
                array(0, 60),
                cPHP::strOffsets( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )->get()
            );

        $this->assertEquals(
                array(),
                cPHP::strOffsets( 'Not in Haystack', 'Stringy string with multiple occurances of the word string, Stringity string' )->get()
            );

        $this->assertEquals(
                array(),
                cPHP::strOffsets( 'Multiple', 'Stringy string with multiple occurances of the word string, Stringity string', FALSE )->get()
            );

        /*
        try {
            cPHP::strOffsets( '', 'Stringy string with multiple occurances of the word string, Stringity string' );
            $this->assert_exception('ArgumentError');
        }
        catch (Exception $err) {
            $this->assert_exception('ArgumentError', FALSE, $err);
        }
        */

    }

    public function testStrnpos ()
    {

        $this->assertEquals(
                0,
                cPHP::strnpos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 0 )
            );

        $this->assertEquals(
                52,
                cPHP::strnpos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 2 )
            );

        $this->assertEquals(
                70,
                cPHP::strnpos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -1 )
            );

        $this->assertEquals(
                52,
                cPHP::strnpos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -3 )
            );

        $this->assertEquals(
                8,
                cPHP::strnpos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 0, FALSE )
            );

        $this->assertEquals(
                52,
                cPHP::strnpos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', 1, FALSE )
            );

        $this->assertEquals(
                70,
                cPHP::strnpos( 'string', 'Stringy string with multiple occurances of the word string, Stringity string', -1, FALSE )
            );

        $this->assertEquals(
                0,
                cPHP::strnpos( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', 0, FALSE )
            );

        $this->assertEquals(
                60,
                cPHP::strnpos( 'String', 'Stringy string with multiple occurances of the word string, Stringity string', -1, FALSE )
            );

    }

    public function testUnshout ()
    {

        $this->assertEquals(
            'This is A String with Some odd Capitals',
            cPHP::unshout( "This is A STRING wiTH SoMe odd CAPITALs" )
        );

    }

    public function testStripW ()
    {
        $this->assertEquals(
                "123abc",
                cPHP::stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc")
            );
        $this->assertEquals(
                "  1 23 a bc",
                cPHP::stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", ALLOW_SPACES)
            );
        $this->assertEquals(
                "  1_ 23 a bc",
                cPHP::stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", ALLOW_SPACES | ALLOW_UNDERSCORES)
            );
        $this->assertEquals(
                "12\n3ab\rc",
                cPHP::stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", ALLOW_NEWLINES)
            );
        $this->assertEquals(
                "12\n3ab\rc",
                cPHP::stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", ALLOW_NEWLINES)
            );
        $this->assertEquals(
                "123\tabc",
                cPHP::stripW("  !@#^1^%_ 2\n3\t <>?a )))b\rc", ALLOW_TABS)
            );
        $this->assertEquals(
                "123-abc",
                cPHP::stripW("  !@#^1^%_ 2\n3\t <->?a )))b\rc", ALLOW_DASHES)
            );
    }

    public function testStripRepeats ()
    {
        $this->assertEquals(
                "start T tytyty yyyy end",
                cPHP::stripRepeats('start TTT ttytyty yyyy end', 't')
            );

        $this->assertEquals(
                "start TTT tty yyyy end",
                cPHP::stripRepeats('start TTT ttytyty yyyy end', 'ty')
            );

        $this->assertEquals(
                "start TTT tytyty yyyy end",
                cPHP::stripRepeats('start TTT ttytyty yyyy end', 't', FALSE)
            );

        $this->assertEquals(
                "start T ttytyty yyyy end",
                cPHP::stripRepeats('start TTT ttytyty yyyy end', 'T', FALSE)
            );

        $this->assertEquals(
                "start T tyty yyyy end",
                cPHP::stripRepeats('start TTT ttytyty yyyy end', array( array('t'), 'ty'))
            );
    }

    public function truncateWords ()
    {
        $this->assertEquals(
                "string with so...ds that ne...ed down",
                truncateWords( "string with someverylongwords that needtobetrimmed down", 10)
            );

        $this->assertEquals(
                "string with so...s that ne...d down",
                truncateWords( "string with someverylongwords that needtobetrimmed down", 10, 6)
            );

        $this->assertEquals(
                "string with so..ds that ne..ed down",
                truncateWords( "string with someverylongwords that needtobetrimmed down", 10, 6, '..')
            );

        $this->assertEquals(
                "string with someverylongwords that needtobetrimmed down",
                truncateWords( "string with someverylongwords that needtobetrimmed down", 20 )
            );
    }

    public function stripQuoted ()
    {
        $this->assertEquals(
                "This  with quotes",
                stripQuoted( "This 'is a string' with\"\" quotes" )
            );

        $this->assertEquals(
                "This  withot",
                stripQuoted( "This /is a string& with/ qu&ot&es", '/', '&' )
            );
    }

    public function substr_icount ()
    {

        $this->assertEquals(
                2,
                substr_icount( 'This Is A Test', 'is' )
            );

        $this->assertEquals(
                2,
                substr_icount( 'This Is A Test', 'Is' )
            );

        $this->assertEquals(
                2,
                substr_icount( 'This Is A Test', 'IS' )
            );

        $this->assertEquals(
                1,
                substr_icount( 'This Is A Test', 'is', 3 )
            );

        $this->assertEquals(
                0,
                substr_icount( 'This Is A Test', 'is', 3, 3 )
            );
    }

    public function startsWith ()
    {
        $this->assertTrue( startsWith('string with content', 'string') );
        $this->assertTrue( startsWith('string with content', 'String') );

        $this->assertTrue( startsWith('string with content', 'string', TRUE) );
        $this->assertTrue( startsWith('string with content', 'String', TRUE) );

        $this->assertTrue( startsWith('string with content', 'string', FALSE) );

        $this->assertFalse( startsWith('string with content', 'String', FALSE) );
        $this->assertFalse( startsWith('string with content', 'strn', FALSE) );
    }

    public function endsWith ()
    {
        $this->assertTrue( endsWith('string with content', 'content') );
        $this->assertTrue( endsWith('string with content', 'Content') );

        $this->assertTrue( endsWith('string with content', 'content', TRUE) );
        $this->assertTrue( endsWith('string with content', 'Content', TRUE) );

        $this->assertTrue( endsWith('string with content', 'content', FALSE) );

        $this->assertFalse( endsWith('string with content', 'Content', FALSE) );
        $this->assertFalse( endsWith('string with content', 'contnt', FALSE) );
    }

    public function strTail ()
    {
        $this->assertEquals(
                'stringtail',
                strTail('string', 'tail')
            );

        $this->assertEquals(
                'stringtail',
                strTail('stringtail', 'tail')
            );

        $this->assertEquals(
                'stringtail',
                strTail('string', 'tail', TRUE)
            );

        $this->assertEquals(
                'stringtail',
                strTail('stringtail', 'tail', TRUE)
            );

        $this->assertEquals(
                'stringtail',
                strTail('string', 'tail', FALSE)
            );

        $this->assertEquals(
                'stringtail',
                strTail('stringtail', 'tail', FALSE)
            );

        $this->assertEquals(
                'stringtailTail',
                strTail('stringtail', 'Tail', FALSE)
            );

        $this->assertEquals(
                'stringTail',
                strTail('stringTail', 'Tail', FALSE)
            );
    }

    public function strStripTail ()
    {
        $this->assertEquals(
                'string with cont',
                strStripTail( "string with content", "ent" )
            );

        $this->assertEquals(
                'string with cont',
                strStripTail( "string with cont", "ent" )
            );

        $this->assertEquals(
                'string with cont',
                strStripTail( "string with content", "ent", TRUE )
            );

        $this->assertEquals(
                'string with cont',
                strStripTail( "string with content", "ENT", TRUE )
            );

        $this->assertEquals(
                'string with cont',
                strStripTail( "string with cont", "ent", TRUE)
            );

        $this->assertEquals(
                'string with cont',
                strStripTail( "string with content", "ent", FALSE )
            );

        $this->assertEquals(
                'string with content',
                strStripTail( "string with content", "Ent", FALSE )
            );

        $this->assertEquals(
                'string with cont',
                strStripTail( "string with cont", "ent", FALSE)
            );

        $this->assertEquals(
                '',
                strStripTail( "string", "string", TRUE )
            );

        $this->assertEquals(
                'string',
                strStripTail( "string", "" )
            );
    }

    public function strHead ()
    {
        $this->assertEquals(
                'headstring',
                strHead('string', 'head')
            );

        $this->assertEquals(
                'headstring',
                strHead('headstring', 'head')
            );

        $this->assertEquals(
                'headstring',
                strHead('string', 'head', TRUE)
            );

        $this->assertEquals(
                'headstring',
                strHead('headstring', 'head', TRUE)
            );

        $this->assertEquals(
                'headstring',
                strHead('string', 'head', FALSE)
            );

        $this->assertEquals(
                'headstring',
                strHead('headstring', 'head', FALSE)
            );

        $this->assertEquals(
                'Headheadstring',
                strHead('headstring', 'Head', FALSE)
            );

        $this->assertEquals(
                'Headstring',
                strHead('Headstring', 'Head', FALSE)
            );
    }

    public function strStripHead ()
    {
        $this->assertEquals(
                'ing with content',
                strStripHead( "ing with content", "str" )
            );

        $this->assertEquals(
                'ing with content',
                strStripHead( "ing with content", "str" )
            );

        $this->assertEquals(
                'ing with content',
                strStripHead( "ing with content", "str", TRUE )
            );

        $this->assertEquals(
                'ing with content',
                strStripHead( "string with content", "STR", TRUE )
            );

        $this->assertEquals(
                'ing with content',
                strStripHead( "ing with content", "str", TRUE)
            );

        $this->assertEquals(
                'ing with content',
                strStripHead( "string with content", "str", FALSE )
            );

        $this->assertEquals(
                'string with content',
                strStripHead( "string with content", "Str", FALSE )
            );

        $this->assertEquals(
                'ing with content',
                strStripHead( "ing with content", "str", FALSE)
            );

        $this->assertEquals(
                '',
                strStripHead( "string", "string", TRUE )
            );

        $this->assertEquals(
                'string',
                strStripHead( "string", "" )
            );
    }

    public function strWeld ()
    {
        $this->assertEquals(
                '/dir/file',
                strWeld('/dir', 'file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                strWeld('/dir/', 'file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                strWeld('/dir', '/file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                strWeld('/dir/', '/file', '/')
            );

        $this->assertEquals(
                'onEleven',
                strWeld('one', 'eleven', 'E')
            );

        $this->assertEquals(
                'onEleven',
                strWeld('one', 'eleven', 'E', TRUE)
            );

        $this->assertEquals(
                'oneEeleven',
                strWeld('one', 'eleven', 'E', FALSE)
            );
    }

    public function strPartition ()
    {
        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                strPartition("This is a string to split", 5, 10, 12)
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                strPartition("This is a string to split", array( 5, 10 ), 12)
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                strPartition("This is a string to split", -10, 5, 10, 12)
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                strPartition("This is a string to split", 0, 5, 10, 12)
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                strPartition("This is a string to split", 12, 10, 5)
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                strPartition("This is a string to split", 5, 10, 12, 10)
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                strPartition( "This is a string to split", array( 5, -10 ), 12, 10, 12, 10, 50 )
            );

        $this->assertEquals(
                array( "T", "his is a string to spli", "t" ),
                strPartition( "This is a string to split", 0, 1, 24, 25 )
            );
    }

    public function strCompare ()
    {
        $this->assertEquals(
                0,
                strCompare('Test', 'test', TRUE)
            );

        $this->assertEquals(
                -6,
                strCompare('Not The Same', 'test', TRUE)
            );

        $this->assertEquals(
                16,
                strCompare('test', 'Different Than', TRUE)
            );

        $this->assertEquals(
                0,
                strCompare('The Same', 'The Same', FALSE)
            );

        $this->assertEquals(
                -1,
                strCompare('Test', 'test', FALSE)
            );

        $this->assertEquals(
                1,
                strCompare('Casesensitive', 'CaseSensitivE', FALSE)
            );
    }

    public function strEnclose ()
    {

        $this->assertEquals(
                "wrap data wrap",
                strEnclose( " data ", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                strEnclose( "wrap data ", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                strEnclose( " data wrap", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                strEnclose( "wrap data wrap", "wrap" )
            );



        $this->assertEquals(
                "Wrap data Wrap",
                strEnclose( " data ", "Wrap", TRUE )
            );

        $this->assertEquals(
                "wrap data Wrap",
                strEnclose( "wrap data ", "Wrap", TRUE )
            );

        $this->assertEquals(
                "Wrap data wrap",
                strEnclose( " data wrap", "Wrap", TRUE )
            );

        $this->assertEquals(
                "wrap data wrap",
                strEnclose( "wrap data wrap", "Wrap", TRUE )
            );



        $this->assertEquals(
                "Wrap data Wrap",
                strEnclose( " data ", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrapwrap data Wrap",
                strEnclose( "wrap data ", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrap data wrapWrap",
                strEnclose( " data wrap", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrapwrap data wrapWrap",
                strEnclose( "wrap data wrap", "Wrap", FALSE )
            );
    }

    public function strTruncate ()
    {
        $this->assertEquals (
                "Not long enough",
                strTruncate ( "Not long enough", 30 )
            );

        $this->assertEquals (
                "too long ...own good",
                strTruncate ( "too long for it's own good", 20 )
            );

        $this->assertEquals (
                "too long -- own good",
                strTruncate ( "too long for it's own good", 20, "--" )
            );
    }

    public function parseQuery ()
    {

        $this->assertEquals(
                array ( "key" => "value" ),
                parseQuery( "key=value" )
            );

        $this->assertEquals(
                array ( "key" => "value", "key2" => "value2", "key3" => "value3" ),
                parseQuery( "?key=value?key2=value2&?&key3=value3?" )
            );

        $this->assertEquals(
                array ( "key" => "value", "key3" => "value3" ),
                parseQuery( "?key=value&=value2&key3=value3" )
            );

        $this->assertEquals(
                array ( "key" => "value2" ),
                parseQuery( "key=value&key=value2" )
            );


        // test the URL decoding
        $this->assertEquals(
                array ( "key more" => "value for decoding" ),
                parseQuery( "key%20more=value%20for%20decoding" )
            );

        $this->assertEquals(
                array ( "key%20more" => "value for decoding" ),
                parseQuery( "key%20more=value%20for%20decoding", PARSEQUERY_ENCODED_KEYS )
            );

        $this->assertEquals(
                array ( "key more" => "value%20for%20decoding" ),
                parseQuery( "key%20more=value%20for%20decoding", PARSEQUERY_ENCODED_VALUES )
            );

        $this->assertEquals(
                array ( "key%20more" => "value%20for%20decoding" ),
                parseQuery( "key%20more=value%20for%20decoding", PARSEQUERY_ENCODED_KEYS | PARSEQUERY_ENCODED_VALUES )
            );

        // Test the recursive parsing
        $this->assertEquals(
                array( "key" => array( 1 => "value" ) ),
                parseQuery( "key[1]=value" )
            );

        // Test the recursive parsing
        $this->assertEquals(
                array( "key" => array( 1 => "value" ) ),
                parseQuery( "key[1]  =value" )
            );

        $this->assertEquals(
                array( "key" => array( 1 => "value" ) ),
                parseQuery( "key[1]  =value" )
            );

        $this->assertEquals(
                array( "key" => array( "index" => array( 1 => "value3", 2 => "value2" ), "other" => "value4" ) ),
                parseQuery( "key[index][1]=value&key[index][2]=value2&key[index][1]=value3&key[other]=value4" )
            );

    }
    public function pluralize ()
    {
        $this->assertEquals( "tests", pluralize("test") );
        $this->assertEquals( "   tests   ", pluralize("   test   ") );

        $this->assertEquals( "tries", pluralize("try") );
        $this->assertEquals( "   tries   ", pluralize("   try   ") );

        $this->assertEquals( "TESTS", pluralize("TEST") );
        $this->assertEquals( "TRIES", pluralize("TRY") );


        $this->assertEquals( "test", pluralize("test", 1) );
        $this->assertEquals( "try", pluralize("try", 1) );

        $this->assertEquals( "tests", pluralize("test", 5) );
        $this->assertEquals( "tries", pluralize("try", 5) );

        try {
            pluralize("      ");
            $this->assert_exception("ArgumentError");
        }
        catch ( Exception $err ){
            $this->assert_exception("ArgumentError", FALSE, $err);
        }
    }
}

?>
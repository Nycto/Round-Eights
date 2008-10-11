<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * test suite
 */
class functions_strings
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP String Functions');
        $suite->addLib();
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

    }
    
    public function testStrOffsetsException ()
    {
        $this->setExpectedException('::cPHP::Exception::Argument');
        cPHP::strOffsets( '', 'Stringy string with multiple occurances of the word string, Stringity string' );
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
    
    public function testStripRepeatsException ()
    {
        $this->setExpectedException('::cPHP::Exception::Argument');
        ::cPHP::stripRepeats( 'Stringy string with multiple occurances of the word string, Stringity string', '' );
    }

    public function testTruncateWords ()
    {
        $this->assertEquals(
                "string with so...ds that ne...ed down",
                cPHP::truncateWords( "string with someverylongwords that needtobetrimmed down", 10)
            );

        $this->assertEquals(
                "string with so...s that ne...d down",
                cPHP::truncateWords( "string with someverylongwords that needtobetrimmed down", 10, 6)
            );

        $this->assertEquals(
                "string with so..ds that ne..ed down",
                cPHP::truncateWords( "string with someverylongwords that needtobetrimmed down", 10, 6, '..')
            );

        $this->assertEquals(
                "string with someverylongwords that needtobetrimmed down",
                cPHP::truncateWords( "string with someverylongwords that needtobetrimmed down", 20 )
            );
    }

    public function testStripQuoted ()
    {
        $this->assertEquals(
                "This  with quotes",
                cPHP::stripQuoted( "This 'is a string' with\"\" quotes" )
            );

        $this->assertEquals(
                "This  withot",
                cPHP::stripQuoted( "This /is a string& with/ qu&ot&es", array('/', '&') )
            );
    }

    public function testSubstr_icount ()
    {

        $this->assertEquals(
                2,
                cPHP::substr_icount( 'This Is A Test', 'is' )
            );

        $this->assertEquals(
                2,
                cPHP::substr_icount( 'This Is A Test', 'Is' )
            );

        $this->assertEquals(
                2,
                cPHP::substr_icount( 'This Is A Test', 'IS' )
            );

        $this->assertEquals(
                1,
                cPHP::substr_icount( 'This Is A Test', 'is', 3 )
            );

        $this->assertEquals(
                0,
                cPHP::substr_icount( 'This Is A Test', 'is', 3, 3 )
            );
    }

    public function testStartsWith ()
    {
        $this->assertTrue( cPHP::startsWith('string with content', 'string') );
        $this->assertTrue( cPHP::startsWith('string with content', 'String') );

        $this->assertTrue( cPHP::startsWith('string with content', 'string', TRUE) );
        $this->assertTrue( cPHP::startsWith('string with content', 'String', TRUE) );

        $this->assertTrue( cPHP::startsWith('string with content', 'string', FALSE) );

        $this->assertFalse( cPHP::startsWith('string with content', 'String', FALSE) );
        $this->assertFalse( cPHP::startsWith('string with content', 'strn', FALSE) );
    }

    public function testEndsWith ()
    {
        $this->assertTrue( cPHP::endsWith('string with content', 'content') );
        $this->assertTrue( cPHP::endsWith('string with content', 'Content') );

        $this->assertTrue( cPHP::endsWith('string with content', 'content', TRUE) );
        $this->assertTrue( cPHP::endsWith('string with content', 'Content', TRUE) );

        $this->assertTrue( cPHP::endsWith('string with content', 'content', FALSE) );

        $this->assertFalse( cPHP::endsWith('string with content', 'Content', FALSE) );
        $this->assertFalse( cPHP::endsWith('string with content', 'contnt', FALSE) );
    }

    public function testStrTail ()
    {
        $this->assertEquals(
                'stringtail',
                cPHP::strTail('string', 'tail')
            );

        $this->assertEquals(
                'stringtail',
                cPHP::strTail('stringtail', 'tail')
            );

        $this->assertEquals(
                'stringtail',
                cPHP::strTail('string', 'tail', TRUE)
            );

        $this->assertEquals(
                'stringtail',
                cPHP::strTail('stringtail', 'tail', TRUE)
            );

        $this->assertEquals(
                'stringtail',
                cPHP::strTail('string', 'tail', FALSE)
            );

        $this->assertEquals(
                'stringtail',
                cPHP::strTail('stringtail', 'tail', FALSE)
            );

        $this->assertEquals(
                'stringtailTail',
                cPHP::strTail('stringtail', 'Tail', FALSE)
            );

        $this->assertEquals(
                'stringTail',
                cPHP::strTail('stringTail', 'Tail', FALSE)
            );
    }

    public function testStrStripTail ()
    {
        $this->assertEquals(
                'string with cont',
                cPHP::strStripTail( "string with content", "ent" )
            );

        $this->assertEquals(
                'string with cont',
                cPHP::strStripTail( "string with cont", "ent" )
            );

        $this->assertEquals(
                'string with cont',
                cPHP::strStripTail( "string with content", "ent", TRUE )
            );

        $this->assertEquals(
                'string with cont',
                cPHP::strStripTail( "string with content", "ENT", TRUE )
            );

        $this->assertEquals(
                'string with cont',
                cPHP::strStripTail( "string with cont", "ent", TRUE)
            );

        $this->assertEquals(
                'string with cont',
                cPHP::strStripTail( "string with content", "ent", FALSE )
            );

        $this->assertEquals(
                'string with content',
                cPHP::strStripTail( "string with content", "Ent", FALSE )
            );

        $this->assertEquals(
                'string with cont',
                cPHP::strStripTail( "string with cont", "ent", FALSE)
            );

        $this->assertEquals(
                '',
                cPHP::strStripTail( "string", "string", TRUE )
            );

        $this->assertEquals(
                'string',
                cPHP::strStripTail( "string", "" )
            );
    }

    public function testStrHead ()
    {
        $this->assertEquals(
                'headstring',
                cPHP::strHead('string', 'head')
            );

        $this->assertEquals(
                'headstring',
                cPHP::strHead('headstring', 'head')
            );

        $this->assertEquals(
                'headstring',
                cPHP::strHead('string', 'head', TRUE)
            );

        $this->assertEquals(
                'headstring',
                cPHP::strHead('headstring', 'head', TRUE)
            );

        $this->assertEquals(
                'headstring',
                cPHP::strHead('string', 'head', FALSE)
            );

        $this->assertEquals(
                'headstring',
                cPHP::strHead('headstring', 'head', FALSE)
            );

        $this->assertEquals(
                'Headheadstring',
                cPHP::strHead('headstring', 'Head', FALSE)
            );

        $this->assertEquals(
                'Headstring',
                cPHP::strHead('Headstring', 'Head', FALSE)
            );
    }

    public function testStrStripHead ()
    {
        $this->assertEquals(
                'ing with content',
                cPHP::strStripHead( "ing with content", "str" )
            );

        $this->assertEquals(
                'ing with content',
                cPHP::strStripHead( "ing with content", "str" )
            );

        $this->assertEquals(
                'ing with content',
                cPHP::strStripHead( "ing with content", "str", TRUE )
            );

        $this->assertEquals(
                'ing with content',
                cPHP::strStripHead( "string with content", "STR", TRUE )
            );

        $this->assertEquals(
                'ing with content',
                cPHP::strStripHead( "ing with content", "str", TRUE)
            );

        $this->assertEquals(
                'ing with content',
                cPHP::strStripHead( "string with content", "str", FALSE )
            );

        $this->assertEquals(
                'string with content',
                cPHP::strStripHead( "string with content", "Str", FALSE )
            );

        $this->assertEquals(
                'ing with content',
                cPHP::strStripHead( "ing with content", "str", FALSE)
            );

        $this->assertEquals(
                '',
                cPHP::strStripHead( "string", "string", TRUE )
            );

        $this->assertEquals(
                'string',
                cPHP::strStripHead( "string", "" )
            );
    }

    public function testStrWeld ()
    {
        $this->assertEquals(
                '/dir/file',
                cPHP::strWeld('/dir', 'file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                cPHP::strWeld('/dir/', 'file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                cPHP::strWeld('/dir', '/file', '/')
            );

        $this->assertEquals(
                '/dir/file',
                cPHP::strWeld('/dir/', '/file', '/')
            );

        $this->assertEquals(
                'onEleven',
                cPHP::strWeld('one', 'eleven', 'E')
            );

        $this->assertEquals(
                'onEleven',
                cPHP::strWeld('one', 'eleven', 'E', TRUE)
            );

        $this->assertEquals(
                'oneEeleven',
                cPHP::strWeld('one', 'eleven', 'E', FALSE)
            );
    }

    public function testStrPartition ()
    {
        $result = cPHP::strPartition("", 5, 10, 12);
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( array(), $result->get() );
        
        
        $result = cPHP::strPartition("This is a string to split", 5, 10, 12);
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals( array( "This ", "is a ", "st", "ring to split" ), $result->get() );
        

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                cPHP::strPartition("This is a string to split", array( 5, 10 ), 12)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                cPHP::strPartition("This is a string to split", -10, 5, 10, 12)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                cPHP::strPartition("This is a string to split", 0, 5, 10, 12)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                cPHP::strPartition("This is a string to split", 12, 10, 5)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                cPHP::strPartition("This is a string to split", 5, 10, 12, 10)->get()
            );

        $this->assertEquals(
                array( "This ", "is a ", "st", "ring to split" ),
                cPHP::strPartition( "This is a string to split", array( 5, -10 ), 12, 10, 12, 10, 50 )->get()
            );

        $this->assertEquals(
                array( "T", "his is a string to spli", "t" ),
                cPHP::strPartition( "This is a string to split", 0, 1, 24, 25 )->get()
            );
    }

    public function testStrCompare ()
    {
        $this->assertEquals(
                0,
                cPHP::strCompare('Test', 'test', TRUE)
            );

        $this->assertEquals(
                -6,
                cPHP::strCompare('Not The Same', 'test', TRUE)
            );

        $this->assertEquals(
                16,
                cPHP::strCompare('test', 'Different Than', TRUE)
            );

        $this->assertEquals(
                0,
                cPHP::strCompare('The Same', 'The Same', FALSE)
            );

        $this->assertEquals(
                -1,
                cPHP::strCompare('Test', 'test', FALSE)
            );

        $this->assertEquals(
                1,
                cPHP::strCompare('Casesensitive', 'CaseSensitivE', FALSE)
            );
    }

    public function testStrEnclose ()
    {

        $this->assertEquals(
                "wrap data wrap",
                cPHP::strEnclose( " data ", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                cPHP::strEnclose( "wrap data ", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                cPHP::strEnclose( " data wrap", "wrap" )
            );

        $this->assertEquals(
                "wrap data wrap",
                cPHP::strEnclose( "wrap data wrap", "wrap" )
            );



        $this->assertEquals(
                "Wrap data Wrap",
                cPHP::strEnclose( " data ", "Wrap", TRUE )
            );

        $this->assertEquals(
                "wrap data Wrap",
                cPHP::strEnclose( "wrap data ", "Wrap", TRUE )
            );

        $this->assertEquals(
                "Wrap data wrap",
                cPHP::strEnclose( " data wrap", "Wrap", TRUE )
            );

        $this->assertEquals(
                "wrap data wrap",
                cPHP::strEnclose( "wrap data wrap", "Wrap", TRUE )
            );



        $this->assertEquals(
                "Wrap data Wrap",
                cPHP::strEnclose( " data ", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrapwrap data Wrap",
                cPHP::strEnclose( "wrap data ", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrap data wrapWrap",
                cPHP::strEnclose( " data wrap", "Wrap", FALSE )
            );

        $this->assertEquals(
                "Wrapwrap data wrapWrap",
                cPHP::strEnclose( "wrap data wrap", "Wrap", FALSE )
            );
    }

    public function testStrTruncate ()
    {
        $this->assertEquals (
                "Not long enough",
                cPHP::strTruncate ( "Not long enough", 30 )
            );

        $this->assertEquals (
                "too long ...own good",
                cPHP::strTruncate ( "too long for it's own good", 20 )
            );

        $this->assertEquals (
                "too long -- own good",
                cPHP::strTruncate ( "too long for it's own good", 20, "--" )
            );
    }

    public function testPluralize ()
    {
        $this->assertEquals( "tests", cPHP::pluralize("test") );
        $this->assertEquals( "   tests   ", cPHP::pluralize("   test   ") );

        $this->assertEquals( "tries", cPHP::pluralize("try") );
        $this->assertEquals( "   tries   ", cPHP::pluralize("   try   ") );

        $this->assertEquals( "TESTS", cPHP::pluralize("TEST") );
        $this->assertEquals( "TRIES", cPHP::pluralize("TRY") );

        $this->assertEquals( "test", cPHP::pluralize("test", 1) );
        $this->assertEquals( "try", cPHP::pluralize("try", 1) );

        $this->assertEquals( "tests", cPHP::pluralize("test", 5) );
        $this->assertEquals( "tries", cPHP::pluralize("try", 5) );
    }
    
    public function testPluralizeException ()
    {
        $this->setExpectedException('::cPHP::Exception::Argument');
        cPHP::pluralize( '' );
    }

}

?>
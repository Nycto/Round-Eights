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
class classes_datetime
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP DateTime Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_datetime_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_datetime_tests extends PHPUnit_Framework_TestCase
{

/*
    public function testIsMySQL ()
    {
        $this->assertTrue( cPHP::DateTime::isMySQL("20071110093625") );
        $this->assertTrue( cPHP::DateTime::isMySQL("2007-11-10 09:36:25") );
        $this->assertTrue( cPHP::DateTime::isMySQL("20071110") );
        $this->assertTrue( cPHP::DateTime::isMySQL("2007-11-10") );
        
        $this->assertTrue( cPHP::DateTime::isMySQL(20071110093625) );
        
        $this->assertFalse( cPHP::DateTime::isMySQL("11102007093625") );
        $this->assertFalse( cPHP::DateTime::isMySQL("11-10-2007 09:36:25") );
        $this->assertFalse( cPHP::DateTime::isMySQL("11102007") );
        $this->assertFalse( cPHP::DateTime::isMySQL("11-10-2007") );
        
        $this->assertFalse( cPHP::DateTime::isMySQL("20071") );
        $this->assertFalse( cPHP::DateTime::isMySQL(1192086000) );
    }

    public function MySQL ()
    {

        $time = new cPHP::DateTime;

        $time->MySQL = "20071110093625";
        $this->assertEquals(1192034185, $time->time);
        $this->assertEquals("2007-10-10 09:36:25", $time->MySQL);

        $time->MySQL = "2007-11-10 09:36:25";
        $this->assertEquals(1192034185, $time->time);
        $this->assertEquals("2007-10-10 09:36:25", $time->MySQL);

        $time->MySQL = "20071111";
        $this->assertEquals(1192086000, $time->time);
        $this->assertEquals("2007-10-11 00:00:00", $time->MySQL);

        $time->MySQL = "2007-11-11";
        $this->assertEquals(1192086000, $time->time);
        $this->assertEquals("2007-10-11 00:00:00", $time->MySQL);
    }

    public function fromArray ()
    {
        $time = new cPHP::DateTime;
        $time->array = array(5, 10, 20, 15, 12, 2007);
        $this->assertEquals( 1197778205, $time->time );

        $this->assertEquals(
                array (
                        'seconds' => 5, 'minutes' => 10,
                        'hours' => 20, 'mday' => 15,
                        'wday' => 6, 'mon' => 12,
                        'year' => 2007, 'yday' => 348,
                        'weekday' => 'Saturday',
                        'month' => 'December',
                        0 => 1197778205
                    ),
                $time->array
            );
    }

    public function string ()
    {
        $time = new cPHP::DateTime;
        $time->string = "10 September 2000";
        $this->assertEquals( 968569200, $time->time );
    }

    public function math ()
    {
        $time = new cPHP::DateTime;
        $time->time = 968569200;

        $time->math("+1 day");
        $this->assertEquals(968655600, $time->time);
    }

    public function construct ()
    {
        // unix timestamp
        $time = new cPHP::DateTime(968655600);
        $this->assertEquals(968655600, $time->time);

        // unix timestamp as a string
        $time = new cPHP::DateTime("968655600");
        $this->assertEquals(968655600, $time->time);

        // mysql date
        $time = new cPHP::DateTime("20071012");
        $this->assertEquals(1189580400, $time->time);

        // mysql date/time
        $time = new cPHP::DateTime("20071012084823");
        $this->assertEquals(1189612103, $time->time);

        // mysql date
        $time = new cPHP::DateTime("2007-10-12");
        $this->assertEquals(1189580400, $time->time);

        // mysql date/time
        $time = new cPHP::DateTime("2007-10-12 08:48:23");
        $this->assertEquals(1189612103, $time->time);

        // from array
        $time = new cPHP::DateTime(array(5, 10, 20, 15, 12, 2007));
        $this->assertEquals(1197778205, $time->time);

        // mktime format
        $time = new cPHP::DateTime(8, 55, 22, 10, 12, 2007);
        $this->assertEquals(1192204522, $time->time);

        // from string
        $time = new cPHP::DateTime("10 September 2000");
        $this->assertEquals( 968569200, $time->time );

        // Make sure nothing gets set if nothing is given
        $time = new cPHP::DateTime;
        $this->assert_empty( $time->time );

    }

    public function format ()
    {
        $time = new cPHP::DateTime;
        $time->time = 1189612103;

        cPHP::DateTime::setFormat('g:i a');
        $this->assertEquals('8:48 am', "$time");
        $this->assertEquals('8:48 am', $time->format());

        cPHP::DateTime::setFormat('F j, Y, g:i a');
        $this->assertEquals('September 12, 2007, 8:48 am', "$time");
        $this->assertEquals('September 12, 2007, 8:48 am', $time->format());

        $time->format = 'F j, Y';
        $this->assertEquals('September 12, 2007', "$time");
        $this->assertEquals('September 12, 2007',  $time->format());

        unset($time->format);
        $this->assertEquals('September 12, 2007, 8:48 am', "$time");
        $this->assertEquals('September 12, 2007, 8:48 am', $time->format());

        $this->assertEquals("09.12.07", $time->format("m.d.y"));
    }

    public function quickReferences ()
    {
        $time = new cPHP::DateTime;
        $time->time = 1189612103;

        $this->assertEquals(23, $time->seconds);
        $this->assertEquals(48, $time->minutes);
        $this->assertEquals(8, $time->hours);
        $this->assertEquals(12, $time->day);
        $this->assertEquals(9, $time->month);
        $this->assertEquals(2007, $time->year);

        $time->seconds = 19;
        $this->assertEquals( 19, $time->seconds );
        $this->assertEquals( 1189612099, $time->time );

        $time->minutes = 59;
        $this->assertEquals( 59, $time->minutes );
        $this->assertEquals( 1189612759, $time->time );

        $time->hours = 13;
        $this->assertEquals( 13, $time->hours );
        $this->assertEquals( 1189630759, $time->time );

        $time->day = 2;
        $this->assertEquals( 2, $time->day );
        $this->assertEquals( 1188766759, $time->time );

        $time->month = 11;
        $this->assertEquals( 11, $time->month );
        $this->assertEquals( 1194037159, $time->time );

        $time->year = 2006;
        $this->assertEquals( 2006, $time->year );
        $this->assertEquals( 1162504759, $time->time );

    }

    public function setTime ()
    {
        $time = new cPHP::DateTime;

        $time->setTime(12, 25, 13);

        $this->assertEquals(12, $time->hours);
        $this->assertEquals(25, $time->minutes);
        $this->assertEquals(13, $time->seconds);

        $time->time = 1200958298;

        $time->setTime(18, 13, 00);

        $this->assertEquals(18, $time->hours);
        $this->assertEquals(13, $time->minutes);
        $this->assertEquals(00, $time->seconds);

        $this->assertEquals(21, $time->day);
        $this->assertEquals(1, $time->month);
        $this->assertEquals(2008, $time->year);
    }

    public function setDate ()
    {
        $time = new cPHP::DateTime;

        $time->setDate(2008, 1, 21);

        $this->assertEquals(2008, $time->year);
        $this->assertEquals(1, $time->month);
        $this->assertEquals(21, $time->day);

        $this->assertEquals(0, $time->hours);
        $this->assertEquals(0, $time->minutes);
        $this->assertEquals(0, $time->seconds);


        $time->time = 1200958298;

        $time->setDate(2008, 1, 32);

        $this->assertEquals(15, $time->hours);
        $this->assertEquals(31, $time->minutes);
        $this->assertEquals(38, $time->seconds);
        $this->assertEquals(2008, $time->year);
        $this->assertEquals(2, $time->month);
        $this->assertEquals(1, $time->day);
    }

    public function to ()
    {
        $time = new cPHP::DateTime;

        $time->time = 1200958298;
        $time->toStartOfDay();

        $this->assertEquals(0, $time->hours);
        $this->assertEquals(0, $time->minutes);
        $this->assertEquals(0, $time->seconds);
        $this->assertEquals(21, $time->day);
        $this->assertEquals(1, $time->month);
        $this->assertEquals(2008, $time->year);

        $time->toEndOfDay();

        $this->assertEquals(23, $time->hours);
        $this->assertEquals(59, $time->minutes);
        $this->assertEquals(59, $time->seconds);
        $this->assertEquals(21, $time->day);
        $this->assertEquals(1, $time->month);
        $this->assertEquals(2008, $time->year);

        $time->toEndOfMonth();

        $this->assertEquals(23, $time->hours);
        $this->assertEquals(59, $time->minutes);
        $this->assertEquals(59, $time->seconds);
        $this->assertEquals(31, $time->day);
        $this->assertEquals(1, $time->month);
        $this->assertEquals(2008, $time->year);

        // Make sure the class throws an exception when someone tries to set an invalid property
        try {
            $time->nonExistantProperty = 'wakka';
            $this->assert_exception('VariableError');
        }
        catch ( Exception $err ) {
            $this->assert_exception('VariableError', FALSE, $err);
        }
    }
*/  
}

?>
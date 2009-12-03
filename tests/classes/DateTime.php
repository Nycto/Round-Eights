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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_datetime extends PHPUnit_Framework_TestCase
{

    protected $timezone;

    protected function setUp ()
    {
        $this->timezone = date_default_timezone_get();
        date_default_timezone_set( "GMT" );
    }

    protected function tearDown ()
    {
        date_default_timezone_set( $this->timezone );
    }

    public function testConstruct ()
    {
        $time = new \r8\DateTime;
        $this->assertNull( $time->getTimeStamp() );

        $time = new \r8\DateTime( null );
        $this->assertNull( $time->getTimeStamp() );

        $time = new \r8\DateTime( true );
        $this->assertNull( $time->getTimeStamp() );

        $time = new \r8\DateTime( false );
        $this->assertNull( $time->getTimeStamp() );

        $time = new \r8\DateTime( "20080920" );
        $this->assertEquals( 1221868800, $time->getTimeStamp() );
    }

    public function testSetTimeStamp ()
    {
        $date = new \r8\DateTime;

        $this->assertSame( $date, $date->setTimeStamp(987654321) );
        $this->assertSame( 987654321, $date->getTimeStamp() );

        $this->assertSame( $date, $date->setTimeStamp("132435") );
        $this->assertSame( 132435, $date->getTimeStamp() );
    }

    public function testDefaultFormat ()
    {
        \r8\DateTime::setDefaultFormat("Y-m-d");

        $this->assertEquals( "Y-m-d", \r8\DateTime::getDefaultFormat() );
    }

    public function testSetGetFormat ()
    {
        $time = new \r8\DateTime;

        \r8\DateTime::setDefaultFormat('F j, Y, g:i a');

        $this->assertEquals('F j, Y, g:i a', $time->getFormat());

        $this->assertSame( $time, $time->setFormat('g:i a') );

        $this->assertEquals( 'g:i a', $time->getFormat() );

        $this->assertSame( $time, $time->clearFormat() );

        $this->assertEquals('F j, Y, g:i a', $time->getFormat());
    }

    public function testClearFormat ()
    {
        $time = new \r8\DateTime;

        \r8\DateTime::setDefaultFormat('F j, Y, g:i a');

        $this->assertEquals('F j, Y, g:i a', $time->getFormat());

        $time->setFormat('g:i a');

        $this->assertEquals( 'g:i a', $time->getFormat() );

        $this->assertSame( $time, $time->clearFormat() );

        $this->assertEquals('F j, Y, g:i a', $time->getFormat());
    }

    public function testGetFormatted ()
    {
        $time = new \r8\DateTime;

        try {
            $time->getFormatted();
            $this->fail("An expected exception was not thrown");
        }
        catch (\r8\Exception\Variable $err) {
            $this->assertEquals("No time has been set for this instance", $err->getMessage());
        }

        // Make sure toString catches the exception and returns empty
        $this->assertEquals("", "$time");


        $time->setTimeStamp(1189612103);

        \r8\DateTime::setDefaultFormat('g:i a');
        $this->assertEquals('3:48 pm', $time->getFormatted());
        $this->assertEquals('3:48 pm', "$time");

        \r8\DateTime::setDefaultFormat('F j, Y, g:i a');
        $this->assertEquals('September 12, 2007, 3:48 pm', $time->getFormatted());
        $this->assertEquals('September 12, 2007, 3:48 pm', "$time");

        $time->setFormat( 'F j, Y' );
        $this->assertEquals('September 12, 2007',  $time->getFormatted());
        $this->assertEquals('September 12, 2007', "$time");

        $time->clearFormat();
        $this->assertEquals('September 12, 2007, 3:48 pm', $time->getFormatted());
        $this->assertEquals('September 12, 2007, 3:48 pm', "$time");

        $this->assertEquals("09.12.07", $time->getFormatted("m.d.y"));
    }

    public function testFormats ()
    {
        $time = new \r8\DateTime(1189612103);

        $this->assertSame(
                "September 12, 2007, 3:48 pm",
                $time->getFormatted( \r8\DateTime::FORMAT_DEFAULT )
            );

        $this->assertSame(
                "2007-09-12 15:48:23",
                $time->getFormatted( \r8\DateTime::FORMAT_SQL_DATETIME )
            );

        $this->assertSame(
                "2007-09-12",
                $time->getFormatted( \r8\DateTime::FORMAT_SQL_DATE)
            );

        $this->assertSame(
                "2007-09-12T15:48:23",
                $time->getFormatted( \r8\DateTime::FORMAT_XSD_DATETIME)
            );
    }

    public function testGetArray ()
    {
        $time = new \r8\DateTime;

        try {
            $time->getArray();
            $this->fail("An expected exception was not thrown");
        }
        catch (\r8\Exception\Variable $err) {
            $this->assertEquals("No time has been set for this instance", $err->getMessage());
        }


        $time->setTimeStamp(1197778205);

        $this->assertEquals(
                array (
                        'seconds' => 5, 'minutes' => 10,
                        'hours' => 4, 'mday' => 16,
                        'wday' => 0, 'mon' => 12,
                        'year' => 2007, 'yday' => 349,
                        'weekday' => 'Sunday',
                        'month' => 'December',
                        0 => 1197778205
                    ),
                $time->getArray()
            );
    }

    public function testSetArray ()
    {
        $time = new \r8\DateTime;
        $time->setArray( array(5, 10, 20, 15, 12, 2007) );
        $this->assertEquals( 1197749405, $time->getTimeStamp() );


        $time = new \r8\DateTime;
        $time->setArray( array("seconds" => 5, "minutes" => 10, "hours" => 20, "day" => 15, "month" => 12, "year" => 2007) );
        $this->assertEquals( 1197749405, $time->getTimeStamp() );


        $time = new \r8\DateTime;
        $time->setArray( array("month" => 12, "minutes" => 10, "year" => 2007, "seconds" => 5, "hours" => 20, "day" => 15) );
        $this->assertEquals( 1197749405, $time->getTimeStamp() );


        $time = new \r8\DateTime;
        $time->setArray( array("mon" => 12, "minutes" => 10, "year" => 2007, "seconds" => 5, "hours" => 20, "mday" => 15) );
        $this->assertEquals( 1197749405, $time->getTimeStamp() );


        $time = new \r8\DateTime;
        $time->setArray(array(
                "mon" => 12,
                "month" => 6,
                "minutes" => 10,
                "year" => 2007,
                "seconds" => 5,
                "hours" => 20,
                "mday" => 15
            ));
        $this->assertEquals( 1197749405, $time->getTimeStamp() );


        $time = new \r8\DateTime;
        $time->setTimeStamp( 1197749405 );
        $this->assertEquals( $time, $time->setArray( $time->getArray() ) );
        $this->assertEquals( 1197749405, $time->getTimeStamp() );


        $time = new \r8\DateTime;
        $time->setSQL("2008-09-20 16:38:45");
        $time->setArray(array(
                "mon" => 12,
                "minutes" => 10,
                "seconds" => 5
            ));
        $this->assertEquals( "2008-12-20 16:10:05", $time->getSQL() );


        $time = new \r8\DateTime;
        $time->setArray(array(
                "mon" => 12,
                "minutes" => 10,
                "seconds" => 5
            ));
        $this->assertRegExp( '/[0-9]{4}\-12\-[0-9]{2} [0-9]{2}\:10\:05/', $time->getSQL() );
    }

    public function testIsSQL ()
    {
        $date = new \r8\DateTime;

        $this->assertTrue( \r8\DateTime::isSQL("20071110093625") );
        $this->assertTrue( \r8\DateTime::isSQL("2007-11-10 09:36:25") );
        $this->assertTrue( \r8\DateTime::isSQL("20071110") );
        $this->assertTrue( \r8\DateTime::isSQL("2007-11-10") );

        $this->assertTrue( \r8\DateTime::isSQL(20071110093625) );

        $this->assertFalse( \r8\DateTime::isSQL("11102007093625") );
        $this->assertFalse( \r8\DateTime::isSQL("11-10-2007 09:36:25") );
        $this->assertFalse( \r8\DateTime::isSQL("11102007") );
        $this->assertFalse( \r8\DateTime::isSQL("11-10-2007") );

        $this->assertFalse( \r8\DateTime::isSQL("20071") );
        $this->assertFalse( \r8\DateTime::isSQL(1192086000) );

        $this->assertFalse( \r8\DateTime::isSQL("2007 11 10") );
        $this->assertFalse( \r8\DateTime::isSQL("2007/11/10") );
    }

    public function testSetSQL ()
    {
        $time = new \r8\DateTime;

        try {
            $time->setSQL("Does not pass isSQL");
            $this->fail("An expected exception was not thrown");
        }
        catch (\r8\Exception\Argument $err) {
            $this->assertEquals("Invalid SQL date time", $err->getMessage());
        }

        $this->assertSame( $time, $time->setSQL("20071110093625") );
        $this->assertSame( 1194687385, $time->getTimeStamp() );

        $this->assertSame( $time, $time->setSQL("20071110") );
        $this->assertSame( 1194652800, $time->getTimeStamp() );
    }

    public function testGetSQL ()
    {
        $time = new \r8\DateTime;

        try {
            $time->getSQL();
            $this->fail("An expected exception was not thrown");
        }
        catch (\r8\Exception\Variable $err) {
            $this->assertEquals("No time has been set for this instance", $err->getMessage());
        }

        $time->setTimeStamp(1194652800);
        $this->assertSame("2007-11-10 00:00:00", $time->getSQL());

        $time->setTimeStamp(1194687385);
        $this->assertSame("2007-11-10 09:36:25", $time->getSQL());

        $time->setTimeStamp(428083174);
        $this->assertSame("1983-07-26 15:59:34", $time->getSQL());

    }

    public function testSetString ()
    {
        $time = new \r8\DateTime;

        $this->assertSame( $time, $time->setString( "10 September 2000" ) );
        $this->assertEquals( 968544000, $time->getTimeStamp() );

        try {
            $time->setString("NOPE");
            $time->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals("Unable to parse string to a valid time", $err->getMessage() );
        }
    }

    public function testInterpret ()
    {
        $time = new \r8\DateTime;

        // unix timestamp
        $this->assertSame( $time, $time->interpret(968655600) );
        $this->assertEquals( 968655600, $time->getTimeStamp() );

        // Float val timestamp
        $this->assertSame( $time, $time->interpret(968655600.75) );
        $this->assertEquals( 968655600, $time->getTimeStamp() );

        // unix timestamp as a string
        $this->assertSame( $time, $time->interpret("968655600") );
        $this->assertEquals(968655600, $time->getTimeStamp());

        // sql date
        $this->assertSame( $time, $time->interpret("20071012") );
        $this->assertEquals(1192147200, $time->getTimeStamp());

        $this->assertSame( $time, $time->interpret("2007-10-12") );
        $this->assertEquals(1192147200, $time->getTimeStamp());

        // sql date/time
        $this->assertSame( $time, $time->interpret("20071012084823") );
        $this->assertEquals(1192178903, $time->getTimeStamp());

        $this->assertSame( $time, $time->interpret("2007-10-12 08:48:23") );
        $this->assertEquals(1192178903, $time->getTimeStamp());

        // from array
        $this->assertSame( $time, $time->interpret(array(5, 10, 20, 15, 12, 2007)) );
        $this->assertEquals(1197749405, $time->getTimeStamp());

        // from string
        $this->assertSame( $time, $time->interpret("10 September 2000") );
        $this->assertEquals( 968544000, $time->getTimeStamp() );

        // From an instance of datetime
        $source = new DateTime;
        $source->setTimeStamp(968569200);
        $this->assertSame( $time, $time->interpret( $source ) );
        $this->assertEquals( 968569200, $time->getTimeStamp() );

        // Object
        $source = $this->getMock("stub_datetime", array("__toString"));
        $source->expects( $this->once() )
            ->method( "__toString" )
            ->will( $this->returnValue("2007-10-12") );
        $this->assertSame( $time, $time->interpret( $source ) );
        $this->assertEquals(1192147200, $time->getTimeStamp());


        try {
            $time->setString("Not a date");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Unable to parse string to a valid time", $err->getMessage() );
        }
    }

    public function testNormalizeUnit ()
    {
        try {
            \r8\DateTime::normalizeUnit("Invalid unit");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertEquals( "Invalid time unit", $err->getMessage() );
        }

        $this->assertEquals("second", \r8\DateTime::normalizeUnit("se c!@#ond s)(*"));

        $this->assertEquals("second", \r8\DateTime::normalizeUnit("second"));
        $this->assertEquals("second", \r8\DateTime::normalizeUnit("seconds"));
        $this->assertEquals("second", \r8\DateTime::normalizeUnit("SEcONds"));

        $this->assertEquals("second", \r8\DateTime::normalizeUnit("sec"));
        $this->assertEquals("second", \r8\DateTime::normalizeUnit("secs"));
        $this->assertEquals("second", \r8\DateTime::normalizeUnit("SeCs"));

        $this->assertEquals("minute", \r8\DateTime::normalizeUnit("minute"));
        $this->assertEquals("minute", \r8\DateTime::normalizeUnit("minutes"));
        $this->assertEquals("minute", \r8\DateTime::normalizeUnit("MiNuTeS"));

        $this->assertEquals("minute", \r8\DateTime::normalizeUnit("min"));
        $this->assertEquals("minute", \r8\DateTime::normalizeUnit("mins"));
        $this->assertEquals("minute", \r8\DateTime::normalizeUnit("MiNs"));

        $this->assertEquals("day", \r8\DateTime::normalizeUnit("day"));
        $this->assertEquals("day", \r8\DateTime::normalizeUnit("days"));
        $this->assertEquals("day", \r8\DateTime::normalizeUnit("DaYs"));

        $this->assertEquals("day", \r8\DateTime::normalizeUnit("mday"));
        $this->assertEquals("day", \r8\DateTime::normalizeUnit("mdays"));
        $this->assertEquals("day", \r8\DateTime::normalizeUnit("mDaYs"));

        $this->assertEquals("week", \r8\DateTime::normalizeUnit("week"));
        $this->assertEquals("week", \r8\DateTime::normalizeUnit("weeks"));
        $this->assertEquals("week", \r8\DateTime::normalizeUnit("WeEkS"));

        $this->assertEquals("month", \r8\DateTime::normalizeUnit("month"));
        $this->assertEquals("month", \r8\DateTime::normalizeUnit("months"));
        $this->assertEquals("month", \r8\DateTime::normalizeUnit("MoNtHs"));

        $this->assertEquals("month", \r8\DateTime::normalizeUnit("mon"));
        $this->assertEquals("month", \r8\DateTime::normalizeUnit("mon"));
        $this->assertEquals("month", \r8\DateTime::normalizeUnit("MoNs"));

        $this->assertEquals("year", \r8\DateTime::normalizeUnit("year"));
        $this->assertEquals("year", \r8\DateTime::normalizeUnit("years"));
        $this->assertEquals("year", \r8\DateTime::normalizeUnit("YeArS"));
    }

    public function testAdd_exceptions ()
    {
        $time = new \r8\DateTime;

        try {
            $time->add( 1, "second" );
            $this->fail("An expected exception was not thrown");
        }
        catch (\r8\Exception\Variable $err) {
            $this->assertEquals("No time has been set for this instance", $err->getMessage());
        }

        $time->setTimeStamp( 968655600 );

        try {
            $time->add( 1, "invalid unit" );
            $this->fail("An expected exception was not thrown");
        }
        catch (\r8\Exception\Argument $err) {
            $this->assertEquals("Invalid time unit", $err->getMessage());
        }
    }

    public function testAdd_add ()
    {
        $time = new \r8\DateTime;

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(0, \r8\DateTime::UNIT_SECONDS) );
        $this->assertEquals( 968655600, $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5, \r8\DateTime::UNIT_SECONDS) );
        $this->assertEquals( 968655600 + 5, $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5, \r8\DateTime::UNIT_MINUTES) );
        $this->assertEquals( 968655600 + (5 * 60), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5, \r8\DateTime::UNIT_HOURS) );
        $this->assertEquals( 968655600 + (5 * 60 * 60), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5, \r8\DateTime::UNIT_WEEKS) );
        $this->assertEquals( 968655600 + (5 * 60 * 60 * 24 * 7), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(2, \r8\DateTime::UNIT_MONTHS) );
        $this->assertEquals( 973926000, $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(2, \r8\DateTime::UNIT_YEARS) );
        $this->assertEquals( 1031727600, $time->getTimeStamp() );

    }

    public function testAdd_subtract ()
    {
        $time = new \r8\DateTime;

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-5, \r8\DateTime::UNIT_SECONDS) );
        $this->assertEquals( 968655600 - 5, $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-5, \r8\DateTime::UNIT_MINUTES) );
        $this->assertEquals( 968655600 - (5 * 60), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-5, \r8\DateTime::UNIT_HOURS) );
        $this->assertEquals( 968655600 - (5 * 60 * 60), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-5, \r8\DateTime::UNIT_WEEKS) );
        $this->assertEquals( 968655600 - (5 * 60 * 60 * 24 * 7), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-2, \r8\DateTime::UNIT_MONTHS) );
        $this->assertEquals( 963298800, $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-2, \r8\DateTime::UNIT_YEARS) );
        $this->assertEquals( 905497200, $time->getTimeStamp() );

    }

    public function testAdd_floats ()
    {
        $time = new \r8\DateTime;

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5.5, \r8\DateTime::UNIT_SECONDS) );
        $this->assertEquals( 968655600 + 5, $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-5.5, \r8\DateTime::UNIT_SECONDS) );
        $this->assertEquals( 968655600 - 5, $time->getTimeStamp() );



        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5.5, \r8\DateTime::UNIT_MINUTES) );
        $this->assertEquals( 968655600 + (5.5 * 60), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5.333, \r8\DateTime::UNIT_MINUTES) );
        $this->assertEquals( 968655600 + intval(5.333 * 60), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-5.75, \r8\DateTime::UNIT_MINUTES) );
        $this->assertEquals( 968655600 - intval(5.75 * 60), $time->getTimeStamp() );



        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5.5, \r8\DateTime::UNIT_HOURS) );
        $this->assertEquals( 968655600 + (5.5 * 60 * 60), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5.333, \r8\DateTime::UNIT_HOURS) );
        $this->assertEquals( 968655600 + intval(5.333 * 60 * 60), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-5.75, \r8\DateTime::UNIT_HOURS) );
        $this->assertEquals( 968655600 - intval(5.75 * 60 * 60), $time->getTimeStamp() );



        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5.5, \r8\DateTime::UNIT_WEEKS) );
        $this->assertEquals( 968655600 + (5.5 * 60 * 60 * 24 * 7), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(5.333, \r8\DateTime::UNIT_WEEKS) );
        $this->assertEquals( 968655600 + intval(5.333 * 60 * 60 * 24 * 7), $time->getTimeStamp() );

        $time->setTimeStamp( 968655600 );
        $this->assertSame( $time, $time->add(-5.75, \r8\DateTime::UNIT_WEEKS) );
        $this->assertEquals( 968655600 - intval(5.75 * 60 * 60 * 24 * 7), $time->getTimeStamp() );



        $time->setSQL( "2007-01-05 07:42:12" );
        $this->assertSame( $time, $time->add(2.5, \r8\DateTime::UNIT_MONTHS) );
        $this->assertEquals( "2007-03-20 19:42:12", $time->getSQL() );

        $time->setSQL( "2007-01-16 07:42:12" );
        $this->assertSame( $time, $time->add(2.333, \r8\DateTime::UNIT_MONTHS) );
        $this->assertEquals( "2007-03-26 15:27:19", $time->getSQL() );

        $time->setSQL( "2007-04-30 07:42:12" );
        $this->assertSame( $time, $time->add(-.75, \r8\DateTime::UNIT_MONTHS) );
        $this->assertEquals( "2007-04-07 01:42:12", $time->getSQL() );



        $time->setSQL( "2007-01-16 07:42:12" );
        $this->assertSame( $time, $time->add(2.5, \r8\DateTime::UNIT_YEARS) );
        $this->assertEquals( "2009-07-17 19:42:12", $time->getSQL() );

        $time->setSQL( "2007-01-16 07:42:12" );
        $this->assertSame( $time, $time->add(2.333, \r8\DateTime::UNIT_YEARS) );
        $this->assertEquals( "2009-05-17 20:47:00", $time->getSQL() );

        $time->setSQL( "2007-01-16 07:42:12" );
        $this->assertSame( $time, $time->add(-2.75, \r8\DateTime::UNIT_YEARS) );
        $this->assertEquals( "2004-04-16 19:42:12", $time->getSQL() );

    }

    public function testGet ()
    {
        $time = new \r8\DateTime;

        try {
            $time->get( "seconds" );
            $this->fail("An expected exception was not thrown");
        }
        catch (\r8\Exception\Variable $err) {
            $this->assertEquals("No time has been set for this instance", $err->getMessage());
        }

        $time->setTimeStamp( 1189612103 );

        $this->assertEquals(23, $time->get("seconds"));
        $this->assertEquals(48, $time->get("minutes"));
        $this->assertEquals(15, $time->get("hours"));
        $this->assertEquals(12, $time->get("day"));
        $this->assertEquals(12, $time->get("mday"));
        $this->assertEquals(3, $time->get("wday"));
        $this->assertEquals(3, $time->get("weekday"));
        $this->assertEquals(9, $time->get("month"));
        $this->assertEquals(9, $time->get("mon"));
        $this->assertEquals(2007, $time->get("year"));
        $this->assertEquals(254, $time->get("yday"));

    }


    public function testSet ()
    {
        $time = new \r8\DateTime;

        $time->setSQL( "2008-09-20 16:27:34" );

        $this->assertSame( $time, $time->set("seconds", 19) );
        $this->assertEquals( 19, $time->get("seconds") );
        $this->assertEquals( "2008-09-20 16:27:19", $time->getSQL() );

        $this->assertSame( $time, $time->set("minutes", 59) );
        $this->assertEquals( 59, $time->get("minutes") );
        $this->assertEquals( "2008-09-20 16:59:19", $time->getSQL() );

        $this->assertSame( $time, $time->set("hours", 13) );
        $this->assertEquals( 13, $time->get("hours") );
        $this->assertEquals( "2008-09-20 13:59:19", $time->getSQL() );

        $this->assertSame( $time, $time->set("day", 2) );
        $this->assertEquals( 2, $time->get("day") );
        $this->assertEquals( "2008-09-02 13:59:19", $time->getSQL() );

        $this->assertSame( $time, $time->set("month", 11) );
        $this->assertEquals( 11, $time->get("month") );
        $this->assertEquals( "2008-11-02 13:59:19", $time->getSQL() );

        $this->assertSame( $time, $time->set("year", 2006) );
        $this->assertEquals( 2006, $time->get("year") );
        $this->assertEquals( "2006-11-02 13:59:19", $time->getSQL() );
    }

    public function testSetTime ()
    {
        $time = new \r8\DateTime;

        $this->assertSame( $time, $time->setTime(12, 25, 13) );

        $this->assertEquals(12, $time->get("hours") );
        $this->assertEquals(25, $time->get("minutes") );
        $this->assertEquals(13, $time->get("seconds") );


        $time->setTimeStamp(1200958298);

        $this->assertSame( $time, $time->setTime(18, 13, 00) );

        $this->assertSame( "2008-01-21 18:13:00", $time->getSQL() );
    }

    public function testSetDate ()
    {
        $time = new \r8\DateTime;

        $this->assertSame( $time, $time->setDate(2008, 1, 21) );
        $this->assertSame( "2008-01-21 00:00:00", $time->getSQL() );


        $this->assertSame( $time, $time->setSQL( "2006-07-26 04:33:10" ) );

        $this->assertSame( $time, $time->setDate(2008, 1, 32) );
        $this->assertSame( "2008-02-01 04:33:10", $time->getSQL() );
    }

    public function testSetDateTime ()
    {
        $time = new \r8\DateTime;

        $this->assertSame( $time, $time->setDateTime(2008, 1, 21, 5, 16, 45) );
        $this->assertSame( "2008-01-21 05:16:45", $time->getSQL() );
    }

    public function testToStartOfDay ()
    {
        $time = new \r8\DateTime;

        $time->setSQL("2008-01-21 05:16:45");

        $this->assertSame( $time, $time->toStartOfDay() );
        $this->assertSame( "2008-01-21 00:00:00", $time->getSQL() );
    }

    public function testToEndOfDay ()
    {
        $time = new \r8\DateTime;

        $time->setSQL("2008-01-21 05:16:45");

        $this->assertSame( $time, $time->toEndOfDay() );
        $this->assertSame( "2008-01-21 23:59:59", $time->getSQL() );
    }

    public function testToEndOfMonth ()
    {
        $time = new \r8\DateTime;

        $time->setSQL("2008-01-21 05:16:45");

        $this->assertSame( $time, $time->toEndOfMonth() );
        $this->assertSame( "2008-01-31 05:16:45", $time->getSQL() );
    }

    public function testToStartOfMonth ()
    {
        $time = new \r8\DateTime;

        $time->setSQL("2008-01-21 05:16:45");

        $this->assertSame( $time, $time->toStartOfMonth() );
        $this->assertSame( "2008-01-01 05:16:45", $time->getSQL() );
    }

    public function testToNow ()
    {
        $time = new \r8\DateTime;

        $this->assertSame( $time, $time->toNow() );
        $this->assertGreaterThanOrEqual( time(), $time->getTimeStamp() );
        $this->assertLessThan( time() + 1, $time->getTimeStamp() );
    }

}

?>
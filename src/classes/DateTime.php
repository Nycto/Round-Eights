<?php
/**
 * File for the array object
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package DateTime
 */

namespace cPHP;

/**
 * Class for interacting with dates and times
 */
class DateTime
{

    /**
     * Standard date formats
     */
    const FORMAT_SQL_DATETIME = 'Y-m-d H:i:s';
    const FORMAT_SQL_DATE = 'Y-m-d';
    const FORMAT_DEFAULT = 'F j, Y, g:i a';

    /**
     * Date/time units for use with the math method
     */
    const UNIT_SECONDS = 'second';
    const UNIT_MINUTES = 'minute';
    const UNIT_HOURS = 'hour';
    const UNIT_DAYS = 'day';
    const UNIT_WEEKS = 'week';
    const UNIT_MONTHS = 'month';
    const UNIT_YEARS = 'year';

    /**
     * The default time format to use
     */
    static protected $defaultFormat = self::FORMAT_DEFAULT;

    /**
     * The unix timestamp this instance represents
     */
    protected $time;

    /**
     * When converting to a string, this is the format to use
     */
    protected $format;

    /**
     * Sets the default date/time format
     *
     * @param String $format The date format
     */
    static public function setDefaultFormat ($format)
    {
        self::$defaultFormat = ::cPHP::strval($format);
    }

    /**
     * Returns the current default date/time format
     *
     * @return String The default date format
     */
    static public function getDefaultFormat ()
    {
        return self::$defaultFormat;
    }

    /**
     * Normalizes a time unit string
     *
     * This will throw an Argumet exception if it can't parse the unit
     *
     * @param string $unit The string of a unit to normalize
     * @return string The normalized version
     */
    static public function normalizeUnit ( $unit )
    {

        $unit = strtolower( ::cPHP::stripW( $unit ) );
        $unit = ::cPHP::strStripTail( $unit, "s" );

        switch ( $unit ) {
            default:
                throw new ::cPHP::Exception::Argument(1, "Units", "Invalid time unit");

            case "second":
            case "minute":
            case "hour":
            case "day":
            case "week":
            case "month":
            case "year":
                return $unit;

            case "sec":
                return "second";

            case "min":
                return "minute";

            case "mday":
                return "day";

            case "mon":
                return "month";

        }
    }

    /**
     * Returns whether a string is a MySQL time stamp
     *
     * Accepts either YYYYMMDD, YYYYMMDDHHMMSS, YYYY-MM-DD HH:MM:SS, YYYY-MM-DD
     *
     * @param String $datetime A MySQL formatted date
     *      Example: 2007-08-16, 20070816, 2008-11-23 11:48:32, or 20081123114832
     * @return Boolean
     */
    static public function isSQL ( $datetime )
    {
        $datetime = ::cPHP::strval( $datetime );

        $year = '(?:[1-9][0-9]{3})';
        $month = '(?:0[0-9]|1[0-2])';
        $day = '(?:[0-2][0-9]|3[01])';

        $hour = '(?:[01][0-9]|2[0-3])';
        $min = '(?:[0-5][0-9])';
        $sec = '(?:[0-5][0-9])';

        $versions = array(
            $year . $month . $day,
            $year ."-". $month ."-". $day,
            $year . $month . $day . $hour . $min . $sec,
            $year ."-". $month ."-". $day ." ". $hour .":". $min .":". $sec
        );

        return preg_match('/^(?:'. implode('|', $versions) .')$/', $datetime) ? TRUE : FALSE;
    }

    /**
     * Constructor...
     *
     * If given a single argument, it will try and devise the best way to translate it to a time
     *
     * @param mixed $input
     */
    public function __construct ( $input = NULL )
    {
        if (func_num_args() > 0 && !::cPHP::is_vague($input) )
            $this->interpret( $input );

    }

    /**
     * Returns the time value currently held in this instance
     *
     * @return Null|Integer Returns NULL if no time has been set yet
     */
    public function getTimeStamp ()
    {
        if ( isset($this->time) )
            return $this->time;
        else
            return NULL;
    }

    /**
     * Sets the time value in this instance from a unix timestamp
     *
     * @param Integer $timestamp
     * @return object Returns a self reference
     */
    public function setTimeStamp ( $timestamp )
    {
        $this->time = intval($timestamp);
        return $this;
    }

    /**
     * Sets the time from an array
     *
     * The following keys are accepted:
     * 'seconds', 'minutes', 'hours', 'mday', 'mon', 'year'
     *
     * These keys are defined by the getdate() php function
     *
     * @param Array|Object An array containing
     * @return object Returns a self reference
     */
    public function setArray ( $time )
    {

        $time = new ::cPHP::Ary( $time );

        $time = $time->changeKeyCase()
            ->translateKeys(array(
                    "day" => "mday",
                    "month" => "mon"
                ))
            ->hone('seconds', 'minutes', 'hours', 'mday', 'mon', 'year');

        $time = $time->get();

        if ( isset($this->time) )
            $time += getdate( $this->time );

        // Fill in the blanks
        $time += getdate();

        $this->time =
            mktime(
                    $time['hours'],
                    $time['minutes'],
                    $time['seconds'],
                    $time['mon'],
                    $time['mday'],
                    $time['year']

                );

        return $this;
    }

    /**
     * Returns an array version of this date
     *
     * The returned value is per the getdate() array format.
     *
     * Note that this will throw a cPHP::Exception::Variable if this instance doesn't contain a time
     *
     * @return Array Returns an array as "getdate()" would
     */
    public function getArray ()
    {
        if ( !isset($this->time) )
            throw new ::cPHP::Exception::Variable('time', 'No time has been set for this instance');
        return getdate( $this->time );
    }

    /**
     * Sets the value for this instance from a SQL date/datetime string
     *
     * @param String $datetime A SQL formatted date
     *      YYYYMMDDHHMMSS, YYYY-MM-DD HH:MM:SS, YYYYMMDD, or YYYY-MM-DD
     *      Example: 2007-08-16, 20070816, 2008-11-23 11:48:32, or 20081123114832
     * @return object Returns a self reference
     */
    public function setSQL ( $datetime )
    {
        $datetime = ::cPHP::stripW($datetime);

        if ( !self::isSQL($datetime) )
            throw new ::cPHP::Exception::Argument(0, "SQL Date/Time", "Invalid SQL date time");

        $result = preg_match(
                '/^'
                .'([1-9][0-9]{3})' // Year
                .'(0[0-9]|1[0-2])' // Month
                .'([0-2][0-9]|3[01])' // Day
                .'(?:'
                    .'([01][0-9]|2[0-3])' // Hour
                    .'([0-5][0-9])' // Minutes
                    .'([0-5][0-9])' // Seconds
                .')?' // time is optional
                .'$/',
                $datetime,
                $parsed
            );

        // Unset the "original value" offset
        unset ( $parsed[0] );

        // If the time is missing from the stamp, fill it in with 0s
        if ( count($parsed) < 6 )
            $parsed = array_merge( $parsed, array_fill(0, 6 - count($parsed), 0) );

        // We reverse it so that it is compatible with the order that setArray takes
        $parsed = array_reverse( $parsed );

        return $this->setArray( $parsed );
    }

    /**
     * Returns a MySQL formatted version of this date
     *
     * The returned value is of the format: YYYY-MM-DD HH:MM:SS
     *
     * @return string
     */
    public function getSQL ()
    {
        return $this->getFormatted( self::FORMAT_SQL_DATETIME );
    }

    /**
     * Sets the timestamp from a string representation of a date
     *
     * @param String $string The string to interpret
     * @return object Returns a self reference
     */
    public function setString ( $string )
    {
        $string = strtotime( ::cPHP::strval( $string ) );
        if ($string === FALSE)
            throw new ::cPHP::Exception::Argument(0, "Date/Time String", "Unable to parse string to a valid time");
        return $this->setTimeStamp( $string );
    }

    /**
     * Returns the format string.
     *
     * If none has been explicitly defined, it will pull the default format
     *
     * @return String
     */
    public function getFormat ()
    {
        if (isset($this->format) )
            return $this->format;
        else
            return self::getDefaultFormat();
    }

    /**
     * Sets the format string
     *
     * @param String $format The format for this instance
     * @return object Returns a self reference
     */
    public function setFormat( $format )
    {
        $this->format = ::cPHP::strval( $format );
        return $this;
    }

    /**
     * Removes the formatting specific to this instance
     *
     * @return object Returns a self reference
     */
    public function clearFormat ()
    {
        $this->format = null;
        return $this;
    }

    /**
     * Returns a formatted version of this time
     *
     * @param $format String|Vague If vague, it uses the default format. Otherwise, it uses the custom string
     * @return String
     */
    public function getFormatted ($format = FALSE)
    {
        if ( !isset($this->time) )
            throw new ::cPHP::Exception::Variable('time', 'No time has been set for this instance');

        if ( is_vague($format) )
            $format = $this->getFormat();
        else
            $format = ::cPHP::strval( $format );

        return date( $format, $this->time );
    }

    /**
     * Return a readable representation of this time
     *
     * @return string
     */
    public function __toString ()
    {
        try {
            return $this->getFormatted();
        }
        // This will be thrown if no time is currently set in this instance.
        // We catch it because toString isn't allowed to throw exceptions
        catch ( ::cPHP::Exception::Variable $err ) {
            return "";
        }
    }

    /**
     * Attempts to determine the best way to convert an input to a date/time
     *
     * It checks for the following (in this order):
     *  - If an integer, it sets it as a unix timestamp
     *  - If an instance of cDateTime, it pulls the data from that instance
     *  - if an array, it works similarly to mktime
     *  - It then converts the value to a string and checks...
     *  - if it is a MySQL date/datetime
     *  - if numeric, it sets it as a unix timestamp
     *  - otherwise, it interprets it via strtotime
     *
     * @param mixed $input The input value to interpret
     * @return object Returns a self reference
     */
    public function interpret ( $input )
    {

        // If it is an integer, set it as a unix timestamp
        if (is_int($input) || is_float($input) ) {
            $this->time = intval( $input );
        }

        else if ($input instanceof cDateTime) {
            $this->setTimeStamp( $input->getTimeStamp() );
        }

        else if ( is_array($input) ){
            $this->setArray( $input );
        }

        else {

            $input = ::cPHP::strval( $input );

            if ( self::isSQL( $input ) )
                $this->setSQL( $input );

            else if ( is_numeric($input) )
                $this->setTimeStamp( $input );

            else
                $this->setString( $input );

        }

        return $this;
    }

    /**
     * Adds or subtracts amounts of time from the current value
     *
     * @param Integer|Float $value The value to add to this instance
     * @param String $unit The units of the value to add
     * @return object Returns a self reference
     */
    public function add ( $value, $unit )
    {
        if ( !isset($this->time) )
            throw new ::cPHP::Exception::Variable('time', 'No time has been set for this instance');

        $value = ::cPHP::numval( $value );

        $unit = ::cPHP::DateTime::normalizeUnit($unit);

        if ( $value == 0 )
            return $this;

        switch ( $unit ) {

            default:
                throw new ::cPHP::Exception::Argument(1, "Units", "Invalid time unit");

            case self::UNIT_MONTHS:
                $unit = "mon";

            case self::UNIT_YEARS:

                $ary = $this->getArray();
                $ary[ $unit ] += intval( $value );

                $this->setArray( $ary );

                // If there is a fraction involved, we need to calculate the length of the unit
                // Because the length of these particular units changes,
                // we do it relative to the current value.
                if ( intval($value) != $value ) {

                    $copy = clone $this;

                    // Add a whole unit to the current time
                    $copy->add(
                            1 * ( $value < 0 ? -1 : 1 ),
                            $unit
                        );

                    // Find the difference of adding a hole unit in seconds
                    $diff = abs( $copy->getTimeStamp() - $this->getTimeStamp() );

                    // Multiply it by the decimal of the original value to
                    $diff *= $value - intval($value);

                    // Then add that many seconds on to the current date
                    $this->add($diff, self::UNIT_SECONDS);
                }

                break;


            // Notice the lack of "breaks" in the next few cases. This allows
            // the units to cascade down until they are converted to seconds

            case self::UNIT_WEEKS:
                $value *= 7;

            case self::UNIT_DAYS:
                $value *= 24;

            case self::UNIT_HOURS:
                $value *= 60;

            case self::UNIT_MINUTES:
                $value *= 60;

            case self::UNIT_SECONDS:
                $this->time += intval( $value );
                break;
        }

        return $this;
    }

    /**
     * Returns a specific unit from the current time value
     *
     * @param string $unit The unit to return
     * @return Integer
     */
    public function get ( $unit )
    {
        if ( !isset($this->time) )
            throw new ::cPHP::Exception::Variable('time', 'No time has been set for this instance');

        try {
            $unit = self::normalizeUnit( $unit );
        }
        catch ( ::cPHP::Exception::Argument $err ) {}

        $ary = $this->getArray();

        switch ( $unit ) {
            default:
                throw new ::cPHP::Exception::Argument(0, "Unit", "Invalid time unit");
            case "second":
                return $ary['seconds'];
            case "minute":
                return $ary['minutes'];
            case "hour":
                return $ary['hours'];
            case "day":
                return $ary['mday'];
            case "weekday":
            case "wday":
                return $ary['wday'];
            case "month":
                return $ary['mon'];
            case "year":
                return $ary['year'];
            case "yday":
                return $ary['yday'];
        }

    }

    /**
     * Generalized function for setting values
     *
     * @param string $unit The time unit to set
     * @param Integer $value The new value for this part of the time
     * @return object Returns a self reference
     */
    public function set ( $unit, $value )
    {
        $value = intval( $value );

        switch ( self::normalizeUnit( $unit ) ) {

            default:
                throw new ::cPHP::Exception::Argument(0, "Unit", "Invalid time unit");

            case "second":
                return $this->setArray( array('seconds' => $value) );

            case "minute":
                return $this->setArray( array('minutes' => $value) );

            case "hour":
                return $this->setArray( array('hours' => $value) );

            case "day":
                return $this->setArray( array('mday' => $value) );

            case "month":
                return $this->setArray( array('mon' => $value) );

            case "year":
                return $this->setArray( array('year' => $value) );

        }

    }

    /**
     * updates just the Time part of the date
     *
     * If this is run without a time currently set, the current year, month and day will be used
     *
     * @param Integer $hours The new hours to set
     * @param Integer $minutes The new minutes
     * @param Integer $seconds The new seconds
     * @return object Returns a self reference
     */
    public function setTime ( $hours, $minutes, $seconds )
    {
        return $this->setArray(array(
                "hours" => intval( $hours ),
                "minutes" => intval( $minutes ),
                "seconds" => intval( $seconds ),
            ));
    }

    /**
     * Updates just the date part of the timestamp
     *
     * If this is run without a time currently in the instance, time will be set to 00:00:00
     *
     * @param Integer $year The new year
     * @param Integer $month The new month
     * @param Integer $day The new day
     * @return object Returns a self reference
     */
    public function setDate ( $year, $month, $day )
    {
        $ary = array(
                "year" => intval( $year ),
                "month" => intval( $month),
                "day" => intval( $day),
            );

        if ( !isset($this->time) )
            $ary += array( "hours" => 0, "minutes" => 0, "seconds" => 0 );

        return $this->setArray( $ary );

    }

    /**
     * Similar to mktime, sets the time from a list of input
     *
     * @param Integer $year The new year
     * @param Integer $month The new month
     * @param Integer $day The new day
     * @param Integer $hours The new hours to set
     * @param Integer $minutes The new minutes
     * @param Integer $seconds The new seconds
     * @return object Returns a self reference
     */
    public function setDateTime ( $year, $month, $day, $hour, $minutes, $seconds )
    {
        $this->time = mktime(
                intval( $hour ),
                intval( $minutes ),
                intval( $seconds ),
                intval( $month ),
                intval( $day ),
                intval( $year )
            );

        return $this;
    }

    /**
     * Sets the time to the start of the day
     *
     * Sets the time to 00:00:00 without changing the date
     *
     * @return object Returns a self reference
     */
    public function toStartOfDay ()
    {
        return $this->setTime(0,0,0);
    }

    /**
     * Sets the time to the End of the day
     *
     * Sets the time to 23:59:59 without changing the date
     *
     * @return object Returns a self reference
     */
    public function toEndOfDay ()
    {
        return $this->setTime(23, 59, 59);
    }

    /**
     * Sets the day to the last day of the month
     *
     * Does not affect the time
     *
     * @return object Returns a self reference
     */
    public function toEndOfMonth ()
    {
        $date = $this->getArray();
        return $this->setDate($date['year'], $date['mon'] + 1, 0 );
    }

    /**
     * Sets the day to the first day of the month
     *
     * Does not affect the time
     *
     * @return object Returns a self reference
     */
    public function toStartOfMonth ()
    {
        return $this->set("day", 1);
    }

    /**
     * Sets the time to now
     *
     * @return object Returns a self reference
     */
    public function toNow()
    {
        $this->time = time();
        return $this;
    }

}

?>
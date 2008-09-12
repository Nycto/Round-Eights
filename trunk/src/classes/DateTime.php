<?php
/**
 * File for the array object
 *
 * @package Array
 */

namespace cPHP;

/**
 * Class for interacting with dates and times
 */
class DateTime
{

    /**
     * The default time format to use
     */
    static protected $defaultFormat = 'F j, Y, g:i a';

    /**
     * The unix timestamp this instance represents
     */
    protected $time;

    /**
     * When converting to a string, this is the format to use
     */
    protected $format;

    /**
     * Returns whether a string is a MySQL time stamp
     *
     * Accepts either YYYYMMDD, YYYYMMDDHHMMSS, YYYY-MM-DD HH:MM:SS, YYYY-MM-DD
     *
     * @param String $datetime A MySQL formatted date
     *      Example: 2007-08-16, 20070816, 2008-11-23 11:48:32, or 20081123114832
     * @return Boolean
     */
    static public function isMySQL ( $datetime )
    {
        $datetime = cPHP::strval( $datetime );

        if (strlen($datetime) != 8 && strlen($datetime) != 14)
            return FALSE;

        return preg_match(
                '/^'
                .'[1-9][0-9]{3}' // Year
                .'(?:0[0-9]|1[0-2])' // Month
                .'(?:[0-2][0-9]|3[01])' // Day
                .'(?:'
                    .'(?:[01][0-9]|2[0-3])' // Hour
                    .'(?:[0-5][0-9]){2}' // Minute and Second
                .')?' // option time
                .'$/',
                $datetime
            )
            ? TRUE : FALSE;
    }

    /**
     * Sets the default date/time format
     */
    static public function setFormat ($format)
    {
        self::$defaultFormat = stringVal($format);
    }

    /**
     * Returns the default format
     */
    static public function getFormat ()
    {
        return self::$defaultFormat;
    }

    /**
     * Translates a string to it's getdate() equivilent
     *
     * @param String $string A string to normalize
     * @return String|Boolean Returns the normalized string on success, FALSE if the string could not be translated
     */
    static public function normalizeTimePart ( $string )
    {

        $string = strStripTail( stripW( strtolower($string) ), "s" );

        $map = array(
            'second' => 'seconds',
            'sec' => 'seconds',

            'minute' => 'minutes',
            'min' => 'minutes',

            'hour' => 'hours',

            'day' => 'mday',

            'month' => 'mon',
            'mon' => 'mon',

            'year' => 'year'
        );

        if ( !array_key_exists($string, $map) )
            return FALSE;
        else
            return $map[ $string ];
    }

    /**
     * Constructor
     *
     * If given a single argument, it will try and devise the best way to translate it to a time
     *
     * If given multiple arguments, it acts like mktime
     */
    public function __construct ( )
    {
        parent::__construct();

        if (func_num_args() == 1) {

            $source = func_get_arg( 0 );

            if (!is_vague($source))
                $this->__set('time', $source);

        }
        else if ( func_num_args() > 1 ) {
            $source = func_get_args();
            $source = array_hone(
                    $source,
                    // These are ordered the same as the mktime arguments
                    'hours', 'minutes', 'seconds', 'mon', 'mday', 'year'
                );
            $this->__set('array', $source);
        }
    }

    /**
     * Apply accessor rules
     */
    public function applySetGet ()
    {
        $this->variable_rw('time', 'format');
        $this->variable_cast('string', 'format');
    }

    /**
     * Generalized function for setting values
     */
    public function _set ( $variable, $value )
    {
        if (is_empty($this->time))
            throw new VariableError('time', 'No time has been set for this instance');

        $variable = self::normalizeTimePart($variable);

        if ( !$variable )
            throw new VariableError($variable, "Could not set variable, invalid time part");

        $array = $this->get_array();

        $array[ $variable ] = intval( numberVal( $value ) );

        $this->set_array( $array );

        return TRUE;
    }
    /**
     * Generalized function for getting values
     */
    public function _get ( $variable )
    {
        if (is_empty($this->time))
            throw new VariableError('time', 'No time has been set for this instance');

        $variable = self::normalizeTimePart($variable);

        if ( !$variable )
            throw new VariableError($variable, "Could not set variable, invalid time part");

        $array = $this->get_array();

        return $array[ $variable ];
    }

    /**
     * Sets the time... attempts to detect the best way to do this
     *
     * It checks for the following (in this order):
     *  - If an integer, it sets it as a unix timestamp
     *  - If an instance of cDateTime, it pulls the data from that instance
     *  - if an array, it works similarly to mktime
     *  - It then converts the value to a string and checks...
     *  - if it is a MySQL date/datetime
     *  - if numeric, it sets it as a unix timestamp
     *  - otherwise, it interprets it via strtotime
     */
    public function set_time ( $time )
    {

        // If it is an integer, set it as a unix timestamp
        if (is_int($time))
            $this->set_unixTimeStamp( $time );

        else if ($time instanceof cDateTime)
            $this->set_unixTimeStamp( $time->time );

        else if (is_array($time))
            $this->set_array( $time );

        else {

            $time = stringVal($time);

            if (self::isMySQL($time))
                $this->set_MySQL( $time );

            else if (is_numeric($time))
                $this->set_unixTimeStamp( $time );

            else
                $this->set_string( $time );

        }

    }

    /**
     * Sets this instance from a unix timestamp
     */
    public function set_unixTimeStamp ( $time )
    {
        $this->time = intval( numberVal($time) );
    }

    /**
     * Sets the time to now
     */
    public function now()
    {
        return $this->__set('unixTimeStamp', time());
    }

    /**
     * Sets the time from an array
     *
     * The following keys are accepted:
     * 'seconds', 'minutes', 'hours', 'mday', 'mon', 'year'
     *
     * These keys are defined by the getdate() php function
     */
    public function set_array ( $time )
    {
        $time = array_hone(
                $time,
                'seconds', 'minutes', 'hours', 'mday', 'mon', 'year'
            );
        // Fill in the blanks
        $time = array_merge( getdate(), $time );
        $this->set_unixTimeStamp(
                mktime(
                        $time['hours'],
                        $time['minutes'],
                        $time['seconds'],
                        $time['mon'],
                        $time['mday'],
                        $time['year']
                    )
            );
    }

    /**
     * Returns an array version of this date
     *
     * The returned value is per the getdate() array format
     *
     * @return Array Returns an array as "getdate()" would
     */
    public function get_array ()
    {
        if (is_empty($this->time))
            throw new VariableError('time', 'No time has been set for this instance');
        return getdate( $this->time );
    }

    /**
     * Sets the value for this instance from a MySQL date/datetime string
     *
     * @param String $datetime A MySQL formatted date
     *      Example: 2007-08-16, 20070816, 2008-11-23 11:48:32, or 20081123114832
     */
    public function set_MySQL ( $datetime )
    {
        $datetime = stripW($datetime);

        $result = preg_match(
                '/^'
                .'([1-9][0-9]{3})' // Year
                .'(0[0-9]|1[0-2])' // Month
                .'([0-2][0-9]|3[01])' // Day
                .'(?:'
                    .'([01][0-9]|2[0-3])' // Hour
                    .'([0-5][0-9])' // Minutes
                    .'([0-5][0-9])' // Seconds
                .')?' // option time
                .'$/',
                $datetime,
                $parsed
            );

        if (!$result)
            throw new ArgumentError(0, "MySQL date/datetime", "Invalid MySQL date time string. Must be YYYYMMDDHHMMSS or YYYYMMDD");

        // Unset the "original value" offset
        unset ( $parsed[0] );

        if ( count($parsed) < 6 )
            $parsed = array_merge( $parsed, array_fill(0, 6 - count($parsed), 0) );

        $parsed = array_reverse( $parsed );

        $this->set_array( $parsed );
    }

    /**
     * Returns a MySQL formatted version of this date
     *
     * The returned value is per the getdate() array format
     */
    public function get_MySQL ()
    {
        if (is_empty($this->time))
            throw new VariableError('time', 'No time has been set for this instance');
        return date( "Y-m-d H:i:s", $this->time );
    }

    /**
     * Sets the value from a string
     */
    public function set_string ( $string )
    {
        $string = stringVal( $string );
        $string = strtotime( $string );
        if ($string === FALSE)
            throw new ArgumentError(0, "Date/Time String", "Unable to parse this string to a valid time");
        $this->set_unixTimeStamp( $string );
    }

    /**
     * updates just the Time part of the date
     *
     * If this is run without a time currently set, the current year, month and day will be used
     *
     * @param Integer $hours The new hours to set
     * @param Integer $minutes The new minutes
     * @param Integer $seconds The new seconds
     */
    public function setTime ( $hours, $minutes, $seconds )
    {

        // If no time is currently set, use the current year/month/day
        $date = is_empty($this->time) ? getdate() : $this->get_array();

        $this->__set(
                'unixTimeStamp',
                mktime(
                        intval( numberVal( $hours ) ),
                        intval( numberVal( $minutes ) ),
                        intval( numberVal( $seconds ) ),
                        $date['mon'], $date['mday'], $date['year']
                    )
            );
    }

    /**
     * Updates just the date part of the timestamp
     *
     * If this is run without a time currently in the instance, time will be set to 00:00:00
     *
     * @param Integer $year The new year
     * @param Integer $month The new month
     * @param Integer $day The new day
     */
    public function setDate ( $year, $month, $day )
    {
        if (is_empty($this->time)) {
            $this->__set(
                    'unixTimeStamp',
                    mktime(
                            0, 0, 0,
                            intval( numberVal( $month ) ),
                            intval( numberVal( $day ) ),
                            intval( numberVal( $year ) )
                        )
                );
        }
        else {
            $this->__set(
                    'unixTimeStamp',
                    mktime(
                            $this->_get('hour'), $this->_get('minute'), $this->_get('second'),
                            intval( numberVal( $month ) ),
                            intval( numberVal( $day ) ),
                            intval( numberVal( $year ) )
                        )
                );
        }

    }

    /**
     * Applies the given date interval to this string
     *
     * Uses strtotime's notation for date math
     */
    public function math ( $interval )
    {
        if (is_empty($this->time))
            throw new VariableError('time', 'No time has been set for this instance');
        $interval = strtotime( $interval, $this->time );
        if ($interval === FALSE)
            throw new ArgumentError(0, "Date/Time Interval", "Unable to parse this string to a valid time");
        $this->set_unixTimeStamp( $interval );
    }

    /**
     * Returns the format string.
     *
     * If none has been explicitly defined, it will pull the default string
     */
    public function get_format ()
    {
        if (isset($this->format))
            return $this->format;
        else
            return self::getFormat();
    }

    /**
     * Return a readable representation of this time
     */
    public function __toString ()
    {
        if (is_empty($this->time))
            throw new VariableError('time', 'No time has been set for this instance');
        return date( $this->__get('format'), $this->time );
    }

    /**
     * Returns a formatted version of this time
     *
     * @param $format String|Vague If vague, it uses the default format. Otherwise, it uses the custom string
     */
    public function format ($format = FALSE)
    {
        if (is_empty($this->time))
            throw new VariableError('time', 'No time has been set for this instance');
        return date( is_vague($format)?$this->__get('format'):stringVal($format), $this->time );
    }

    /**
     * Sets the time to the start of the day
     *
     * Sets the time to 00:00:00 without changing the date
     */
    public function toStartOfDay ()
    {
        $this->setTime(0,0,0);
    }

    /**
     * Sets the time to the End of the day
     *
     * Sets the time to 23:59:59 without changing the date
     */
    public function toEndOfDay ()
    {
        $this->setTime(23, 59, 59);
    }

    /**
     * Sets the day to the last day of the month
     *
     * Does not affect the time
     */
    public function toEndOfMonth ()
    {
        $date = $this->get_array();
        $this->setDate($date['year'], $date['mon'] + 1, 0 );
    }
}

?>
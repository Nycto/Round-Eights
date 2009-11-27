<?php
/**
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
 * @package Backtrace
 */

namespace r8\Backtrace\Event;

/**
 * Callable Backtrace Events
 */
abstract class Call extends \r8\Backtrace\Event
{

    /**
     * The line the call was made on
     *
     * @var Integer
     */
    private $line;

    /**
     * The arguments passed to this call
     *
     * @var Array
     */
    private $args;

    /**
     * Constructor...
     *
     * @param String $file The file the event occurred within
     * @param Integer $line The line the call was made on
     * @param Array $args The arguments passed in to this call
     */
    public function __construct ( $file, $line, array $args )
    {
        parent::__construct( $file );

        $line = (int) $line;

        if ( $line <= 0 )
            throw new \r8\Exception\Argument( 2, "Line Number", "Must be greater than 0" );

        $this->line = $line;
        $this->args = $args;
    }

    /**
     * Returns the Line the call was made on
     *
     * @return Integer
     */
    public function getLine ()
    {
        return $this->line;
    }

    /**
     * Returns the Arguments passed in on this call
     *
     * @return Array
     */
    public function getArgs ()
    {
        return $this->args;
    }

}

?>
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
 * Object based function call Backtrace Events
 */
abstract class Object extends \r8\Backtrace\Event\Named
{

    /**
     * The name of the class this function is a member of
     *
     * @var String
     */
    private $class;

    /**
     * Constructor...
     *
     * @param String $class The name of the class this function is a member of
     * @param String $name The name of this function
     * @param String $file The file the event occurred within
     * @param Integer $line The line the call was made on
     * @param Array $args The arguments passed in to this call
     */
    public function __construct ( $class, $name, $file, $line, array $args )
    {
        parent::__construct( $name, $file, $line, $args );

        $class = trim( (string) $class );

        if ( empty($class) )
            throw new \r8\Exception\Argument( 0, "Class Name", "Must not be empty" );

        $this->class = $class;
    }

    /**
     * Returns the name of the class this function is a member of
     *
     * @return String
     */
    public function getClass ()
    {
        return $this->class;
    }

}

?>
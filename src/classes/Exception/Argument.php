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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace r8\Exception;

/**
 * Exception class to handle bad arguments
 */
class Argument extends \r8\Exception
{

    /**
     * The title of this exception
     */
    const TITLE = "Argument Error";

    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors caused by faulty arguments";

    /**
     * The offset of the argument at fault
     *
     * @var Integer
     */
    private $arg;

    /**
     * Constructor
     *
     * @param Integer $arg The argument offset (from 0) that caused the error
     * @param String $label The name of the argument
     * @param String $message The error message
     * @param Integer $code The error code
     */
    public function __construct( $arg, $label = NULL, $message = NULL, $code = 0 )
    {
        parent::__construct( $message, $code );

        $this->arg = (int) $arg;

        if ( !\r8\isVague($label) )
            $this->addData("Arg Label", $label);
    }

    /**
     * Get the integer offset of the problem integer
     *
     * @return Integer|NULL Returns null if the argument isn't set
     */
    public function getArgOffset ()
    {
        try {
            return \r8\ary\calcOffset(
                $this->getTraceByOffset(0)->getArgs(),
                $this->arg,
                \r8\ary\OFFSET_RESTRICT
            );
        }
        catch ( \r8\Exception\Index $err ) {
            return NULL;
        }
    }

    /**
     * Get the value of the argument at fault
     *
     * @return mixed
     */
    public function getArgData ()
    {
        return $this->getTraceByOffset(0)->getArg( $this->arg );
    }

    /**
     * Returns the data list for the current instance
     *
     * @return Array
     */
    public function getData ()
    {
        return array(
                "Arg Offset" => $this->getArgOffset(),
                "Arg Value" => \r8\getDump( $this->getArgData() )
            )
            + parent::getData();
    }

}


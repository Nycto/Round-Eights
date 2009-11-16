<?php
/**
 * Exception Class
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
 * @package Exception
 */

namespace r8\Exception;

/**
 * Exception class for data that is not what it should be
 */
class Index extends \r8\Exception
{

    /**
     * The title of this exception
     */
    const TITLE = "Index Error";

    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors relating to list indexes";

    /**
     * Constructor
     *
     * @param String $value The value of the data that caused the error
     * @param String $label The name of the data
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct($value, $label = NULL, $message = NULL, $code = 0, $fault = NULL)
    {
        parent::__construct($message, $code, $fault);

        $this->addData( "Index Label", \r8\strval( $label ) );
        $this->addData( "Value", \r8\getDump($value) );
    }
}

?>
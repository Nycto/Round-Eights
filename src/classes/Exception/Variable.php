<?php
/**
 * Exception Class
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace cPHP\Exception;

/**
 * Exception class for errors caused by variables, but not necissarily their data
 */
class Variable extends \cPHP\Exception
{

    /**
     * The title of this exception
     */
    const TITLE = "Variable Error";

    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors caused by variables, not necissarily their data";

    /**
     * Constructor
     *
     * @param String $variable The name of the variable that caused the error
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct($variable, $message = NULL, $code = 0, $fault = NULL)
    {
        parent::__construct($message, $code, $fault);

        $this->addData(
                "Variable Name",
                \cPHP\strval( $variable )
            );
    }

}

?>
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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace r8\Exception;

/**
 * Exception class for errors caused by variables, but not necissarily their data
 */
class Variable extends \r8\Exception
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
     */
    public function __construct ( $variable, $message = NULL, $code = 0 )
    {
        parent::__construct($message, $code);
        $this->addData( "Variable Name", (string) $variable );
    }

}

?>
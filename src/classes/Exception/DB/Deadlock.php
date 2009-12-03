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

namespace r8\Exception\DB;

/**
 * Exception class for database queries
 */
class Deadlock extends \r8\Exception\DB
{

    /**
     * Title of this exception
     */
    const TITLE = "Database Query Deadlock";

    /**
     * A brief description of this exception
     */
    const DESCRIPTION = "Thrown when a query experiences a deadlock";

    /**
     * Constructor...
     *
     * @param String $query The query that caused the deadlock
     * @param String $message The error message
     * @param Integer $code The error code
     * @param mixed $link The database Link associated with this error
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct ( $query, $message = NULL, $code = 0, $link = null, $fault = NULL )
    {
        parent::__construct( $message, $code, $link, $fault );
        $this->addData("Query", $query);
    }

}

?>
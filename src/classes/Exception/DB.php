<?php
/**
 * Exception Class
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace h2o\Exception;

/**
 * Exception class for database errors
 */
class DB extends \h2o\Exception
{

    /**
     * Title of this exception
     */
    const TITLE = "Database Error";

    /**
     * A brief description of this exception
     */
    const DESCRIPTION = "Database related errors";

    /**
     * Constructor...
     *
     * @param String $query The query that caused the error
     * @param String $message The error message
     * @param Integer $code The error code
     * @param mixed $link The database Link associated with this error
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct ( $message = NULL, $code = 0, $link = null, $fault = NULL )
    {
        parent::__construct( $message, $code, $fault );

        if ( $link instanceof \h2o\DB\LinkWrap )
            $link = $link->getTopLink();

        if ( $link instanceof \h2o\DB\Link )
            $this->addData( 'Link', $link->getIdentifier() );
        else
            $this->addData( 'Link', \h2o\getDump($link) );
    }

}

?>
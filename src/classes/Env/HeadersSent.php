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
 * @package Env
 */

namespace r8\Env;

/**
 * Collects information about the current request and allows readonly access to it
 */
class HeadersSent extends \r8\Exception
{

    /**
     * The title of this exception
     */
    const TITLE = "HTTP Headers Sent";

    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Thrown when trying to set HTTP headers after they have been sent";

    /**
     * Constructor...
     *
     * @param String $file The file that caused the headers to be sent
     * @param Integer $line The line that caused the headers to be sent
     */
    public function __construct ( $file, $line )
    {
        parent::__construct("HTTP Headers have already been sent");
        $this->addData("Output Started in File", (string) $file);
        $this->addData("Output Started on Line", (int) $line);
    }

}

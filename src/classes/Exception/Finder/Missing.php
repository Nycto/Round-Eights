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

namespace r8\Exception\Finder;

/**
 * Exception class for when a Finder can't locate a file
 */
class Missing extends \r8\Exception
{

    /**
     * The title of this exception
     */
    const TITLE = "Missing Finder Error";

    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "A Finder was unable to locate a file";

    /**
     * Constructor...
     *
     * @param String $path The path that the finder was looking for
     * @param \r8\Finder\Tracker $tracker The details of the Find atemp
     */
    public function __construct ( $path, \r8\Finder\Tracker $tracker )
    {
        parent::__construct( "Finder was unable to locate file", 0, -1 );
        $this->addData( "Search Path", (string) $path );
        $this->addData( "Tested Paths", $tracker->getTested() );
    }

}

?>
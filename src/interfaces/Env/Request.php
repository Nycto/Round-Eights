<?php
/**
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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Env
 */

namespace h2o\iface\Env;

/**
 * Defines an interface for accessing information about the current request
 */
interface Request
{

    /**
     * Returns the data posted by the client
     *
     * @return array
     */
    public function getPost ();

    /**
     * Returns the query string parsed as an array
     *
     * @return array
     */
    public function getGet ();

    /**
     * Returns the list of files uploaded by the client
     *
     * @return array
     */
    public function getFiles ();

    /**
     * Returns the URL the client requested
     *
     * @return \h2o\URL
     */
    public function getURL ();

    /**
     * Returns the file requested by the client, in the context of the file system
     *
     * @return \h2o\FileSys\File
     */
    public function getFile ();

    /**
     * Returns whether this request was made via command line
     *
     * @return Boolean
     */
    public function isCLI ();

    /**
     * Returns whether this request was made over a secure connection
     *
     * @return Boolean
     */
    public function isSecure ();

    /**
     * Returns the request headers
     *
     * @return array
     */
    public function getHeaders ();

}

?>
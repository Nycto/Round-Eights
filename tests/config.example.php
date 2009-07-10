<?php
/**
 * Unit Test configuration file
 *
 * Copy this file to config.php and customize the settings
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
 */

define ( "MYSQLI_HOST", "localhost" );
define ( "MYSQLI_PORT", 3306 );
define ( "MYSQLI_DATABASE", "test" );
define ( "MYSQLI_USERNAME", "phpunit" );
define ( "MYSQLI_PASSWORD", FALSE );
define ( "MYSQLI_TABLE", "h2o_Test_Table" );

define ( "MEMCACHE_HOST", "127.0.0.1" );
define ( "MEMCACHE_PORT", 11211 );

?>
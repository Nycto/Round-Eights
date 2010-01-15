<?php
/**
 * Unit Test configuration file
 *
 * Copy this file to config.php and customize the settings
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
 */

define ( "MYSQLI_HOST", "localhost" );
define ( "MYSQLI_PORT", 3306 );
define ( "MYSQLI_DATABASE", "test" );
define ( "MYSQLI_USERNAME", "phpunit" );
define ( "MYSQLI_PASSWORD", FALSE );
define ( "MYSQLI_TABLE", "r8_Test_Table" );

define ( "SQLITE_FILE", "/tmp/r8_SQLiteTestDB" );
define ( "SQLITE_TABLE", "r8_Test_Table" );

define ( "MEMCACHE_HOST", "127.0.0.1" );
define ( "MEMCACHE_PORT", 11211 );

?>
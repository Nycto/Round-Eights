<?php
/**
 * Primary raindropPHP include file
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 */

// If h2o has already been included...
if ( defined("h2o_INCLUDED") )
    return;

// Define the version
define("h2o_VERSION", "0.2.0");

// Mark that raindropPHP has been included
define("h2o_INCLUDED", TRUE);

// mark the location of the raindropPHP library
if ( !defined("h2o_DIR") ) {

    // Detect if this is currently being executed inside a Phar file
    if ( Phar::running() === "" )
        $raindropPHPdir = str_replace("\\", "/", __DIR__);
    else
        $raindropPHPdir = Phar::running();

    $raindropPHPdir = rtrim( $raindropPHPdir, "/" ) ."/";
    define("h2o_DIR", $raindropPHPdir);
    unset($raindropPHPdir);

}

if (!defined("h2o_DIR_FUNCTIONS"))
    define("h2o_DIR_FUNCTIONS", h2o_DIR ."functions/");

if (!defined("h2o_DIR_CLASSES"))
    define("h2o_DIR_CLASSES", h2o_DIR ."classes/");

if (!defined("h2o_DIR_INTERFACES"))
    define("h2o_DIR_INTERFACES", h2o_DIR ."interfaces/");

/**
 * Include the function files
 */
require_once h2o_DIR_FUNCTIONS ."general.php";
require_once h2o_DIR_FUNCTIONS ."numbers.php";
require_once h2o_DIR_FUNCTIONS ."strings.php";
require_once h2o_DIR_FUNCTIONS ."debug.php";
require_once h2o_DIR_FUNCTIONS ."array.php";

/**
 * Register the autoloader
 */
function h2o_autoload ( $class ) {

    $class = explode("\\", $class);
    $class = array_filter( $class );
    array_shift( $class );

    $first = reset( $class );

    if ( $first == "iface" )
        $class = h2o_DIR_INTERFACES . implode( "/", array_slice( $class, 1 ) ) .".php";

    else
        $class = h2o_DIR_CLASSES . implode( "/", $class ) .".php";

    if ( file_exists( $class ) )
        require_once $class;

}

spl_autoload_register("h2o_autoload");

/**
 * Set up custom exception handling
 */
set_exception_handler(function ( $exception ) {

    // If we are running in script mode, we don't need HTML
    if ( \h2o\Env::request()->local ) {
        echo "FATAL ERROR: Uncaught Exception Thrown:\n" .$exception;
    }
    else {

        echo "<div class='phpException'>\n"
            ."<h3>Fatal Error: Uncaught Exception Thrown</h3>\n";

        if ( $exception instanceof GeneralError )
            echo $exception->getVerboseHTML();
        else
            echo "<pre>". $exception ."</pre>";

        echo "</div>";

    }
});

/**
 * Take a snapshot of the environment
 */
\h2o\Env::Request();

?>
<?php
/**
 * Primary Round Eights include file
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
 */

// Ensure they are running the appropriate PHP version
if ( version_compare( phpversion(), '5.3.1' ) < 0 )
    trigger_error("Could not load Round Eights: PHP version 5.3.1 required", E_USER_ERROR);

// If r8 has already been included...
if ( defined("r8_INCLUDED") )
    return;

// Define the version
define("r8_VERSION", "0.3.0dev");

// Mark that Round Eights has been included
define("r8_INCLUDED", TRUE);

// mark the location of the Round Eights library
if ( !defined("r8_DIR") ) {

    // Detect if this is currently being executed inside a Phar file
    if ( Phar::running() === "" )
        $roundEightsDir = str_replace("\\", "/", __DIR__);
    else
        $roundEightsDir = Phar::running();

    $roundEightsDir = rtrim( $roundEightsDir, "/" ) ."/";
    define("r8_DIR", $roundEightsDir);
    unset($roundEightsDir);

}

if (!defined("r8_DIR_FUNCTIONS"))
    define("r8_DIR_FUNCTIONS", r8_DIR ."functions/");

if (!defined("r8_DIR_CLASSES"))
    define("r8_DIR_CLASSES", r8_DIR ."classes/");

if (!defined("r8_DIR_INTERFACES"))
    define("r8_DIR_INTERFACES", r8_DIR ."interfaces/");

/**
 * Include the function files
 */
require_once r8_DIR_FUNCTIONS ."general.php";
require_once r8_DIR_FUNCTIONS ."numbers.php";
require_once r8_DIR_FUNCTIONS ."strings.php";
require_once r8_DIR_FUNCTIONS ."debug.php";
require_once r8_DIR_FUNCTIONS ."array.php";

/**
 * Register the autoloader
 */
function r8_autoload ( $class ) {

    $class = explode("\\", $class);
    $class = array_filter( $class );
    array_shift( $class );

    $first = reset( $class );

    if ( $first == "iface" )
        $class = r8_DIR_INTERFACES . implode( "/", array_slice( $class, 1 ) ) .".php";

    else
        $class = r8_DIR_CLASSES . implode( "/", $class ) .".php";

    if ( file_exists( $class ) )
        require_once $class;

}

spl_autoload_register("r8_autoload");

/**
 * Set up custom exception handling
 */
set_exception_handler(function ( $exception ) {

    // If we are running in script mode, we don't need HTML
    if ( \r8\Env::request()->isCLI() ) {
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
\r8\Env::Request();

?>
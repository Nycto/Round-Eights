<?php
/**
 * Primary commonPHP include file
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 */

// If cPHP has already been included...
if ( defined("cPHP_INCLUDED") )
    return;

// Define the version
define("cPHP_VERSION", "0.2.0");

// Mark that commonPHP has been included
define("cPHP_INCLUDED", TRUE);

// mark the location of the commonPHP library
if ( !defined("cPHP_DIR") ) {

    // Detect if this is currently being executed inside a Phar file
    if ( Phar::running() === "" )
        $commonPHPdir = str_replace("\\", "/", __DIR__);
    else
        $commonPHPdir = Phar::running();

    $commonPHPdir = rtrim( $commonPHPdir, "/" ) ."/";
    define("cPHP_DIR", $commonPHPdir);
    unset($commonPHPdir);

}

if (!defined("cPHP_DIR_FUNCTIONS"))
    define("cPHP_DIR_FUNCTIONS", cPHP_DIR ."functions/");

if (!defined("cPHP_DIR_CLASSES"))
    define("cPHP_DIR_CLASSES", cPHP_DIR ."classes/");

if (!defined("cPHP_DIR_INTERFACES"))
    define("cPHP_DIR_INTERFACES", cPHP_DIR ."interfaces/");

/**
 * Include the function files
 */
require_once cPHP_DIR_FUNCTIONS ."general.php";
require_once cPHP_DIR_FUNCTIONS ."numbers.php";
require_once cPHP_DIR_FUNCTIONS ."strings.php";
require_once cPHP_DIR_FUNCTIONS ."debug.php";
require_once cPHP_DIR_FUNCTIONS ."array.php";

/**
 * Register the autoloader
 */
function cPHP_autoload ( $class ) {

    $class = explode("\\", $class);
    $class = array_filter( $class );
    array_shift( $class );

    $first = reset( $class );

    if ( $first == "iface" )
        $class = cPHP_DIR_INTERFACES . implode( "/", array_slice( $class, 1 ) ) .".php";

    else
        $class = cPHP_DIR_CLASSES . implode( "/", $class ) .".php";

    if ( file_exists( $class ) )
        require_once $class;

}

spl_autoload_register("cPHP_autoload");

/**
 * Set up custom exception handling
 */
set_exception_handler(function ( $exception ) {

    // If we are running in script mode, we don't need HTML
    if ( \cPHP\Env::request()->local ) {
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
\cPHP\Env::Request();

?>
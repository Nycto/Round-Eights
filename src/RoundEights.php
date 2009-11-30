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
spl_autoload_register(function ( $class ) {

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

});

/**
 * Take a snapshot of the environment
 */
\r8\Env::Request();

/**
 * Set up error handling
 */
set_error_handler(function ( $code, $message, $file, $line ) {

    $level = (int) ini_get('error_reporting');
    $code = (int) $code;

    if ( !( $code & $level ) )
        return TRUE;

    $backtrace = \r8\Backtrace::create()->popEvent();
    \r8\Error::getInstance()->handle(
        new \r8\Error\PHP( $file, $line, $code, $message, $backtrace )
    );
});

/**
 * Set up exception handling
 */
set_exception_handler(function ( $exception ) {
    \r8\Error::getInstance()->handle(
        new \r8\Error\Exception( $exception )
    );
});

/**
 * Hook in the error handler to the error log
 */
\r8\Error::getInstance()->register(
    new \r8\Error\Handler\Stream(
        new \r8\Error\Formatter\JSON( \r8\Env::request() ),
        new \r8\Stream\Out\ErrorLog
    )
);

/**
 * If display errors is enabled, hook in an error handler for outputting the errors
 */
$r8_displayErrors = strtolower( ini_get('display_errors') );
if ( $r8_displayErrors == "1" || $r8_displayErrors == "on" ) {

    if ( \r8\Env::request()->isCLI() )
        $r8_formatter = new \r8\Error\Formatter\Text( \r8\Env::request() );
    else
        $r8_formatter = new \r8\Error\Formatter\HTML( \r8\Env::request() );

    \r8\Error::getInstance()->register(
        new \r8\Error\Handler\Stream(
            $r8_formatter,
            new \r8\Stream\Out\StdOut
        )
    );

    unset( $r8_formatter );
}

unset( $r8_displayErrors );

?>
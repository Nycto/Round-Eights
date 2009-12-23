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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 */

// @codeCoverageIgnoreStart

// Ensure they are running the appropriate PHP version
if ( version_compare( phpversion(), '5.3.1' ) < 0 )
    trigger_error("Could not load Round Eights: PHP version 5.3.1 required", E_USER_ERROR);

// If r8 has already been included...
if ( defined("r8_INCLUDED") )
    return;

// Define the version
define("r8_VERSION", "0.4.0dev");

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
 * Include the required files
 */
require_once r8_DIR_CLASSES ."Autoload.php";
require_once r8_DIR_FUNCTIONS ."general.php";
require_once r8_DIR_FUNCTIONS ."numbers.php";
require_once r8_DIR_FUNCTIONS ."strings.php";
require_once r8_DIR_FUNCTIONS ."debug.php";
require_once r8_DIR_FUNCTIONS ."array.php";

/**
 * Register the autoloader
 */
\r8\Autoload::getInstance()
    ->register('r8', r8_DIR_CLASSES)
    ->register('r8\iface', r8_DIR_INTERFACES);

spl_autoload_register( array( \r8\Autoload::getInstance(), "load" ) );

/**
 * Take a snapshot of the environment
 */
\r8\Env::Request();

/**
 * Set up error handling, but only if it isn't being suppressed by the including code
 */
if ( !defined("r8_SUPPRESS_HANDLERS") ) {

    // Register the error handler
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

    // Register an exception handler
    set_exception_handler(function ( $exception ) {
        \r8\Error::getInstance()->handle(
            new \r8\Error\Exception( $exception )
        );
    });

    // Hook in the error handler to the error log
    \r8\Error::getInstance()->register(
        new \r8\Error\Handler\Stream(
            new \r8\Error\Formatter\JSON( \r8\Env::request() ),
            new \r8\Stream\Out\ErrorLog
        )
    );

    // Hook in the error handler to output the error to the client
    \r8\Error::getInstance()->register(
        new \r8\Error\Handler\IniDisplay(
            new \r8\Error\Handler\Stream(
                \r8\Env::request()->isCLI()
                    ? new \r8\Error\Formatter\Text( \r8\Env::request() )
                    : new \r8\Error\Formatter\HTML( \r8\Env::request() ),
                new \r8\Stream\Out\StdOut
            )
        )
    );

    unset( $r8_formatter );
}

// @codeCoverageIgnoreEnd

?>
<?php
/**
 * Makes raindropPHP in to a phar file for distribution
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
 * @package Package
 */

if ( !Phar::canWrite() )
    die("Archive creation restricted because of 'phar.readonly' ini setting");

require_once rtrim( __DIR__, "/" ) .'/../src/raindropPHP.php';

$phar = new Phar('raindropPHP.phar');

// Add the source directory
$phar->buildFromDirectory( h2o_DIR );

if ( Phar::canCompress(Phar::GZ) )
    $phar->compressFiles(Phar::GZ);

// Add the stub based on the data at the bottom of this file
$stub = file_get_contents("stub.php");
$stub = str_replace('[$version]', h2o_VERSION, $stub);

$phar->setStub( $stub ) ;

echo "Phar file packed\n";

/**
 * Everything below the halt compiler construct will be used as the stub for the phar file
 */
__halt_compiler();
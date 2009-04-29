<?php
/**
 * Creates a zip and tar.gz files of the current code base
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
 * @package Package
 */

// Get the base path for the cPHP files
$base = realpath( rtrim( __DIR__, "/" ) ."/.." ) ."/";


// include cPHP
require_once $base .'/src/commonPHP.php';

// Create the phar file
include "make.php";


// Pull the system's temporary directory
$tempDir = rtrim( sys_get_temp_dir(), "/" );


// Pull the temporary directory that the package will be pooled in
$tmp = new \cPHP\FileSys\Dir( $tempDir ."/commonPHP" ) ;


// Empty the temp dir or create it if it doesn't exist
if ( $tmp->isDir() )
    $tmp->purge();
else
    $tmp->make();

echo "Temporary directory created: ". $tmp->getPath() ."\n";


// Pull a list of dirs and files that need to be copied and their destination
$copy = array(
    "src" => "",
    "tests" => "",
    "INSTALL.txt" => "",
    "LICENSE.txt" => "",
    "tools/commonPHP.phar" => ""
);


// Copy the above files
foreach ( $copy AS $source => $destination ) {

    echo "Copying: ". $source ." ";

    $result = system(
            "cp -R "
            . escapeshellarg($base . $source) ." "
            . escapeshellarg( $tmp->getSubPath($destination)->getPath() )
        );

    if ( $result === FALSE )
        die("Failed");

    echo "complete\n";

}


// Remove the config file from pooled directory
if ( $tmp->contains("tests/config.php") ) {
    $tmp->getSubPath("tests/config.php")->delete();
    echo "test config file deleted\n";
}


$zipFile = new \cPHP\FileSys\File($base ."/tools/commonPHP-". cPHP_VERSION .".zip");
$targzFile = new \cPHP\FileSys\File($base ."/tools/commonPHP-". cPHP_VERSION .".tar.gz");


if ( $zipFile->exists() ) {
    echo "Removing existing zip file\n";
    $zipFile->delete();
}

if ( $targzFile->exists() ) {
    echo "Removing existing tar.gz file\n";
    $targzFile->delete();
}


// Create the archives archive
echo "Creating zip archive";
system(
        "cd ". escapeshellarg($tempDir) ."; "
        ."zip -r "
        .escapeshellarg( $zipFile->getPath() ) ." "
        .escapeshellarg( "commonPHP" )
    );

echo "Creating tar.gz archive\n";
system(
        "cd ". escapeshellarg($tempDir) ."; "
        ."tar -cvzf "
        . escapeshellarg( $targzFile->getPath()  ) ." "
        .escapeshellarg( "commonPHP" )
    );


// Purge and remove the temporary dir
echo "Removing temporary directory\n";
$tmp->purge()->delete();


echo "End of script";

?>
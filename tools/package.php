<?php
/**
 * Creates a zip and tar.gz files of the current code base
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
 * @package Package
 */

// Get the base path for the r8 files
$base = realpath( rtrim( __DIR__, "/" ) ."/.." ) ."/";


// include r8
require_once $base .'src/RoundEights.php';

// Create the phar file
include "make.php";


// Pull the base temporary directory. Keep creating directory names
// until we find one that doesn't yet exist
do {
    $tempDir = new \r8\FileSys\Dir(
        rtrim( sys_get_temp_dir(), "/" ) ."/RoundEights_". uniqid()
    );
}
while ( $tempDir->isDir() );


// Pull the destination directory that the package will be pooled in
$dest = new \r8\FileSys\Dir( $tempDir->getSubPath("RoundEights")->getPath() );

// Create the destination dir
$dest->make();


echo "Temporary directory created: ". $dest->getPath() ."\n";


// Pull a list of dirs and files that need to be copied and their destination
$copy = array(
    "src" => "",
    "tests" => "",
    "INSTALL.txt" => "",
    "LICENSE.txt" => "",
    "tools/RoundEights.phar" => ""
);


// Copy the above files
foreach ( $copy AS $source => $destination ) {

    echo "Copying: ". $source ." ";

    $result = system(
            "cp -R "
            . escapeshellarg($base . $source) ." "
            . escapeshellarg( $dest->getSubPath($destination)->getPath() )
        );

    if ( $result === FALSE )
        die("Failed");

    echo "complete\n";

}


// Remove the config file from pooled directory
if ( $dest->contains("tests/config.php") ) {
    $dest->getSubPath("tests/config.php")->delete();
    echo "test config file deleted\n";
}


$zipFile = new \r8\FileSys\File($base ."tools/RoundEights-". r8_VERSION .".zip");
$targzFile = new \r8\FileSys\File($base ."tools/RoundEights-". r8_VERSION .".tar.gz");


if ( $zipFile->exists() ) {
    echo "Removing existing zip file\n";
    $zipFile->delete();
}

if ( $targzFile->exists() ) {
    echo "Removing existing tar.gz file\n";
    $targzFile->delete();
}


// Create the archives archive
echo "Creating zip archive\n";
system(
        "cd ". escapeshellarg($tempDir) ."; "
        ."zip -r "
        .escapeshellarg( $zipFile->getPath() ) ." "
        .escapeshellarg( "RoundEights" )
    );

echo "Creating tar.gz archive\n";
system(
        "cd ". escapeshellarg($tempDir) ."; "
        ."tar -cvzf "
        .escapeshellarg( $targzFile->getPath()  ) ." "
        .escapeshellarg( "RoundEights" )
    );


// Purge and remove the temporary dir
echo "Removing temporary directory\n";
//$tempDir->purge()->delete();


echo "End of script\n";

?>
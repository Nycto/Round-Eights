<?php
/**
 * Makes commonPHP in to a phar file for distribution
 */

if ( !Phar::canWrite() )
    die("Archive creation restricted because of 'phar.readonly' ini setting");

$phar = new Phar('commonPHP.phar');

// Add the source directory
$phar->buildFromDirectory( rtrim( __DIR__, "/" ) .'/../src');

if ( Phar::canCompress(Phar::GZ) )
    $phar->compressFiles(Phar::GZ);

$file = fopen(__FILE__, 'r');
fseek($file, __COMPILER_HALT_OFFSET__);
$phar->setStub( stream_get_contents($file) ) ;

echo "Script Complete\n";

/**
 * Everything below the halt compiler construct will be used as the stub for the phar file
 */
__halt_compiler();<?php
require_once "phar://". __FILE__ ."/commonPHP.php";
__HALT_COMPILER();
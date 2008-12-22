<?php
/**
 * Makes commonPHP in to a phar file for distribution
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
 * @package Phar
 */

if ( !Phar::canWrite() )
    die("Archive creation restricted because of 'phar.readonly' ini setting");

$phar = new Phar('commonPHP.phar');

// Add the source directory
$phar->buildFromDirectory( rtrim( __DIR__, "/" ) .'/../src');

if ( Phar::canCompress(Phar::GZ) )
    $phar->compressFiles(Phar::GZ);

// Add the stub based on the data at the bottom of this file
$file = fopen(__FILE__, 'r');
fseek($file, __COMPILER_HALT_OFFSET__);
$phar->setStub( stream_get_contents($file) ) ;

echo "Script Complete\n";

/**
 * Everything below the halt compiler construct will be used as the stub for the phar file
 */
__halt_compiler();<?php
/**
 * Phar archived commonPHP library
 *
 * This file is a Phar archive of the commonPHP library. For more information on
 * commonPHP, visit the website at the following URL:
 *
 * http://www.commonPHP.com
 *
 * For more information on the Phar file format, it has been documented in the
 * php manual, at this URL:
 *
 * http://www.php.net/phar
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
 * @package Phar
 */

if ( version_compare( phpversion(), '5.3.0alpha3' ) < 0 )
    trigger_error("Could not load commonPHP: PHP version 5.3 required", E_USER_ERROR);

if ( !class_exists('Phar', FALSE) )
    trigger_error("Could not load commonPHP: Phar class does not exist", E_USER_ERROR);

if ( !in_array('phar', stream_get_wrappers()) )
    trigger_error("Could not load commonPHP: Phar stream is not supported", E_USER_ERROR);

require_once "phar://". __FILE__ ."/commonPHP.php";

__HALT_COMPILER();
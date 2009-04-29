<?php
/**
 * commonPHP library, version [$version]
 *
 * This file is a Phar archive of the commonPHP library. For more information on
 * commonPHP, including downloads, documentation, roadmaps, and news,
 * visit the website at the following URL:
 *
 * http://www.commonPHP.com
 *
 * More information about the Phar file format can be found at this URL:
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
 * @package Package
 */

if ( version_compare( phpversion(), '5.3.0RC1' ) < 0 )
    trigger_error("Could not load commonPHP: PHP version 5.3 required", E_USER_ERROR);

if ( !class_exists('Phar', FALSE) )
    trigger_error("Could not load commonPHP: Phar class does not exist", E_USER_ERROR);

if ( !in_array('phar', stream_get_wrappers()) )
    trigger_error("Could not load commonPHP: Phar stream is not supported", E_USER_ERROR);

require_once "phar://". __FILE__ ."/commonPHP.php";

__HALT_COMPILER();
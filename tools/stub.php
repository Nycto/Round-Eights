<?php
/**
 * Round Eights library, version [$version]
 *
 * This file is a Phar archive of the Round Eights library. For more information on
 * Round Eights, including downloads, documentation, roadmaps, and news,
 * visit the website at the following URL:
 *
 * http://www.RoundEights.com
 *
 * More information about the Phar file format can be found at this URL:
 *
 * http://www.php.net/phar
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

if ( !class_exists('Phar', FALSE) )
    trigger_error("Could not load Round Eights: Phar class does not exist", E_USER_ERROR);

if ( !in_array('phar', stream_get_wrappers()) )
    trigger_error("Could not load Round Eights: Phar stream is not supported", E_USER_ERROR);

require_once "phar://". __FILE__ ."/RoundEights.php";

__HALT_COMPILER();
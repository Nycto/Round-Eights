<?php
/**
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
 * @package Filters
 */

namespace cPHP\Filter;

/**
 * Converts a value to a string and removes anything that isn't a letter
 */
class Alpha extends \cPHP\Filter
{

    /**
     * Cleans up a string in preparation for
     *
     * @param mixed $value The value to filter
     * @return
     */
    public function filter ( $value )
    {
        return preg_replace(
                '/[^a-zA-Z]/',
                '',
                \cPHP\strval( $value )
            );
    }

}

?>
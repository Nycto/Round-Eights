<?php
/**
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
 * @package Filters
 */

namespace r8\Filter;

/**
 * Removes any characters that are not valid in a PHP variable name
 */
class Variable extends \r8\Filter
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
                '/[^a-zA-Z0-9_\x7f-\xff]/',
                '',
                \r8\strval( $value )
            );
    }

}

?>
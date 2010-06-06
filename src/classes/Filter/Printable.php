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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Filters
 */

namespace r8\Filter;

/**
 * Strips out non-printable characters from a string
 */
class Printable extends \r8\Filter
{

    /**
     * Cleans up a string in preparation for
     *
     * @param mixed $value The value to filter
     * @return
     */
    public function filter ( $value )
    {
        return filter_var(
            (string) $value,
            FILTER_UNSAFE_RAW,
            FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH
        );
    }

}

?>
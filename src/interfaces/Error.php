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
 * @package Error
 */

namespace r8\iface;

/**
 * Describes an error
 */
interface Error
{

    /**
     * Returns the error code identifying this error
     *
     * @return Integer
     */
    public function getCode ();

    /**
     * Returns the Error Message associated with this error
     *
     * @return String
     */
    public function getMessage ();

    /**
     * Returns the file the error occurred in
     *
     * @return String
     */
    public function getFile ();

    /**
     * Returns the line number the error occurred on
     *
     * @return Integer
     */
    public function getLine ();

    /**
     * Returns whether this error should halt execution of the script
     *
     * @return Boolean
     */
    public function isFatal ();

    /**
     * Returns the backtrace that lead up to this error
     *
     * @return \r8\Backtrace
     */
    public function getBacktrace ();

    /**
     * Returns the human readable type of this error
     *
     * @return String
     */
    public function getType ();

}

?>
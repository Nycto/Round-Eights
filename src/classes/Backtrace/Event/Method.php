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
 * @package Backtrace
 */

namespace r8\Backtrace\Event;

/**
 * An object method call event
 */
class Method extends \r8\Backtrace\Event\Object
{

    /**
     * Invokes the appropriate visitor method
     *
     * @param \r8\iface\Backtrace\Visitor $visitor The object to visit
     * @return NULL
     */
    public function visit ( \r8\iface\Backtrace\Visitor $visitor )
    {
        $visitor->method( $this );
    }

}

?>
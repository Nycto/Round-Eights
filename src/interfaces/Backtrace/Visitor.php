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
 * @package Cache
 */

namespace r8\iface\Backtrace;

/**
 * The description of a backtrace visitor
 */
interface Visitor
{

    /**
     * The visitor callback for the beginning of a visit
     *
     * @param \r8\Backtrace $backtrace The backtrace being visited
     * @return NULL
     */
    public function begin ( \r8\Backtrace $backtrace );

    /**
     * The visitor callback for a Main event
     *
     * @param \r8\Backtrace\Event\Main $event The event invoking this visit
     * @return NULL
     */
    public function main ( \r8\Backtrace\Event\Main $event );

    /**
     * The visitor callback for a Closure call
     *
     * @param \r8\Backtrace\Event\Closure $event The event invoking this visit
     * @return NULL
     */
    public function closure ( \r8\Backtrace\Event\Closure $event );

    /**
     * The visitor callback for a Function call
     *
     * @param \r8\Backtrace\Event\Func $event The event invoking this visit
     * @return NULL
     */
    public function func ( \r8\Backtrace\Event\Func $event );

    /**
     * The visitor callback for a Method call
     *
     * @param \r8\Backtrace\Event\Method $event The event invoking this visit
     * @return NULL
     */
    public function method ( \r8\Backtrace\Event\Method $event );

    /**
     * The visitor callback for a Static Method call
     *
     * @param \r8\Backtrace\Event\StaticMethod $event The event invoking this visit
     * @return NULL
     */
    public function staticMethod ( \r8\Backtrace\Event\StaticMethod $event );

    /**
     * The visitor callback for the ending of a visit
     *
     * @param \r8\Backtrace $backtrace The backtrace being visited
     * @return NULL
     */
    public function end ( \r8\Backtrace $backtrace );

}

?>
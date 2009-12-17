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
 * @package Backtrace
 */

namespace r8\Backtrace;

/**
 * Formats a backtrace as a string
 */
class Formatter
{

    /**
     * The formatter to use for putting together the backtrace string
     *
     * @var \r8\iface\Backtrace\Formatter
     */
    private $formatter;

    /**
     * Constructor...
     *
     * @param \r8\iface\Backtrace\Formatter $formatter
     */
    public function __construct ( \r8\iface\Backtrace\Formatter $formatter )
    {
        $this->formatter = $formatter;
    }

    /**
     * Formats a backtrace as a string
     *
     * @param \r8\Backtrace $backtrace The backtrace being formatted
     * @return String
     */
    public function format ( \r8\Backtrace $backtrace )
    {
        $position = $backtrace->count();
        $formatted = $this->formatter->prefix();

        foreach ( $backtrace AS $event )
        {
            $position--;

            if ( $event instanceof \r8\Backtrace\Event\Main ) {
                $formatted .= $this->formatter->main(
                    $position,
                    $event->getFile()
                );
            }
            else {
                $formatted .= $this->formatter->event(
                    $position,
                    $event->getResolvedName(),
                    $event->getArgs(),
                    $event->getFile(),
                    $event->getLine()
                );
            }
        }

        return $formatted . $this->formatter->suffix();
    }

}

?>
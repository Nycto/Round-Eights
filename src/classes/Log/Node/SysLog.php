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
 * @package Log
 */

namespace r8\Log\Node;

/**
 * Writes a log message to the syslog
 */
class SysLog implements \r8\iface\Log\Node
{

    /**
     * Where to log the messages to
     *
     * @see \openlog
     * @var Integer
     */
    private $facility;

    /**
     * Any flags to pass in to the syslog
     *
     * @see \openlog
     * @var Integer
     */
    private $flags;

    /**
     * Returns the syslog level a message should be logged as part of
     *
     * @param String $level The Message Level to resolve
     * @return Integer
     */
    static public function getSysLogLevel ( $level )
    {
        $map = array(
            \r8\Log\Level::EMERG => \LOG_EMERG,
            \r8\Log\Level::ALERT => \LOG_ALERT,
            \r8\Log\Level::CRIT => \LOG_CRIT,
            \r8\Log\Level::ERR => \LOG_ERR,
            \r8\Log\Level::WARN => \LOG_WARNING,
            \r8\Log\Level::NOTICE => \LOG_NOTICE,
            \r8\Log\Level::INFO => \LOG_INFO,
            \r8\Log\Level::DEBUG => \LOG_DEBUG,
        );
        return $map[ \r8\Log\Level::resolveValue( $level ) ];
    }

    /**
     * Constructor...
     *
     * @see \openlog For details on the flags that can be passed in
     * @param Integer $facility The location to send the messages to
     * @param Integer $flags Any addition flags to pass in
     */
    public function __construct ( $facility = \LOG_USER, $flags = 0 )
    {
        $this->facility = max(0, (int) $facility);
        $this->flags = max(0, (int) $flags);
    }

    /**
     * @see \r8\iface\Log\Node::dispatch
     */
    public function dispatch ( \r8\Log\Message $message )
    {
        $flags = $this->facility | $this->flags;
        $flags |= self::getSysLogLevel( $message->getLevel() );
        \syslog( $flags, $message->__toString() );
        return $this;
    }

}

?>
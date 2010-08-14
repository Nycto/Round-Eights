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

namespace r8;

/**
 * An interface for easily dispatching log messages
 */
class Log implements \r8\iface\Log\Node
{

    /**
     * The node to dispatch messages to
     *
     * @var \r8\iface\Log\Node
     */
    private $node;

    /**
     * Constructor...
     *
     * @param \r8\iface\Log\Node $node The node to dispatch messages to
     */
    public function __construct ( \r8\iface\Log\Node $node )
    {
        $this->node = $node;
    }

    /**
     * @see \r8\iface\Log\Node::dispatch
     */
    public function dispatch ( \r8\Log\Message $message )
    {
        $this->node->dispatch($message);
        return $this;
    }

    /**
     * Dispatches an emergency message. These messages should be used when
     * the system is unusable.
     *
     * @param String $message The human readable message to dispatch
     * @param String $code The message code
     * @param Array $data Any key/value data to associate with this message
     * @return \r8\Log Returns a self reference
     */
    public function emergency ( $message, $code, array $data = array() )
    {
        $this->dispatch( new \r8\Log\Message(
            $message, \r8\Log\Level::EMERGENCY, $code, $data
        ));
        return $this;
    }

    /**
     * Dispatches an alert message. These messages should be sent when an
     * action must be taken immediately.
     *
     * @param String $message The human readable message to dispatch
     * @param String $code The message code
     * @param Array $data Any key/value data to associate with this message
     * @return \r8\Log Returns a self reference
     */
    public function alert ( $message, $code, array $data = array() )
    {
        $this->dispatch( new \r8\Log\Message(
            $message, \r8\Log\Level::ALERT, $code, $data
        ));
        return $this;
    }

    /**
     * Dispatches a critical message. These messages should be sent when
     * the system is in critical conditions
     *
     * @param String $message The human readable message to dispatch
     * @param String $code The message code
     * @param Array $data Any key/value data to associate with this message
     * @return \r8\Log Returns a self reference
     */
    public function critical ( $message, $code, array $data = array() )
    {
        $this->dispatch( new \r8\Log\Message(
            $message, \r8\Log\Level::CRITICAL, $code, $data
        ));
        return $this;
    }

    /**
     * Dispatches an error message. These messages should be sent when
     * the system encounters an error condition
     *
     * @param String $message The human readable message to dispatch
     * @param String $code The message code
     * @param Array $data Any key/value data to associate with this message
     * @return \r8\Log Returns a self reference
     */
    public function error ( $message, $code, array $data = array() )
    {
        $this->dispatch( new \r8\Log\Message(
            $message, \r8\Log\Level::ERROR, $code, $data
        ));
        return $this;
    }

    /**
     * Dispatches a warning message. These messages should be sent when
     * the system encounters a potential or recoverable error condition
     *
     * @param String $message The human readable message to dispatch
     * @param String $code The message code
     * @param Array $data Any key/value data to associate with this message
     * @return \r8\Log Returns a self reference
     */
    public function warning ( $message, $code, array $data = array() )
    {
        $this->dispatch( new \r8\Log\Message(
            $message, \r8\Log\Level::WARNING, $code, $data
        ));
        return $this;
    }

    /**
     * Dispatches a notice message. These messages should be sent when an
     * the system encounters a normal, but significant, condition
     *
     * @param String $message The human readable message to dispatch
     * @param String $code The message code
     * @param Array $data Any key/value data to associate with this message
     * @return \r8\Log Returns a self reference
     */
    public function notice ( $message, $code, array $data = array() )
    {
        $this->dispatch( new \r8\Log\Message(
            $message, \r8\Log\Level::NOTICE, $code, $data
        ));
        return $this;
    }

    /**
     * Dispatches an informational message. These messages should be sent to
     * log useful, but non-critical, details of the state of the system
     *
     * @param String $message The human readable message to dispatch
     * @param String $code The message code
     * @param Array $data Any key/value data to associate with this message
     * @return \r8\Log Returns a self reference
     */
    public function info ( $message, $code, array $data = array() )
    {
        $this->dispatch( new \r8\Log\Message(
            $message, \r8\Log\Level::INFO, $code, $data
        ));
        return $this;
    }

    /**
     * Dispatches a debug message. These messages should be sent to track
     * extensive developmental details about the system
     *
     * @param String $message The human readable message to dispatch
     * @param String $code The message code
     * @param Array $data Any key/value data to associate with this message
     * @return \r8\Log Returns a self reference
     */
    public function debug ( $message, $code, array $data = array() )
    {
        $this->dispatch( new \r8\Log\Message(
            $message, \r8\Log\Level::DEBUG, $code, $data
        ));
        return $this;
    }

}

?>
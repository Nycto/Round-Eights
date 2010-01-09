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

namespace r8;

/**
 * Represents a debug backtrace
 */
class Backtrace implements \IteratorAggregate, \Countable
{

    /**
     * The list of events in this backtrace
     *
     * @var Array An array of \r8\Backtrace\Event
     */
    private $events = array();

    /**
     * Constructs a new backtrace from an debug_backtrace array
     *
     * @param Array $backtrace The backtrace array to build from
     * @return \r8\Backtrace
     */
    static public function from ( array $backtrace )
    {
        $result = new self;

        foreach ( $backtrace AS $event ) {
            if ( is_array($event) ) {
                $result->pushEvent(
                    \r8\Backtrace\Event::from( $event )
                );
            }
        }

        $result->pushEvent(
            new \r8\Backtrace\Event\Main(
                \r8\Env::request()->getFile()->getPath()
            )
        );

        return $result;
    }

    /**
     * Creates a new backtrace from the currenct scope
     *
     * @return \r8\Backtrace
     */
    static public function create ()
    {
        $backtrace = debug_backtrace( FALSE );
        array_shift( $backtrace );
        return self::from( $backtrace );
    }

    /**
     * A helper method that dumps the current backtrace to the client
     *
     * @return NULL
     */
    static public function dump ()
    {
        if ( \r8\Env::request()->isCLI() )
            $format = new \r8\Backtrace\Formatter\Text;
        else
            $format = new \r8\Backtrace\Formatter\HTML;

        echo r8(new \r8\Backtrace\Formatter( $format ) )
            ->format( self::create()->popEvent() );
    }

    /**
     * Returns the Events in this backtrace
     *
     * @return Array An array of \r8\Backtrace\Event
     */
    public function getEvents ()
    {
        return $this->events;
    }

    /**
     * Adds a new event onto this backtrace
     *
     * @param \r8\Backtrace\Event $event The event to add
     * @return \r8\Backtrace Returns a self reference
     */
    public function pushEvent ( \r8\Backtrace\Event $event )
    {
        if ( !in_array($event, $this->events, TRUE) )
            $this->events[] = $event;
        return $this;
    }

    /**
     * Adds a new event onto the beginning of this backtrace
     *
     * @param \r8\Backtrace\Event $event The event to add
     * @return \r8\Backtrace Returns a self reference
     */
    public function unshiftEvent ( \r8\Backtrace\Event $event )
    {
        if ( !in_array($event, $this->events, TRUE) )
            \array_unshift( $this->events, $event );
        return $this;
    }

    /**
     * Pops an event off the top of the backtrace
     *
     * @return \r8\Backtrace
     */
    public function popEvent ()
    {
        array_shift( $this->events );
        return $this;
    }

    /**
     * Visits all the events in this backtrace
     *
     * @param \r8\iface\Backtrace\Visitor $visitor The visitor object
     * @return \r8\iface\Backtrace\Visitor Returns the input visitor
     */
    public function visit ( \r8\iface\Backtrace\Visitor $visitor )
    {
        $visitor->begin( $this );

        foreach ( $this->events AS $event ) {
            $event->visit( $visitor );
        }

        $visitor->end( $this );

        return $visitor;
    }

    /**
     * Returns the number of events registered in this backtrace
     *
     * @return Integer
     */
    public function count ()
    {
        return count( $this->events );
    }

    /**
     * Returns an iterator containing the list of events in this backtrace
     *
     * @return \Iterator
     */
    public function getIterator ()
    {
        return new \ArrayIterator( $this->events );
    }

    /**
     * Returns a specific event from this backtrace
     *
     * @param Integer $offset The offset of the event to fetch
     * @return \r8\Backtrace\Event
     */
    public function getEvent ( $offset )
    {
        return \r8\ary\offset( $this->events, $offset );
    }


}

?>
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
 * @package SysV
 */

namespace r8\SysV;

/**
 * A System V based semaphore interface
 */
class Semaphore
{

    /**
     * The key for this semaphore
     *
     * @var Integer
     */
    private $key;

    /**
     * The maximum number of processes that can lock this semaphore at the same time
     *
     * @var Integer
     */
    private $max;

    /**
     * Once acquired, the semaphore resource
     *
     * @var Resource
     */
    private $resource;

    /**
     * Whether this semaphore is currently locked
     *
     * @var Boolean
     */
    private $locked = FALSE;

    /**
     * Generates an integer key from a variety of source
     *
     * @param Mixed $source The source key to generate the key from
     * @return Integer
     */
    static public function makeKey ( $source )
    {
        if ( $source instanceof \r8\Seed )
            return $source->getInteger();
        else if ( !is_int($source) )
            return \r8\num\intHash( sha1( (string) $source ) );
        else
            return $source;
    }

    /**
     * Constructor...
     *
     * @param Mixed $key The ID of this semaphore
     * @param Integer $max The maximum number of processes that can lock this
     *      semaphore at the same time
     */
    public function __construct ( $key, $max = 1 )
    {
        if ( !extension_loaded( 'sysvsem' ) )
            throw new \r8\Exception\Extension( "sysvsem", "Extension is not loaded" );

        $this->key = self::makeKey( $key );
        $this->max = max( 1, (int) $max );
    }

    /**
     * Returns the ID of this semaphore
     *
     * @return Integer
     */
    public function getKey ()
    {
        return $this->key;
    }

    /**
     * Returns the maximum number of processes that can lock this semaphore at
     * the same time
     *
     * @return Integer
     */
    public function getMax ()
    {
        return $this->max;
    }

    /**
     * Returns the semaphore PHP resource
     *
     * @return Resource
     */
    private function getResource ()
    {
        // If the resource doesn't exist already, create it
        if ( !$this->resource ) {
            $this->resource = @sem_get( $this->key, $this->max, 0666, TRUE );

            if ( $this->resource === FALSE ) {
                throw r8( new \r8\Exception\Resource("Unable to create semaphore") )
                    ->addData( "Key", $this->key );
            }
        }

        return $this->resource;
    }

    /**
     * Requests a lock against this semaphore
     *
     * @throws \r8\Exception\Resource Thrown if this semaphore lock can't be acquired
     * @return \r8\SysV\Semaphore Returns a self reference
     */
    public function lock ()
    {
        if ( $this->locked )
            return $this;

        if ( !@sem_acquire( $this->getResource() ) ) {
            throw r8( new \r8\Exception\Resource("Unable to acquire lock") )
                ->addData( "Key", $this->key );
        }

        $this->locked = TRUE;

        return $this;
    }

    /**
     * Returns whether this semaphore is locked
     *
     * @return Boolean
     */
    public function isLocked ()
    {
        return $this->locked;
    }

    /**
     * Releases the lock against this semaphore
     *
     * @throws \r8\Exception\Resource Thrown if this semaphore lock can't be released
     * @return \r8\SysV\Semaphore Returns a self reference
     */
    public function unlock ()
    {
        if ( !$this->locked )
            return $this;

        if ( !sem_release( $this->resource ) ) {
            throw r8( new \r8\Exception\Resource("Unable to release lock") )
                ->addData( "Key", $this->key );
        }

        $this->locked = FALSE;

        return $this;
    }

    /**
     * Deletes this semaphore from the system
     *
     * This is not the same as unlocking. This literally expunges the semaphore
     * from the underlying system. If another process currently has a lock, it
     * will be lost.
     *
     * @return \r8\SysV\Semaphore
     */
    public function delete ()
    {
        $this->unlock();
        sem_remove( $this->getResource() );
        $this->resource = NULL;
        return $this;
    }

    /**
     * Handles aqcuiring and releasing a lock before and after executing a callback
     *
     * If an exception is thrown by the callback, it will be caught, the lock
     * will be released and the exception is re-thrown
     *
     * @param Callable $callback The callback being wrapped
     * @return Mixed Returns the result of the callback
     */
    public function synchronize ( $callback )
    {
        if ( !is_callable($callback) )
            throw new \r8\Exception\Argument(0, "Callback", "Must be callable");

        // If we're already locked, then some other chunk of code must be
        // managing this... leave it to them
        if ( $this->locked )
            return $callback();

        $this->lock();

        try {
            $result = $callback();
        }
        catch ( \Exception $err ) {
            $this->unlock();
            throw $err;
        }

        $this->unlock();
        return $result;
    }

    /**
     * Destructor
     *
     * @return NULL
     */
    public function __destruct ()
    {
        $this->unlock();
        $this->resource = NULL;
    }

    /**
     * Returns the list of properties to serialize
     *
     * @return Array
     */
    public function __sleep ()
    {
        return array( "key", "max" );
    }

}

?>
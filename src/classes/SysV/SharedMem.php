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
 * Access to System V shared memory
 */
class SharedMem
{

    /**
     * The ID of the shared memory segment to access
     *
     * @var Integer
     */
    private $key;

    /**
     * The size, in bytes, of the shared memory segment to create.
     *
     * @var Integer
     */
    private $size;

    /**
     * Once opened the PHP resource pointing to the shared memory
     *
     * @var Resource
     */
    private $resource;

    /**
     * Constructor...
     *
     * @param Mixed $key The ID of the shared memory segment to access
     * @param Integer $size The size, in bytes, of the shared memory segment to
     *      create. This is only used if the shared memory hasn't already been
     *      allocated. Unfortunately, it can't be resized once created.
     */
    public function __construct ( $key, $size )
    {
        if ( !extension_loaded( 'sysvshm' ) )
            throw new \r8\Exception\Extension( "sysvshm", "Extension is not loaded" );

        $this->key = \r8\SysV\Semaphore::makeKey( $key );
        $this->size = $size;
    }

    /**
     * Returns the SysV ID of this shared memory block
     *
     * @return Integer
     */
    public function getKey ()
    {
        return $this->key;
    }

    /**
     * Returns the Size in bytes that will be allocated to this shared memory
     * block when it is opened
     *
     * @return Integer
     */
    public function getSize ()
    {
        return $this->size;
    }

    /**
     * Returns the PHP resource for accessing the shared memory
     *
     * @return Resource
     */
    private function getResource ()
    {
        if ( !$this->resource ) {

            $resource = shm_attach( $this->key, $this->size, 0666 );

            if ( $resource === FALSE ) {
                throw r8( new \r8\Exception\Resource("Unable to open Shared Memory") )
                    ->addData( "Key", $key )
                    ->addData( "Size", $this->size );
            }

            $this->resource = $resource;
        }

        return $this->resource;
    }

    /**
     * Sets a value into shared memory
     *
     * @throws \r8\Exception\Resource This is thrown if an error occurs while
     *      writing to the shared memory block
     * @param Mixed $key The key to save this chunk of data under
     * @param Mixed $value The value being saved
     * @return \r8\SysV\SharedMem Returns a self reference
     */
    public function set ( $key, $value )
    {
        $resource = $this->getResource();
        $key = \r8\SysV\Semaphore::makeKey($key);
        $value = serialize($value);

        if ( !@shm_put_var( $resource, $key, $value ) ) {
            throw r8( new \r8\Exception\Resource("Unable to write to Shared Memory") )
                ->addData( "Key", $key );
        }

        return $this;
    }

    /**
     * Returns a value from shared memory
     *
     * @throws \r8\Exception\Resource This is thrown if an error occurs while
     *      reading from the shared memory block
     * @param Mixed $key The key to read from memory
     * @return Mixed
     */
    public function get ( $key )
    {
        $key = \r8\SysV\Semaphore::makeKey($key);

        $resource = $this->getResource();

        if ( !shm_has_var($resource, $key) )
            return NULL;

        $result = @shm_get_var($resource, $key);

        if ( $result === FALSE ) {
            throw r8( new \r8\Exception\Resource("Unable to read from Shared Memory") )
                ->addData( "Key", $key );
        }

        if ( $result == "b:0;" )
            return FALSE;

        $result = @unserialize( $result );

        return $result === FALSE ? NULL : $result;
    }

    /**
     * Returns whether a key exists in shared memory
     *
     * @param Mixed $key The key to look up
     * @return Boolean
     */
    public function exists ( $key )
    {
        return (bool) @shm_has_var(
            $this->getResource(),
            \r8\SysV\Semaphore::makeKey($key)
        );
    }

    /**
     * Deletes a key from shared memory
     *
     * @param Mixed $key The key to look up
     * @return Boolean
     */
    public function clear ( $key )
    {
        $key = \r8\SysV\Semaphore::makeKey($key);
        $resource = $this->getResource();

        @shm_remove_var($resource, $key);

        return $this;
    }

    /**
     * Deletes the entire block of shared memory from the system
     *
     * @return \r8\SysV\SharedMem Returns a self reference
     */
    public function expunge ()
    {
        $resource = $this->getResource();
        @shm_remove($resource);
        @shm_detach($resource);
        $this->resource = NULL;
        return $this;
    }

    /**
     * Returns the list of properties to serialize
     *
     * @return Array
     */
    public function __sleep ()
    {
        return array( "key", "size" );
    }

}


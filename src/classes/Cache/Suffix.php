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

namespace r8\Cache;

/**
 * Transparently attaches a predefined suffix to all keys as the are saved to
 * the cache. This provides a mechanism for automatically expiring an entire
 * set of data by changing the suffix.
 */
class Suffix extends \r8\Cache\Base
{

    /**
     * The suffix to attach to each key
     *
     * @var String
     */
    private $suffix;

    /**
     * The inner cache to save values to
     *
     * @var \r8\iface\Cache
     */
    private $inner;

    /**
     * Constructor...
     *
     * @param String $suffix The suffix to attach to each key
     * @param \r8\iface\Cache The inner cache to save values to
     */
    public function __construct ( $suffix, \r8\iface\Cache $inner )
    {
        $this->suffix = \r8\indexVal( $suffix );
        $this->inner = $inner;
    }

    /**
     * Attaches the prefix to the given key
     *
     * @param String $key
     * @return String
     */
    private function prepareKey ( $key )
    {
        return \r8\indexVal( $key ) . $this->suffix;
    }

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function set ( $key, $value, $expire = 0 )
    {
        $this->inner->set( $this->prepareKey($key), $value, $expire );
        return $this;
    }

    /**
     * Returns a cached value based on it's key
     *
     * @param String $key The value to retrieve
     * @return mixed Returns the cached value
     */
    public function get ( $key )
    {
        return $this->inner->get( $this->prepareKey($key) );
    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function delete ( $key )
    {
        $this->inner->delete( $this->prepareKey($key) );
        return $this;
    }

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function add ( $key, $value, $expire = 0 )
    {
        $this->inner->add( $this->prepareKey($key), $value, $expire );
        return $this;
    }

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function replace ( $key, $value, $expire = 0 )
    {
        $this->inner->replace( $this->prepareKey($key), $value, $expire );
        return $this;
    }

    /**
     * Appends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to append
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function append ( $key, $value, $expire = 0 )
    {
        $this->inner->append( $this->prepareKey($key), $value, $expire );
        return $this;
    }

    /**
     * Prepends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to prepend
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function prepend ( $key, $value, $expire = 0 )
    {
        $this->inner->prepend( $this->prepareKey($key), $value, $expire );
        return $this;
    }

    /**
     * Increments a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function increment ( $key )
    {
        $this->inner->increment( $this->prepareKey($key) );
        return $this;
    }

    /**
     * Decrements a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function decrement ( $key )
    {
        $this->inner->decrement( $this->prepareKey($key) );
        return $this;
    }

    /**
     * Deletes all values in the cache
     *
     * @return \r8\Cache\Suffix Returns a self reference
     */
    public function flush ()
    {
        $this->inner->flush();
        return $this;
    }

}


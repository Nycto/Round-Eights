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
 * A decorator that automatically groups keys with a prefix as they are accessed.
 *
 * This also provieds functionality for all the values in this group to be flushed
 * at the same time without affecting anything in the rest of the cache.
 */
class Group extends \r8\Cache\Base implements \r8\iface\Cache\Updatable
{

    /**
     * The group prefix to attach to each key
     *
     * @var String
     */
    private $group;

    /**
     * The cache being wrapped
     *
     * @var \r8\iface\Cache
     */
    private $cache;

    /**
     * The group value to use for expiring this group
     *
     * @var String
     */
    private $groupValue;

    /**
     * Constructor...
     *
     * @param String $group The group name
     * @param \r8\iface\Cache $cache The cache being wrapped
     */
    public function __construct ( $group, \r8\iface\Cache $cache )
    {
        $this->group = \r8\indexVal( $group );
        $this->cache = $cache;
    }

    /**
     * Modifies each key accourding to this group
     *
     * @param String $key
     * @return String
     */
    private function modifyKey ( $key )
    {
        if ( !isset($this->groupValue) )
        {
            $this->groupValue = $this->cache->get( $this->group ."_GroupValue" );
            if ( empty($this->groupValue) )
                $this->flush();
        }

        return $this->group ."_". $this->groupValue ."_". $key;
    }

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Group Returns a self reference
     */
    public function set ( $key, $value, $expire = 0 )
    {
        $this->cache->set( $this->modifyKey($key), $value, $expire );
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
        return $this->cache->get( $this->modifyKey($key) );
    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return \r8\Cache\Group Returns a self reference
     */
    public function delete ( $key )
    {
        $this->cache->delete( $this->modifyKey($key) );
        return $this;
    }

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Group Returns a self reference
     */
    public function add ( $key, $value, $expire = 0 )
    {
        $this->cache->add( $this->modifyKey($key), $value, $expire );
        return $this;
    }

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Group Returns a self reference
     */
    public function replace ( $key, $value, $expire = 0 )
    {
        $this->cache->replace( $this->modifyKey($key), $value, $expire );
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
     * @return \r8\Cache\Group Returns a self reference
     */
    public function append ( $key, $value, $expire = 0 )
    {
        $this->cache->append( $this->modifyKey($key), $value, $expire );
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
     * @return \r8\Cache\Group Returns a self reference
     */
    public function prepend ( $key, $value, $expire = 0 )
    {
        $this->cache->prepend( $this->modifyKey($key), $value, $expire );
        return $this;
    }

    /**
     * Increments a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Group Returns a self reference
     */
    public function increment ( $key )
    {
        $this->cache->increment( $this->modifyKey($key) );
        return $this;
    }

    /**
     * Decrements a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Group Returns a self reference
     */
    public function decrement ( $key )
    {
        $this->cache->decrement( $this->modifyKey($key) );
        return $this;
    }

    /**
     * Deletes all values in the cache
     *
     * @return \r8\Cache\Group Returns a self reference
     */
    public function flush ()
    {
        $this->groupValue = base_convert( uniqid( dechex( mt_rand(0, 99999) ) ), 16, 36 );
        $this->cache->set( $this->group ."_GroupValue", $this->groupValue, 0 );
        return $this;
    }

    /**
     * Returns a cached value based on it's key
     *
     * This returns a cached value in the form of an object. This object will allow
     * you to run an update on the value with the clause that it shouldn't be
     * changed if it has changed since it was retrieved. This can be used to
     * prevent race conditions.
     *
     * @param String $key The value to retrieve
     * @return \r8\Cache\Result
     */
    public function getForUpdate ( $key )
    {
        if ( !($this->cache instanceof \r8\iface\Cache\Updatable) ) {
            throw new \r8\Exception\Data(
                $this->cache,
                'Inner Cache',
                'Must be an instance of \r8\iface\Cache\Updatable'
            );
        }

        $result = $this->cache->getForUpdate( $this->modifyKey($key) );
        return new \r8\Cache\Result(
            $this,
            $key,
            $result->getHash(),
            $result->getValue()
        );
    }

    /**
     * Sets the value for this key only if the value hasn't changed in the cache
     * since it was originally pulled
     *
     * @param \r8\Cache\Result $result A result object that was returned by
     *      the getForUpdate method
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\iface\Cache Returns a self reference
     */
    public function setIfSame ( \r8\Cache\Result $result, $value, $expire = 0 )
    {
        if ( !($this->cache instanceof \r8\iface\Cache\Updatable) ) {
            throw new \r8\Exception\Data(
                $this->cache,
                'Inner Cache',
                'Must be an instance of \r8\iface\Cache\Updatable'
            );
        }

        $this->cache->setIfSame(
            new \r8\Cache\Result(
                $this->cache,
                $this->modifyKey( $result->getKey() ),
                $result->getHash(),
                $result->getValue()
            ),
            $value,
            $expire
        );
        return $this;
    }

}


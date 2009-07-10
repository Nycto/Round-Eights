<?php
/**
 * The result of a getForUpdate cache request
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package FileFinder
 */

namespace h2o\Cache;

/**
 * The result of a getForUpdate cache request
 */
class Result
{

    /**
     * The cache this result was pulled from
     *
     * @var \h2o\iface\Cache
     */
    private $cache;

    /**
     * The descriptor the current state of this data in the cache.
     *
     * This piece of data allows us to determine whether the value has changed
     * before we update it
     *
     * @var mixed
     */
    private $hash;

    /**
     * The key used to pull this value
     *
     * @var String
     */
    private $key;

    /**
     * The value of this key
     *
     * @var mixed
     */
    private $value;

    /**
     * Constructor...
     *
     * @param \h2o\iface\Cache $cache The cache instance this result was pulled from
     * @param String $key The key used to pull this value
     * @param mixed $hash The descriptor of the current value in the cache. This
     *      will allow us to determine whether the value has changed before we
     *      update it
     * @param mixed $value The value from the cache
     */
    public function __construct ( \h2o\iface\Cache $cache, $key, $hash, $value )
    {
        $this->cache = $cache;
        $this->key = \h2o\strval( $key );
        $this->hash = $hash;
        $this->value = $value;
    }

    /**
     * Returns the cache object his value was pulled from
     *
     * @return \h2o\iface\Cache
     */
    public function getCache ()
    {
        return $this->cache;
    }

    /**
     * Returns the key used to pull this value
     *
     * @return string
     */
    public function getKey ()
    {
        return $this->key;
    }

    /**
     * Returns the descriptor of the current value in the cache
     *
     * @return mixed
     */
    public function getHash ()
    {
        return $this->hash;
    }

    /**
     * Returns the value from the cache
     *
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Sets the value for this key in the cache
     *
     * @param mixed $value The new value
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \h2o\Cache\Result Returns a self reference
     */
    public function set ( $value, $expire = 0 )
    {
        $this->cache->set( $this->key, $value, $expire );
        return $this;
    }

    /**
     * Sets the value for this key only if the value hasn't changed in the cache
     * since it was originally pulled
     *
     * @param mixed $value The new value
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \h2o\Cache\Result Returns a self reference
     */
    public function setIfSame ( $value, $expire = 0 )
    {
        $this->cache->setIfSame( $this, $value, $expire );
        return $this;
    }

}

?>
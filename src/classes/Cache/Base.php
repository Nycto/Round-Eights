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
 * Base functionality for Cache implementations
 */
abstract class Base implements \r8\iface\Cache
{

    /**
     * Checks the cache for a value and returns it if it exists.
     * Otherwise, the callback is invoked. The return value is saved
     * to the cache and returned.
     *
     * @param String $key The value to retrieve
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @param Callable $callback The method to invoke if the key
     *      doesn't exist in the database
     * @return mixed Returns the cached value
     */
    public function yield ( $key, $expire, $callback )
    {
        if ( !is_callable($callback) )
            throw new \r8\Exception\Argument(2, "Callback", "Must be callable");

        $result = $this->get( $key );

        if ( $result === null ) {
            $result = $callback();
            $this->set($key, $result, $expire);
        }

        return $result;
    }

}


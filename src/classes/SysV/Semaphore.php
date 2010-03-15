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
     * Constructor...
     *
     * @param Mixed $key The ID of this semaphore
     * @param Integer $max The maximum number of processes that can lock this
     *      semaphore at the same time
     */
    public function __construct ( $key, $max = 1 )
    {
        if ( $key instanceof \r8\Seed )
            $key = $key->getInteger();
        else if ( !is_int($key) )
            $key = \r8\num\intHash( sha1( (string) $key ) );

        $this->key = $key;
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

}

?>
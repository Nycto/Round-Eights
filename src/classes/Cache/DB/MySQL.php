<?php
/**
 * Hash table caching that uses a database
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package FileFinder
 */

namespace cPHP\Cache\DB;

/**
 * Base hash table object for key/value caching with MySQL
 *
 * The basic fields required are:
 *
 * Key: VarChar(255), Primary Key
 * Hash: VarChar(32)
 * Expiration: DateTime
 * Value: LongText
 */
class MySQL extends \cPHP\Cache\DB
{

    /**
     * Internal method to generate the query needed to fetch a key's value from
     * the DB
     *
     * @param String $key The value to retrieve
     * @return String A SQL query that will result in a single row, two field
     *      result set. The fields should be labelled Value and Hash
     */
    protected function createGetSQL ( $key )
    {
        return "SELECT `". $this->getValue() ."` AS `Value`,
                       `". $this->getHash() ."` AS `Hash`
                  FROM `". $this->getTable() ."`
                 WHERE `". $this->getExpiration() ."` >= NOW()
                   AND `". $this->getKey() ."` = ". $this->getLink()->quote( $key ) ."
                 LIMIT 1";
    }

    /**
     * Internal method to generate the query needed to set a key's value
     *
     * @param String $key The key to set
     * @param String $hash The hash representing the state of this value
     * @param String $value The already encoded value
     * @return String A SQL query that will result in a single row, two field
     *      result set. The fields should be labelled Value and Hash
     */
    protected function createSetSQL ( $key, $hash, $value )
    {
        $link = $this->getLink();

        return "INSERT INTO `". $this->getTable() ."`
                        SET `". $this->getKey() ."` = ". $link->quote( $key ) .",
                            `". $this->getHash() ."` = ". $link->quote( $hash ) .",
                            `". $this->getValue() ."` = ". $link->quote( $value ) ."
                         ON DUPLICATE KEY
                     UPDATE `". $this->getValue() ."` = ". $link->quote( $value );
    }

    /**
     * Sets the value for this key only if the value hasn't changed in the cache
     * since it was originally pulled
     *
     * @param cPHP\Cache\Result $result A result object that was returned by
     *      the getForUpdate method
     * @param mixed $value The value to set
     * @return Object Returns a self reference
     */
    public function setIfSame ( \cPHP\Cache\Result $result, $value )
    {

    }

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @return Object Returns a self reference
     */
    public function add ( $key, $value )
    {

    }

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @return Object Returns a self reference
     */
    public function replace ( $key, $value )
    {

    }

    /**
     * Appends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to append
     * @return Object Returns a self reference
     */
    public function append ( $key, $value )
    {

    }

    /**
     * Prepends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to prepend
     * @return Object Returns a self reference
     */
    public function prepend ( $key, $value )
    {

    }

    /**
     * Increments a given value by one
     *
     * If a given value isn't numeric, it will be treated as 0
     *
     * @param String $key The key for the value
     * @return Object Returns a self reference
     */
    public function increment ( $key )
    {

    }

    /**
     * Decrements a given value by one
     *
     * If a given value isn't numeric, it will be treated as 0
     *
     * @param String $key The key for the value
     * @return Object Returns a self reference
     */
    public function decrement ( $key )
    {

    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return Object Returns a self reference
     */
    public function delete ( $key )
    {

    }

    /**
     * Deletes all values in the cache
     *
     * @return Object Returns a self reference
     */
    public function flush ()
    {

    }

}

?>
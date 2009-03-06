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

namespace cPHP\Cache;

/**
 * Base hash table object for key/value caches that use a database
 *
 * Database driven caches require a very basic table with four fields. in MySQL
 * terms, The basic fields required are:
 *
 * Key: VarChar(255), Primary Key
 * Hash: VarChar(32)
 * Expiration: DateTime
 * Value: LongText
 */
abstract class DB implements \cPHP\iface\Cache
{

    /**
     * The database connection to run the queries against
     *
     * @var Object
     */
    private $link;

    /**
     * The name of the database table
     *
     * @var String
     */
    private $table;

    /**
     * The name of key field in the table
     *
     * @var String
     */
    private $key;

    /**
     * The name of hash field in the table
     *
     * @var String
     */
    private $hash;

    /**
     * The name of expiration field in the table
     *
     * @var String
     */
    private $expiration;

    /**
     * The name of key field in the table
     *
     * @var String
     */
    private $value;

    /**
     * Constructor...
     *
     * Takes the database connection, the table name and the name of it's fields
     * on construction
     *
     * @param Object $link The database connection to run the queries against
     * @param String $table The name of the database table
     * @param String $key The name of key field in the table
     * @param String $hash The name of hash field in the table
     * @param String $expir The name of expiration field in the table
     * @param String $value The name of key field in the table
     */
    public function __construct ( \cPHP\iface\DB\Link $link, $table, $key, $hash, $expir, $value )
    {
        $this->setLink( $link );
        $this->setTable( $table );
        $this->setKey( $key );
        $this->setHash( $hash );
        $this->setExpiration( $expir );
        $this->setValue( $value );
    }

    /**
     * Sets the database link to query against
     *
     * @param Object $link The database connection to run the queries against
     * @return Object Returns a self reference
     */
    public function setLink ( \cPHP\iface\DB\Link $link )
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Returns the database link the cache queries will be run against
     *
     * @return Object Returns a cPHP\iface\DB\Link object
     */
    public function getLink ()
    {
        return $this->link;
    }

    /**
     * Sets the name of the table to query against
     *
     * @param String $table The name of the database table
     * @return Object Returns a self reference
     */
    public function setTable ( $table )
    {
        $table = \cPHP\str\stripW( $table, \cPHP\str\ALLOW_UNDERSCORES );

        if ( \cPHP\isEmpty($table) )
            throw new \cPHP\Exception\Argument( 0, "Table Name", "Must not be empty" );

        $this->table = $table;

        return $this;
    }

    /**
     * Returns the name of the table that will be queried against
     *
     * @return Object Returns a cPHP\iface\DB\Link object
     */
    public function getTable ()
    {
        return $this->table;
    }

    /**
     * Sets the name of the field the for the key
     *
     * @param String $key The name of key field
     * @return Object Returns a self reference
     */
    public function setKey ( $key )
    {
        $key = \cPHP\str\stripW( $key, \cPHP\str\ALLOW_UNDERSCORES );

        if ( \cPHP\isEmpty($key) )
            throw new \cPHP\Exception\Argument( 0, "Key Field", "Must not be empty" );

        $this->key = $key;

        return $this;
    }

    /**
     * Returns the name of the field for the keys
     *
     * @return String The name of the key field
     */
    public function getKey ()
    {
        return $this->key;
    }

    /**
     * Sets the name of the field the for the hash
     *
     * @param String $hash The name of hash field
     * @return Object Returns a self reference
     */
    public function setHash ( $hash )
    {
        $hash = \cPHP\str\stripW( $hash, \cPHP\str\ALLOW_UNDERSCORES );

        if ( \cPHP\isEmpty($hash) )
            throw new \cPHP\Exception\Argument( 0, "Hash Field", "Must not be empty" );

        $this->hash = $hash;

        return $this;
    }

    /**
     * Returns the name of the field for the hashs
     *
     * @return String The name of the hash field
     */
    public function getHash ()
    {
        return $this->hash;
    }

    /**
     * Sets the name of the field the for the expiration
     *
     * @param String $expiration The name of expiration field
     * @return Object Returns a self reference
     */
    public function setExpiration ( $expiration )
    {
        $expiration = \cPHP\str\stripW( $expiration, \cPHP\str\ALLOW_UNDERSCORES );

        if ( \cPHP\isEmpty($expiration) )
            throw new \cPHP\Exception\Argument( 0, "Expiration Field", "Must not be empty" );

        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Returns the name of the field for the expirations
     *
     * @return String The name of the expiration field
     */
    public function getExpiration ()
    {
        return $this->expiration;
    }

    /**
     * Sets the name of the field the for the value
     *
     * @param String $value The name of value field
     * @return Object Returns a self reference
     */
    public function setValue ( $value )
    {
        $value = \cPHP\str\stripW( $value, \cPHP\str\ALLOW_UNDERSCORES );

        if ( \cPHP\isEmpty($value) )
            throw new \cPHP\Exception\Argument( 0, "Value Field", "Must not be empty" );

        $this->value = $value;

        return $this;
    }

    /**
     * Returns the name of the field for the values
     *
     * @return String The name of the value field
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Takes a raw key and normalizes it before using it in a database query
     *
     * @param mixed $key The key to normalize
     * @return String Returns a 32 character alphanumeric string
     */
    public function normalizeKey ( $key )
    {
        return md5( \cPHP\strval($key) );
    }

    /**
     * Creates the hash of the value being saved
     *
     * @param String $value The value to hash
     * @return String Returns a 32 character alphanumeric string
     */
    private function createHash ( $value )
    {
        return md5( $value );
    }

    /**
     * Internal method to generate the query needed to fetch a key's value from
     * the DB
     *
     * @param String $key The value to retrieve
     * @return String A SQL query that will result in a single row, two field
     *      result set. The fields should be labelled Value and Hash
     */
    abstract protected function createGetSQL ( $key );

    /**
     * Returns a cached value based on it's key
     *
     * @param String $key The value to retrieve
     * @return mixed Returns the cached value. If the cache value hasn't been
     *      set, NULL will be returned
     */
    public function get ( $key )
    {
        $query = $this->createGetSQL(
                $this->normalizeKey($key)
            );

        $result = $this->getLink()->query( $query );

        if ( $result->count() <= 0 )
            return NULL;

        $row = $result->rewind()->current();

        $result->free();

        return @unserialize( $row['Value'] );
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
     * @return Object A cPHP\Cache\Value object
     */
    public function getForUpdate ( $key )
    {
        $query = $this->createGetSQL(
                $this->normalizeKey($key)
            );

        $result = $this->getLink()->query( $query );

        if ( $result->count() <= 0 ) {
            $row = array( 'Hash' => NULL, 'Value' => NULL );
        }
        else {
            $row = $result->rewind()->current();
            $row['Value'] = @unserialize( $row['Value'] );
        }

        $result->free();

        return new \cPHP\Cache\Result( $this, $key, $row['Hash'], $row['Value'] );
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
    abstract protected function createSetSQL ( $key, $hash, $value );

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @return Object Returns a self reference
     */
    public function set ( $key, $value )
    {
        $value = serialize($value);

        $query = $this->createSetSQL(
                $this->normalizeKey($key),
                $this->createHash($value),
                $value
            );

        $this->getLink()->query( $query );

        return $this;
    }

    /**
     * Internal method to generate the query needed to set a key's value only
     * if it hasn't changed
     *
     * @param String $key The key to set
     * @param String $oldHash The hash representing the existing value
     * @param String $newHash The hash representing the new value
     * @param String $value The already encoded value
     * @return String A SQL query that will result in a single row, two field
     *      result set. The fields should be labelled Value and Hash
     */
    abstract protected function createSetIfSameSQL ( $key, $oldHash, $newHash, $value );

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
        $value = serialize($value);

        $query = $this->createSetIfSameSQL(
                $this->normalizeKey( $result->getKey() ),
                $result->getHash(),
                $this->createHash($value),
                $value
            );

        $this->getLink()->query( $query );

        return $this;
    }

}

?>
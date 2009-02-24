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

}

?>
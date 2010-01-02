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
 * @package Database
 */

namespace r8\DB\MySQLi;

/**
 * MySQL Database Link
 */
class Link extends \r8\DB\Link
{

    /**
     * This is the PHP extension required for this interface to work
     */
    const PHP_EXTENSION = "mysqli";

    /**
     * Connect to the server
     *
     * @return Resource|Object Returns a database connection resource
     */
    protected function rawConnect ()
    {
        $link = @mysqli_connect(
                $this->getHost(),
                $this->getUserName(),
                $this->getPassword(),
                $this->getDatabase(),
                $this->getPort()
            );

        if ( !$link ) {

            throw new \r8\Exception\DB\Link(
                    mysqli_connect_error(),
                    mysqli_connect_errno(),
                    $this
                );

        }

        return $link;

    }

    /**
     * Returns whether a given resource is still connected
     *
     * @param Resource|Object $connection The connection being tested
     * @return Boolean
     */
    protected function rawIsConnected ( $connection )
    {
        if ( !($connection instanceof \mysqli) )
            return FALSE;

        if ( @$connection->ping() !== TRUE )
            return FALSE;

        return TRUE;
    }

    /**
     * Used to escape a string for use in a query.
     *
     * @param String $value The string to escape
     * @return String An escaped version of the string
     */
    public function escapeString ( $value )
    {
        if ( is_array( $value ) )
            return array_map( array($this, "escapeString"), $value );

        $value = (string) $value;

        // Don't force a connection just to escape a string
        if ( $this->isConnected() )
            return $this->getLink()->real_escape_string( $value );
        else
            return addslashes( $value );
    }

    /**
     * Execute a query and return a result object
     *
     * @param String $query The query to execute
     * @return \r8\DB\Result Returns a result object
     */
    protected function rawQuery ( $query )
    {
        $link = $this->getLink();

        $result = $link->query( $query );

        if ( $result === FALSE )
            throw new \r8\Exception\DB\Query( $query, $link->error, $link->errno );

        if ( self::isSelect($query) )
            return new \r8\DB\MySQLi\Read( $result, $query );
        else
            return new \r8\DB\Result\Write( $link->affected_rows, $link->insert_id, $query );
    }

    /**
     * Disconnect from the server
     *
     * @return null
     */
    protected function rawDisconnect ()
    {
        $link = $this->getLink();
        $link->close();
    }

}

?>
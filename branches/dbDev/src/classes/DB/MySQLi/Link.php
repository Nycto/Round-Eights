<?php
/**
 * Database Link
 *
 * @package Database
 */

namespace cPHP::DB::MySQLi;

/**
 * MySQL Database Link
 */
class Link extends ::cPHP::DB::Link
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
            
            throw new ::cPHP::Exception::DB::Link(
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
        if ( !($connection instanceof mysqli) )
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
    protected function rawEscape ( $value )
    {
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
     * @return Object Returns a cPHP::DB::Result object
     */
    protected function rawQuery ( $query )
    {
        $link = $this->getLink();
        
        $result = $link->query( $query );
        
        if ( $result === FALSE )
            throw new ::cPHP::Exception::DB::Query( $query, $link->error, $link->errno );
        
        if ( self::isSelect($query) )
            return new ::cPHP::DB::MySQLi::Read( $result, $query );
        else
            return new ::cPHP::DB::MySQLi::Write( $result, $query );
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
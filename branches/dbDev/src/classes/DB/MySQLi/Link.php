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
        
    }

    /**
     * Disconnect from the server
     *
     * @return null
     */
    protected function rawDisconnect ()
    {
        $this->getLink()->close();
    }
    
}

?>
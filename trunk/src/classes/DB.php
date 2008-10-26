<?php
/**
 * Database Registry
 *
 * @package Database
 */

namespace cPHP;

/**
 * Database Registry class
 *
 * This provides an interface for registering database Links in a global
 * repository and later retrieving them in a different scope.
 */
class DB
{
    
    /**
     * This list of registered connections indexed by a shortcut
     */
    static protected $links = array();
    
    /**
     * The default database connection
     */
    static protected $default;
    
    /**
     * Returns the full list of database connections
     *
     * @return Array
     */
    static public function getLinks ()
    {
        return self::$links;
    }
    
    /**
     * Returns the default connection
     *
     * @return Object The default connection
     */
    static public function getDefault ()
    {
        return self::$default;
    }
    
    /**
     * Registers a new link
     *
     * If no default link has been set, the link passed to this instance
     * will be set as the default
     *
     * @param String $label The reference string used to index the connection
     * @param Object $link The actual database connection
     * @return Null
     */
    static public function setLink( $label, ::cPHP::iface::DB::Link $link )
    {
        $label = ::cPHP::strval( $label );
        
        if ( ::cPHP::is_empty($label) )
            throw new ::cPHP::Exception::Argument( 0, "Connection Label", "Must not be empty" );
        
        self::$links[ $label ] = $link;
        
        if ( !isset(self::$default) )
            self::setDefault( $label );
    }
    
    /**
     * Returns a registered link by it's label
     *
     * @param String $label The connection to return
     *      If no label is given, the default connection will be returned
     * @return Object The default connection
     */
    static public function get ( $label = NULL )
    {
        if ( !is_string($label) && ::cPHP::is_vague($label) )
            return self::getDefault();
        
        $label = ::cPHP::strval( $label );
        
        if ( ::cPHP::is_empty($label) )
            throw new ::cPHP::Exception::Argument( 0, "Connection Label", "Must not be empty" );
        
        if ( !array_key_exists($label, self::$links) )
            throw new ::cPHP::Exception::Index("Connection Label", $label, "Connection does not exist");
        
        return self::$links[$label];
    }
    
    /**
     * Sets the default connection based on an already registered label
     *
     * @param String $label The name of the connection to make the default
     * @return NULL
     */
    static public function setDefault ( $label )
    {
        $label = ::cPHP::strval( $label );
        
        if ( ::cPHP::is_empty($label) )
            throw new ::cPHP::Exception::Argument( 0, "Connection Label", "Must not be empty" );
        
        self::$default = self::get( $label );
    }
    
}

?>
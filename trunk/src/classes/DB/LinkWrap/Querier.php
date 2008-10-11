<?php
/**
 * Advanced querying linkwrap
 */

namespace cPHP::DB::LinkWrap;

/**
 * Link wrapper to provide advanced 
 */
class Querier extends ::cPHP::DB::LinkWrap
{
    
    /**
     * Query Flags
     */
    const SILENT = 1;
    const INSERT_IGNORE = 2;
    
    /**
     * Runs a query and returns the result
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @result Object Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        $flags = intval( $flags );
        $query = ::cPHP::strval($query);

        try {
            return $this->getLink()->query( $query, $flags );
        }
        catch (::cPHP::Exception::Database::Query $err) {
            
            if ( !( $flags & self::SILENT) ) {
                $err->shiftFault();
                throw $err;
            }
            
            return FALSE;
        
        }
        
    }
    
    /**
     * Marks the start of a transaction
     *
     * @return Object Returns a self reference
     */
    public function begin ()
    {
        $this->getLink()->query( "BEGIN" );
        return $this;
    }
    
    /**
     * Commits a transaction
     *
     * @return Object Returns a self reference
     */
    public function commit ()
    {
        $this->getLink()->query( "COMMIT" );
        return $this;
    }
    
    /**
     * Rolls back the current transaction
     *
     * @return Object Returns a self reference
     */
    public function rollBack ()
    {
        $this->getLink()->query( "ROLLBACK" );
        return $this;
    }
    
    /**
     * Takes an array of fields and constructs a field list for a query
     *
     * @param array|object $fields The fields to iterate over where the key
     *      is the field name and the value is the field value
     * @return String Returns a SQL field list
     */
    public function getFieldList ($fields)
    {
        if ( !::cPHP::Ary::is($fields) )
            throw new ::cPHP::Exception::Argument(0, "Field List", "Must be an array or traversable");
        
        $fields = ::cPHP::Ary::create($fields)->flatten();

        if (count($fields) <= 0)
            throw new ::cPHP::Exception::Argument(0, "Field List", "Must not be empty");

        foreach ($fields AS $name => $value) {
            $fields[$name] = "`". $name ."` = ". $this->quote($value);
        }

        return $fields->implode(", ");
    }
    
    /**
     * Inserts a row in to a table
     *
     * Field values are set by passing an array to the $fields argument. Array
     * keys are used as field names and array values are used as field values.
     * Values are automatically escaped.
     *
     * @param String $table The table to insert into
     * @param Array|Object $fields The associative array of fields to insert
     * @param Integer $flags Any query flags to use
     * @return Integer Returns the ID of the inserted row
     */
    public function insert ( $table, $fields, $flags = 0 )
    {
        $table = ::cPHP::strval($table);

        if ( ::cPHP::is_empty($table) )
            throw new ::cPHP::Exception::Argument(0, "Table Name", "Must not be empty");

        $query = "INSERT INTO ". $table ." SET ". $this->getFieldList($fields);

        $result = $this->query($query, $flags);

        if ( $result === FALSE )
            return FALSE;
        
        $id = $result->getInsertID();
        $result->free();

        return $result;
    }
    
}

?>
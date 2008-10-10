<?php
/**
 * Advanced querying adapter
 */

namespace cPHP::DB::Adapter;

/**
 * Connection wrapper to provide advanced 
 */
class Query extends ::cPHP::DB::Adapter
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
            $result = $this->getConnection()->query( $query, $flags );
        }
        catch (::cPHP::Exception::Database::Query $err) {
            
            if ( !( $flags & self::SILENT) ) {
                $err->shiftFault();
                throw $err;
            }
            
        }
        
        return $result;
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
     */
    public function insert ( $table, $fields, $flags = 0 )
    {
        $table = stringVal($table);

        if (is_empty($table))
            throw new ArgumentError(0, "Table Name", "Must not be empty");

        try {
            $query = "INSERT INTO ". $table ." SET "
                    .$this->constructFields($fields);
        }
        catch (GeneralError $err) {
            $err->shiftFault();
            throw $err;
        }

        try {
            $result = $this->query($query, $flags);
        }
        catch (QueryError $err) {

            if ( flagTest(cDB::VERBOSE, $flags) ) {
                $err->shiftFault();
                throw $err;
            }

            return FALSE;
        }

        $id = $result->get_insert_id();
        $result->free();

        return $id;
    }
    
}

?>
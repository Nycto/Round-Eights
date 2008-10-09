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
    
}

?>
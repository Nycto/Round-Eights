<?php
/**
 * Array filtering class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Allows you to register filters that will be run against specific array offsets
 *
 * This will ignore any offsets that do not have registered filters and return
 * them as they are. It will also ignore registered filters that don't have
 * corresponding offsets in the array being filtered.
 */
class AryOffset extends cPHP::Filter
{
    
    /**
     * The filters to apply indexed by their matching offset
     */
    private $filters = array();
    
    /**
     * Constructor...
     *
     * @param Array|Object A list of filters to apply indexed by the offsets to apply each filter to
     */
    public function __construct( $filters = array() )
    {
        $this->import( $filters );
    }
    
    /**
     * Returns the list of filters loaded in this instance
     *
     * @return Object Returns a cPHP::Ary object
     */
    public function getFilters ()
    {
        return new ::cPHP::Ary( $this->filters );
    }
    
    /**
     * Sets an index/filter pair in this instance
     *
     * This will overwrite any previous filters for the given index
     *
     * @param mixed $index The index this filter will be applied to
     * @param Object $filter The filter to apply to the given index
     * @return Object Returns a self reference
     */
    public function setFilter ( $index, ::cPHP::iface::Filter $filter )
    {
        $index = ::cPHP::reduce( $index );
        $this->filters[ $index ] = $filter;
        return $this;
    }
    
    /**
     * Imports a list of filters in to this instance
     *
     * @param Array|ObjectThe list of filters to import indexed by the offsets to apply each filter to
     * @return Object Returns a self reference
     */
    public function import ( $filters )
    {
        if ( !::cPHP::Ary::is( $filters) )
            throw new ::cPHP::Exception::Argument( 0, "Filter List", "Must be an array or a traversable object" );
        
        $filters = new ::cPHP::Ary( $filters );
        foreach ( $filters AS $key => $value ) {
            if ( $value instanceof ::cPHP::iface::Filter )
                $this->setFilter( $key, $value );
        }
        
        return $this;
    }
    
    /**
     * Apply this filter to an array value
     *
     * @param Array $value The value to filter
     * @return Array Returns the filtered version
     */
    public function filter ( $value )
    {
        if ( !::cPHP::Ary::is( $value ) )
            return $value;
        
        else if ( is_object($value) && ( !( $value instanceof ArrayAccess ) || !( $value instanceof Traversable ) ) )
            return $value;
        
        foreach ( $this->filters AS $key => $filter ) {
            if ( isset($value[$key]) )
                $value[$key] = $filter->filter( $value[$key] );
        }
        
        return $value;
    }
    
}

?>
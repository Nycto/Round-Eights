<?php
/**
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
 * @package Curry
 */

namespace cPHP\Curry;

/**
 * The most basic curry class. Invokes a defined callback
 */
class Call extends \cPHP\Curry\Unbound
{

    /**
     * The callback to be invoked
     */
    protected $callback;

    /**
     * Constructor...
     *
     * @param mixed $callback The callback to invoke
     * @param mixed $args... Any rightward arguments
     */
    public function __construct ( $callback )
    {
        if ( !is_callable($callback) )
            throw new \cPHP\Exception\Argument( 0, "Callback", "Must be Callable" );

        $this->callback = $callback;

        if ( func_num_args() > 1 ) {
            $args = func_get_args();
            array_shift( $args );
            $this->setRightByArray( $args );
        }
    }

    /**
     * Invokes the current callback with the given array of arguments and returns the results
     *
     * @param $args Array The arguments to apply to the callback
     * @return mixed
     */
    protected function rawExec ( array $args = array() )
    {
        return call_user_func_array(

                // For object, skip the shortcuts and just jump straight to the invoke method
                is_object($this->callback) ?
                    array( $this->callback, "__invoke") : $this->callback,

                $args

            );
    }

}

?>
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
 * @package Template
 */

namespace r8\Template;

/**
 * A template that injects values into a callback
 */
class Callback extends \r8\Template\Access implements \r8\iface\Template\Access
{

    /**
     * The list of arguments to pass into the callback
     *
     * @var array
     */
    private $args = array();

    /**
     * The callback to invoke
     *
     * @var Callable
     */
    private $callback;

    /**
     * Constructor...
     *
     * @param Array $arg The arguments to pass into the callback
     * @param Callable $callback The callback to invoke
     */
    public function __construct ( array $args, $callback )
    {
        if ( !is_callable($callback) )
            throw new \r8\Exception\Argument( 1, "Callback", "Must be callable");

        $this->callback = $callback;
        $this->args = $args;
    }

    /**
     * Outputs the rendered template to the client
     *
     * @return object Returns a self reference
     */
    public function display ()
    {
        echo $this->render();
        return $this;
    }

    /**
     * Returns the rendered template as a string
     *
     * @return String
     */
    public function render ()
    {
        $args = array();

        foreach ( $this->args AS $key ) {
            $args[] = $this->get( $key );
        }

        return (string) call_user_func_array( $this->callback, $args );
    }

    /**
     * Returns the rendered template as a string
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->render();
    }

}


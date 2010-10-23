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
 * @package CLI
 */

namespace r8\CLI;

/**
 * A command line argument
 */
abstract class Arg implements \r8\iface\CLI\Arg
{

    /**
     * The name of this argument
     *
     * @var String
     */
    private $name;

    /**
     * The filter to apply to this argument's data
     *
     * @var \r8\iface\Filter
     */
    private $filter;

    /**
     * The validator to use for checking this argument's data
     *
     * @var \r8\iface\Validator
     */
    private $validator;

    /**
     * Constructor...
     *
     * @param String $name The name of this argument
     * @param \r8\iface\Filter $filter The filter to apply to this argument's data
     * @param \r8\iface\Validator $validator The validator to use for checking
     *      this argument's data
     */
    public function __construct (
        $name,
        \r8\iface\Filter $filter = NULL,
        \r8\iface\Validator $validator = NULL
    ) {
        $name = \r8\str\stripNoPrint( $name );

        if ( \r8\isEmpty($name) )
            throw new \r8\Exception\Argument(0, "Name", "Must not be empty");

        $this->name = $name;
        $this->filter = $filter ?: new \r8\Filter\Identity;
        $this->validator = $validator ?: new \r8\Validator\Pass;
    }

    /**
     * Returns the name of this argument
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Returns the Filter to apply to this arguments data
     *
     * @return \r8\iface\Filter
     */
    public function getFilter ()
    {
        return $this->filter;
    }

    /**
     * Returns the Validator to use for checking this argument's data
     *
     * @return \r8\iface\Validator
     */
    public function getValidator ()
    {
        return $this->validator;
    }

}


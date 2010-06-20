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
 * @package CLIArgs
 */

namespace r8\CLIArgs\Arg;

/**
 * A command line argument that consumes a single argument
 */
class One extends \r8\CLIArgs\Arg
{

    /**
     * Given an input of arguments, pops elements off, filters them, validates
     * them and returns an array of everything it consumes
     *
     * @param \r8\CLIArgs\Input $input The input list to consume
     * @return Array
     */
    public function consume ( \r8\CLIArgs\Input $input )
    {
        $arg = $this->getFilter()->filter( $input->popArgument() );
        $this->getValidator()->ensure( $arg );
        return array( $arg );
    }

}

?>
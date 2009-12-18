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
 * @package Error
 */

namespace r8\Error;

/**
 *Formats an Error for display
 */
abstract class Formatter implements \r8\iface\Error\Formatter
{

    /**
     * The environment of the current request
     *
     * @var \r8\iface\Env\Request
     */
    private $request;

    /**
     * Constructor...
     *
     * @param \r8\iface\Env\Request $request The environment of the current request
     */
    public function __construct ( \r8\iface\Env\Request $request ) {
        $this->request = $request;
    }

    /**
     * Returns the Request loaded in this instance
     *
     * @return \r8\iface\Env\Request
     */
    public function getRequest ()
    {
        return $this->request;
    }

    /**
     * Returns the details of this error as an Array
     *
     * @param \r8\iface\Error $error The error to format
     * @return Array
     */
    protected function toArray ( \r8\iface\Error $error )
    {
        return array_filter( array(
            "Time" => date("Y-m-d H:i:s"),
            "Type" => $error->getType(),
        	"Message" => $error->getMessage(),
            "Code" => $error->getcode(),
            "File" => $error->getFile(),
            "Line" => $error->getLine(),
            "URI" => $this->getRequest()->getURL()->__toString()
        ) );
    }

}

?>
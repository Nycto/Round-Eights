<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Template
 */

namespace h2o\Template;

/**
 * Loads a PHP file as a template
 */
class PHP extends \h2o\Template\File
{

    /**
     * Render and output this template to the client
     *
     * @return \h2o\Template\PHP Returns a self reference
     */
    public function display ()
    {
        // Choose a variable name that is unlikely to run into a conflict
        ${""} = $this->getValues();
        extract( ${""}, EXTR_PREFIX_SAME, "var" );
        include $this->findFile()->getPath();
        return $this;
    }

}

?>
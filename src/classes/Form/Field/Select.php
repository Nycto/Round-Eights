<?php
/**
 * An HTML select form field
 *
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
 * @package Forms
 */

namespace cPHP\Form\Field;

/**
 * An HTML select field
 */
class Select extends \cPHP\Form\Multi
{

    /**
     * Returns a string representation of the option list
     *
     * @return String The list of select options
     */
    public function getOptionList ()
    {
        $result = "";

        foreach ( $this->getOptions() AS $key => $value ) {
            $result .= "<option"
                ." value='" .htmlspecialchars($key) ."'"
                .( $this->getValue() == $key ? " selected='selected'" : "" )
                .">"
                .htmlspecialchars($value)
                ."</option>";
        }

        return $result;
    }

    /**
     * Returns a \cPHP\Tag object that represents this instance
     *
     * @return Object A \cPHP\Tag object
     */
    public function getTag()
    {
        return new \cPHP\Tag(
                'select',
                $this->getOptionList(),
                array("name" => $this->getName())
            );
    }

}

?>
<?php
/**
 * An HTML select form field
 *
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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Forms
 */

namespace r8\Form\Field;

/**
 * An HTML select field
 */
class Select extends \r8\Form\Multi
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
     * Returns a \r8\Tag object that represents this instance
     *
     * @return Object A \r8\Tag object
     */
    public function getTag()
    {
        return new \r8\Tag(
                'select',
                $this->getOptionList(),
                array("name" => $this->getName())
            );
    }

}

?>
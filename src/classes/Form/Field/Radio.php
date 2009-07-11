<?php
/**
 * An HTML radio button list
 *
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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Forms
 */

namespace h2o\Form\Field;

/**
 * An HTML radio button field list
 */
class Radio extends \h2o\Form\Multi
{

    /**
     * Returns the HTML ID that will be used to identify each radio button
     *
     * The fields need to have an ID so that the label tags are correctly
     * associated with the radio tags
     *
     * @return
     */
    public function getRadioOptionID ( $value )
    {
        $value = \h2o\indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new \h2o\Exception\Index($value, "Option Value", "Option does not exist in field");

        return "radio_"
            .\h2o\str\stripW( $this->getName() )
            ."_"
            .substr(sha1($value), 0, 10);
    }

    /**
     * Returns the an HTML tag that represents an individual option's radio button
     *
     * @param String|Integer $value The value of the option whose tag should be returned
     * @return Object Returns a \h2o\Tag object
     */
    public function getOptionRadioTag ( $value )
    {
        $value = \h2o\indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new \h2o\Exception\Index($value, "Option Value", "Option does not exist in field");

        $tag = new \h2o\Tag( 'input' );

        $tag->importAttrs(array(
                "name" => $this->getName(),
                "value" => $value,
                "type" => "radio",
                "id" => $this->getRadioOptionID($value)
            ));

        if ( $value == $this->getValue())
            $tag['checked'] = 'checked';

        return $tag;
    }

    /**
     * Returns the an HTML tag that represents an individual option's label
     *
     * @param String|Integer $value The value of the option whose label tag should be returned
     * @return Object Returns a \h2o\Tag object
     */
    public function getOptionLabelTag ( $value )
    {
        $value = \h2o\indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new \h2o\Exception\Index($value, "Option Value", "Option does not exist in field");

        return new \h2o\Tag(
                'label',
                $this->getOptionLabel( $value ),
                array( "for" => $this->getRadioOptionID($value) )
            );
    }

    /**
     * Returns a string representation of the option list
     *
     * @return String The list of radio buttons and their labels
     */
    public function getOptionList ()
    {
        $result = "";

        foreach ( $this->getOptions() AS $key => $value ) {
            $result .= "<li>"
                .$this->getOptionRadioTag( $key )
                ." "
                .$this->getOptionLabelTag( $key )
                ."</li>";
        }

        return $result;
    }

    /**
     * Returns a \h2o\Tag object that represents this instance
     *
     * @return Object A \h2o\Tag object
     */
    public function getTag()
    {
        return new \h2o\Tag(
                'ul',
                $this->getOptionList()
            );
    }

}

?>
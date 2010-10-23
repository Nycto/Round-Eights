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

namespace r8\Error\Formatter;

/**
 * Formats an error as JSON
 */
class JSON extends \r8\Error\Formatter
{

    /**
     * Formats an array as a JSON object
     *
     * @param Array $data
     * @return String
     */
    private function formatArray ( array $data )
    {
        $result = "";

        foreach ( $data AS $key => $value ) {

            if ( !\r8\isBasic($value) )
                $value = \r8\getDump( $value );

            $result .= '"'. $key .'":'. json_encode($value) .", ";
        }

        return rtrim( $result, ", " );
    }

    /**
     * Formats an error as a string
     *
     * @param \r8\iface\Error $error The error to format
     * @return String Returns the formatted error
     */
    public function format ( \r8\iface\Error $error )
    {
        $formatter = new \r8\Backtrace\Formatter(
            new \r8\Backtrace\Formatter\JSON
        );

        $result = "{". $this->formatArray( $this->toArray($error) );

        $details = $error->getDetails();
        if ( !empty($details) )
            $result .= ', "Details":{'. $this->formatArray($details) .'}';

        $result .= ', "Backtrace":'
            .$formatter->format( $error->getBacktrace() )
            ."}";

        return $result ;
    }

}



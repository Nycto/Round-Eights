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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace h2o\Soap;

/**
 * Soap server fault interrupt
 *
 * This exception is used by the Soap server class to halt server
 * execution and return a soap fault
 */
class Fault extends \h2o\Exception\Interrupt
{

    /**
     * The title of this exception
     */
    const TITLE = "Soap Fault";

    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Soap Server Fault";

    /**
     * The primary code for this fault
     *
     * @var String
     */
    private $primeCode;

    /**
     * The list of subcodes for this fault
     *
     * There can be as many subcodes as you want in a fault
     *
     * @var array
     */
    private $subCodes;

    /**
     * takes a string and returns the well formatted primary fault code
     *
     * @param String $code The primary code to cleanup
     * @return String
     */
    static public function translatePrimeCode ( $code )
    {
        $code = trim( strtolower( (string) $code ) );

        $map = array(
            "versionmismatch" => "VersionMismatch",
        	"mustunderstand" => "MustUnderstand",
            "dataencodingunknown" => "DataEncodingUnknown",
            "sender" => "Sender",
            "receiver" => "Receiver",
        );

        return isset($map[ $code ]) ? $map[ $code ] : null;
    }

    /**
     * Constructor
     *
     * @param String $message The error message
     * @param Integer $primeCode The primary
     */
    public function __construct( $message, $primeCode = null, array $subCodes = array() )
    {
        parent::__construct($message);

        $this->primeCode = self::translatePrimeCode( $primeCode );
        if ( empty($primeCode) )
            $this->primeCode = "Sender";

        $this->subCodes = array_values(
            \h2o\ary\compact(
                array_map(
                	'\h2o\str\stripW',
                    \h2o\ary\flatten( $subCodes )
                )
            )
        );
    }

    /**
     * Returns the primary code from this instance
     *
     * @return String
     */
    public function getPrimeCode ()
    {
        return $this->primeCode;
    }

    /**
     * Returns the SubCodes from this instance
     *
     * @return array Returns an array of strings
     */
    public function getSubCodes ()
    {
        return $this->subCodes;
    }

}

?>
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
 * @package Soap
 */

namespace r8\Soap;

/**
 * Soap server fault interrupt
 *
 * This exception is used by the Soap server class to halt server
 * execution and return a soap fault
 */
class Fault extends \r8\Exception\Interrupt
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
     * The role associated with the current error
     *
     * @var String
     */
    private $role;

    /**
     * Any random details associated with this fault
     *
     * @var Array
     */
    private $details = array();

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
            \r8\ary\compact(
                array_map(
                	'\r8\str\stripW',
                    \r8\ary\flatten( $subCodes )
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

    /**
     * Returns the Role that was being processed when this fault was encountered
     *
     * @return String The URI of the Role
     */
    public function getRole ()
    {
        return $this->role;
    }

    /**
     * Sets the Role that was being processed when this fault was encountered
     *
     * @param String $role The Role URI
     * @return \r8\Soap\Fault Returns a self reference
     */
    public function setRole ( $role )
    {
        $role = r8(new \r8\Filter\URL)->filter( $role );
        $this->role = empty($role) ? NULL : $role;
        return $this;
    }

    /**
     * Sets an array of details to send back in the fault
     *
     * @param array $details The list of details
     * @return \r8\Soap\Fault Return a self reference
     */
    public function setDetails ( array $details )
    {
        $this->details = $details;
        return $this;
    }

    /**
     * Returns the Details of this fault
     *
     * @return array
     */
    public function getDetails ()
    {
        return $this->details;
    }

}

?>
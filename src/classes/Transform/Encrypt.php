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
 * @package Transform
 */

namespace r8\Transform;

/**
 * Encrypts and Decrypts a string
 *
 * This class requires the MCrypt extension to work
 */
class Encrypt implements \r8\iface\Transform\Encrypt
{

    /**
     * The encryption key
     *
     * @var \r8\Seed
     */
    private $key;

    /**
     * The cipher to use for encryption
     *
     * @var String
     */
    private $cipher = 'rijndael-256';

    /**
     * The block cipher mode
     *
     * @var String
     */
    private $mode = 'ctr';

    /**
     * Constructor...
     *
     * @param \r8\Seed $key The encryption key
     * @param String $cipher The cipher to use for encryption
     * @param String $mode The block cipher mode
     */
    public function __construct ( \r8\Seed $key, $cipher = 'rijndael-256', $mode = 'ctr' )
    {
        if ( !extension_loaded('mcrypt') )
            throw new \r8\Exception\Extension("MCrypt extension required");

        $this->key = $key;
        $this->cipher = $cipher;
        $this->mode = $mode;
    }

    /**
     * Returns the encryption resource
     *
     * @return Resource
     */
    private function getResource ()
    {
        // Load the encryption resource
        $resource = mcrypt_module_open( $this->cipher, '', $this->mode, '');

        if ( $resource === FALSE )
            throw new \r8\Exception\Interaction("Unable to initialize encryption resource");

        return $resource;
    }

    /**
     * Runs the initialization routine on the resource and handles the results
     *
     * @param Resource $resource The encryption resource
     * @param String $iv The initialization vector
     * @return NULL
     */
    private function initialize ( $resource, $iv )
    {
        $key = substr(
            $this->key->getString(),
            0,
            mcrypt_enc_get_key_size( $resource )
        );

        $result = mcrypt_generic_init( $resource, $key, $iv );

        if ( $result == -3 )
            throw new \r8\Exception\Interaction("Incorrect encryption key length");

        else if ( $result == -4 )
            throw new \r8\Exception\Interaction("Unable to allocate memory for encryption");

        else if ( $result === FALSE || $result < 0 )
            throw new \r8\Exception\Interaction("An unknown error occured while initializing encryption resource");
    }

    /**
     * Encrypts a string
     *
     * @param mixed $value The value to encrypt
     * @return mixed The result of the encyrption process
     */
    public function to ( $string )
    {
        // Grab the mcrypt resource
        $resource = $this->getResource();

        // Create an initialization vector
        $iv = mcrypt_create_iv(
            mcrypt_enc_get_iv_size( $resource ),
            stripos(PHP_OS, "WIN") === FALSE ? MCRYPT_RAND : MCRYPT_DEV_RANDOM
        );

        // Apply the IV and key to the resource
        $this->initialize( $resource, $iv );

        // Do the actual encryption
        $encrypted = mcrypt_generic( $resource, (string) $string );

        // Clean up the resource
        mcrypt_generic_deinit( $resource );
        mcrypt_module_close( $resource );

        // Prepend the IV to make the encrypted data more portable
        return $iv . $encrypted;
    }

    /**
     * Decrypts a string
     *
     * @param mixed $value The value to decode
     * @return mixed The original, unencrypted value
     */
    public function from ( $string )
    {
        $string = (string) $string;

        $resource = $this->getResource();

        // Pull the IV off the front of the string
        $ivLength = mcrypt_enc_get_iv_size( $resource );
        $iv = substr( $string, 0, $ivLength );
        $string = substr( $string, $ivLength );

        if ( strlen($iv) != $ivLength || empty($string) )
            throw new \r8\Exception\Interaction("Unable to derive initialization vector");

        $this->initialize( $resource, $iv );

        return mdecrypt_generic( $resource, $string );
    }

}


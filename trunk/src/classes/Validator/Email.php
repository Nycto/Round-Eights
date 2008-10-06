<?php
/**
 * Validation class
 *
 * @package Validator
 */

namespace cPHP::Validator;

/**
 * Validates an e-mail address
 *
 * This validator is much looser than the RFC. There are RFC compatible e-mail
 * addresses that will not make it through this test. However, this will do
 * a good job at accepting most real world e-mail addresses. It will also
 * try to spit out an error that describes what the specific problem is.
 *
 * If it is *really* needed, perhaps an RFC compliant flag could be added
 * to the constructor.
 *
 * Information was taken from wikipedia:
 * http://en.wikipedia.org/wiki/Email_address
 *
 * As well as the following article:
 * http://www.hm2k.com/posts/what-is-a-valid-email-address
 */
class Email extends ::cPHP::Validator
{

    /**
     * Validates an e-mail address
     */
    public function process ( $value )
    {
        $value = ::cPHP::strval( $value );
        
        $atCount = substr_count($value, "@");
        if ( $atCount == 0 )
            return "Email Address must contain an 'at' (@) symbol";
        
        if ( $atCount > 1 )
            return "Email Address must only contain one 'at' (@) symbol";
        
        if ( ::cPHP::strContains(" ", $value) )
            return "Email Address must not contain spaces";
        
        if ( ::cPHP::strContains("\n", $value) || ::cPHP::strContains("\r", $value) )
            return "Email Address must not contain line breaks";
        
        if ( ::cPHP::strContains("\t", $value) )
            return "Email Address must not contain tabs";
        
        if ( preg_match('/\.\.+/', $value) )
            return "Email Address must not contain repeated periods";
        
        if ( preg_match('/[^a-z0-9'. preg_quote('!#$%&\'*+-/=?^_`{|}~@.[]', '/') .']/i', $value) )
            return "Email Address contains invalid characters";
        
        if ( ::cPHP::endsWith($value, ".") )
            return "Email Address must not end with a period";
        
        
        list( $local, $domain ) = explode("@", $value);
        
        if ( ::cPHP::startsWith($local, ".") )
            return "Email Address must not start with a period";
        
        // This is hard to describe to a user, so just give them a vague description
        if ( ::cPHP::endsWith($local, ".") )
            return "Email Address is not valid";
        
        if ( strlen($local) > 64 || strlen($domain) > 255 )
            return "Email Address is too long";
        
        $regex = '/'
            .'^'
            .'[\w!#$%&\'*+\/=?^`{|}~.-]+'
            .'@'
            .'(?:[a-z\d][a-z\d-]*(?:\.[a-z\d][a-z\d-]*)?)+'
            .'\.(?:[a-z][a-z\d-]+)'
            .'$'
            .'/iD';
        
        // Do a final regex to match the basic form
        if ( !preg_match($regex, $value) )
            return "Email Address is not valid";
        
    }

}

?>
<?php
/**
 * Validation class
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package Validators
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
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
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
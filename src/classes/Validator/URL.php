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
 * Validates a URL
 */
class URL extends ::cPHP::Validator
{
    
    /**
     * Flags that a relative URL should be allowed
     */
    const ALLOW_RELATIVE = 1;
    
    /**
     * Any flags to pass to the is_empty function
     */
    protected $flags = 0;
    
    /**
     * Constructor...
     *
     * @param Integer $flags Any flags to pass to the is_empty function. For
     *      more details, take a look at that function
     */
    public function __construct ( $flags = 0 )
    {
        $this->flags = max( intval($flags), 0 );
    }

    /**
     * Validates a URL
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !is_string($value) )
            return "URL must be a string";
        
        if ( ::cPHP::strContains(" ", $value) )
            return "URL must not contain spaces";
        
        if ( ::cPHP::strContains("\t", $value) )
            return "URL must not contain tabs";
        
        if ( ::cPHP::strContains("\n", $value) || ::cPHP::strContains("\r", $value) )
            return "URL must not contain line breaks";
        
        if ( preg_match('/[^a-z0-9'. preg_quote('$-_.+!*\'(),{}|\\^~[]`<>#%";/?:@&=', '/') .']/i', $value) )
            return "URL contains invalid characters";
        
        if ( $this->flags & self::ALLOW_RELATIVE ) {
            
            $parsed = @parse_url( $value );
            
            if ( $parsed === FALSE )
                return "URL is not valid";
            
        }
        
        else if ( !filter_var( $value, FILTER_VALIDATE_URL ) ) {
            return "URL is not valid";
        }
    }

}

?>
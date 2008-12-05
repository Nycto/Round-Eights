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

namespace cPHP\Validator;

/**
 * Validates an uploaded file based on the field name
 */
class FileUpload extends \cPHP\Validator
{

    /**
     * Returns the value of the $_FILE variable
     *
     * This has been added to make it easier to unit test. By mocking this class
     * and overwriting this method, you can make the rest of the methods think
     * that a file was uploaded
     *
     * @return Array
     */
    protected function getUploadedFiles ()
    {
        return $_FILES;
    }

    /**
     * Wrapper for the is_uploaded_file method
     *
     * This has been added to make it easier to unit test. By mocking this class
     * and overwriting this method, you can make the rest of the methods think
     * that a file was uploaded
     *
     * @return Array
     */
    protected function isUploadedFile ( $file )
    {
        return is_uploaded_file( $file );
    }

    /**
     * Validates an uploaded file based on the field name
     *
     * @param String $field The name of the file upload field being validated
     *      This is NOT the name of the file. This is the index that appears
     *      in the $_FILES global array
     * @return String Any errors encountered
     */
    protected function process ( $field )
    {
        $field = \cPHP\Filter::Variable()->filter( $field );

        if ( !\cPHP\Validator::Variable()->isValid( $field ) )
            throw new \cPHP\Exception\Argument( 0, "Field Name", "Must be a valid PHP variable name" );

        $files = $this->getUploadedFiles();

        if ( !isset($files[$field]) )
            return "No file was uploaded";

        // Handle any explicit errors that PHP gives us
        switch ( $files[$field]['error']) {

            case 0:
                break;

            case UPLOAD_ERR_INI_SIZE:
                return "File exceeds the server's maximum allowed size";

            case UPLOAD_ERR_FORM_SIZE:
                return "File exceeds the maximum allowed size";

            case UPLOAD_ERR_PARTIAL:
                return "File was only partially uploaded";

            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded";

            case UPLOAD_ERR_NO_TMP_DIR:
                return "No temporary directory was defined on the server";

            case UPLOAD_ERR_CANT_WRITE:
                return "Unable to write the uploaded file to the server";

            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension has restricted this upload";

            default:
                return "An unknown error occured";

        }

        if (!$this->isUploadedFile($files[$field]['tmp_name']))
            return "File is restricted";

        if ( @filesize($files[$field]['tmp_name']) == 0 )
            return "Uploaded file is empty";

        if ( !is_readable($files[$field]['tmp_name']) )
            return "Uploaded file is not readable";
    }

}

?>
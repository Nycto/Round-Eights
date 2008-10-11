<?php
/**
 * Primary commonPHP include file
 */

// Mark that commonPHP has been included
define("cPHP_included", TRUE);

// mark the location of the commonPHP library
if ( !defined("cPHP_dir") ) {
    $commonPHPdir = str_replace("\\", "/", __DIR__);
    $commonPHPdir = rtrim( $commonPHPdir, "/" ) ."/";
    define("cPHP_dir", $commonPHPdir);
    unset($commonPHPdir);
}

if (!defined("cPHP_dir_functions"))
    define("cPHP_dir_functions", cPHP_dir ."functions/");

if (!defined("dir_classes"))
    define("cPHP_dir_classes", cPHP_dir ."classes/");
    
if (!defined("dir_interfaces"))
    define("cPHP_dir_interfaces", cPHP_dir ."interfaces/");

/**
 * This is temporary,... auto loader
 */
function __autoload ( $class ) {
    
    $class = explode("::", $class);
    array_shift( $class );
    
    $first = reset( $class );
    
    if ( $first == "iface" ) 
        $class = cPHP_dir_interfaces . implode( "/", array_slice( $class, 1 ) ) .".php";
        
    else
        $class = cPHP_dir_classes . implode( "/", $class ) .".php";
    
    if ( file_exists( $class ) )
        require_once $class;
    
}

/**
 * Set up custom exception handling
 */
set_exception_handler(function ( $exception ) {

    // If we are running in script mode, we don't need HTML
    if (_LOCAL) {
        echo "FATAL ERROR: Uncaught Exception Thrown:\n" .$exception;
    }
    else {

        echo "<div class='phpException'>\n"
            ."<h3>Fatal Error: Uncaught Exception Thrown</h3>\n";

        if ( $exception instanceof GeneralError )
            echo $exception->getVerboseHTML();
        else
            echo "<pre>". $exception ."</pre>";

        echo "</div>";

    }
});

/**
 * Function flags
 */

// Used by is_empty to define what is allowed
define ("ALLOW_NULL", 1);
define ("ALLOW_FALSE", 2);
define ("ALLOW_ZERO", 4);
define ("ALLOW_BLANK", 8);
define ("ALLOW_SPACES", 16);
define ("ALLOW_EMPTY_ARRAYS", 32);

// Used by stripW to define what to keep
define ("ALLOW_UNDERSCORES", 64);
define ("ALLOW_NEWLINES", 128);
define ("ALLOW_TABS", 256);
define ("ALLOW_DASHES", 512);


/**
 * Include the function files
 */
require_once cPHP_dir_functions ."general.php";
require_once cPHP_dir_functions ."numbers.php";
require_once cPHP_dir_functions ."strings.php";
require_once cPHP_dir_functions ."debug.php";


/**
 * define a few useful constants
 */

//$commonPHP_host = 'SERVER_NAME';
$commonPHP_host = 'HTTP_HOST';

cPHP::defineIf ( "_IP", array_key_exists("SERVER_ADDR", $_SERVER) ? $_SERVER['SERVER_ADDR'] : NULL );
cPHP::defineIf ( "_QUERY", array_key_exists("QUERY_STRING", $_SERVER) && !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : NULL);
cPHP::defineIf ( "_PORT", isset($_SERVER['SERVER_PORT']) ? intval($_SERVER['SERVER_PORT']) : NULL );
cPHP::defineIf ( "_PROTOCOL", isset($_SERVER['SERVER_PROTOCOL']) ? strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], "/"))) : NULL );

cPHP::defineIf ( "_BASENAME", basename($_SERVER['SCRIPT_FILENAME']) );

$commonPHP_base = pathinfo(_BASENAME);
cPHP::defineIf( "_FILENAME", $commonPHP_base['filename'] );

if ( array_key_exists('extension', $commonPHP_base) )
    cPHP::defineIf( "_EXTENSION", $commonPHP_base['extension'] );
else
    cPHP::defineIf( "_EXTENSION", NULL );
    
unset( $commonPHP_base );

cPHP::defineIf( "_CWD", rtrim( realpath( getcwd() ), "/" ) ."/" );



// If these aren't set, we're running a local script
if ( !isset($_SERVER['SHELL']) ) {

    cPHP::defineIf ("_LOCAL", FALSE);

    // This confirms that the host is not set to the IP
    if ($_SERVER['SERVER_ADDR'] != $_SERVER[ $commonPHP_host ] && preg_match("/^(?:(.*)\\.)?([^\\.\\:]+)\\.([^\\.\\:]+)(?:\\:([0-9]*))?$/", $_SERVER[ $commonPHP_host ], $commonPHP_domain)) {
        cPHP::defineIf ("_SUBDOMAIN", isset($commonPHP_domain[1]) && !is_empty($commonPHP_domain[1])?$commonPHP_domain[1]:"www");
        cPHP::defineIf ("_SLD", $commonPHP_domain[2]);
        cPHP::defineIf ("_TLD", $commonPHP_domain[3]);
        cPHP::defineIf ("_DOMAIN", $commonPHP_domain[2] . "." . $commonPHP_domain[3]);
        cPHP::defineIf ("_HOST", (is_null(_SUBDOMAIN)?"":_SUBDOMAIN .".") ._DOMAIN);
    }
    else {
        cPHP::defineIf ("_SUBDOMAIN", NULL);
        cPHP::defineIf ("_SLD", NULL);
        cPHP::defineIf ("_TLD", NULL);
        cPHP::defineIf ("_DOMAIN", NULL);
        cPHP::defineIf ("_HOST", $_SERVER['SERVER_ADDR']);
    }

    unset($commonPHP_domain);

    $commonPHP_dir = str_replace("\\", "/", $_SERVER['SCRIPT_NAME']);
    $commonPHP_dir = strHead($commonPHP_dir, "/");
    cPHP::defineIf ("_PATH", $commonPHP_dir);
    cPHP::defineIf ("_ABSOLUTE_PATH", strWeld(_HOST .(_PORT != 80?":". _PORT:NULL), $commonPHP_dir, "/"));

    $commonPHP_dir = str_replace("\\", "/", dirname($commonPHP_dir));
    $commonPHP_dir = strTail($commonPHP_dir, "/");
    cPHP::defineIf("_DIR", $commonPHP_dir);
    cPHP::defineIf("_ABSOLUTE_DIR", strWeld(_HOST .(_PORT != 80?":". _PORT:NULL), $commonPHP_dir, "/"));
    unset($commonPHP_dir);

    // Through much toil I discovered a problem with this snippet. It occurs when PHP is
    // loaded as a CGI module. Its a bug in PHP. You can get more info about it here:
    // http://bugs.php.net/bug.php?id=25047
    if (array_key_exists('PATH_INFO', $_SERVER) && !is_empty($_SERVER['PATH_INFO'])) {
        cPHP::defineIf("_PATH_INFO", $_SERVER['PATH_INFO']);
    }
    // If path_info does not exist exists, try to create it
    else if (array_key_exists('REQUEST_URI', $_SERVER) && array_key_exists('SCRIPT_NAME', $_SERVER)) {

        // strip the query off the URL
        $requestURI = explode("?", $_SERVER['REQUEST_URI'], 2);
        $requestURI = reset($requestURI);

        // If the requestURI is longer than the script name, that means there are faux directories!
        if (strlen($requestURI) > strlen($_SERVER['SCRIPT_NAME']))
            cPHP::defineIf("_PATH_INFO", substr($requestURI, strlen($_SERVER['SCRIPT_NAME'])));
        else
            cPHP::defineIf ("_PATH_INFO", NULL);

        unset($requestURI);
    }
    else {
        cPHP::defineIf ("_PATH_INFO", NULL);
    }

    cPHP::defineIf(
            "_ABSOLUTE_URL",
            _PROTOCOL . "://"
            ._ABSOLUTE_PATH
            .(!is_empty(_PATH_INFO)?strHead(_PATH_INFO, "/"):NULL)
            .(!is_empty(_QUERY)?strHead(_QUERY, "?"):NULL)
        );

    cPHP::defineIf(
            "_RELATIVE_URL",
            _PATH
            .(!is_empty(_PATH_INFO)?strHead(_PATH_INFO, "/"):NULL)
            .(!is_empty(_QUERY)?strHead(_QUERY, "?"):NULL)
        );

}
else {
    cPHP::defineIf ("_LOCAL", TRUE);

    cPHP::defineIf ("_SUBDOMAIN", NULL);
    cPHP::defineIf ("_SLD", NULL);
    cPHP::defineIf ("_TLD", NULL);
    cPHP::defineIf ("_DOMAIN", NULL);
    cPHP::defineIf ("_HOST", NULL);

    $commonPHP_dir = str_replace("\\", "/", $_SERVER['SCRIPT_NAME']);
    cPHP::defineIf ("_PATH", $commonPHP_dir);
    cPHP::defineIf ("_ABSOLUTE_PATH", $commonPHP_dir);

    $commonPHP_dir = dirname($commonPHP_dir);
    cPHP::defineIf ("_DIR", $commonPHP_dir);
    cPHP::defineIf ("_ABSOLUTE_DIR", $commonPHP_dir);

    unset($commonPHP_dir);

    cPHP::defineIf ("_PATH_INFO", NULL);

    cPHP::defineIf ("_ABSOLUTE_URL", NULL);
    cPHP::defineIf ("_RELATIVE_URL", NULL);
}

unset($commonPHP_host);

/*

echo "<pre>\n"

    ."_IP: ". _IP ."\n"
    ."_QUERY: ". _QUERY ."\n"
    ."_PORT: ". _PORT ."\n"
    ."_PROTOCOL: ". _PROTOCOL ."\n"

    ."_BASENAME: ". _BASENAME ."\n"
    ."_FILENAME: ". _FILENAME ."\n"
    ."_EXTENSION: ". _EXTENSION ."\n"
    ."_CWD: ". _CWD ."\n"

    ."_LOCAL: ". ( _LOCAL ? "TRUE" : "FALSE" ) ."\n"

    ."_SUBDOMAIN: ". _SUBDOMAIN ."\n"
    ."_SLD: ". _SLD ."\n"
    ."_TLD: ". _TLD ."\n"
    ."_DOMAIN: ". _DOMAIN ."\n"
    ."_HOST: ". _HOST ."\n"

    ."_PATH: ". _PATH ."\n"
    ."_ABSOLUTE_PATH: ". _ABSOLUTE_PATH ."\n"

    ."_DIR: ". _DIR ."\n"
    ."_ABSOLUTE_DIR: ". _ABSOLUTE_DIR ."\n"

    ."_PATH_INFO: ". _PATH_INFO ."\n"

    ."_ABSOLUTE_URL: ". _ABSOLUTE_URL ."\n"
    ."_RELATIVE_URL: ". _RELATIVE_URL ."\n"

    ."</pre>";
*/
?>
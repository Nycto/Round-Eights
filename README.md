# Round Eights

Round Eights is an open source, object oriented, test driven PHP 5.3
library designed to help web application coders tackle common hurdles.


## Installing Round Eights

Round Eights comes in two flavours, the raw source files and the single file PHP
archive. Both are equivilent as far as functionality is concerned.


## Installing the PHP archive

Included in your download is Round Eights.phar. If you choose this path, this file
is all you need. Simply copy it to your chosen destination, and include it like
you would any other file, for example:

    <?php
    require_once "/path/to/RoundEights.phar";
    ?>

For more information about phar files, visit the PHP manual here:

http://www.php.net/phar


## Installing the Source Files

Optionally, you may simply copy the "src" directory and all of its contents to
your code base, and include the contained "Round Eights.php" file, for example:

    <?php
    require_once "/path/to/src/RoundEights.php";
    ?>


## Requirements

Round Eights requires version 5.3.1 or later of PHP. There are a few components that
require specific PHP extensions.
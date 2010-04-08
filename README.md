# Round Eights

Round Eights is an open source, object oriented web application library built
on PHP 5.3. It is designed to take care of the fundamentals, so you can spend
more time building solid apps.

The reasons to use it are straight forward:

 * It's Simple. You can drop it in and start using it immediately. The basic,
   common functionality is easy to access, but it still offers granularity when
   you need it.
 * It's Well Tested. By making use of PHPUnit's powerful framework, Round Eights
   uses extensive unit tests to stamp out bugs and ensure solid code.
 * It has a "Use-at-Will" Design. Round Eights strives to offer you a common
   sense solution when you need one, and get out of your way when you don't.
   This paradigm offers you the freedom to code how you want, rather than
   constantly having to reference documentation to make sure you're following
   someone else's standards.
 * It's Composition Oriented. When it comes down to the choice between
   inheritance and composition, Round Eights tries to use composition. This
   allows for extensive modularity and code re-use. Inheritance is still used
   when appropriate, but only as a polymorphic enhancement for building object
   graphs.


# What do you get?

The list of features is long, but here are a few highlights:

 * Simple Autoloading
 * Extended error handling
 * A full suite of data Filters and Validators
 * Form generation objects
 * OO interfaces for interacting with the scripts environment
 * Encryption helper objects
 * Simple Database abstraction
 * A unified caching interface
 * And a whole lot more!


# Installing Round Eights

Round Eights comes in two flavors, the raw source files and the single file PHP
archive. Both are equivalent as far as functionality is concerned.


## Installing the PHP archive

Included in your download is RoundEights.phar. If you choose this path, this
file is all you need. You can think of phar files like Java's jar files:
everything is swept up into a single file that automatically expands when you
use it.

To use it, simply copy it to your chosen destination, and include it like you
would any other php file. For example:

    <?php
    require_once "/path/to/RoundEights.phar";
    ?>

For more information about phar files, visit the PHP manual here:

http://www.php.net/phar


## Installing the Source Files

If you prefer, you can simply copy the "src" directory and all of its contents
to your code base. Then, include the contained "RoundEights.php" file, like so:

    <?php
    require_once "/path/to/src/RoundEights.php";
    ?>


# Requirements

Round Eights requires version 5.3.1 or later of PHP. There are a few components
that require specific PHP extensions, but the dependencies are usually obvious.
If something is missing, an exception will be raised that lets you know exactly
what you need.

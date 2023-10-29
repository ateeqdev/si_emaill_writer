<?php

namespace si_Campaigner;

/**
 * This methods is responsible for loading files
 *
 * @method load
 * @param string $class The fully-qualified class name.
 * @return void
 */
function load($class)
{

    // project-specific namespace prefix
    $prefix = 'si_Campaigner';

    // base directory for the namespace prefix
    $base_dir = dirname(__FILE__);

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        $GLOBALS['log']->debug('si_Campaigner - Including Class: ' . $class);
        require $file;
    }
};

$GLOBALS['log']->debug('si_Campaigner - Initiating Autoloader');
$classes = array(
    // Sugar\Helpers
    'si_Campaigner\Sugar\Helpers\DBHelper',
    'si_Campaigner\Sugar\Helpers\UpdateBean',
    // apiCalls
    'si_Campaigner\apiCalls\ApiAdapter',
    'si_Campaigner\apiCalls\MailApiAdapter',
    'si_Campaigner\apiCalls\OAuthApiAdapter',
    // Google
    'si_Campaigner\Google\AccessToken',
);
foreach ($classes as $class) {
    load($class);
}
$GLOBALS['log']->debug('si_Campaigner - Autoloader Completed');

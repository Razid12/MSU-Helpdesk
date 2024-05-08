<?php
/**
 * PHPMailer SPL autoloader.
 * PHP Version 5
 * @package PHPMailer
 * @link https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 * @author Marcus Bointon <phpmailer@synchromedia.co.uk>
 */

// Load Composer's autoloader if available
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    // Composer autoloader
    require_once dirname(__DIR__) . '/vendor/autoload.php';
} elseif (file_exists(dirname(__DIR__) . '/src/PHPMailer.php')) {
    // Fallback to loading classes manually if the PHPMailer src directory exists
    require_once dirname(__DIR__) . '/src/PHPMailer.php';
    require_once dirname(__DIR__) . '/src/Exception.php';
    require_once dirname(__DIR__) . '/src/SMTP.php';
} else {
    // Error: Missing PHPMailer classes
    die('Unable to load PHPMailer classes. Autoloader not found.');
}

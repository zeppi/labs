<?php
/**
 * PHP versions 5.3.8
 *
 * @copyright  2013 Zeppi <giuslee@gmail.com>
 * @author     giuslee@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */

// Initialisation
define('ROOT', realpath('../'));

define('APP', ROOT . DIRECTORY_SEPARATOR . "app");
define('LOGS', APP . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "app.log");
define('CACHE', APP . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR);
define('DATABASES', APP . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "profile.sqlite");
define('VIEWS', APP . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "ZpBiorythme" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR );


// Load Silex
$loader = require ROOT. DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";
$app = new Silex\Application();

// Register autoloader
$app['autoloader'] = $app->share(function()use($loader){return $loader;});
$app['autoloader']->add('ZpBiorythme', ROOT . DIRECTORY_SEPARATOR . 'app' .DIRECTORY_SEPARATOR  . 'src');

/**
 * @return Composer\Autoload\ClassLoader
 */
function get_autoloader() {global $app; return $app['autoloader'];}

// Register logs ______________________________________________________________
use Silex\Provider\MonologServiceProvider;

$app->register(
    new MonologServiceProvider(),
    array(
        'monolog.logfile' => LOGS,
        'monolog.name'    => 'app',
        'monolog.level'   =>  Monolog\Logger::WARNING
    ));

/**
 * @return  \Monolog\Logger
 */
function get_logger() {global $app; return $app['monolog'];}

// Register views _____________________________________________________________
use Silex\Provider\TwigServiceProvider;

$app->register(
    new TwigServiceProvider(),
    array(
        'twig.options' =>
        array(
            'cache' => CACHE,
            'strict_variables' => true),
        'twig.path' =>
        array(VIEWS)
    ));

/**
 * @return  \Twig_Environment
 */
function get_views() {global $app; return $app['twig'];}

// Register cache _____________________________________________________________
$app->register(
    new Silex\Provider\HttpCacheServiceProvider(),
    array(
        'http_cache.cache_dir' => CACHE,
    ));

/**
 * @return  \Silex\HttpCache
 */
function get_cache() {global $app; return $app['http_cache'];}

// Register ORM _______________________________________________________________
use ZpBiorythme\Services\RedBean\RedBeanServiceProvider;

$app->register(
    new RedBeanServiceProvider(),
    array(
        'db.dsn' => sprintf('sqlite:%s', DATABASES)
    ));

/**
 * @return  \RedBean_OODB
 */
function get_db() {global $app; return $app['db'];}

// Register Facebook __________________________________________________________
use ZpBiorythme\Services\Facebook\FacebookServiceProvider;

$app->register(
    new FacebookServiceProvider(),
    array(
        'fb.appId' => getenv('FB_APPID'),
        'fb.secret' => getenv('FB_SECRET'),
    ));

/**
 * @return  \Facebook
 */
function get_facebook() {global $app; return $app['fb'];}

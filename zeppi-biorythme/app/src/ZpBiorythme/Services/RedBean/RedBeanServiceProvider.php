<?php
/**
 * PHP versions 5.3.8
 *
 * @copyright  2013 Zeppi <giuslee@gmail.com>
 * @author     giuslee@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace ZpBiorythme\Services\RedBean;
 
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * RedBean service provider
 *
 * @author     giuslee@gmail.com
 */
class RedBeanServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['db'] = $app->share(function() use ($app)
        {
            return \RedBean_Facade::setup($app['db.options']['dsn'])->getRedBean();
        });
    }
    
    public function boot(Application $app){}
}

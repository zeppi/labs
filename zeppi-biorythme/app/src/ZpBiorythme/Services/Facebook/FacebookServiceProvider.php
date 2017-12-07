<?php
/**
 * PHP versions 5.3.8
 *
 * @copyright  2013 Zeppi <giuslee@gmail.com>
 * @author     giuslee@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace  ZpBiorythme\Services\Facebook;
 
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Facebook service provider
 *
 * @author     giuslee@gmail.com 
 */
class FacebookServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['fb'] = $app->share(function() use ($app)
        {
            return new \Facebook(
                    array(
                      'appId'  => $app['fb.appId'],
                      'secret' => $app['fb.secret']));
        });
    }
    
    public function boot(Application $app){}
}

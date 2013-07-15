<?php
/**
 * PHP versions 5.3.8
 *
 * @copyright  2013 Zeppi <giuslee@gmail.com>
 * @author     giuslee@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */


require_once "../app/src/bootstrap.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZpBiorythme\Models\Birthday;
use ZpBiorythme\Models\BirthdayExcpetion;
use ZpBiorythme\Models\Biorythme;

// Controlers _________________________________________________________________
$app->match('/', function() use ($app) {

    try
    {
        // Facebook identification
        $user = get_facebook()->getUser();
        if(!$user) return redirect_to_login();

        // Retrieve informations about logged user
        $infos = get_facebook()->api('/me');
        $birthday = retrieveBirthday($infos['birthday']);

        // Store user in database
        $player = get_db()->load('players', $infos['id']);
        if(!$player) $player = get_db()->dispense('players');

        $player->id = $infos['id'];
        $player->name = $infos['name'];
        $player->gender = $infos['gender'];
        $player->birthday = $infos['birthday'];
        $player->updated_time = $infos['updated_time'];

        get_db()->store($player);

        // process to view
        $response = new Response(
            get_views()->render('index.twig',
                array(
                    'biorithme' => Biorythme::getBiorythme($birthday, new \DateTime('now')),
                    'birthday' => $birthday->format('d/m/Y')
                )));

        $response->setTtl(10);

        return $response;

    }
    catch(Exception $f)
    {
        get_logger()->alert($f->getMessage());
        return new Response('Houston nous avons un problème!', 500);
    }
});

/**
 * Redirect to login
 *
 * @return Response
 */
function redirect_to_login()
{
    $fb_login = get_facebook()->getLoginUrl(array('scope' => 'user_birthday'));

    return new Response(
        sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Redirecting to %1$s</title>
        <script type="text/javascript">
            window.location.href = "%1$s";
        </script>
    </head>
    <body>
        Redirecting to %1$s
    </body>
</html>', $fb_login), 201);
}

/**
 * Retrieve Birthday
 *
 * @param string $birthday
 * @return Birthday
 */
function retrieveBirthday($birthday)
{
    try
    {
        return new Birthday($birthday);
    }
    catch(BirthdayExcpetion $b)
    {
        return new Birthday('2000-01-01');
    }
}

// Run ________________________________________________________________________
get_cache()->run();
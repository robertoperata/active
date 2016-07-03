<?php
/**
 * Created by PhpStorm.
 * User: roberto
 * Date: 03/07/16
 * Time: 16.09
 */

namespace PrizUserBundle\Security;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{

    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        // redirect the user to where they were before the login process begun.
        //$referer_url = $request->headers->get('referer');
        //$response = new RedirectResponse("www.letiziasportrelax.it");
        //return $response;
        return new RedirectResponse('http://www.letiziasportrelax.it');
    }

}
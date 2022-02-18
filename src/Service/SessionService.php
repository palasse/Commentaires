<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;


class SessionService
{
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function getSession()
    {
        $session = $this->request->getSession();
        
        if (!$session){
            $session =  new Session(new NativeSessionStorage(), new AttributeBag());
        }
        
        return $session;
    }
}
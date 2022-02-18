<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

use App\Service\CommentRequestService;

class HomeController extends AbstractController

{
    #[Route("/")]
    public function ShowHome(CommentRequestService $commentRequest): Response
    {
        $session = new Session(new NativeSessionStorage(), new AttributeBag());
        $token = $session->get('token');
        $commentLimit = 5;
        $lastComments = array_slice($commentRequest->getComments(null),0, $commentLimit);

        return $this->render('home.html.twig', ["session_token" => $token,
                                                "comments" => $lastComments]);

    }

}
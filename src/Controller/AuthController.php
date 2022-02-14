<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends AbstractController
{

    public function __construct() 
    {
    }


    #[Route('/api/login', name: 'signUpViaGoogle', methods: 'POST')]
    public function signUpViaGoogle(Request $request, JWTTokenManagerInterface $JWTManager, ManagerRegistry $doctrine): Response
    {
        $userInfo = $request->request;
        $mail = $userInfo->get('email');
        $token = $userInfo->get('token');
        $entityManager = $doctrine->getManager();

        $user =  $doctrine->getRepository(User::class)->findOneByEmail($mail);
        if ($user) {

            return $this->json(['token' => $JWTManager->create($user)]);
        }

        $newUser = new User();

        if ($mail && $token)
        {
            $newUser->setEmail($mail);
            $newUser->setApiKey($token);
            $newUser->setRoles(["ROLE_USER"]);
            $entityManager->persist($newUser);
            $entityManager->flush();
            
            return $this->json(['token' => $JWTManager->create($newUser)]);
        }
        $errorData = [
            'type' => 'data_not_find',
            'title' => 'No user found',
        ];
        return new JsonResponse($errorData, 400);
    }

}
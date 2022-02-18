<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CommentRequestService
{
    private $params;
    private $Httpclient;
    private$session;

    public function __construct(ContainerBagInterface $params, HttpClientInterface $Httpclient, SessionService $sessionService)
    {
        $this->params = $params;
        $this->Httpclient = $Httpclient;
        $this->session = $sessionService;
    }


    public function getComments($id_page)
    {
        
        $session = $this->session->getSession();
        $token = json_decode($session->get('token'), true);
        $uri = $this->params->get("app.api_uri");

        // Retourn vide si utilisateur non connecter
        if(!$token)
            return [];

        if($id_page)
            $uri .= '/comments?id_article='.$id_page;
        else
            $uri .= '/comments';

        $response = $this->Httpclient->request('GET',$uri, [
            'verify_peer' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '. $token["token"],
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            return [];
        }
        

        return json_decode($response->getContent(), true);
    }

    
    public function sendComments($id_page, $comment)
    {
        
        $session = $this->session->getSession();
        $token = json_decode($session->get('token'), true);
        $uri = $this->params->get("app.api_uri");
        $uri .= '/comments';

        // Retourn vide si utilisateur non connecter
        if(!$token && !$id_page)
            return [];

        $response = $this->Httpclient->request('POST',$uri, [
            'verify_peer' => false,
            'headers' => [
                'Authorization' => 'Bearer '. $token["token"],
            ],
            'json' => [
                'idArticle' => (int)$id_page,
                'content' =>$comment,
            ]
        ]);

        if (200 !== $response->getStatusCode()) {
            return [];
        }

        return json_decode($response->getContent(), true);
    }

    public function sendResponseComments($idComment, $comment)
    {
        
        $session = $this->session->getSession();
        $token = json_decode($session->get('token'), true);
        $uri = $this->params->get("app.api_uri");
        $uri .= '/comment_responses';
        $IRI = "/api/comments/".$idComment;

        // Retourn vide si utilisateur non connecter
        if(!$token && !$idComment)
            return [];

        $response = $this->Httpclient->request('POST',$uri, [
            'verify_peer' => false,
            'headers' => [
                'Authorization' => 'Bearer '. $token["token"],
            ],
            'json' => [
                'comment' => $IRI,
                'content' =>$comment,
            ]
        ]);

        if (200 !== $response->getStatusCode()) {
            return [];
        }

        return json_decode($response->getContent(), true);
    }
}
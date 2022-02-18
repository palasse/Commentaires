<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use App\Form\CommentForm;
use App\Form\CommentResponseForm;
use App\Service\CommentRequestService;

class ArticlesController extends AbstractController

{
    #[Route("article/{slug}", name: "article")]
    public function ShowArticles($slug, Request $request, CommentRequestService $commentRequest): Response
    {
        $uri = "articles/article" . $slug . ".html.twig";
        $comments = $commentRequest->getComments($slug);

        $commentForm = $this->createForm(CommentForm::class);
        $commentResponseForm = $this->createForm(CommentResponseForm::class);

        // gestion des réponses
        foreach ($comments as $comment) {
            $commentResponseForms[$comment["id"]] = $commentResponseForm->createView();
        }

        $commentResponseForm->handleRequest($request);
        
        if ($commentResponseForm->isSubmitted() && $commentResponseForm->isValid()) {
            $commentResponseData = $commentResponseForm->getData();

            $commentRequest->sendResponseComments($commentResponseData["idComment"], $commentResponseData["text"]);
        }

        // gestion des commentaires
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentData = $commentForm->getData();

            $commentRequest->sendComments($slug, $commentData["text"]);
        }

        if (count($comments) > 0) {
            return $this->render($uri, [
                "comments" => $comments,
                "commentForm" => $commentForm->createView(),
                "commentResponseForm" => $commentResponseForms
            ]);
        } else {
            return $this->render($uri, [
                "comments" => $comments,
                "commentForm" => $commentForm->createView()
            ]);
        }
    }
}

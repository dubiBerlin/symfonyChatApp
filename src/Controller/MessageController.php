<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\ConversationRepository;
use App\Entity\Participant;
use App\Entity\Conversation;
use App\Entity\User;
 /**
  * @Route("/messages", name="messages.")
  */
class MessageController extends AbstractController {
  
    /**
     * @Route("/{id}", name="getMessages", methods={"GET"})
     * @param Request request
     * @param Conversation $conversation
     * @return Response
     */
    public function index(Request $request, Conversation $conversation)
    {
        $this->denyAccessUnlessGranted("view", $conversation);
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
}

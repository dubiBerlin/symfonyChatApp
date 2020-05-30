<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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

        $messages = $conversation->getMessages();


        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
}

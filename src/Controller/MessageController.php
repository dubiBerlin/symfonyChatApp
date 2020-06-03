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


    const ATTRIBUTES_TO_SERIALIZE = ["id", "content", "createdAt", "mine"];

    private $messageRepository;
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, MessageRepository $messageRepository) 
    {
      $this->entityManager = $entityManager;
      $this->messageRepository = $messageRepository;
    } 
  
    /**
     * @Route("/{id}", name="getMessages")
     * @param Request request
     * @param Conversation $conversation
     * @return Response
     */
    public function index(Request $request, Conversation $conversation)
    {
        $this->denyAccessUnlessGranted("view", $conversation);

        $messages = $this->messageRepository->findMessagesByConversationId($conversation->getId());

        // dd($messages);
        if (!is_null($messages)) {
          array_map(function ($message) {
          $message->setMine($message->getUser()->getId() === $this->getUser()->getId() ? true : false);
        }, $messages);
        }
        

        // return $this->render('message/index.html.twig', [
        //     'controller_name' => 'MessageController',
        // ]);

        return $this->json($messages, Response::HTTP_OK, [] , [
          "attributes" => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
}

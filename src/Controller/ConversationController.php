<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\ConversationRepository;


/**
 * @Route("/conversations", name="conversations")
 */
class ConversationController extends AbstractController
{

    private $userRepository;

    private $entityManager;

    private $conversationRepository;

    public function __construct(
      UserRepository $userRepository, 
      EntityManagerInterface $entityManager, 
      ConversationRepository $conversationRepository
    ) 
    {
      $this->userRepository = $userRepository; 
      $this->entityManager = $entityManager;
      $this->conversationRepository = $conversationRepository;
    }

    /**
     * @Route("/", name="getConversation")
     * @param Request $request#
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Request $request)
    {
        $otherUser = $request->get("otherUser");
        $otherUser = $this->userRepository->find($otherUser);

        if (is_null($otherUser)) {
          throw new \Exception("The user was not found");
        }

        // cannot create conversation with myself
        if ($otherUser->getId() === $this->getUser()->getId()) {
          throw new \Exception("Cant create a conversation with yourself");
        }

        // Check if conversation already exists
        $conversation = $this->conversationRepository->findConversationByParticipants(
          $otherUser->getId(),
          $this->getUser()->getId()
        );

        return $this->json();
    }
}

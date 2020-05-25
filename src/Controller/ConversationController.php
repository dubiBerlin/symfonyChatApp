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
     * @Route("/{id}", name="getConversation")
     * @param Request $request#
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Request $request, int $id)
    {
        $otherUser = $request->get("otherUser");
        $otherUser = $this->userRepository->find($id);
      
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

        if (count($conversation)) {
          throw new \Exception("The conversation already exists");
        }
        $conversation = new Conversation(); 

        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);

        
        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        $this->entityManager->getConnection()->beginTransaction();
        try {
          $this->entityManager->persist($conversation);
          $this->entityManager->persist($participant);
          $this->entityManager->persist($otherParticipant);
          $this->entityManager->flush();
          $this->entityManager->commit();
        } catch (\Exception $e) {
          $this->entityManager->rollback();
          throw $e;
        }
        
        return $this->json([
          "id" => $conversation->getId()
        ], Response::HTTP_CREATED,[],[]);
    }
}

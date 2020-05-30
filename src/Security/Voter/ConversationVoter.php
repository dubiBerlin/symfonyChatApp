<?php

namespace App\Security\Voter;

use App\Entity\Conversation;
use App\Repository\ConversationRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationVoter extends Voter {

  const VIEW = "view";

  protected function supports( string $attribute, $subject) {
   return $attribute == self::VIEW && $subject instanceof Conversation;
  }

  protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
    dd($attribute, $subject, $token);
  }


}

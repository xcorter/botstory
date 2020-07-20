<?php

namespace App\Web\Security;

use App\Core\Game\Entity\Game;
use App\Core\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class GameVoter extends Voter
{
    const EDIT = 'edit';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        // only vote on `Game` objects
        if (!$subject instanceof Game) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Game object, thanks to `supports()`
        /** @var Game $game */
        $game = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($game, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Game $game, User $user): bool
    {
        return $game->belongsToUser($user);
    }
}
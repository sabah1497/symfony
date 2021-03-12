<?php
/**
 * Created by PhpStorm.
 * User: sgb638
 * Date: 06/01/20
 * Time: 15:51
 */

namespace Blogger\BlogBundle\Security;

use Blogger\BlogBundle\Entity\User;
use Blogger\BlogBundle\Entity\Album;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class AlbumVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    private $decisionManager;
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::DELETE, self::EDIT))) {
            return false;
        }
        // only vote on Post objects inside this voter
        if (!$subject instanceof Album) {
            return false;
        }
        return true;
    }
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }
        $user = $token->getUser();
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }
        if ($subject->getWriter() !== $user)
        {
            return false;
        }
        return true;
    }
}
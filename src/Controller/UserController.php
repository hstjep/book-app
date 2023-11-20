<?php

namespace App\Controller;

use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Transformer\User\UserTransformerInterface;

/**
 * Controller used to manage the application security.
 */
class UserController extends AbstractController
{
    public function __construct(
        private UserTransformerInterface $userTransformer
    )
    {
    }

    #[Route('/profile', name: 'user_profile')]
    public function index(
        #[CurrentUser] ?User $user,
        Request $request,
        AuthenticationUtils $helper,
    ): Response {
        
        return $this->render('user/profile.html.twig', [
            'user' => $this->userTransformer->transform($user)
        ]);
    }
}
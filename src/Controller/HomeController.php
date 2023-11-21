<?php

namespace App\Controller;

use App\Security\User;
use App\Transformer\User\UserTransformerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class HomeController extends AbstractController
{
    public function __construct(
        private UserTransformerInterface $userTransformer
    )
    {
    }

    #[Route('/', name: 'home_index')]
    public function index(
        #[CurrentUser] ?User $user,
        Request $request
    ): Response {
        return $this->render('home/index.html.twig', [
            'user' => $this->userTransformer->transform($user)
        ]);
    }
}
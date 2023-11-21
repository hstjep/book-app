<?php

namespace App\Controller;

use App\Domain\Repository\AuthorRepositoryInterface;
use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Domain\Repository\BookRepositoryInterface;
use App\Form\BookFormType;
use App\Transformer\User\UserTransformerInterface;
use Psr\Log\LoggerInterface;

class BookController extends AbstractController
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private AuthorRepositoryInterface $authorRepository,
        private UserTransformerInterface $userTransformer,
        private LoggerInterface $logger
    )
    {
    }

    #[Route('/authors/{author_id}/books/{book_id}/delete', name: 'book_delete', methods: ['GET', 'DELETE'])]
    public function delete(
        #[CurrentUser] ?User $user,
        Request $request
    ): Response {
        try {
            $authorId = $request->get('author_id');
            $author = $this->authorRepository->getById($authorId);

            if ($author === null) {
                throw new NotFoundHttpException('Author not found.');
            }

            $bookId = $request->get('book_id');
            $book = $this->bookRepository->getById($bookId);

            if ($book === null) {
                throw new NotFoundHttpException('Book not found.');
            }

            $this->bookRepository->remove($bookId);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return $this->redirectToRoute('author_detail', ['id' => $authorId]);
        }

        return $this->redirectToRoute('author_detail', ['id' => $authorId]);
    }

    #[Route('/authors/{author_id}/books/create', name: 'book_create', methods: ['GET'])]
    public function create(
        #[CurrentUser] ?User $user,
        Request $request
    ): Response {
        $authorId = $request->get('author_id');
        $author = $this->authorRepository->getById($authorId);

        if ($author === null) {
            throw new NotFoundHttpException('Author not found.');
        }

        // Render form
        $form = $this->createForm(BookFormType::class);

        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
            'user' => $this->userTransformer->transform($user)
        ]);
    }

    #[Route('/authors/{author_id}/books/create', name: 'book_create_action', methods: ['POST'])]
    public function createAction(
        #[CurrentUser] ?User $user,
        Request $request
    ): Response {
        $authorId = $request->get('author_id');
        $author = $this->authorRepository->getById($authorId);

        if ($author === null) {
            throw new NotFoundHttpException('Author not found.');
        }
        
        // Create book
        $form = $this->createForm(BookFormType::class);
        $form->handleRequest($request);
        $data = $form->getData();

        $response = $this->bookRepository->create($data);

        return $this->redirectToRoute('author_detail', ['id' => $response['author']['id']]);
    }
}
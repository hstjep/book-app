<?php

namespace App\Controller;

use App\Domain\Repository\AuthorRepositoryInterface;
use App\Security\User;
use App\Utils\Filter\FilterOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Domain\Repository\BookRepositoryInterface;
use App\Transformer\User\UserTransformerInterface;
use Psr\Log\LoggerInterface;

class AuthorController extends AbstractController
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
        private BookRepositoryInterface $bookRepository,
        private UserTransformerInterface $userTransformer,
        private LoggerInterface $logger
    ) {
    }

    #[Route('/authors', name: 'author_index')]
    public function index(
        #[CurrentUser] ?User $user,
        Request $request
    ): Response {

        $filterOptions = (new FilterOptions())
            ->setPage($request->get('page') ?: 1)
            ->setPageSize(10)
            ->setOrderBy($request->get('orderBy'))
            ->setOrderDirection($request->get('direction'));

        $data = $this->authorRepository->find($filterOptions);

        return $this->render('author/index.html.twig', [
            'data' => (array) $data,
            'user' => $this->userTransformer->transform($user)
        ]);
    }

    #[Route('/authors/{id}', name: 'author_detail')]
    public function detail(
        #[CurrentUser] ?User $user,
        Request $request,
        AuthenticationUtils $helper
    ): Response {

        $id = $request->get('id');
        $author = $this->authorRepository->getById($id);

        if ($author === null) {
            throw new NotFoundHttpException('Author not found.');
        }

        return $this->render('author/detail.html.twig', [
            'author' => $author,
            'user' => $this->userTransformer->transform($user)
        ]);
    }

    #[Route('/authors/{id}/delete', name: 'author_delete', methods: ['GET', 'DELETE'])]
    public function delete(
        #[CurrentUser] ?User $user,
        Request $request
    ): Response {
        $id = $request->get('id');

        try {
            $author = $this->authorRepository->getById($id);

            if ($author === null) {
                throw new NotFoundHttpException('Author not found.');
            }

            if (count($author['books']) > 0) {
                throw new \Exception('Cannot delete an author that has books.');
            }

            $this->authorRepository->remove($id);
        } catch (\Exception $ex) {
            $this->logger->error($ex->Message());
            return $this->redirectToRoute('author_index');
        }

        return $this->redirectToRoute('author_index');
    }
}

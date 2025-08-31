<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contracts\Services\AuthorServices\GetListAuthorsServiceContract;
use App\Dto\Requests\AuthorListRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/authors', name: 'api_authors_')]
final class AuthorController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(#[MapQueryString] AuthorListRequest $request, GetListAuthorsServiceContract $authorsService): JsonResponse
    {
        $authors = $authorsService->handle($request);

        return $this->json([
            'data' => $authors,
        ]);
    }
}

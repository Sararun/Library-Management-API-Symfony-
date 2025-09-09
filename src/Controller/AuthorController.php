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
    public function list(
        #[MapQueryString] AuthorListRequest $request,
        GetListAuthorsServiceContract $authorsService
    ): JsonResponse {
        $authors = $authorsService->handle(
            $request->page,
            $request->limit,
            $request->filters,
            $request->sort->field,
            $request->sort->order,
        );

        return $this->json([
            'data' => [
                'items' => $authors->items,
                'meta' => [
                    'total' => $authors->total,
                    'page' => $authors->page,
                    'limit' => $authors->limit,
                    'pages' => $authors->pages,
                    'has_next_page' => $authors->hasNextPage(),
                    'has_previous_page' => $authors->hasPreviousPage(),
                    'from' => $authors->getFrom(),
                    'to' => $authors->getTo(),
                ],
            ],
        ]);
    }
}

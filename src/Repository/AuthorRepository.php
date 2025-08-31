<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Requests\AuthorListRequest;
use App\Entity\Author;
use App\Filters\FilterBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private FilterBuilder $filterBuilder,
    ) {
        parent::__construct($registry, Author::class);
    }

    /**
     * @throws \Exception
     */
    public function getPaginatedAuthors(AuthorListRequest $request): array
    {
        $qb = $this->createQueryBuilder('a');

        $this->filterBuilder->buildFilters(
            $qb,
            Author::class,
            $request->filters,
            'a',
        );

        $qb->orderBy('a.id', 'ASC');

        $qb->setFirstResult(($request->page - 1) * $request->limit)
            ->setMaxResults($request->limit);

        $paginator = new Paginator($qb->getQuery());

        return [
            'items' => $paginator->getIterator(),
            'total' => \count($paginator),
            'page' => $request->page,
            'limit' => $request->limit,
            'pages' => ceil(\count($paginator) / $request->limit),
        ];
    }
}

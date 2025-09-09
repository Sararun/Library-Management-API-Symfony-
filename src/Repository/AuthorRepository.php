<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contracts\Repositories\AuthorRepositoryContract;
use App\Dto\PaginatedEntities;
use App\Dto\Requests\AuthorListRequest;
use App\Dto\Requests\FilterDto;
use App\Dto\Requests\SortDto;
use App\Entity\Author;
use App\Filters\FilterBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use ReflectionException;

/**
 * @extends ServiceEntityRepository<Author>
 */
final class AuthorRepository extends ServiceEntityRepository implements AuthorRepositoryContract
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly FilterBuilder $filterBuilder,
    ) {
        parent::__construct($registry, Author::class);
    }

    /**
     *
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function getPaginatedAuthors(
        int $page,
        int $limit,
        array $filters,
        string $sortField = 'id',
        string $sortOrder = Order::Ascending->value
    ): PaginatedEntities {
        $qb = $this->createQueryBuilder('a');

        $this->filterBuilder->buildFilters(
            $qb,
            Author::class,
            $filters,
            'a',
        );

        $qb->orderBy('a.' . $sortField, $sortOrder)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);


        $paginator = new Paginator($qb->getQuery());

        return new PaginatedEntities(
            items: iterator_to_array($paginator->getIterator()),
            total: count($paginator),
            page: $page,
            limit: $limit,
        );
    }
}
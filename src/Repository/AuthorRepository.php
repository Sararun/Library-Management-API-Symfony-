<?php

declare(strict_types=1);

namespace App\Repository;

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
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private FilterBuilder $filterBuilder,
    ) {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param FilterDto[] $filters
     * @param SortDto|null $sort
     * @return array
     * @throws ReflectionException
     */
    public function getPaginatedAuthors(
        int $page,
        int $limit,
        array $filters,
        ?SortDto $sort = null
    ): array {
        $qb = $this->createQueryBuilder('a');

        $this->filterBuilder->buildFilters(
            $qb,
            Author::class,
            $filters,
            'a',
        );

        if ($sort) {
            $qb->orderBy('a.' . $sort->field, $sort->order);
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb->getQuery());


        return [
            'items' => $paginator->getIterator(),
            'total' => \count($paginator),
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil(\count($paginator) / $limit),
        ];
    }
}

<?php

namespace App\Repository;

use App\Dto\Requests\AuthorListRequest;
use App\Entity\Author;
use App\Enums\FiltersTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Author>
*/
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @throws Exception
     */
    public function getPaginatedAuthors(AuthorListRequest $request): array
    {
        $qb = $this->createQueryBuilder('a');

        $this->applyFilters($qb, $request->filters);

        $qb->orderBy('a.id', 'ASC');

        $qb->setFirstResult(($request->page - 1) * $request->limit)
            ->setMaxResults($request->limit);

        $paginator = new Paginator($qb->getQuery());


        return [
            'items' =>$paginator->getIterator(),
            'total' => count($paginator),
            'page' => $request->page,
            'limit' => $request->limit,
            'pages' => ceil(count($paginator) / $request->limit)
        ];
    }

    /**
     * @param QueryBuilder $qb
     * @param array<int, array{name: string, value: string, type: string}> $filters
     * @throws Exception
     */
    private function applyFilters(QueryBuilder $qb, array $filters): void
    {
        foreach ($filters as $index => $filter) {
            $paramName = 'value_' . $index;

            switch ($filter['name']) {
                case 'name':
                    if ($filter['type'] === FiltersTypes::LIKE->name) {
                        $qb->andWhere('a.name LIKE :' . $paramName)
                            ->setParameter($paramName, '%' . $filter['value'] . '%');
                    } else {
                        $qb->andWhere('a.name = :' . $paramName)
                            ->setParameter($paramName, $filter['value']);
                    }
                    break;

                default:
                    throw new Exception('Unknown filter: ' . $filter['name']);
            }
        }
    }
}
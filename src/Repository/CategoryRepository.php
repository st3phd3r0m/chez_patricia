<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[] Returns an array of Brand objects
     */
    public function getPage(int $limit, int $offset, int $hierarchy_order)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.hierarchy_order = :val')
            ->setParameter('val', $hierarchy_order)
            ->setFirstResult( $offset )
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getNumberOfPages(int $limit, int $hierarchy_order): int
    {
        $dql = 'SELECT COUNT(*) FROM category as c WHERE c.hierarchy_order = ?';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->executeQuery($dql, [$hierarchy_order], []);
        $result = $stmt->fetchOne();
        $conn->close();

        return (int) ceil($result / $limit);
    }

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Brand objects
     */
    public function getPage(int $limit, int $offset)
    {
        return $this->createQueryBuilder('c')
            ->setFirstResult( $offset )
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getNumberOfPages(int $limit): int
    {
        $dql = 'SELECT COUNT(*) FROM product as p';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->executeQuery($dql, [], []);
        $result = $stmt->fetchOne();
        $conn->close();

        return (int) ceil($result / $limit);
    }

    public function getProductId(string $product_slug): int
    {
        $dql = 'SELECT p.id, p.slug FROM product as p WHERE p.slug = ?';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->executeQuery($dql, [$product_slug], [ParameterType::STRING]);
        $result = $stmt->fetchOne();
        $conn->close();
        return (int) $result;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

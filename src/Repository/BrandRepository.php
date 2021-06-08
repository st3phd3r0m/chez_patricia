<?php

namespace App\Repository;

use App\Entity\Brand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    /**
     * @return Brand[] Returns an array of Brand objects
     */
    public function getPage(int $limit, int $offset)
    {
        return $this->createQueryBuilder('b')
            ->setFirstResult( $offset )
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getNumberOfPages(int $limit): int
    {
        $dql = 'SELECT COUNT(*) FROM brand as b';
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->executeQuery($dql, [], []);
        $result = $stmt->fetchOne();
        $conn->close();

        return (int) ceil($result / $limit);
    }
    

    /*
    public function findOneBySomeField($value): ?Brand
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

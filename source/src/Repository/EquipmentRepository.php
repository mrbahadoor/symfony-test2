<?php

namespace App\Repository;

use App\Entity\Equipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Equipment>
 *
 * @method Equipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipment[]    findAll()
 * @method Equipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipment::class);
    }

    public function add(Equipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Equipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Equipment[] Returns an array of Equipment objects
    */
    public function findByFilters($filters): array
    {
       
        $queryBuilder = $this->createQueryBuilder('e');

        $exactFilters = ['id', 'category', 'number'];

        foreach($exactFilters as $filter){
            if(isset($filters[$filter])){
                $queryBuilder->andWhere('e.'.$filter.' = :'.$filter);
                $queryBuilder->setParameter($filter, $filters[$filter]);
            }
        }


        $partialFilters = ['name', 'description'];

        foreach($partialFilters as $filter){
            if(isset($filters[$filter])){
                $queryBuilder->andWhere('e.'.$filter.' LIKE :'.$filter);
                $queryBuilder->setParameter($filter, '%' . $filters[$filter] . '%');
            }
        }

        return $queryBuilder
                //->setMaxResults(10)
                ->getQuery()
                ->getResult();
    }

//    public function findOneBySomeField($value): ?Equipment
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

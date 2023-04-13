<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function save(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByNameFamilyFilter($user,$filters, $limit, $offset){

        $query =  $this->getEntityManager()
            ->getRepository(Fruit::class)
            ->createQueryBuilder('f')
            ->select("f");

        // check if name search value is set
        $name_search = $family_search = 0;

        if(isset($filters->name) && $filters->name != ""){
            $query->where('f.name like :name');
            $name_search = 1;
        }

        // check if family search value is set
        if(isset($filters->family) && $filters->family != ""){
            if($name_search == 1){
                $query->orWhere('f.family like :family');
            }else{
                $query->where('f.family like :family');
            }

            $family_search = 1;
        }

        if($name_search == 1){
            $query->setParameter('name', '%'.$filters->name.'%');
        }

        if($family_search == 1){
            $query->setParameter('family', '%'.$filters->family.'%');
        }
        
        $fruits = $query   
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult(Query::HYDRATE_SIMPLEOBJECT);

        return $fruits;
    }

    public function findByNameFamilyFilterCount($filters){

        $query =  $this->getEntityManager()
            ->getRepository(Fruit::class)
            ->createQueryBuilder('f')
            ->select('COUNT(f)');

        // check if name search value is set
        $name_search = $family_search = 0;

        if(isset($filters->name) && $filters->name != ""){
            $query->where('f.name like :name');
            $name_search = 1;
        }

        // check if family search value is set
        if(isset($filters->family) && $filters->family != ""){
            if($name_search == 1){
                $query->orWhere('f.family like :family');
            }else{
                $query->where('f.family like :family');
            }

            $family_search = 1;
        }

        if($name_search == 1){
            $query->setParameter('name', '%'.$filters->name.'%');
        }

        if($family_search == 1){
            $query->setParameter('family', '%'.$filters->family.'%');
        }
        
        $fruits = $query
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);

        return $fruits;
    }

//    /**
//     * @return Fruit[] Returns an array of Fruit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fruit
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

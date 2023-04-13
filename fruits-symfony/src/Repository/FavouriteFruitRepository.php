<?php

namespace App\Repository;

use App\Entity\FavouriteFruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<FavouriteFruit>
 *
 * @method FavouriteFruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavouriteFruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavouriteFruit[]    findAll()
 * @method FavouriteFruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavouriteFruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavouriteFruit::class);
    }

    public function save(FavouriteFruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FavouriteFruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUserId($user,$fruit){
        return $this->getEntityManager()
            ->getRepository(FavouriteFruit::class)
            ->createQueryBuilder('ff')
            ->where('ff.user = :user')
            ->andWhere('ff.fruit = :fruit')
            ->setParameter('user', $user)
            ->setParameter('fruit', $fruit)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCountByUser($user){
        return $this->getEntityManager()
            ->getRepository(FavouriteFruit::class)            
            ->createQueryBuilder('ff')
            ->select('COUNT(ff)')
            ->where('ff.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    public function createNew(\App\Entity\User $user, \App\Entity\Fruit $fruit){

        $newFavouriteFruit = new FavouriteFruit();
        $newFavouriteFruit->setUser($user);
        $newFavouriteFruit->setFruit($fruit);
        
        return $newFavouriteFruit;
    }

    public function findFavByUserId(\App\Entity\User $user){
        $arr = $this->getEntityManager()
            ->getRepository(FavouriteFruit::class)            
            ->createQueryBuilder('ff')            
            ->where('ff.user = :user')
            ->setParameter('user', $user)
            ->select('(ff.fruit) as fruit_id')
            ->getQuery()
            ->getResult();

        return array_map(function($a){ return $a['fruit_id']; }, $arr);

    }

//    /**
//     * @return FavouriteFruit[] Returns an array of FavouriteFruit objects
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

//    public function findOneBySomeField($value): ?FavouriteFruit
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

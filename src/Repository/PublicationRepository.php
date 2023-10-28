<?php

namespace App\Repository;

use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    // /**
    //  * @return Publication[] Returns an array of Publication objects
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
    public function findOneBySomeField($value): ?Publication
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function TriParNomClient()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom_client','ASC ')
            ->getQuery()->getResult();
    }
    public function TriParDatePublication()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date_publication','ASC ')
            ->getQuery()->getResult();
    }
    public function TriParCategorie()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.categorie_publication','ASC ')
            ->getQuery()->getResult();
    }
    public function searchPublication($categorie_publication)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.categorie_publication LIKE :categorie_publication')
            ->setParameter('categorie_publication', '%'.$categorie_publication.'%')
            ->getQuery()
            ->execute();
    }
}
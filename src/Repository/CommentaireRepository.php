<?php

namespace App\Repository;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    /* public function trouverCommentaire($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isPublished = true')
            ->setParameter('val', $value->getIdPublication())
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    } */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->join('c.id_publication', 'comment')
            ->addSelect('comment')
            ->where('comment.id = :val')
            ->andWhere('c.isPublished = true')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

}
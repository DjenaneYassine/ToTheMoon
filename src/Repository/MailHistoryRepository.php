<?php

namespace App\Repository;

use App\Entity\MailHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MailHistory>
 *
 * @method MailHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailHistory[]    findAll()
 * @method MailHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailHistory::class);
    }

//    /**
//     * @return MailHistory[] Returns an array of MailHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MailHistory
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function idUnique($match): bool
    {
        $id = $match["id"];
        $existingId = $this->findOneBy(['idMatch' => $id]);

        if($existingId === null){
            return true;
        }else{
            return false;
        }
    }

    public function newMail($match)
    {
        $newStat = new MailHistory();
        $entityManager = $this->getEntityManager();

        $newStat
            ->setIdMatch($match["id"])
            ->setScore($match["ss"])
            ->setScoreSet($match["set"]);

        $existingStat = $this->findOneBy(['idMatch' => $newStat->getIdMatch()]);

        if($existingStat === null){
            $entityManager->persist($newStat);
            $entityManager->flush();
        }
    }
}

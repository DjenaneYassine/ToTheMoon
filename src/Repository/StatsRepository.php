<?php

namespace App\Repository;

use App\Entity\Stats;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\VarDumper\Cloner\Data;

/**
 * @extends ServiceEntityRepository<Stats>
 *
 * @method Stats|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stats|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stats[]    findAll()
 * @method Stats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stats::class);
    }

//    /**
//     * @return Stats[] Returns an array of Stats objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stats
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function newsStats($match)
    {
        $newStat = new Stats();
        $entityManager = $this->getEntityManager();

        $newStat
            ->setLeague($match["league"]["name"])
            ->setDate(new DateTime())
            ->setNameHome($match["home"]["name"])
            ->setNameAway($match["away"]["name"])
            ->setIdMatch($match["id"])
            ->setScore($match["ss"])
            ->setScoreSet($match["set"]);

        $existingStat = $this->findOneBy(['idMatch' => $newStat->getIdMatch()]);

        if($existingStat === null){
            $entityManager->persist($newStat);
            $entityManager->flush();
        }
    }

    public function findIdMatch()
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i.idMatch')
            ->where('i.winner IS NULL');

        $result = $qb->getQuery()->getResult();

        return count($result) === 0 ? null : $result;
    }

    public function newSet($set, $id)
    {
        $entityManager = $this->getEntityManager();
        $statsRepos = $entityManager->getRepository(Stats::class);
        $match = $statsRepos->findOneBy(['idMatch' => $id]);

        $match->setWinner((string)$set);
        $entityManager->persist($match);
        $entityManager->flush();
    }

    public function findSS($id)
    {
        $entityManager = $this->getEntityManager();
        $statsRepos = $entityManager->getRepository(Stats::class);
        $match = $statsRepos->findOneBy(['idMatch' => $id]);

        $scoreSet = $match->getScore();
        return $scoreSet;
    }

    public function valueWin($id, $val)
    {
        $entityManager = $this->getEntityManager();
        $statsRepos = $entityManager->getRepository(Stats::class);
        $match = $statsRepos->findOneBy(['idMatch' => $id]);

        $match->setWin($val);
        $entityManager->persist($match);
        $entityManager->flush();
    }

    public function getDataForDays($date)
    {
        $currentYear = date('Y');
        $dateAndYear = $date . '-' . $currentYear;
        $date = DateTime::createFromFormat('d-m-Y', $dateAndYear);
        $newDateString = $date->format('Y-m-d');

        $tabDate = $this->createQueryBuilder('d')
        ->andWhere('d.date = :date')
        ->setParameter('date', $newDateString)
        ->getQuery()
        ->getResult();

        return $tabDate;
    }

    public function getNbDataForDays($date)
    {
        return count($this->getDataForDays($date));
    }

    public function getWinForDays($date)
    {
        $currentYear = date('Y');
        $dateAndYear = $date . '-' . $currentYear;
        $date = DateTime::createFromFormat('d-m-Y', $dateAndYear);
        $newDateString = $date->format('Y-m-d');
        
        $tabDate = $this->createQueryBuilder('d')
        ->andWhere('d.date = :date')
        ->andWhere('d.win = true')
        ->setParameter('date', $newDateString)
        ->getQuery()
        ->getResult();

        return count($tabDate);
    }

    public function getLooseForDays($date)
    {
        $currentYear = date('Y');
        $dateAndYear = $date . '-' . $currentYear;
        $date = DateTime::createFromFormat('d-m-Y', $dateAndYear);
        $newDateString = $date->format('Y-m-d');
        
        $tabDate = $this->createQueryBuilder('d')
        ->andWhere('d.date = :date')
        ->andWhere('d.win = false')
        ->setParameter('date', $newDateString)
        ->getQuery()
        ->getResult();

        return count($tabDate);
    }



}

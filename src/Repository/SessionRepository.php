<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Session;
use App\Entity\Student;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    // ^ find none registered studends in a session
    public function findNoneRegistered($session_id) {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        //sélectionner tous les stagiaires d'une sessions dont l'id est passé en paramètre
        $qb->select('s')
            ->from('App\Entity\Student', 's')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id=:id');
        $sub = $em->createQueryBuilder();
        //Sélectionner tous les stagiaires qui ne sont pas (NOT IN) dans le résultat précédent
        // on obtient donc les stagiaires non inscrits pour une session définie
        $sub->select('st')
            ->from('App\Entity\Student', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            //requête paramétrée
            ->setParameter('id', $session_id)
            //trier la liste des stagiaires sur le nom de famille
            ->orderBy('st.lastName');
        //renvoyer le résultat
        $query = $sub->getQuery();
        return $query->getResult();
    }

    // ^ find none added modules in a session
    public function findNoneAdded($session_id) {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        //sélectionner tous les module  d'une session dont l'id est passé en paramètre
        $qb->select('mf')
            ->from('App\Entity\ModuleFormation', 'mf')
            ->leftJoin('mf.programmes', 'p')
            ->leftJoin('p.session', 's')
            ->where('s.id=:id');
        $sub = $em->createQueryBuilder();
        //Sélectionner tous les stagiaires qui ne sont pas (NOT IN) dans le résultat précédent
        // on obtient donc les stagiaires non inscrits pour une session définie
        $sub->select('m')
            ->from('App\Entity\ModuleFormation', 'm')
            ->where($sub->expr()->notIn('m.id', $qb->getDQL()))
            //requête paramétrée
            ->setParameter('id', $session_id)
            //trier la liste des stagiaires sur le nom de famille
            ->orderBy('m.name');
        //renvoyer le résultat
        $query = $sub->getQuery();
        return $query->getResult();
    }


    // ^ find current sessions
    public function findCurrentSessions(?Student $student = null) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        
        $now = new \DateTime();
        $qb ->select('s')
            ->from('App\Entity\Session', 's')
            ->leftJoin('s.students', 'st')
            ->where('s.startDate < :val')
            ->andWhere('s.endDate > :val')
            ->setParameter('val', $now);

        // Ajouter la condition pour filtrer par étudiant, si un étudiant est fourni
        if ($student !== null) {
            $qb->andWhere('st.id = :studentId')
               ->setParameter('studentId', $student->getId());
        }



        $qb->orderBy('s.startDate');

        $query = $qb->getQuery();
        return $query->getResult();
    }



    // ^ find upcoming sessions
    public function findUpcomingSessions(?Student $student = null)  {
        $em = $this->getEntityManager();
        $qb= $em->createQueryBuilder();

        $now = new \DateTime();
        $qb ->select('s')
            ->from('App\Entity\Session', 's')
            ->leftJoin('s.students', 'st')
            ->where('s.startDate >= :val')
            ->setParameter('val', $now);

        // Ajouter la condition pour filtrer par étudiant, si un étudiant est fourni
        if ($student !== null) {
            $qb->andWhere('st.id = :studentId')
               ->setParameter('studentId', $student->getId());
        }

    
        $qb->orderBy('s.startDate');

        $query = $qb->getQuery();
        return $query->getResult();
    }

    // ^ find past sessions
    public function findPastSessions(?Student $student = null)  {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

    
        $now = new \DateTime();
        $qb ->select('s')
            ->from('App\Entity\Session', 's')
            ->leftJoin('s.students', 'st')
            ->where('s.endDate < :val')
            ->setParameter('val', $now);

         // Ajouter la condition pour filtrer par étudiant, si un étudiant est fourni
         if ($student !== null) {
            $qb->andWhere('st.id = :studentId')
               ->setParameter('studentId', $student->getId());
        }

        $qb->orderBy('s.startDate');

        $query = $qb->getQuery();
        return $query->getResult();
        
    }



    // ----------------------


    // ^ find current sessions
    public function findCurrentSessionsUser(?User $user = null) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        
        $now = new \DateTime();
        $qb ->select('s')
            ->from('App\Entity\Session', 's')
            ->leftJoin('s.students', 'st')
            ->where('s.startDate < :val')
            ->andWhere('s.endDate > :val')
            ->setParameter('val', $now);

    
        if ($user !== null) {
            $qb->andWhere('s.id = :userId')
               ->setParameter('userId', $user->getId());
        }

        $qb->orderBy('s.startDate');

        $query = $qb->getQuery();
        return $query->getResult();
    }



    // ^ find upcoming sessions
    public function findUpcomingSessionsUser(?User $user = null)  {
        $em = $this->getEntityManager();
        $qb= $em->createQueryBuilder();

        $now = new \DateTime();
        $qb ->select('s')
            ->from('App\Entity\Session', 's')
            ->leftJoin('s.students', 'st')
            ->where('s.startDate >= :val')
            ->setParameter('val', $now);

    

        if ($user !== null) {
            $qb->andWhere('s.id = :userId')
               ->setParameter('userId', $user->getId());
        }

        $qb->orderBy('s.startDate');

        $query = $qb->getQuery();
        return $query->getResult();
    }

    // ^ find past sessions
    public function findPastSessionsUser(?User $user = null)  {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

    
        $now = new \DateTime();
        $qb ->select('s')
            ->from('App\Entity\Session', 's')
            ->leftJoin('s.students', 'st')
            ->where('s.endDate < :val')
            ->setParameter('val', $now);

        if ($user !== null) {
            $qb->andWhere('s.id = :userId')
                ->setParameter('userId', $user->getId());
        }

        $qb->orderBy('s.startDate');

        $query = $qb->getQuery();
        return $query->getResult();
        
    }



    // ----------------


    public function findForPagination(?Session $session = null ): Query {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.starDate', 'DESC');

            if ($session) {
                $db->leftJoin ('a.session', 's')
                ->where($qb->expr()->eq('s.id', ':sessionId'))
                ->setParameter('sessionId', $session->getId());
            }

            return $qb->getQuery();
    }

    
        //  factorisé:

        // $now = new \DateTime();
        // return $this->createQueryBuilder('s')
        // ->andWhere('s.endDate <:val')
        // ->setParameter('val', $now)
        // ->orderBy('s.startDate', 'ASC')
        // ->getQuery()
        // ->getResult();


//    /**
//     * @return Session[] Returns an array of Session objects
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

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    
}

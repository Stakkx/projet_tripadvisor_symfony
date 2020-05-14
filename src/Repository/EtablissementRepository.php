<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Etablissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Etablissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etablissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etablissement[]    findAll()
 * @method Etablissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtablissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Etablissement::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Etablissement[] Returns an array of Etablissement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etablissement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /** 
     * Récupère les établissements en lien avec la recherche
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface {
        
        $query = $this
            ->createQueryBuilder('e')
            ->select('categorie', 'e')
            ->join('e.categorie', 'categorie')
            ->where('e.etat = 1');

        if (!empty($search->q)){
            $query = $query
                ->andWhere('e.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->categorie)){
            $query = $query
                ->andWhere('e.categorie = :cat')
                ->setParameter('cat', $search->categorie);
        }


        $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            6
        );
    }
}

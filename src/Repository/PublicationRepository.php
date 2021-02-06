<?php

namespace App\Repository;

use App\Entity\Publication;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    /*public function findGaleries($galeries) {
        return $this->createQueryBuilder('p')
            ->leftjoin('p.galeries', 'g')
            ->where('g IN (:galeries)')
            ->setParameter('galeries', $galeries)
            ->getQuery()
            ->getResult()
        ;
    }*/

    public function countByCategory($categoryNom) {
        return $this->createQueryBuilder('p')
                    ->select('count(p)')
                    ->join('p.category', 'c')
                    ->where('c.nom = :categoryNom')
                    ->setParameter('categoryNom', $categoryNom)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function lastByCategory($categoryNom,$nb) {
        return $this->createQueryBuilder('p')
                    ->join('p.category', 'c')
                    ->where('c.nom = :categoryNom')
                    ->setParameter('categoryNom', $categoryNom)
                    ->orderBy('p.create_at','DESC')
                    ->setMaxResults($nb)
                    ->getQuery()
                    ->getResult();
    }

    public function findByCategoryActu($listCategory) {
        $timeNow = new \DateTime('now');
        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->leftjoin('p.actualite', 'a')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r')
                    ->join('p.category', 'c')
                    //->join('p.actualite','a')
                    ->where('c.nom = :actualite')
                    ->andWhere('p.statut = 1')
                    ->andWhere('p.trash = 0')
                    ->andWhere('a.category IN (:listCategory)') 
                    ->andWhere('a.debut_publication <= :timeNow')
                    ->andWhere('a.fin_publication >= :timeNow OR a.fin_publication IS NULL')
                    ->setParameter('actualite', 'actualite')
                    ->setParameter('timeNow', $timeNow->format('y-m-d'))
                    ->setParameter('listCategory', $listCategory)
                    ->orderBy('p.create_at', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function findByCategoryEvent($listCategory) {
        $timeNow = new \DateTime('now');
        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->leftjoin('p.actualite', 'a')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r')
                    ->join('p.category', 'c')
                    //->join('p.evenement','e')
                    ->where('c.nom = :evenement')
                    ->andWhere('p.statut = 1')
                    ->andWhere('p.trash = 0')
                    ->andWhere('e.category IN (:listCategory)')
                    ->andWhere('e.fin_evenement >= :timeNow OR e.fin_evenement IS NULL') 
                    ->setParameter('evenement', 'evenement')
                    ->setParameter('listCategory', $listCategory)
                    ->setParameter('timeNow', $timeNow->format('y-m-d'))
                    ->orderBy('p.create_at', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function findSousRubriqueAndPage() {
        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->leftjoin('p.actualite', 'a')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r')
                    ->join('p.category', 'c')
                    ->where('c.nom = :sousrubrique')
                    ->orWhere('c.nom = :page')
                    ->setParameter('sousrubrique', 'sous-rubrique')
                    ->setParameter('page', 'page')
                    ->orderBy('p.title','ASC');
    }

    public function findAllExceptRubriques() {
        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->leftjoin('p.actualite', 'a')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r')
                    ->join('p.category', 'c')
                    /*->where('p.statut = 1')
                    ->andWhere('c.nom = :page')*/
                    ->where('c.nom = :page')
                    ->orWhere('c.nom = :evenement')
                    ->orWhere('c.nom = :actualite')
                    ->setParameter('page', 'page')
                    ->setParameter('evenement', 'evenement')
                    ->setParameter('actualite', 'actualite')
                    ->orderBy('p.title','ASC');
    }

    public function findAllByCategory() {
        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->leftjoin('p.actualite', 'a')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r')
                    ->join('p.category', 'c')
                    //->Where('p.statut = 1')
                    ->orderBy('c.nom', 'DESC');
    }

    public function findRubrique() {
        return $this->createQueryBuilder('p')
                    ->join('p.category', 'c')
                    ->where('c.nom = :rubrique')
                    ->andWhere('p.trash = 0')
                    ->setParameter('rubrique', 'rubrique')
                    ->orderBy('p.create_at', 'DESC');
    }

    public function findAllRubrique() {
        return $this->createQueryBuilder('p')
                    ->join('p.category', 'c')
                    ->where('c.nom = :rubrique')
                    ->orWhere('c.nom = :sousrubrique')
                    ->setParameter('rubrique', 'rubrique')
                    ->setParameter('sousrubrique', 'sous-rubrique')
                    ->orderBy('p.title','ASC');
    }

    public function findCategory($category, $trash) {
        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->leftjoin('p.actualite', 'a')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r')
                    ->join('p.category', 'c')
                    ->Where('c.nom = :category')
                    ->andWhere('p.trash = :trash')
                    ->setParameter('category', $category)
                    ->setParameter('trash', $trash)
                    ->orderBy('p.create_at', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function findActualiteLimit($limit) {

        $timeNow = new \DateTime('now');

        return $this->createQueryBuilder('p')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->addSelect('a')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r') 
                    ->leftjoin('p.actualite', 'a')
                    ->join('p.category', 'c')
                    ->Where('c.nom = :actualite')
                    ->andWhere('p.statut = 1')
                    ->andWhere('p.trash = 0')
                    ->andWhere('a.debut_publication <= :timeNow')
                    ->andWhere('a.fin_publication >= :timeNow OR a.fin_publication IS NULL')
                    ->setParameter('actualite','actualite')
                    ->setParameter('timeNow', $timeNow->format('y-m-d'))
                    ->orderBy('a.debut_publication', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult(); 
    }

    public function findAllActualites() {

        $timeNow = new \DateTime('now');

        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r')
                    ->join('p.actualite', 'a')
                    ->join('p.category', 'c')
                    ->Where('c.nom = :actualite')
                    ->andWhere('p.statut = 1')
                    ->andWhere('p.trash = 0')
                    ->andWhere('a.debut_publication <= :timeNow')
                    ->andWhere('a.fin_publication >= :timeNow OR a.fin_publication IS NULL')
                    ->setParameter('actualite','actualite')
                    ->setParameter('timeNow', $timeNow->format('y-m-d'))
                    ->orderBy('a.debut_publication', 'DESC')
                    ->getQuery()
                   ->getResult(); 
    }

    public function findEvenementLimit($limit) {

        $timeNow = new \DateTime('now');

        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('r')
                    ->addSelect('e')
                    ->leftjoin('p.actualite', 'a')
                    ->leftjoin('p.rubrique', 'r')
                    ->leftjoin('p.evenement', 'e')
                    ->join('p.category', 'c')
                    ->Where('c.nom = :evenement')
                    ->andWhere('p.statut = 1')
                    ->andWhere('p.trash = 0')
                    ->andWhere('e.fin_evenement >= :timeNow OR e.fin_evenement IS NULL')
                    ->setParameter('evenement','evenement')
                    ->setParameter('timeNow', $timeNow->format('y-m-d'))
                    /*->orderBy('e.debut_evenement', 'ASC')*/
                    ->orderBy('p.create_at', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult(); 
    }

    public function findAllEvenements() {

        $timeNow = new \DateTime('now');

        return $this->createQueryBuilder('p')
                    ->addSelect('a')
                    ->addSelect('e')
                    ->addSelect('r')
                    ->leftjoin('p.actualite', 'a')
                    ->leftjoin('p.evenement', 'e')
                    ->leftjoin('p.rubrique', 'r')
                    ->join('p.category', 'c')
                    ->Where('c.nom = :evenement')
                    ->andWhere('p.statut = 1')
                    ->andWhere('p.trash = 0')
                    ->andWhere('e.fin_evenement >= :timeNow OR e.fin_evenement IS NULL')
                    ->setParameter('evenement','evenement')
                    ->setParameter('timeNow', $timeNow->format('y-m-d'))
                    /*->orderBy('e.debut_evenement', 'ASC')*/
                    ->orderBy('p.create_at', 'DESC')
                    ->getQuery()
                    ->getResult(); 
    }

    public function findAllEvenementsPasses() {

        $timeNow = new \DateTime('now');

        return $this->createQueryBuilder('p')
                   ->join('p.evenement', 'e')
                   ->join('p.category', 'c')
                   ->Where('c.nom = :evenement')
                   ->andWhere('p.statut = 1')
                   ->andWhere('p.trash = 0')
                   ->andWhere('e.fin_evenement < :timeNow')
                   ->setParameter('evenement','evenement')
                   ->setParameter('timeNow', $timeNow->format('y-m-d'))
                   /*->orderBy('e.debut_evenement', 'ASC')*/
                   ->orderBy('p.create_at', 'DESC')
                   ->getQuery()
                   ->getResult(); 
    }

    public function findOneEvenementsPassesExiste($idPublication) {

        $timeNow = new \DateTime('now');

        return $this->createQueryBuilder('p')
                   ->join('p.evenement', 'e')
                   ->join('p.category', 'c')
                   ->Where('c.nom = :evenement')
                   ->andWhere('p.statut = 1')
                   ->andWhere('p.trash = 0')
                   ->andWhere('e.fin_evenement < :timeNow')
                   ->andWhere('p.id = :idPublication')
                   ->setParameter('evenement','evenement')
                   ->setParameter('timeNow', $timeNow->format('y-m-d'))
                   ->setParameter('idPublication', $idPublication)
                   ->setMaxResults(1)
                   ->getQuery()
                   ->getOneOrNullResult() !== null; 
    }

    public function findAllEvenementsPassesExiste() {

        $timeNow = new \DateTime('now');

        return $this->createQueryBuilder('p')
                   ->join('p.evenement', 'e')
                   ->join('p.category', 'c')
                   ->Where('c.nom = :evenement')
                   ->andWhere('p.statut = 1')
                   ->andWhere('p.trash = 0')
                   ->andWhere('e.fin_evenement < :timeNow')
                   ->setParameter('evenement','evenement')
                   ->setParameter('timeNow', $timeNow->format('y-m-d'))
                   ->setMaxResults(1)
                   ->getQuery()
                   ->getOneOrNullResult() !== null;
    }

    public function findCategoryLimit($category, $limit) {
        return $this->createQueryBuilder('p')
                   ->join('p.category', 'c')
                   ->Where('c.nom = :category')
                   ->andWhere('p.statut = 1')
                   ->andWhere('p.trash = 0')
                   ->setParameter('category', $category)
                   ->orderBy('p.create_at', 'DESC')
                   ->setMaxResults($limit)
                   ->getQuery()
                   ->getResult();
    }
    
    /*
    ALTER TABLE publication ADD FULLTEXT fulltext_index(title, introduction, content)
    ALTER TABLE publication ADD FULLTEXT fulltext_title(title)    
    ALTER TABLE publication ADD FULLTEXT fulltext_introduction(introduction)
    ALTER TABLE publication ADD FULLTEXT fulltext_content(content)
    ALTER TABLE evenement ADD FULLTEXT fulltext_category(category)
    ALTER TABLE actualite ADD FULLTEXT fulltext_category(category)
    ALTER TABLE galerie ADD FULLTEXT fulltext_title(title)
    ALTER TABLE galerie ADD FULLTEXT fulltext_description(description)
    */

    /*public function fullText($searchterm) {
        return $this->createQueryBuilder('p')
                    ->addSelect('MATCH (p.title, p.content, p.introduction) AGAINST (:searchterm) as score')
                    ->add('where', 'MATCH (p.title, p.content, p.introduction) AGAINST (:searchterm) > 0.1')
                    ->setParameter('searchterm', $searchterm)
                    ->setMaxResults(12)
                    ->getQuery()
                    ->getResult();
    }*/

    /*public function fullText($searchterm) {
        $timeNow = new \DateTime('now');
        $qb = $this->createQueryBuilder('p');
        return $qb
                ->join('p.actualite', 'a')
                ->join('p.evenement', 'e')
                ->Where('c.nom = :category')
                ->addSelect('MATCH (p.title) AGAINST (:searchterm) as title ')
                ->addSelect('MATCH (p.introduction) AGAINST (:searchterm) as introduction ')
                ->addSelect('(MATCH (p.content) AGAINST (:searchterm)*2) as content ')
                ->setParameter('searchterm', $searchterm)
                ->where('((MATCH (p.content) AGAINST (:searchterm)*2)+(MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm)))>0.4')
                ->andWhere('c.nom = :evenement')
                ->andWhere('c.nom = :actualite')
                ->andWhere('p.statut = 1')
                ->andWhere('a.debut_publication <= :timeNow')
                ->andWhere('a.fin_publication >= :timeNow OR a.fin_publication IS NULL')
                ->andWhere('e.fin_evenement >= :timeNow OR e.fin_evenement IS NULL')
                ->setParameter('timeNow', $timeNow->format('y-m-d'))
                ->setParameter('evenement','evenement')
                ->setParameter('actualite','actualite')
                ->orderBy('title+introduction+content', 'desc')
                ->setMaxResults(12)
                ->getQuery()
                ->getResult();
    }*/

    public function fullTextActualite($searchterm) {
        $timeNow = new \DateTime('now');
        $qb = $this->createQueryBuilder('p');
        return $qb
                ->addSelect('a')
                ->addSelect('e')
                ->addSelect('r')
                ->leftjoin('p.actualite', 'a')
                ->leftjoin('p.evenement', 'e')
                ->leftjoin('p.rubrique', 'r')
                ->join('p.category', 'c')
                //->join('p.actualite', 'a')
                /*->addSelect('MATCH (p.title) AGAINST (:searchterm) as title ')
                ->addSelect('MATCH (p.introduction) AGAINST (:searchterm) as introduction ')
                ->addSelect('(MATCH (p.content) AGAINST (:searchterm)*2) as content ')*/
                ->addSelect('((MATCH (a.category) AGAINST (:searchterm)*3)+(MATCH (p.content) AGAINST (:searchterm)*2)+(MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm))) as score')
                ->where('((MATCH (a.category) AGAINST (:searchterm)*3)+(MATCH (p.content) AGAINST (:searchterm)*2)+(MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm)))>0.4')
                ->andWhere('c.nom = :actualite')
                ->andWhere('p.statut = 1')
                ->andWhere('p.trash = 0')
                ->andWhere('a.debut_publication <= :timeNow')
                ->andWhere('a.fin_publication >= :timeNow OR a.fin_publication IS NULL')
                ->setParameter('searchterm', $searchterm)
                ->setParameter('timeNow', $timeNow->format('y-m-d'))
                ->setParameter('actualite','actualite')
                ->orderBy('score', 'desc')
                //->setMaxResults(12)
                ->getQuery()
                ->getResult();
    }

    public function fullTextEvenement($searchterm) {
        $timeNow = new \DateTime('now');
        $qb = $this->createQueryBuilder('p');
        return $qb
                ->addSelect('a')
                ->addSelect('e')
                ->addSelect('r')
                ->leftjoin('p.actualite', 'a')
                ->leftjoin('p.evenement', 'e')
                ->leftjoin('p.rubrique', 'r')
                //->join('p.evenement', 'e')
                ->join('p.category', 'c')
                ->addSelect('((MATCH (e.category) AGAINST (:searchterm)*3)+(MATCH (p.content) AGAINST (:searchterm)*2)+(MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm))) as score')
                ->where('((MATCH (e.category) AGAINST (:searchterm)*3)+(MATCH (p.content) AGAINST (:searchterm)*2)+(MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm)))>0.4')
                ->andWhere('c.nom = :evenement')
                ->andWhere('p.statut = 1')
                ->andWhere('p.trash = 0')
                ->andWhere('e.fin_evenement >= :timeNow OR e.fin_evenement IS NULL')
                ->setParameter('searchterm', $searchterm)
                ->setParameter('timeNow', $timeNow->format('y-m-d'))
                ->setParameter('evenement','evenement')
                ->orderBy('score', 'desc')
                ->getQuery()
                ->getResult();
    }

    public function fullTextRubriques($searchterm) {
        $qb = $this->createQueryBuilder('p');
        return $qb
                ->addSelect('a')
                ->addSelect('e')
                ->addSelect('r')
                ->leftjoin('p.actualite', 'a')
                ->leftjoin('p.evenement', 'e')
                ->leftjoin('p.rubrique', 'r')
                ->join('p.category', 'c')
                ->addSelect('((MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm))) as score')
                ->where('((MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm)))>0.4')
                ->andWhere('c.nom = :sousrubrique OR c.nom = :rubrique')
                ->setParameter('searchterm', $searchterm)
                ->setParameter('sousrubrique','sous-rubrique')
                ->setParameter('rubrique','rubrique')
                ->orderBy('score', 'desc')
                ->getQuery()
                ->getResult();
    }

    public function fullTextPage($searchterm) {
        $qb = $this->createQueryBuilder('p');
        return $qb
                ->addSelect('a')
                ->addSelect('e')
                ->addSelect('r')
                ->leftjoin('p.actualite', 'a')
                ->leftjoin('p.evenement', 'e')
                ->leftjoin('p.rubrique', 'r')
                ->join('p.category', 'c')
                ->addSelect('((MATCH (p.content) AGAINST (:searchterm)*2)+(MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm))) as score')
                ->where('((MATCH (p.content) AGAINST (:searchterm)*2)+(MATCH (p.introduction) AGAINST (:searchterm))+(MATCH (p.title) AGAINST (:searchterm)))>0.4')
                ->andWhere('c.nom = :page')
                ->andWhere('p.statut = 1')
                ->andWhere('p.trash = 0')
                ->setParameter('searchterm', $searchterm)
                ->setParameter('page','page')
                ->orderBy('score', 'desc')
                ->getQuery()
                ->getResult();
    }

    // /**
    //  * @return Publication[] Returns an array of Publication objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Publication
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

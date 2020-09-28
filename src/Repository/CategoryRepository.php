<?php

namespace App\Repository;

use App\Repository\GifRepository;
use App\Entity\Category;
use App\Entity\Gif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    private GifRepository $gifRepository;

    public function __construct(ManagerRegistry $registry, GifRepository $gifRepository)
    {
        $this->gifRepository = $gifRepository;
        parent::__construct($registry, Category::class);
    }

    public function getSubCategoryByMainCategorySlug(string  $slugCategory):Query
    {
        /*$query = $this->createQueryBuilder('category')
            ->select('category.name, category.slug')
            ->where('category.name LIKE :name')
            ->setParameters([
                'name' => 'b%'
            ])
            ->setMaxResults(2)
            ->getQuery()
        ;*/
        $query = $this->createQueryBuilder('category')
            ->join('category.parent', 'parent')
            ->where('parent.slug = :slug')
            ->setParameters([
                'slug' => $slugCategory
            ])
            ->getQuery()
        ;

        return $query;
    }

    // récupération des sours catégorie
    public function getSubCategories():QueryBuilder
    {
        $query = $this->createQueryBuilder('category')
            ->where('category.parent IS NOT NULL')
        ;

        return $query;
    }

    public function search($value) {

        $query = $this->createQueryBuilder('category')
            ->where('category.slug LIKE :value')
            ->setParameter('value', '%'.$value.'%')
            ->getQuery()
            ->getResult();

        foreach ($query as $result)
        {
            if($result->getParent() == NULL)
            {
                $id = $result->getId();
                $val = $this->gifRepository->getGifByCategoryParent($id);
                return $val;
            }
        }

        if (isset($query[0]))
        {
            $return = $this->gifRepository->getGifBySlug($query[0]->getSlug());
            return $return;
        }
        return $query;
    }



    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

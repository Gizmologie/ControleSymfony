<?php

namespace App\Repository;

use App\Entity\Gif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gif|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gif|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gif[]    findAll()
 * @method Gif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gif::class);
    }

    public function getByUserId(int $userId):Query
    {
        $query = $this->createQueryBuilder('gif')
            ->join('gif.user', 'user')
            ->where('user.id = :id')
            ->setParameters([
                'id' => $userId
                ])
            ->getQuery()
        ;

        return $query;
    }

    public function search($value) {
        return $this->createQueryBuilder('gif')
            ->where('gif.slug LIKE :value')
            ->setParameter('value', '%'.$value.'%')
            ->getQuery()
            ->execute();
    }

    public function getGifByCategoryParent ($value)
    {
        $query = $this->createQueryBuilder('gif')
            ->join('gif.category', 'category')
            ->where('category.parent = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getGifBySlug ($slug)
    {
        $query = $this->createQueryBuilder('gif')
            ->where('gif.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult();
        return $query;
    }
}

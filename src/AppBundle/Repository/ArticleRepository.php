<?php

namespace AppBundle\Repository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLatestArticles($limit = null)
    {
        return $this->findBy([], ['id' => 'desc'], $limit);
    }

    public function getSearchResult($query)
    {
        return $this->createQueryBuilder('s')
            ->where('s.title like :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getArrayResult();
    }
}

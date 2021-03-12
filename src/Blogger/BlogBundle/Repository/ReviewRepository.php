<?php
/**
 * Created by PhpStorm.
 * User: sgb638
 * Date: 06/01/20
 * Time: 13:23
 */

namespace Blogger\BlogBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ReviewRepository extends EntityRepository
{
    public function findAllReviewsby($id)
    {
        /**
         * @return Review[]
         */
        return $this->createQueryBuilder('reviews')
            ->andWhere('reviews.reviewof = :reviewof')
            ->orderBy('reviews.timestamp', 'DESC')
            ->setParameter('reviewof', $id)
            ->getQuery()
            ->execute();
    }

}
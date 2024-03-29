<?php

namespace TopOrFlopBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use TopOrFlopBundle\Entity\Media;
use TopOrFlopBundle\Entity\User;

/**
 * MediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MediaRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $this
     * @return mixed
     */
    public function getRandomMedia()
    {
        $ids = $this->createQueryBuilder('m')
            ->select('m.id')
            ->getQuery()
            ->getResult();

        if (!count($ids)) {
            return null;
        }

        $index = rand(0, count($ids) - 1);
        $randomId = $ids[$index]['id'];

        // get the media corresponding to this id
        $media = $this
            ->createQueryBuilder('m')
            ->where('m.id = :randomId')
            ->setParameter('randomId', $randomId)
            ->getQuery()
            ->getOneOrNullResult();

        return $media;
    }

    /**
     * Get 5 top medias
     *
     * @return ArrayCollection
     */
    public function getTopMedias($order = 'DESC')
    {
        $query = $this
            ->createQueryBuilder('m')
            ->select('m, v')
            ->leftJoin('m.votes', 'v')
            ->where('m.average IS NOT NULL')
            ->orderBy('m.average', $order)
            ->setFirstResult(0)
            ->setMaxResults(5)
            ->getQuery();

        return new Paginator($query, true);
    }

    /**
     * Get a media User hasn´t voted for yet
     *
     * @param User $user
     */
    public function getNewMediaForUser(User $user)
    {
        $repoString = Media::class;

        $dql = sprintf('SELECT m.id FROM %s m
            WHERE m.id NOT IN (
                SELECT m2.id FROM %s m2
                INNER JOIN m2.votes v2 WITH v2.user = %s
            )',
            $repoString,
            $repoString,
            $user->getId()
        );

        $results = $this->createQueryBuilder('m')
            ->getQuery()
            ->setDQL($dql)
            ->getResult();

        // return one random media
        if ($count = count($results)) {
            $index = rand(0, $count-1);
            $id = $results[$index]['id'];

            return $this->findOneById($id);
        }

        // or null if none are found
        return null;
    }
}

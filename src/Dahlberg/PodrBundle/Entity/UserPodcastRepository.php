<?php
// src/Dahlberg/PodrBundle/Entity/UserPodcastRepository.php;
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserPodcastRepository extends EntityRepository {
    public function findAllPodcastsOrderedByTitle($user)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM DahlbergPodrBundle:UserPodcast up
                 JOIN DahlbergPodrBundle:Podcast p WITH up.podcast = p
                 WHERE up.user = :user
                 ORDER BY p.title ASC')
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /* Advanced search */

    public function findByDataOptions($data, $user) {
        $builder = $this->createQueryBuilder('up');
        $builder->select('up AS userpodcast, (CASE WHEN (p.title LIKE :searchterm) THEN 10 ELSE 0 END) +
                        (CASE WHEN (p.itunesSubtitle LIKE :searchterm) THEN 5 ELSE 0 END) +
                        (CASE WHEN (p.itunesSummary LIKE :searchterm) THEN 5 ELSE 0 END)
                        AS weight')
            ->innerJoin('up.podcast', 'p')
            ->where('up.user = :user')
            ->andWhere($builder->expr()->orX(
                $builder->expr()->like('p.title', ':searchterm'),
                $builder->expr()->like('p.itunesSubtitle', ':searchterm'),
                $builder->expr()->like('p.itunesSummary', ':searchterm')
            ))
            ->orderBy('weight', 'DESC')
            ->setParameter('user', $user)
            ->setParameter('searchterm', '%'.$data['searchterm'].'%');

        try {
            return $builder->getQuery()->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
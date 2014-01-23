<?php
// src/Dahlberg/PodrBundle/Entity/UserEpisodeRepository.php;
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserEpisodeRepository extends EntityRepository {
    /**
     * @param $user
     * @return array|null
     */
    public function findLatestUnreadEpisodes($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 WHERE ue.user = :user and ue.unread = true
                 ORDER BY e.publishedDate DESC')
            ->setMaxResults(10)
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @return array|null
     */
    public function findMostLikedPodcasts($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT NEW DahlbergPodrBundle:PodcastDTO(p.id, p.title, SUM(ue.rating)), SUM(ue.rating) as sum_rating FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 JOIN DahlbergPodrBundle:Podcast p WITH e.podcast = p
                 WHERE ue.user = :user
                 GROUP BY p
                 ORDER BY sum_rating DESC')
            ->setMaxResults(10)
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @return array|null
     */
    public function findMostUnarchivedPodcasts($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT NEW DahlbergPodrBundle:PodcastDTO(p.id, p.title, COUNT(ue.archived)), COUNT(ue.archived) as num_unarchived FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 JOIN DahlbergPodrBundle:Podcast p WITH e.podcast = p
                 WHERE ue.user = :user AND ue.archived = false
                 GROUP BY p
                 ORDER BY num_unarchived DESC')
            ->setMaxResults(10)
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @return array|null
     */
    public function findMostUnreadPodcasts($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT NEW DahlbergPodrBundle:PodcastDTO(p.id, p.title, SUM(ue.unread)), SUM(ue.unread) as num_unread FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 JOIN DahlbergPodrBundle:Podcast p WITH e.podcast = p
                 WHERE ue.user = :user
                 GROUP BY p
                 ORDER BY num_unread DESC')
            ->setMaxResults(10)
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @return array|null
     */
    public function findOldestUnarchivedEpisodes($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 WHERE ue.user = :user and ue.archived = false
                 ORDER BY e.publishedDate ASC')
            ->setMaxResults(10)
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @return array|null
     */
    public function findStartedEpisodes($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT ue FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 WHERE ue.user = :user and ue.archived = false
                 ORDER BY ue.currentPosition DESC')
            ->setMaxResults(10)
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @return array|null
     */
    public function findStashedEpisodes($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 WHERE ue.user = :user and ue.stashed = true
                 ORDER BY e.publishedDate DESC')
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @return array|null
     */
    public function findUnarchivedEpisodes($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 WHERE ue.user = :user and ue.archived = false
                 ORDER BY e.publishedDate DESC')
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $user
     * @return array|null
     */
    public function findUnreadEpisodes($user) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 WHERE ue.user = :user and ue.unread = true
                 ORDER BY e.publishedDate DESC')
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /* Advanced search */

    public function findByDataOptions($data, $user) {
        $builder = $this->createQueryBuilder('ue');
        $builder->select('ue AS userepisode, (CASE WHEN (e.title LIKE :searchterm) THEN 10 ELSE 0 END) +
                        (CASE WHEN (e.itunesSubtitle LIKE :searchterm) THEN 5 ELSE 0 END) +
                        (CASE WHEN (e.itunesSummary LIKE :searchterm) THEN 5 ELSE 0 END)
                        AS weight')
            ->innerJoin('ue.episode', 'e')
            ->where('ue.user = :user')
            ->andWhere($builder->expr()->orX(
                $builder->expr()->like('e.title', ':searchterm'),
                $builder->expr()->like('e.itunesSubtitle', ':searchterm'),
                $builder->expr()->like('e.itunesSummary', ':searchterm')
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
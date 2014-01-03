<?php
// src/Dahlberg/PodrBundle/Entity/UserEpisodeRepository.php;
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserEpisodeRepository extends EntityRepository {
    /**
     * TODO: Known error when rating is negative
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
}
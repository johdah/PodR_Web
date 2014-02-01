<?php
// src/Dahlberg/PodrBundle/Entity/PodcastRepository.php;
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PodcastRepository extends EntityRepository {
    public function findAllPodcastsByPlaylist($playlist)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM DahlbergPodrBundle:Podcast p
                 JOIN DahlbergPodrBundle:PlaylistPodcast pp WITH pp.podcast = p
                 WHERE pp.playlist = :playlist
                 ORDER BY p.title ASC')
            ->setParameter('playlist', $playlist);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findAllPodcastsByUser($user)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM DahlbergPodrBundle:Podcast p
                 JOIN DahlbergPodrBundle:UserPodcast up WITH up.podcast = p
                 WHERE up.user = :user
                 ORDER BY p.title ASC')
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findPartialBy(array $values, array $criterias = array())
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select($values);

        foreach ($criterias as $key => $value) {
            $qb->andWhere(sprintf("i.%s", $key), sprintf(":%s", $key));
            $qb->setParameter(sprintf(":%s", $key), $value);
        }

        return $qb->getQuery()->getResult();
    }
}
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
}
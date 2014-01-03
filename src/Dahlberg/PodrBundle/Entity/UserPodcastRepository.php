<?php
// src/Dahlberg/PodrBundle/Entity/UserPodcastRepository.php;
namespace Dahlberg\PodrBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserPodcastRepository extends EntityRepository {
    public function findMostLikedPodcast() {
        $user = $this->get('security.context')->getToken()->getUser();
        return $this->getEntityManager()
            ->createQuery(
                'SELECT up FROM DahlbergPodrBundle:UserPodcast up
                 WHERE up.user = :user')
            ->setParameter('user', $user)
            ->getResult();

        /*$mostLikedPodcasts = DB::table('user_episode')->where('user_id', Auth::user()->id)
            ->join('episodes', 'user_episode.episode_id', '=', 'episodes.id')
            ->join('podcasts', 'episodes.podcast_id', '=', 'podcasts.id')
            ->select('episodes.podcast_id', 'podcasts.title', DB::raw('SUM(user_episode.rating) as podcast_rating'))
            ->groupBy('episodes.podcast_id')->take(10)->get();*/
    }
}
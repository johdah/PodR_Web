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
                'SELECT NEW DahlbergPodrBundle:PodcastDTO(p.id, p.title, SUM(ue.rating)) FROM DahlbergPodrBundle:UserEpisode ue
                 JOIN DahlbergPodrBundle:Episode e WITH ue.episode = e
                 JOIN DahlbergPodrBundle:Podcast p WITH e.podcast = p
                 WHERE ue.user = :user
                 GROUP BY p
                 ORDER BY ue.rating DESC')
            ->setMaxResults(10)
            ->setParameter('user', $user);

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }

        /*$mostLikedPodcasts = DB::table('user_episode')->where('user_id', Auth::user()->id)
            ->join('episodes', 'user_episode.episode_id', '=', 'episodes.id')
            ->join('podcasts', 'episodes.podcast_id', '=', 'podcasts.id')
            ->select('episodes.podcast_id', 'podcasts.title', DB::raw('SUM(user_episode.rating) as podcast_rating'))
            ->groupBy('episodes.podcast_id')->take(10)->get();*/
    }
}
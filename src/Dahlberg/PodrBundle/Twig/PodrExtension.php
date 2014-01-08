<?php
// src/Dahlberg/PodrBundle/Twig/PodrExtension.php
namespace Dahlberg\PodrBundle\Twig;

class PodrExtension extends \Twig_Extension {
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('secondsToTime', array($this, 'secondsToTimeFilter')),
        );
    }

    /**
     * Convert seconds to a time string
     *
     * @param $totalSeconds
     * @return string
     */
    public function secondsToTimeFilter($totalSeconds) {
        if($totalSeconds == -1) return "-";

        $hours = ($totalSeconds / 3600) % 24;
        $minutes = ($totalSeconds / 60) % 60;
        $seconds = $totalSeconds % 60;

        return ($hours < 10 ? "0" . $hours : $hours)
        . ":" . ($minutes < 10 ? "0" . $minutes : $minutes)
        . ":" . ($seconds < 10 ? "0" . $seconds : $seconds);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'podr_extension';
    }
}
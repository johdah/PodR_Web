<?php
// src/Dahlberg/PodrBundle/Lib/DateTimeParser.php;

namespace Dahlberg\PodrBundle\Lib;

class DateTimeParser {
    public static function parseColonSeparatedTime($time) {
        $seconds = 0;

        foreach(explode(':', $time) as $part)
            $seconds = $seconds*60 + intval($part);

        return $seconds;
    }
}
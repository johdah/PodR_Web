<?php
// src/Dahlberg/PodrBundle/Lib/PodcastParser.php;
namespace Dahlberg\PodrBundle\Lib;

/**
 * TODO: Maybe there is need for a sortPodcastItems method?
 * TODO: Is every official field supported?
 * TODO: Better error handling and messages
 *
 * Priorities:
 * - Ease of Maintenance
 * - Well Documented
 * - Efficiency and Speed
 * - Reusable
 */
class PodcastParser {
    const iTunesNamespace = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
    const atomNamespace = 'http://www.w3.org/2005/Atom';

    private $podcastFileContents;
    private $podcastURL;
    private $podcastXML;
    private $totalFeedItems;

    private $iterPos = 1;
    private $iterEpisode = null;
    public $validationErrors = array();

    /**
     * @param $url TODO: Sanity check?
     */
    function __construct($url) {
        $this->podcastURL = $url;

        # Go ahead and load the Podcast feed now
        $headers = get_headers($url);
        if(substr($headers[0], 9, 3) != "404")
            $this->podcastFileContents = file_get_contents($this->podcastURL, FILE_TEXT);
        else
            $this->podcastFileContents = false;

        if ($this->podcastFileContents === FALSE) {
            $this->validationErrors['failedOpenURL'] = 'Failed to open the URL "' . $this->podcastURL . '"';
        }

        if ($this->isValidPodcastFeed() === TRUE) {
            $this->preProcessFeed();
        } else {
            $this->validationErrors['invalidPodcastFeed'] = 'The document at "' . $this->podcastURL .'" does not appear to be a valid podcast feed.';
        }
    }

    /**
     * PreCond:
     * - Data in podcastFileContents
     * PostCond:
     * - Return True if valid feed
     * - Return Exception if not valid feed
     *
     * @throws Exception
     * @return bool
     */
    public function isValidPodcastFeed() {
        // Test for valid XML and if so store it to podcastXML
        $this->podcastXML = $this->isValidXML();
        if ($this->podcastXML === false) {
            // If this is not XML, let's not bother going further
            $this->validationErrors['invalidXML'] = 'The document at "' . $this->podcastURL . '" is not valid XML';
            return false;
        }

        // Test if feed has a channel tag
        if (isset($this->podcastXML->channel) === false) {
            $this->validationErrors['noChannel'] = 'The document at "' . $this->podcastURL . '" does not contain a <channel> tag where appropriate. As a result, this does not appear to be a podcast feed.';
            return false;
        }

        // Test if feed has any enclosure tag(s)
        $counter = 0;
        $foundEnclosure = false;
        $this->totalFeedItems = count($this->podcastXML->channel[0]->item);
        $this->iterPos = $this->totalFeedItems-1;
        if($this->iterPos > -1)
            $this->iterEpisode = $this->podcastXML->channel[0]->item[$this->iterPos];

        while (($counter < $this->totalFeedItems) and ($foundEnclosure === false)) {
            if (isset($this->podcastXML->channel[0]->item[$counter]->enclosure['url'])) {
                $foundEnclosure = true;
            }

            // Ideally, this loop is only executed once.
            $counter++;
        }

        if ($foundEnclosure === false) {
            $this->validationErrors['noEnclosures'] = 'The document at "' . $this->podcastURL . '" does not contain any appropriate englosure tags where appropriate. As a result, this does not appear to be a podcast feed.';
            return false;
        }

        return true;
    }

    /**
     * PreCond:
     * - Data in podcastFileContents
     * PostCond:
     * - Return False if invalid
     * - Return SimpleXML object if valid
     * @return \SimpleXMLElement
     */
    public function isValidXml() {
        $isXML = @simplexml_load_string($this->podcastFileContents, 'SimpleXMLElement', LIBXML_NOCDATA);

        return $isXML;
    }

    /**
     * PreCond:
     * - $this->podcastXML is a simpleXML object
     * PostCond:
     * - $this->podcastXML is organizated efficiently
     *
     * TODO: Needed?
     */
    private function preProcessFeed() {
        return;
    }

    /*
     * Episode parsing
     */
    public function getEpisodeEnclosureLength() {
        return intval($this->iterEpisode->enclosure["length"]);
    }

    public function getEpisodeEnclosureType() {
        return (string) $this->iterEpisode->enclosure["type"];
    }

    public function getEpisodeEnclosureURL() {
        return (string) $this->iterEpisode->enclosure["url"];
    }

    public function getEpisodeGuid() {
        return (string) $this->iterEpisode->guid;
    }

    public function getEpisodeItunesAuthor() {
        return (string) $this->iterEpisode->children(self::iTunesNamespace)->author;
    }

    public function getEpisodeItunesBlock() {
        return $this->iterEpisode->children(self::iTunesNamespace)->block == "yes";
    }

    public function getEpisodeItunesDuration() {
        return DateTimeParser::parseColonSeparatedTime($this->iterEpisode->children(self::iTunesNamespace)->duration);
    }

    public function getEpisodeItunesExplicit() {
        return $this->iterEpisode->children(self::iTunesNamespace)->explicit == "yes";
    }

    public function getEpisodeItunesIsClosedCaption() {
        return $this->iterEpisode->children(self::iTunesNamespace)->isClosedCaptioned == "yes";
    }

    public function getEpisodeItunesImage() {
        if(!$this->iterEpisode->children(self::iTunesNamespace) || !$this->iterEpisode->children(self::iTunesNamespace)->image
            || !$this->iterEpisode->children(self::iTunesNamespace)->image->attributes())
            return null;
        return (string) $this->iterEpisode->children(self::iTunesNamespace)->image->attributes()->href;
    }

    public function getEpisodeItunesSubtitle() {
        return (string) $this->iterEpisode->children(self::iTunesNamespace)->subtitle;
    }

    public function getEpisodeItunesSummary() {
        return (string) $this->iterEpisode->children(self::iTunesNamespace)->summary;
    }

    public function getEpisodePubDate() {
        return new \DateTime((string)$this->iterEpisode->pubDate);
    }

    public function getEpisodeTitle() {
        return (string) $this->iterEpisode->title;
    }

    /*
     * Podcast parsing
     */
    public function getPodcastCopyright() {
        return (string) $this->podcastXML->channel[0]->copyright;
    }

    public function getPodcastDescription() {
        return (string) $this->podcastXML->channel[0]->description;
    }

    public function getPodcastItunesAuthor() {
        return (string) $this->podcastXML->channel[0]->children(self::iTunesNamespace)->author;
    }

    public function getPodcastItunesBlock() {
        return $this->podcastXML->channel[0]->children(self::iTunesNamespace)->block == "yes";
    }

    public function getPodcastItunesComplete() {
        return $this->podcastXML->channel[0]->children(self::iTunesNamespace)->complete == "yes";
    }

    public function getPodcastItunesExplicit() {
        return $this->podcastXML->channel[0]->children(self::iTunesNamespace)->explicit == "yes";
    }

    public function getPodcastItunesImage() {
        if(!$this->podcastXML->channel[0]->children(self::iTunesNamespace) || !$this->podcastXML->channel[0]->children(self::iTunesNamespace)->image
            || !$this->podcastXML->channel[0]->children(self::iTunesNamespace)->image->attributes())
            return null;
        return (string) $this->podcastXML->channel[0]->children(self::iTunesNamespace)->image->attributes()->href;
    }

    public function getPodcastItunesOwnerEmail() {
        return (string) $this->podcastXML->channel[0]->children(self::iTunesNamespace)->owner->children(self::iTunesNamespace)->email;
    }

    public function getPodcastItunesOwnerName() {
        return (string) $this->podcastXML->channel[0]->children(self::iTunesNamespace)->owner->children(self::iTunesNamespace)->name;
    }

    public function getPodcastItunesSubtitle() {
        return (string) $this->podcastXML->channel[0]->children(self::iTunesNamespace)->subtitle;
    }

    public function getPodcastItunesSummary() {
        return (string) $this->podcastXML->channel[0]->children(self::iTunesNamespace)->summary;
    }

    public function getPodcastLanguage() {
        return (string) $this->podcastXML->channel[0]->language;
    }

    public function getPodcastLink() {
        return (string) $this->podcastXML->channel[0]->link;
    }

    public function getPodcastTitle() {
        return (string) $this->podcastXML->channel[0]->title;
    }

    /* Iterator methods */
    public function current() {
        return $this->iterPos;
    }

    public function exists() {
        return $this->iterPos > -1;
    }

    public function hasNext() {
        return $this->iterPos-1 > -1;
    }

    public function next() {
        $this->iterPos--;
        $this->iterEpisode = ($this->iterPos > -1) ? $this->podcastXML->channel[0]->item[$this->iterPos] : null;
    }

    public function rewind() {
        $this->iterPos = $this->totalFeedItems-1;
        $this->iterEpisode = ($this->iterPos > -1) ? $this->podcastXML->channel[0]->item[$this->iterPos] : null;
    }

    public function rewindToGUID($guid) {
        $counter = 0;

        while ($counter < $this->totalFeedItems) {
            if ($this->podcastXML->channel[0]->item[$counter]->guid == $guid) {
                $this->iterPos = $counter;
                $this->iterEpisode = $this->podcastXML->channel[0]->item[$this->iterPos];
                return;
            }

            $counter++;
        }

        $this->iterPos = -1;
        $this->iterEpisode = null;
    }
}
<?php
// src/Dahlberg/PodrBundle/Lib/OpmlParser.php;
namespace Dahlberg\PodrBundle\Lib;

/**
 * TODO: Complient with official OPML2 specs?
 * TODO: Better error handling and messages
 * TODO: No support for multiple levels
 *
 * Priorities:
 * - Ease of Maintenance
 * - Well Documented
 * - Efficiency and Speed
 * - Reusable
 */
class OpmlParser {
    private $opmlFileContents;
    private $opmlURL;
    private $opmlXML;
    private $totalBodyItems;

    private $iterPos = -1;
    private $iterOutline = null;
    public $errors = array();


    /**
     * @param $url TODO: Sanity check?
     */
    function __construct($url) {
        $this->opmlURL = $url;

        # Go ahead and load the OPML feed now
        if($this->isValidURL($this->opmlURL))
            $this->opmlFileContents = file_get_contents($this->opmlURL, FILE_TEXT);
        else
            $this->opmlFileContents = false;

        if ($this->opmlFileContents === FALSE) {
            $this->errors['failedOpenURL'] = 'Failed to open the URL "' . $this->opmlURL . '"';
        }

        if ($this->isValidOPMLFeed() === TRUE) {
            //$this->preProcessFeed();
        } else {
            $this->errors['invalidOPML'] = 'The document at "' . $this->opmlURL .'" does not appear to be a valid OPML feed.';
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
    public function isValidOPMLFeed() {
        // Test for valid XML and if so store it to opmlXML
        $this->opmlXML = $this->isValidXML();
        if ($this->opmlXML === false) {
            // If this is not XML, let's not bother going further
            $this->errors['invalidXML'] = 'The document at "' . $this->opmlURL . '" is not valid XML';
            return false;
        }

        // Test if feed has a body tag
        if (isset($this->opmlXML->body) === false) {
            $this->errors['noBody'] = 'The document at "' . $this->opmlURL . '" does not contain a <body> tag where appropriate. As a result, this does not appear to be a OPML file.';
            return false;
        }

        $this->totalBodyItems = count($this->opmlXML->body->outline);

        return true;
    }

    /**
     * TODO: Not working for local files
     * @param $url
     * @return bool
     */
    public static function isValidURL($url) {
        //$headers = get_headers($url);
        //return substr($headers[0], 9, 3) != "404";
        return true;
    }

    /**
     * PreCond:
     * - Data in opmlFileContents
     * PostCond:
     * - Return False if invalid
     * - Return SimpleXML object if valid
     * @return \SimpleXMLElement
     */
    public function isValidXml() {
        $isXML = @simplexml_load_string($this->opmlFileContents, 'SimpleXMLElement', LIBXML_NOCDATA);

        return $isXML;
    }

    public function noOfErrors() {
        return count($this->errors);
    }

    /*
     * Head parsing
     */
    public function getHeadTitle() {
        return (string) $this->opmlXML->head[0]->title;
    }

    /**
     * TODO: Not tested
     * @return \DateTime
     */
    public function getDateCreated() {
        return new \DateTime((string) $this->opmlXML->head[0]->dateCreated);
    }

    /**
     * TODO: Not tested
     * @return \DateTime
     */
    public function getDateModified() {
        return new \DateTime((string) $this->opmlXML->head[0]->dateModified);
    }

    public function getHeadOwnerEmail() {
        return (string) $this->opmlXML->head[0]->ownerEmail;
    }

    public function getHeadOwnerName() {
        return (string) $this->opmlXML->head[0]->ownerName;
    }

    /*
     * Body parsing
     */
    public function getBodyDescription() {
        if (!$this->iterOutline->attributes())
            return false;
        return (string) $this->iterOutline->attributes()->description;
    }

    public function getBodyHtmlUrl() {
        if (!$this->iterOutline->attributes())
            return false;
        return (string) $this->iterOutline->attributes()->htmlUrl;
    }

    public function getBodyLanguage() {
        if (!$this->iterOutline->attributes())
            return false;
        return (string) $this->iterOutline->attributes()->language;
    }

    public function getBodyText() {
        if (!$this->iterOutline->attributes())
            return false;
        return (string) $this->iterOutline->attributes()->text;
    }

    public function getBodyTitle() {
        if (!$this->iterOutline->attributes())
            return false;
        return (string) $this->iterOutline->attributes()->title;
    }

    public function getBodyType() {
        if (!$this->iterOutline->attributes())
            return false;
        return (string) $this->iterOutline->attributes()->type;
    }

    public function getBodyVersion() {
        if (!$this->iterOutline->attributes())
            return false;
        return (string) $this->iterOutline->attributes()->version;
    }

    public function getBodyXmlUrl() {
        if (!$this->iterOutline->attributes())
            return false;
        return (string) $this->iterOutline->attributes()->xmlUrl;
    }

    /*
     * Iterator methods
     */
    public function current() {
        return $this->iterPos;
    }

    public function exists() {
        return $this->iterOutline != null;
    }

    public function hasNext() {
        return $this->iterPos+1 < $this->totalBodyItems;
    }

    public function jump($id) {
        if($id < 0 || $id > $this->totalBodyItems-1)
            return false;
        $this->iterPos = $id;
        $this->iterOutline = $this->opmlXML->body->outline[$this->iterPos];
        return true;
    }

    public function next() {
        $this->iterPos++;
        $this->iterOutline = ($this->iterPos > -1 && $this->iterPos < $this->totalBodyItems) ? $this->opmlXML->body->outline[$this->iterPos] : null;
    }

    public function reset() {
        $this->iterPos = -1;
        $this->iterOutline = null;
    }
}
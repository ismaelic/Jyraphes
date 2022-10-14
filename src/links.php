<?php

/**
 * Model to manage the file links database.
 */
class JyrapheLinks {
    protected $db;
    protected $link_length = 8;

    function __construct() {
        $this->db = new PDO('sqlite:' . paths('links.db'));
    }

    protected function randomLink() {
        $chars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');

        $sel_chars = array_rand($chars, $this->link_length);

        $link = '';
        foreach($sel_chars as $charkey) {
            $link.= $chars[$charkey];
        }

        return $link;
    }
    
    protected function uniqueLink() {
        // Let's generate the unique random string.
        $count = 1;
        while($count > 0) {
            $link = $this->randomLink();
            $stmt = $this->db->query("SELECT COUNT(link) FROM links WHERE link='$link'");
            $count = $stmt->fetch()[0];
        }

        return $link;
    }

    function makeLink($target, $expiry = false) {
        if(!$expiry) {
            $expiry = time() + 7 * 24 * 3600; // 1 week validity.
        }

        $link = $this->uniqueLink();
        $this->db->query("INSERT INTO links(link, target, expiry) VALUES('$link', '$target', '$expiry')");

        return $link;
    }

    function getLink($link) {
        $stmt = $this->db->prepare(
            "SELECT target FROM links WHERE link=:link AND expiry >= :expiry"
        );
        if(!$stmt->execute(['link' => $link, 'expiry' => time()])) {
            die("didn't work...");
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(count($result) < 1) {
            return false;
        }
        
        return $result['target'];
    }

    function prepareDB() {
        $this->db->query("CREATE TABLE links(link CHAR(32), target TEXT, expiry INTEGER, PRIMARY KEY(link))");
        $this->db->query("CREATE TABLE info(name CHAR(32), value TEXT, PRIMARY KEY(name))");
        $this->db->query("INSERT INTO info(name, value) VALUES('version', '1.0')");
        return true;
    }
}

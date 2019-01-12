<?php

require_once 'model/Language.php';

class FilmInfo extends Film // classi loomine pärimisega
{
    public $actors=array();
    public $categories=array();
    public $language; // muutujate maaramine

    public function __construct($id, $title, $description, $releaseYear, $length, $actors, $categories, $language) // konstrueerimis funktsioon
    {
        parent::__construct($id, $title, $description, $releaseYear, $length); // konstrueerimis funktsioon
        $this->actors=$actors;
        $this->categories=$categories;
        $this->language=$language; // selle välja maaramine
    }

    public function __toString() {
        return parent::__toString()." Language: {".$this->$language->getName()."}";
    }

    function getCategories() {
        return $this->categories;
    }

    function getActors() {
        return $this->actors;
    }
}


<?php

class Film // classi loomine
{
    public $id;
    public $title;
    public $description;
    public $releaseYear;
    public $length; // muutujate maaramine

    public function __construct($id, $title, $description, $releaseYear, $length) // konstrueerimis funktsioon
    {
        $this->id=$id;
        $this->title=$title;
        $this->description=$description;
        $this->releaseYear=$releaseYear;
        $this->length=$length; // selle välja maaramine
    }

    public function __toString() {
        return "{$this->title} Year: {$this->releaseYear} Length: {$this->length}";
    }
}



<?php

class Actor // classi loomine
{
    public $id;
    public $lastname;
    public $firstname; // muutujate maaramine

    public function __construct($id, $firstname, $lastname) // konstrueerimis funktsioon
    {
        $this->id=$id; // selle välja maaramine
        $this->firstname=$firstname;
        $this->lastname=$lastname;
    }

    public function getId() {
        return $this->id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }
}
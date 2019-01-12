<?php

class Language // classi loomine
{
    public $id;
    public $name; // muutujate maaramine

     public function __construct($id, $name) // konstrueerimis funktsioon
     {
         $this->id=$id;
         $this->name=$name; // selle välja maaramine
     }

    public function __toString() {
        return "{$this->name}";
    }

    public function getName() {
        return $this->name;
    }
}
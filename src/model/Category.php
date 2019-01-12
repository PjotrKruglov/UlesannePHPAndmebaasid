<?php

class Category // classi loomine
{
    public $id;
    public $name; // muutujate maaramine

     public function __construct($id, $name) // konstrueerimis funktsioon
     {
         $this->id=$id;
         $this->name=$name; // selle välja maaramine
     }

     public function getName() {
        return $this->name;
    }
}
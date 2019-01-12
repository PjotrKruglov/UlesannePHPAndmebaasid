<?php
 
interface IServiceDB 
{ // interface loomine
    public function connect();
    public function getAllFilms();
    public function getFilmByID($id);
    public function getAllFilmsInfo(); // abstraksete funktsioonide määramine
}
<?php

class PDOService implements IServiceDB
{	
	private $connectDB; // ühenduse muutuja
	
	public function connect() {	// ühenduse funktsioon
        try {
            $this->connectDB = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE.";charset=".DB_CHARSET, 
                                DB_USERNAME, DB_PASSWORD); // ühenduse määramine
        }
		catch (PDOException $ex) { // viga
			printf("Connection failed: %s", $ex->getMessage()); // vea printimine
			exit();
		}
		return true;
	}
	
	public function getAllFilms() // funktsioon koiki filmi saamiseks
	{	
		$films=array();
		if ($this->connect()) { // kui ühendus toimub
			if ($result = $this->connectDB->query('SELECT * FROM film')) { // kui lause toimib
				$rows = $result->fetchAll(PDO::FETCH_ASSOC); // array tagastamine
                foreach($rows as $row){ // iga array liige jaoks
					$films[]=new Film($row['film_id'], $row['title'], $row['description'], 
										$row['release_year'], $row['language_id'], $row=['length']); // film objekti loomine reast
                 } 
			}
		}
        $this->connectDB=null; // uhenduse katkestamine
		return $films;
	}

	
	public function getFilmByID($id) // filmi tagastamine id jargi
	{	
		$film=null;
		if ($this->connect()) {
			if ($result = $this->connectDB->prepare('SELECT * FROM film WHERE film_id=:id')) {
				$result->execute(array('id'=>$id)); // array loomine vastusest
				//$result->execute(['id'=>$id]);
                // $result->bindValue(':id', $id, PDO::PARAM_INT);
                // $result->execute();
				
				$numRows = $result->rowCount(); // ridade arvu saamine
				if ($numRows==1) { // on üks vastus
					$row=$result->fetch(); // array loomine
					$film=new Film($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);  // objkti film määramine
				}
			}
		}
        $this->connectDB=null;
	    return $film;	// ühenduse katkestamine ja filmi tagastamine
	}

    public function getAllFilmsInfo()
	{
		$films=array();
		if ($this->connect()) {
			if ($result = $this->connectDB->query('SELECT * FROM film_info')) {
				$rows = $result->fetchAll(PDO::FETCH_ASSOC); // associative array loomine
                foreach($rows as $row){ // iga rea jaoks
					$actors=array(); // actors array loomine
					foreach (explode(";",$row['actors']) as $item) { // iga rida jagada ';' abil ja määrata actors nagu item
					   $actor=explode(",",$item); // item jagada array-ks
					   $actors[]=new Actor($actor[0], $actor[1],$actor[2]); // actors array taitmine
					}
					$categories=array();
					foreach (explode(";",$row['categories']) as $item) {
					   $category=explode(",",$item); // category array täitmine
					   $categories[]=new Category($category[0], $category[1]); // categories array loomine
					}
					$item=explode(',',$row['language']); // rea jagamine ja item täitmine
					$language=new Language($item[0], $item[1]); // language täitmine
					$films[]=new FilmInfo($row['id'], $row['title'], $row['description'], 
										$row['year'],  $row=['length'], $actors, $categories, $language); // films array loomine					
                 } 				
			}
		    $this->connectDB=null;
		}
		return $films;
	}

	public function getAllCategories()
	{
		$categories=array();
		if ($this->connect()) {
			if ($result = $this->connectDB ->query('SELECT * FROM category')) {
				$rows = $result->fetchAll(PDO::FETCH_ASSOC);
				foreach($rows as $row){ // iga array liige jaoks
					$categories[]=new Category($row['category_id'], $row['name']); // categories array loomine rea andmetest
                }
			}
		}
		$this->connectDB=null;
		return $categories;
	}

	public function getFilmInfoByCategory($category) // filmid category järgi funktsioon
	{	
		$films=array();
		$filminfos=array();
		$filminfos=getAllFilmsInfo();
		if ($this->connect()) {
			foreach ($filminfos as $filminfo) {
				foreach ($filminfo->getCategories() as $categor) {
					foreach ($categor as $categ) {
						$categName = $categ->getName();
						if ($categName = $category)
						$films[]=$filminfo;
					}
				}
			}	
		}
		$this->connectDB=null;
	    return $films;	
	}

	public function getAllActors() // kogu actorite saamine
	{
		$actors=array();
		if ($this->connect()) { // ühinduse tingimus
			if ($result = $this->connectDB ->query('SELECT * FROM actor')) { // query loomine
				$rows = $result->fetchAll(PDO::FETCH_ASSOC);
				foreach($rows as $row){ // tsukkel ridade jaoks
					$actors[]=new Actor($row['actor_id'], $row['firsname'], $row['lastname']); // films array loomine rea andmetest
                }
			}
		    
		}
		$this->connectDB=null;
		usort($actors, "cmp_lastname"); // http://php.net/manual/en/function.usort.php
		return $actors;
	}

	public function cmp_lastName($a, $b)
	{
		return strcmp($a->getLastname(), $b->getLastname());
	}

	public function getFilmInfoByActorId($actor) // filmid category järgi funktsioon
	{	
		$films=array();
		$filminfos=array();
		$filminfos=getAllFilmsInfo();
		if ($this->connect()) {
			foreach ($filminfos as $filminfo) {
				foreach ($filminfo->getActors() as $actors) {
					foreach ($actors as $actr) {
						$actorId = $actr->getId();
						if ($actorId = $actor)
						$films[]=$filminfo;
					}
				}
			}
		}
		$this->connectDB=null;
	    return $films;	
	}
}


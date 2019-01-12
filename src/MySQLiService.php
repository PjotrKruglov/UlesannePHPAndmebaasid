<?php

class MySQLiService implements IServiceDB // classi loomine interface kasutamisega
{	
	private $connectDB; // muutuja määramine
	
	public function connect() {	// ühinduse funktsioon
		$this->connectDB = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE); // ühindamine andmebaasiga
		$this->connectDB->set_charset(DB_CHARSET); // char määramine
		if (mysqli_connect_errno()) { // ühindus puudub
			printf("Connection failed: %s", mysqli_connect_error()); // printida viga
			exit(); // välju
		}
		return true; // tagastada true
	}
	
	public function getAllFilms() // filmi tagastamise funktsioon
	{	
		$films=array(); // array määramine
		if ($this->connect()) { // ühinduse tingimus
			if ($result = mysqli_query($this->connectDB, 'SELECT * FROM film')) { // query loomine
				while ($row = mysqli_fetch_assoc($result)) { // tsukkel ridade jaoks
					$films[]=new Film($row['film_id'], $row['title'], $row['description'], 
										$row['release_year'],  $row=['length']); // films array loomine rea andmetest
                 } 
				 mysqli_free_result($result); // tühistama result mälu
			}
		    mysqli_close($this->connectDB);	// uhinduse katkestamine
		}
		return $films; // filmide tagasamine
	}
	
	public function getFilmByID($id) // filmid ID järgi funktsioon
	{	
		$film=null;
		if ($this->connect()) {
			if ($query = mysqli_prepare($this->connectDB, 'SELECT * FROM film WHERE film_id=?')) { // lause valmistamine kasutamiseks
				$query->bind_param("i", $id); //"i" - $id is integer
				$query->execute(); // lause kasutamine
				$result = $query->get_result(); // tulemuste saamine
				$numRows = $result->num_rows; // tulemuste ridade numbri saamine
				if ($numRows==1) { // tingimus ridade numbri järgi
					$row=$result->fetch_array(MYSQLI_NUM); // tagastab väärtused array kujul
					$film=new Film($row[0], $row[1], $row[2], $row[3], $row[5]); // film objekti loomine
				}
				$query->close(); // query lõpetamine
			}
		    mysqli_close($this->connectDB);	
		}
	    return $film;	
	}

	public function getAllFilmsInfo() // kõike filmide info funktsioon
	{
		$films=array(); // array määramine
		if ($this->connect()) { // ühendus
			if ($result = mysqli_query($this->connectDB, 'SELECT * FROM film_info')) {
				while ($row = mysqli_fetch_assoc($result)) { // tagastab väärtused associative array kujul
					$actors=array(); // actors array loomine
					foreach (explode(";",$row['actors']) as $item) { // iga objeki row array-st nimega actors jagamine ; abil ja säilitamine nagu item
					   $actor=explode(",",$item); // item jaoks jagamine ,-ga masiiviks
					   $actors[]=new Actor($actor[0], $actor[1],$actor[2]); // actors array täitmine actor objektidega
					}
					$categories=array();
					foreach (explode(";",$row['categories']) as $item) {
					   $category=explode(",",$item);
					   $categories[]=new Category($category[0], $category[1]);
					}
					$item=explode(',',$row['language']);
					$language=new Language($item[0], $item[1]);
					$films[]=new FilmInfo($row['id'], $row['title'], $row['description'], 
										$row['year'],  $row=['length'], $actors, $categories, $language); // films array loomine objekidega filminfo
					
                 } 
				 mysqli_free_result($result);
			}
		    mysqli_close($this->connectDB);	
		}
		return $films;
	}

	public function getAllCategories()
	{
		$categories=array();
		if ($this->connect()) {
			if ($result = mysqli_query($this->connectDB, 'SELECT * FROM category')) {
				while ($row = mysqli_fetch_assoc($result)) { // tsukkel ridade jaoks
					$categories[]=new Category($row['category_id'], $row['name']); // categories array loomine rea andmetest
                 } 
				 mysqli_free_result($result); // tühistama result mälu
			}
		    mysqli_close($this->connectDB);	// uhinduse katkestamine
		}
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
			mysqli_close($this->connectDB);	
		}
	    return $films;	
	}

	public function getAllActors()
	{
		$actors=array();
		if ($this->connect()) { // ühinduse tingimus
			if ($result = mysqli_query($this->connectDB, 'SELECT * FROM actor')) { // query loomine
				while ($row = mysqli_fetch_assoc($result)) { // tsukkel ridade jaoks
					$actors[]=new Actor($row['actor_id'], $row['firsname'], $row['lastname']); // films array loomine rea andmetest
                }
				mysqli_free_result($result); // tühistama result mälu
			}
		    mysqli_close($this->connectDB);	// uhinduse katkestamine
		}
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
			mysqli_close($this->connectDB);	
		}
	    return $films;	
	}
}


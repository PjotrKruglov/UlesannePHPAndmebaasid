<?php
require_once "autoloader.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Films</title>
</head>
<body>
	<div class="sidenav">
    	<h2 >SELECT CATEGORY</h2>
        <?php
			///$db=new MySQLiService();
			$db=new PDOService();
			/*foreach ($db->getAllFilms() as $film) {
				echo $film->id." ".$film->title."<br />";
			}*/
			
    
			foreach ($db->getAllCategories() as $category) {
				print "<a href=result.php?name=".$category->id.">" .$category->name. "</a>";
			}
			/*
			$film=$db->getFilmByID(3);
			if (!is_null($film)) {
				echo "Film found: ".$film->title."<br />";
			}
			else {
				echo "Not found"."<br />";
			}
			echo "<pre>";
			$films=$db->getAllFilmsInfo();
			foreach ($films as $film) {
				var_dump($film);
			}
			echo "</pre>";
			*/
        ?>
    </body>
</html>

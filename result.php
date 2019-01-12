<?php
require_once "autoloader.php";
require_once 'model/Category.php';
$name = $_GET['name'];
$rightCategory = new Category();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="sidenav">
    <h2>SELECT CATEGORY</h2>
        <?php
            $db=new PDOService();
            foreach ($db->getAllCategories() as $category) {
				print "<a href=result.php?name=".$category->id.">" .$category->name. "</a>";
                if ($name == $category->id)
                {
                    $rightCategory = $category;
                }
            }
            ?>
    </div>
    <div class="main">
    <?php
        $db=new PDOService();
        echo "<h2>".$rightCategory->name."</h2>";
        $films=getFilmInfoByCategory($rightCategory->name);
        if (!is_null($films)) {
            foreach ($films as $film) {
            echo $film->title."<br />";
            }
        }
        else {
            echo "Not found"."<br />";
        }
    ?>
    </div>
</body>
</html>
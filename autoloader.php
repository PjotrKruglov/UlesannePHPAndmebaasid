<?php

 spl_autoload_register(function($name){
    require_once('settings.php');
    $dir=['src/','src/model/'];
    for ($i=0; $i < count($dir); $i++) { 
        if(file_exists($dir[$i].$name.'.php')) {
             require_once $dir[$i].$name.'.php';
         }
    }     
 });
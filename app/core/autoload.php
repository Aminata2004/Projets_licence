<?php
/**
 * Created by PhpStorm.
 * User: SNT
 * Date: 21/11/2022
 * Time: 12:11
 */

// include_once ("config.php");
// include_once ("database.php");
// include_once ("Controller.php");
// include_once ("model.php");
// include_once ("app.php");
// // Inclus Composer autoload si présent
// require_once ROOT . '/vendor/autoload.php'; // ✅ CORRECT


//  spl_autoload_register(function ($class_name)
//  {
//      require_once('app/models/'.ucfirst($class_name).'.php');
//  });







/**
 * Created by PhpStorm.
 * User: SNT
 * Date: 21/11/2022
 * Time: 12:11
 */

include_once ("config.php");
include_once ("database.php");
include_once ("Controller.php");
include_once ("model.php");
include_once ("app.php");

// **Ne PAS inclure vendor/autoload.php ici, c'est déjà fait dans index.php**
// require_once ROOT . '/vendor/autoload.php'; // <-- commenter ou supprimer

spl_autoload_register(function ($class_name) {
    // Ignorer les classes externes (gérées par Composer)
    if (strpos($class_name, 'Endroid\\') === 0) {
        return;
    }

    // Construire le chemin fichier depuis le namespace
    $file = __DIR__ . '/../models/' . str_replace('\\', '/', $class_name) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

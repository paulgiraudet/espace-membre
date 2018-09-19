<?php

session_start();
//connexion to the sql database
try {
  $bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (\Exception $e) {
  die('Erreur : ' . $e->getMessage());
}

//  Récupération de l'utilisateur et de son pass hashé
$req = $bdd->prepare('SELECT id, pass FROM membres WHERE pseudo = :pseudo');
$req->execute(array(
  'pseudo' => $pseudo = $_POST['pseudo']
));
$resultat = $req->fetch();

// Comparaison du pass envoyé via le formulaire avec la base
$isPasswordCorrect = password_verify($_POST['password'], $resultat['pass']);
 ?>


 <!doctype html>
 <html class="no-js" lang="fr">

 <head>
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <title>Connexion</title>
   <meta name="description" content="">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <link rel="manifest" href="site.webmanifest">
   <link rel="apple-touch-icon" href="icon.png">
   <!-- Place favicon.ico in the root directory -->

   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <link rel="stylesheet" href="css/normalize.css">
   <link rel="stylesheet" href="css/main.css">
 </head>

 <body>
   <?php
   // checking isset pseudo
   if (!$resultat)
   {
     echo 'Mauvais identifiant ou mot de passe !';
       ?>
       <a href="connexion.php" class="m-5">Connexion</a>
       <?php
   }
   else
   {
     if ($isPasswordCorrect) {
       session_start();
       $_SESSION['id'] = $resultat['id'];
       $_SESSION['pseudo'] = $_POST['pseudo'];
       echo 'Vous êtes connecté !';
       ?>
       <a href="index.php" class="m-5">Accueil</a>
       <a href="connexion.php" class="m-5">Page de connexion</a>
      <?php
     }
     else {
       echo 'Mauvais identifiant ou mot de passe !';
         ?>
         <a href="connexion.php" class="m-5">Connexion</a>
         <?php
     }
   }
  ?>

   <script src="js/vendor/modernizr-3.6.0.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
   <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
   <script src="js/plugins.js"></script>
   <script src="js/main.js"></script>

   <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
   <script>
     window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
     ga('create', 'UA-XXXXX-Y', 'auto'); ga('send', 'pageview')
   </script>
   <script src="https://www.google-analytics.com/analytics.js" async defer></script>
 </body>

 </html>

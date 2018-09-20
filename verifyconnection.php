<?php

//connexion to the sql database
include('db.php');

$pseudo = htmlspecialchars($_POST['pseudo']);
$password = htmlspecialchars($_POST['password']);

//  Récupération de l'utilisateur et de son pass hashé
$req = $bdd->prepare('SELECT id, pass FROM membres WHERE pseudo = :pseudo');
$req->execute(array(
  'pseudo' => $pseudo
));
$resultat = $req->fetch();

// Comparaison du pass envoyé via le formulaire avec la base
$isPasswordCorrect = password_verify($password, $resultat['pass']);

 include('header.php');
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
       $_SESSION['pseudo'] = $pseudo;
       echo 'Vous êtes connecté !';
       ?>
       <a href="index.php" class="m-5">Accueil</a>
       <a href="connexion.php" class="m-5">Page de Profil</a>
      <?php
     }
     else {
       echo 'Mauvais identifiant ou mot de passe !';
         ?>
         <a href="connexion.php" class="m-5">Connexion</a>
         <?php
     }
   }

include('footer.php');
  ?>

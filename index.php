<?php

session_start();



if (isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
  echo 'Bonjour ' . $_SESSION['pseudo'];
  ?>
  <a href="disconnection.php" class="m-5">Se déconnecter</a>
<?php
}
else {
  //connexion to the sql database
  try {
    $bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  }
  catch (\Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }


  // Validation tests

  // basic verification on our inputs
  if (isset($_POST['pseudo']) AND !empty($_POST['pseudo']) AND
      isset($_POST['password']) AND !empty($_POST['password']) AND
      isset($_POST['passwordbis']) AND !empty($_POST['passwordbis']) AND
      isset($_POST['email']) AND !empty($_POST['email'])) {

        //avoiding any dangerous html tag
        $_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
        $_POST['password'] = htmlspecialchars($_POST['password']);
        $_POST['passwordbis'] = htmlspecialchars($_POST['passwordbis']);
        $_POST['email'] = htmlspecialchars($_POST['email']);

        // asking in our table if we already have a pseudo with this name
        $reqPseudo = $bdd->query('SELECT pseudo FROM membres WHERE pseudo ="' . $_POST['pseudo'] . '"');
        // if there is one he is unique
        $samePseudo = $reqPseudo->fetch();

        // if there is one this condition is correct else we continue our tests
        if ($samePseudo['pseudo'] == $_POST['pseudo']) {
          echo "Ce pseudo est déjà utilisé, choisissez en un autre";
        }

        // verifying if the two passwords are the same one
        else if ($_POST['password'] != $_POST['passwordbis']) {
          echo "Les deux mots de passe ne sont pas identiques";
        }

        // regex for email verification
        else if (preg_match("#^[a-z0-9-_.]+@[a-z0-9-_.]{2,}\.[a-z]{2,4}$#", $_POST['email'])) {

          // asking our table if we already have an email with this name
          $reqMail = $bdd->query('SELECT email FROM membres WHERE email ="' . $_POST['email'] . '"');
          // it can only be unique
          $sameMail = $reqMail->fetch();

          // if there is one we stop the inscription
          if ($sameMail['email'] == $_POST['email']) {
            echo "Cet email est deja utilisé.";
          }

          // if we passed all the tests we finally go there

          else {
            //crypting password for our database
            $pass_hache = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Insertion
            $req = $bdd->prepare('INSERT INTO membres(pseudo, pass, email, date_inscription) VALUES(:pseudo, :pass, :email, CURDATE())');
            $req->execute(array(
              'pseudo' => $pseudo = $_POST['pseudo'],
              'pass' => $pass_hache,
              'email' => $email = $_POST['email']
            ));

            echo "Vous avez bien été inscrit(e) !";
          }

        }
        else {
          echo "Votre email est invalide.";
        }

    } //end of tests

  //in case someone tried to erase our required inputs
  else {
    echo "Au moins un champ est invalide.";
  }


 ?>


<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Inscription</title>
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

  ?>
  <form method="post" action="index.php" class="my-5">
    <div class="form-group">
      <label for="exampleInputPseudo">Pseudo</label>
      <input type="text" class="form-control" id="exampleInputPseudo" aria-describedby="pseudoHelp" placeholder="Entrez votre pseudo" name="pseudo" required>
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Mot de passe</label>
      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe" name="password" required>
    </div>
    <div class="form-group">
      <label for="exampleInputPassword2">Mot de passe (vérification)</label>
      <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Vérifiez votre mot de passe" name="passwordbis" required>
    </div>
    <div class="form-group">
    <label for="exampleInputEmail1">Email</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Entrez votre email" name="email" required>
    <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre email avec qui que ce soit</small>
  </div>
    <button type="submit" class="btn btn-primary">Inscription</button>
  </form>

  <a href="connexion.php" class="m-5">Me connecter</a>

  <?php
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

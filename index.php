<?php

session_start();

include('header.php');

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
  echo 'Bonjour ' . $_SESSION['pseudo'];
  ?>
  <a href="disconnection.php" class="m-5">Se déconnecter</a>
<?php
}
else {
  //connexion to the sql database
  include('db.php');
  $inscription = false;

if (isset($_POST['addUser'])) {

  // Validation tests

  // basic verification on our inputs
  if (isset($_POST['pseudo']) AND !empty($_POST['pseudo']) AND
      isset($_POST['password']) AND !empty($_POST['password']) AND
      isset($_POST['passwordbis']) AND !empty($_POST['passwordbis']) AND
      isset($_POST['email']) AND !empty($_POST['email'])) {

        //avoiding any dangerous html tag
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $password = htmlspecialchars($_POST['password']);
        $passwordbis = htmlspecialchars($_POST['passwordbis']);
        $email = htmlspecialchars($_POST['email']);

        // asking in our table if we already have a pseudo with this name
        $reqPseudo = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo = :pseudo');
        $reqPseudo->execute(array(
          'pseudo' => $pseudo
        ));
        // if there is one he is unique
        $samePseudo = $reqPseudo->fetch();

        // if there is one this condition is correct else we continue our tests
        if ($samePseudo['pseudo'] == $pseudo) {
          echo "Ce pseudo est déjà utilisé, choisissez en un autre";
        }

        // verifying if the two passwords are the same one
        else if ($password != $passwordbis) {
          echo "Les deux mots de passe ne sont pas identiques";
        }

        // regex for email verification
        else if (preg_match("#^[a-z0-9-_.]+@[a-z0-9-_.]{2,}\.[a-z]{2,4}$#", $email)) {

          // if we passed all the tests we finally go there

            //crypting password for our database
            $pass_hache = password_hash($password, PASSWORD_DEFAULT);

            // Insertion
            $req = $bdd->prepare('INSERT INTO membres(pseudo, pass, email, date_inscription) VALUES(:pseudo, :pass, :email, CURDATE())');
            $req->execute(array(
              'pseudo' => $pseudo,
              'pass' => $pass_hache,
              'email' => $email
            ));

            echo "Vous avez bien été inscrit(e) !";
            $inscription = true;
            ?>
            <a href="connexion.php" class="m-5">Me connecter</a>
            <?php
          }

        else {
          echo "Votre email est invalide.";
        }

    } //end of tests

  //in case someone tried to erase our required inputs
  else {
    echo "Au moins un champ est invalide.";
  }

}

  if (!$inscription) {

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
    <button type="submit" name="addUser" class="btn btn-primary">Inscription</button>
  </form>

  <a href="connexion.php" class="m-5">Me connecter</a>

  <?php
  }
}
include('footer.php');
?>

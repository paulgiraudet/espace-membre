<?php
session_start();

include('header.php');

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
  echo 'Bonjour ' . $_SESSION['pseudo'];
  ?>
  <br/>
  <a href="changeprofil.php">Changement d'identifiant/mot de passe</a>
  <a href="disconnection.php" class="m-5">Se déconnecter</a>
<?php
}
else {

?>
  <a href="index.php" class="m-5">Pas encore inscrit ?</a>

  <form method="post" action="verifyconnection.php" class="my-5">
    <div class="form-group">
      <label for="exampleInputPseudo">Pseudo</label>
      <input type="text" class="form-control" id="exampleInputPseudo" aria-describedby="pseudoHelp" placeholder="Entrez votre pseudo" name="pseudo" required>
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Mot de passe</label>
      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Connexion</button>
  </form>

<?php

}
include('footer.php');
?>

<?php
session_start();

include('header.php');

require('db.php');

if (isset($_POST['validChange'])) {

  if (isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND
      isset($_POST['password']) AND !empty($_POST['password']) AND
      isset($_POST['newpassword']) AND !empty($_POST['newpassword'])) {

    $newpseudo = htmlspecialchars($_POST['newpseudo']);
    $password = htmlspecialchars($_POST['password']);
    $newpassword = htmlspecialchars($_POST['newpassword']);

    // asking in our table if we already have a pseudo with this name
    $reqPseudo = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo = :pseudo');
    $reqPseudo->execute(array(
      'pseudo' => $newpseudo
    ));
    // if there is one he is unique
    $samePseudo = $reqPseudo->fetch();

    // if there is one this condition is correct else we continue our tests
    if ($samePseudo['pseudo'] == $newpseudo) {
      echo "Ce pseudo est déjà utilisé, choisissez en un autre";
    }

    else{

      $reqPseudo = $bdd->prepare('SELECT pass FROM membres WHERE pseudo = :pseudo');
      $reqPseudo->execute(array(
        'pseudo' => $_SESSION['pseudo']
      ));
      $actualPassword = $reqPseudo->fetch();

      $isPasswordCorrect = password_verify($password, $actualPassword['pass']);

      if ($isPasswordCorrect) {

        $pass_hache = password_hash($newpassword, PASSWORD_DEFAULT);

        $req = $bdd->prepare('UPDATE membres SET pseudo = :newpseudo, pass = :newpass WHERE pseudo = :oldpseudo');
        $req->execute(array(
          'newpseudo' => $newpseudo,
          'newpass' => $pass_hache,
          'oldpseudo' => $_SESSION['pseudo']
        ));
        echo "Vous avez un nouveau pseudo et un nouveau mot de passe !";
        $_SESSION['pseudo'] = $newpseudo;

        ?>
        <a href="connexion.php" class="m-5">Retour</a>
        <?php

      }
      else{
        echo "Mauvais mot de passe.";
      }
    }
  }

  else if (isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND
           isset($_POST['password']) AND !empty($_POST['password'])) {

    $newpseudo = htmlspecialchars($_POST['newpseudo']);
    $password = htmlspecialchars($_POST['password']);

    // asking in our table if we already have a pseudo with this name
    $reqPseudo = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo = :pseudo');
    $reqPseudo->execute(array(
      'pseudo' => $newpseudo
    ));
    // if there is one he is unique
    $samePseudo = $reqPseudo->fetch();

    // if there is one this condition is correct else we continue our tests
    if ($samePseudo['pseudo'] == $newpseudo) {
      echo "Ce pseudo est déjà utilisé, choisissez en un autre";
    }

    else {
      $reqPseudo = $bdd->prepare('SELECT pass FROM membres WHERE pseudo = :pseudo');
      $reqPseudo->execute(array(
        'pseudo' => $_SESSION['pseudo']
      ));
      $actualPassword = $reqPseudo->fetch();

      $isPasswordCorrect = password_verify($password, $actualPassword['pass']);

      if ($isPasswordCorrect) {
        $req = $bdd->prepare('UPDATE membres SET pseudo = :newpseudo WHERE pseudo = :oldpseudo');
        $req->execute(array(
          'newpseudo' => $newpseudo,
          'oldpseudo' => $_SESSION['pseudo']
        ));

        echo "Vous avez un nouveau pseudo !";
        $_SESSION['pseudo'] = $newpseudo;
        ?>
        <a href="connexion.php" class="m-5">Retour</a>
        <?php
      }
      else{
        echo "Mauvais mot de passe.";
      }

    }

  }

  else if (isset($_POST['password']) AND !empty($_POST['password']) AND
           isset($_POST['newpassword']) AND !empty($_POST['newpassword'])) {

      $password = htmlspecialchars($_POST['password']);
       $reqPassword = $bdd->prepare('SELECT pass FROM membres WHERE pseudo = :pseudo');
       $reqPassword->execute(array(
         'pseudo' => $_SESSION['pseudo']
       ));
       $actualPassword = $reqPassword->fetch();

       $isPasswordCorrect = password_verify($password, $actualPassword['pass']);

       if ($isPasswordCorrect) {

         $newpassword = htmlspecialchars($_POST['newpassword']);
         $pass_hache = password_hash($newpassword, PASSWORD_DEFAULT);

         $req = $bdd->prepare('UPDATE membres SET pass = :newpass WHERE pseudo = :oldpseudo');
         $req->execute(array(
           'oldpseudo' => $_SESSION['pseudo'],
           'newpass' => $pass_hache,
         ));
         echo "Vous avez un nouveau mot de passe !";
         ?>
         <a href="connexion.php" class="m-5">Retour</a>
         <?php
       }
       else{
         echo "Mauvais mot de passe.";
       }
  }
  else{
    echo "coco";
  }

}

else if (isset($_POST['changeInfo']) AND (!empty($_POST['pseudocheck']) OR !empty($_POST['passwordcheck']))) {
?>
<form class="my-5 container" action="changeprofil.php" method="post">

<?php
  if (isset($_POST['pseudocheck'])) {
    ?>
        <div class="form-group">
          <label for="exampleInputText">Nouveau pseudo :</label>
          <input type="text" name="newpseudo" class="form-control" id="exampleInputText" aria-describedby="textHelp" placeholder="New pseudo" required>
        </div>
    <?php
  }
    ?>
        <div class="form-group">
          <label for="exampleInputPassword1">Mot de passe actuel :</label>
          <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
        </div>
    <?php

  if (isset($_POST['passwordcheck'])){
    ?>
    <div class="form-group">
      <label for="exampleInputPassword2">Nouveau mot de passe :</label>
      <input type="password" name="newpassword" class="form-control" id="exampleInputPassword2" placeholder="New password" required>
    </div>
    <?php
  }
    ?>
    <button type="submit" class="btn btn-primary mt-3" name="validChange">Submit</button>
</form>

    <a href="changeprofil.php" class="ml-5">Annuler</a>
    <?php
}
else{
?>

<label class="mt-5 ml-4">Vous voulez changer votre :</label><br/>

<form class="ml-5 mb-5" action="changeprofil.php" method="post">

  <div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" id="customCheck1" name="pseudocheck" value="pseudocheck">
    <label class="custom-control-label" for="customCheck1">Pseudo</label>
  </div>
  <div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" id="customCheck2" name="passwordcheck" value="passwordcheck">
    <label class="custom-control-label" for="customCheck2">Mot de passe</label>
  </div>

   <button type="submit" class="btn btn-primary mt-3" name="changeInfo">DO IT</button>
</form>

<a href="connexion.php" class="ml-5">Retour</a>

<?php

}
include('footer.php');
 ?>

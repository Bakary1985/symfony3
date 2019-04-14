<?php

require_once('inc/init.inc.php');

//---------------- TRAITEMENT -------------------------
if ($_POST) {
  //debug($_POST,1);
  
        // Cryptage du mot de passe :
        //$_POST['mdp'] = md5($_POST['mdp']); // la fonction prédéfinie md5() permet de crypter un string. 
        
        // Retraitement du $_POST dans htmlentities() pour convertir les caractères spéciaux en entité HTML :
        foreach($_POST as $indice => $valeur) {
            $_POST[$indice] = htmlentities($valeur, ENT_QUOTES);
        }
        
        // Traitement des apostrophes :
        foreach($_POST as $indice => $valeur) {
            $_POST[$indice] = addslashes($valeur);
        }
            
        // Insertion en base :
        $mysqli->query("INSERT INTO membre (nom, email, mdp, statut) VALUES('$_POST[nom]', '$_POST[email]','$_POST[mdp]', 0)"); // 0 pour un membre non admin
        
        $contenu .= '<div class="bg-success">Vous êtes inscrit à notre site. <a href="connexion.php">Cliquez ici pour vous connecter</a></div>';
      

} // fin du if ($_POST)

 // 8- Pré-remplissage du formulaire de modification :
  if (isset($_POST['membre'])) { // si id_produit existe c'est que nous sommes en modification (car on ne passe pas d'id_produit en ajout)
    debug($_POST,1);

    $resultat = $mysqli->query("SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]'");
    $membre_actuel = $resultat->fetch_assoc(); // array qui permet de pré-remplir le formulaire ci-dessous
  }
// --------------- AFFICHAGE -------------------------
require_once('inc/haut.inc.php');
echo $contenu;
?>
<h3>Veuillez renseigner le formulaire pour vous inscrire :</h3>
<form method="post" action="">
 <label for="nom">Nom</label><br>
  <input type="text" id="nom" name="nom" value="">
  <br>
  
  <label for="email">Email</label><br>
  <input type="email" id="email" name="email" value="">
  <br>

  <label for="mdp">Mot de passe</label><br>
  <input type="password" id="mdp" name="mdp" value="">
  <br>

  <input type="submit" name="inscription" value="inscrire" class="btn">
</form>
<?php
require_once('inc/bas.inc.php');




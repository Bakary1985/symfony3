<?php
require_once('inc/init.inc.php');

// Demande de déconnexion par l'internaute :
if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
  session_destroy();  // on supprime la session du membre s'il demande la déconnexion
}

// Internaute déjà connecté est envoyé vers son profil :
if (internauteEstConnecte()) {
  // redirection vers la page profil :
  header('location:profil.php');
  exit();
}

// Traitement du formulaire :
if ($_POST) { // si le formulaire de connexion est soumis
  
   $resultat = $mysqli->query("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
   
   if ($resultat->num_rows != 0) { // s'il y a des enregistrements on vérifie le mdp : 
      $membre = $resultat->fetch_assoc(); // pas de boucle while car un seul résultat tout au plus possible
      
      //debug($membre);
      
      if ($membre['mdp'] == md5($_POST['mdp'])) {
        
        // on remplit la session avec les infos de $membre :
        foreach($membre as $indice => $valeur) {
          $_SESSION['membre'][$indice] = $valeur;
        }
        header('location:profil.php');
        exit();
      } else {
        $contenu .= '<div class="bg-danger">Erreur sur le mdp</div>';
      }
   } else {
     $contenu .= '<div class="bg-danger">Erreur sur le pseudo</div>';
   }
} // fin du if ($_POST)


//------------------------ AFFICHAGE --------------------
require_once('inc/haut.inc.php');
echo $contenu;
?>
<h3>Renseignez votre pseudo et votre mot de passe pour vous connecter</h3>
<form method = "post" action="">
  <label for="pseudo">Pseudo</label><br>
  <input type="text" id="pseudo" name="pseudo">
  <br>
  
  <label for="mdp">Mot de passe</label><br>
  <input type="password" id="mdp" name="mdp">
  <br>
  
  <input type="submit" value="Se connecter" class="btn">
</form>
<?php
require_once('inc/bas.inc.php');





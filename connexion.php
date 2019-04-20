<?php
require_once('inc/init.inc.php');

// Demande de d�connexion par l'internaute :
if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
  session_destroy();  // on supprime la session du membre s'il demande la d�connexion
}

// Internaute d�j� connect� est envoy� vers son profil :
if (internauteEstConnecte()) {
  // redirection vers la page profil :
  header('location:profil.php');
  exit();
}

// Traitement du formulaire :
if ($_POST) { // si le formulaire de connexion est soumis
  
   $resultat = $mysqli->query("SELECT * FROM membre WHERE email = '$_POST[email]'");
   
   if ($resultat->num_rows != 0) { // s'il y a des enregistrements on v�rifie le mdp : 
      $membre = $resultat->fetch_assoc(); // pas de boucle while car un seul r�sultat tout au plus possible
      
      debug($membre);
      debug($membre);
      
      if ($membre['mdp'] == $_POST['mdp']) {
        
        // on remplit la session avec les infos de $membre :
        foreach($membre as $indice => $valeur) {
          $_SESSION['membre'][$indice] = $valeur;
        }
        //header('location:profil.php');
        //exit();
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
  <label for="pseudo">Email</label><br>
  <input type="text" id="email" name="email">
  <br><br>
  
  <label for="mdp">Mot de passe</label><br>
  <input type="password" id="mdp" name="mdp">
  <br>
  
  <input type="submit" value="Se connecter" class="btn">
</form>
<?php
require_once('inc/bas.inc.php');





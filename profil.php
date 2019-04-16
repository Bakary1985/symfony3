<?php
require_once('inc/init.inc.php');

//--------------------- TRAITEMENT -------------------
// Redirection si visiteur non connecté :
if (!internauteEstConnecte()) {
  header('location:connexion.php'); // nous invitons l'internaute non connecté à se connecter
  exit(); // pour sortir du script
}

// Préparation de l'affichage du profil :
//debug($_SESSION);

$contenu .= '<h2>Bonjour '. $_SESSION['membre']['nom'] .'</h2>';

if (internauteEstConnecteEtEstAdmin()) {
  $contenu .= '<p>Vous êtes un administrateur.</p>';
} else {
  $contenu .= '<p>Vous êtes un membre.</p>';
}

$contenu .= '<div><h3>Voici vos informations de profil</h3>';
    $contenu .= '<p> Votre email : ' . $_SESSION['membre']['email'] . '</p>';
$contenu .= '</div>';

//-------------------
// EXERCICE :
//-------------------
/*  1- Affichez la liste des commandes passées par le membre sous son profil, sous forme de liste <ul><li>. Vous indiquerez l'id_commande, la date et l'état. Si toutefois il n'y avait pas de commande, vous afficherez "aucune commande en cours."

*/
$contenu .= '<h3>Historique de vos commandes</h3>';
$id_membre = $_SESSION['membre']['id_membre'];

$commande = $mysqli->query("SELECT id_commande, DATE_FORMAT(date_enregistrement, '%d-%m-%Y') AS date_enregistrement  , etat FROM commande WHERE id_membre = '$id_membre'");

if ($commande->num_rows > 0) {
    $contenu .= '<ul>';
        while($caps = $commande->fetch_assoc()) {
        $contenu .= '<li>Commande numéro '. $caps['id_commande'] .' passée le '. $caps['date_enregistrement'] .'. Actuellement le statut de votre commande est '. $caps['etat'] .'</li>';  

        }
    $contenu .= '</ul>';
} else {
  $contenu .='Vous n\'avez pas passé de commande.';
}

// ---------------------- AFFICHAGE --------------------
require_once('inc/haut.inc.php');
echo $contenu;


?>
<a href="profil.php/?action=modification&id_membre=<?php echo $id_membre ;?>">modifier</a> / <a href="?action=suppression&id_membre=<?php echo $id_membre ;?>" onclick="return(confirm(\'Etes-vous sûr de vouloir votre compte \'));" >supprimer</a>
<?php 
// 3- Formulaire HTML :
if (isset($_GET['action']) &&  $_GET['action'] == 'modification') { // affichage du formulaire quand on est en ajout ou modif. Attention : endif en bas du fichier !

  // 8- Pré-remplissage du formulaire de modification :
  if (isset($_GET['id_membre'])) { // si id_produit existe c'est que nous sommes en modification (car on ne passe pas d'id_produit en ajout)
    
    $resultat = $mysqli->query("SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]'");
    $membre_actuel = $resultat->fetch_assoc(); // array qui permet de pré-remplir le formulaire ci-dessous
 //debug($membre_actuel,1);
  }

  //---------------- TRAITEMENT -------------------------
if ($_POST) {
  debug($_POST,1);
  
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
        $mysqli->query("UPDATE `membre` SET `mdp` = '$_POST[mdp]', `nom` = '$_POST[nom]', `email` = '$_POST[email]' WHERE `membre`.`id_membre` = '$_GET[id_membre]';"); // 0 pour un membre non admin
        
        $contenu .= '<div class="bg-success">Vous avez modifier votre profil</div>';
      

} // fin du if ($_POST)
  ?>
   <h3>Vous pouvez modifier votre profil</h3>
<form method="post" action="">
 <label for="nom">Nom</label><br>
  <input type="text" id="nom" name="nom" value="<?php echo $membre_actuel['nom']; ?>">
  <br>
  
  <label for="email">Email</label><br>
  <input type="email" id="email" name="email" value="<?php echo $membre_actuel['email']; ?>">
  <br>

  <label for="mdp">Mot de passe</label><br>
  <input type="password" id="mdp" name="mdp" value="<?php echo $membre_actuel['email']; ?>">
  <br>

  <input type="submit" name="inscription" value="Modifiez" class="btn">
</form>
  <?php
}
require_once('inc/bas.inc.php');
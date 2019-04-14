<?php
require_once('inc/init.inc.php');
$aside = '';

//------------------ TRAITEMENT -------------------
// 1- Contrôler l'existence du produit demandé :
if (isset($_GET['id_produit'])) {
    $resultat = $mysqli->query("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");
    
    if ($resultat->num_rows == 0) { // si l'id_produit n'existe pas on oriente vers la boutique :
      header('location:boutique.php');
      exit();
    }
    
    
    //2- Affichage et mise en forme des infos sur le produit 
    $produit = $resultat->fetch_assoc();
    
    $contenu .= '<div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">'. $produit['titre'] .'</h1>
                    </div>
                </div>';
    
    $contenu .= '<div class="col-md-6">
                      <img class="img-responsive" src="'. $produit['photo'] .'">
                </div>';
    
    $public = array('m'=>'masculin','f'=>'féminin', 'mixte'=>'mixte');
    
    $contenu .= '<div class="col-md-6">
                      <h3>Description</h3>
                      <p>'. $produit['description'] .'</p>
                      <h3>Détails</h3>
                      <ul>
                        <li>Catégorie : '. $produit['categorie'] .'</li>
                        <li>Couleur : '. $produit['couleur'] .'</li>
                        <li>Taille : '. $produit['taille'] .'</li>
                        <li>Public : '. $public[$produit['public']] .'</li>
                      </ul>
    
                      <p class="lead">Prix : '. $produit['prix'] .'€</p>
                 </div>';
                 
  // 3- Affichage du formulaire d'ajout au panier si stock>0 
  $contenu .='<div class="col-md-6">';
    if ($produit['stock'] > 0) {
        $contenu .= '<form method="post" action="panier.php">';
            $contenu .= '<input type="hidden" name="id_produit" value="'. $produit['id_produit'] .'">'; 
            
            $contenu .= '<select name="quantite" class="form-group-sm form-control-static">';
              for($i = 1; $i <= $produit['stock']; $i++) {
                $contenu .= '<option>'. $i .'</option>';
              }
            $contenu .= '</select>';
            $contenu .= '<input type="submit" name="ajout_panier" value="ajouter au panier" class="btn">';
            
        $contenu .= '</form>';
        $contenu .= '<i>Nombre de produits disponibles : '. $produit['stock'] .'</i><br>';
    } else {
      // si pas de stock :
      $contenu .= '<p>Rupture de stock</p>';
    }
  
    // 4- lien retour vers la boutique :
    $contenu .= '<p><a href="boutique.php?categorie='. $produit['categorie'] .'">Retour vers la sélection de '. $produit['categorie'] .'</a></p>';
      
  $contenu .='</div>';
} // fin du if (isset($_GET['id_produit']))

  
// Affichage du message de confirmation d'ajout du produit :
if (isset($_GET['statut_produit']) && $_GET['statut_produit'] == 'ajoute') {
  
  $contenu_gauche .= '<div id="overlay" style="position: absolute; top:0; left: 0; z-index: 1000; height: 100vh; width: 100vw; background: #000; opacity: 0.8;">
                      
                      <p style="text-align: center; width: 20%; height: 20%; margin: 40vh auto; font-size: 25px; color: #fff;">
                          Le produit a bien été ajouté !
                          
                          <a href="#" style=" display: block; font-size: 20px;" onclick="$(\'#overlay\').hide(); return false;">fermer</a>
                          
                      </p>  
  
                      </div>';
}  
  
    
  
  
 
//------------------ AFFICHAGE -------------------
require_once('inc/haut.inc.php');
echo $contenu_gauche;  // pour afficher la confirmation d'ajout de produit au panier 
?>
  <div class="row">
    <?php echo $contenu; ?>
  </div>
  
  <!-- Pour le TP : -->
  <div class="row">
    <div class="col-lg-12">
      <h3 class="page-header">Suggestions de produits</h3>
    </div>
  
    <?php echo $aside; ?>
  
  </div>
<?php
require_once('inc/bas.inc.php');




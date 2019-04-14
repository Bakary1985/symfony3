<?php
require_once('inc/init.inc.php');

//------------------ TRAITEMENT --------------------
// 1- Affichage des catégories :
$categories_des_produits = $mysqli->query("SELECT DISTINCT categorie FROM produit ORDER BY categorie");

$contenu_gauche .= '<div class="col-md-3">';
    $contenu_gauche .= '<p class="lead">Vêtements</p>';
    $contenu_gauche .='<div class="list-group">'; 
      
      $contenu_gauche .= '<a href="?categorie=all" class="list-group-item">Tous</a>';
      
      while($cat = $categories_des_produits->fetch_assoc()) {
        //debug($cat);
        
        $contenu_gauche .='<a href="?categorie='. $cat['categorie'] .'" class="list-group-item">'. $cat['categorie'] .'</a>';
      }
    $contenu_gauche .= '</div>';
$contenu_gauche .= '</div>';


// 2- Affichage des produits selon la catégorie choisie :
if (isset($_GET['categorie']) && $_GET['categorie'] != 'all') {
    $donnees = $mysqli->query("SELECT id_produit, reference, titre, photo, prix, description FROM produit WHERE categorie = '$_GET[categorie]'");
    
} else {
    $donnees = $mysqli->query("SELECT id_produit, reference, titre, photo, prix, description FROM produit");
}

while($produit = $donnees->fetch_assoc()) {
    //debug($produit);
    
    $contenu_droite .='<div class="col-sm-4 col-lg-4 col-md-4">';
      $contenu_droite .= '<div class="thumbnail">';
          $contenu_droite .= '<a href="fiche_produit.php?id_produit='. $produit['id_produit'] .'"><img src="'. $produit['photo'] .'" alt="" width=130 max-height=100></a>';
          
          $contenu_droite .='<div class="caption">';
            $contenu_droite .= '<h4 class="pull-right">'. $produit['prix'] .' €</h4>';
            
            $contenu_droite .= '<h4>'. $produit['titre'] .'</h4>';
            
            $contenu_droite .= '<p>'. $produit['description'] .'</p>';
          
          $contenu_droite .= '</div>'; 
      $contenu_droite .= '</div>'; 
    $contenu_droite .= '</div>';  
}  // fin boucle while 




//------------------ AFFICHAGE --------------------
require_once('inc/haut.inc.php');
?>
  <div class="row">
      <?php echo $contenu_gauche; ?>
      
      <div class="col-md-9">
          <div class="row">
            <?php echo $contenu_droite; ?>
          </div> 
      </div>
  </div>
<?php
require_once('inc/bas.inc.php');



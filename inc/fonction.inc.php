<?php
function debug($var, $mode = 1) {
  echo '<div style="background: orange; padding: 5px;">';
    if ($mode == 1) {
      echo '<pre>'; print_r($var); echo '</pre>';
    } else {
      echo '<pre>'; var_dump($var); echo '</pre>';
    }
  echo '</div>';
}

//-------------------------------
function internauteEstConnecte() {
  if (isset($_SESSION['membre'])) {
    return true;
  } else {
    return false;
  }
}

//--------------------------------
function internauteEstConnecteEtEstAdmin() {
  if (internauteEstConnecte() && $_SESSION['membre']['statut'] == 1) { // statut = 1 correspond � un admin
    return true;
  } else {
    return false;
  }
}

//---------------------------------
function creationDuPanier() {
  if(!isset($_SESSION['panier'])) { // si le panier n'existe pas dans la session, on le cr�e
      $_SESSION['panier'] = array();
      $_SESSION['panier']['titre'] = array();
       $_SESSION['panier']['id'] = array();
      $_SESSION['panier']['id_produit'] = array();
      $_SESSION['panier']['quantite'] = array();
      $_SESSION['panier']['prix'] = array();
  }
}

function ajouterProduitDansPanier($titre, $id_produit, $quantite, $prix) { // r�ception des arguments en provenance de panier.php
  
  creationDuPanier();  // la premi�re fois, le panier est cr��, on peut donc le remplir (= remplir $_SESSION['panier']) :
  
  // On v�rifie si l'article ajout� est d�j� dans le panier :  
  $position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']);  // array_search() permet de rechercher la position de $id_produit dans l'array $_SESSION['panier']['id_produit']. Il retourne l'indice du produit s'il existe, sinon false.  
    
  if ($position_produit !== false) {
    // Le produit est d�j� pr�sent dans le panier :
    $_SESSION['panier']['quantite'][$position_produit] += $quantite;  // on ajoute alors uniquement la nouvelle quantit� � la quantit� pr�c�demment inscrite dans le panier
    
  } else {
    // Le produit n'est pas encore dans le panier, on l'y ajoute donc :
      $_SESSION['panier']['titre'][] = $titre;
      $_SESSION['panier']['id_produit'][] = $id_produit;
      $_SESSION['panier']['quantite'][] = $quantite;
      $_SESSION['panier']['prix'][] = $prix;
  }
}


//------------------------------
function montantTotal() {
  $total = 0;
  
  for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
    $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i]; // on multiplie quantit� par prix que l'on ajoute � la valeur pr�c�dente de $total (+=)
  }
  return $total; // renvoie le total � l'endroit o� la fonction est appel�e
}

//-------------------------------
function retirerProduitDuPanier($id_produit_a_supprimer) {
  
  // On cherche l'indice dans $_SESSION['panier'] de la ligne � supprimer :
  $position_produit = array_search($id_produit_a_supprimer, $_SESSION['panier']['id_produit']); 

  if ($position_produit !== false) { // l'article est bien dans le panier
    array_splice($_SESSION['panier']['titre'], $position_produit, 1); // ici array_splice() coupe l'array indiqu� � partir de la position $position_produit et sur 1 indice
    array_splice($_SESSION['panier']['id_produit'], $position_produit, 1);
    array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
    array_splice($_SESSION['panier']['prix'], $position_produit, 1);
  }  
}


//--------------------------------------
function quantiteProduit() {
  if (isset($_SESSION['panier']['quantite'])) {
    return array_sum($_SESSION['panier']['quantite']); // fait la somme des valeurs des indices "quantite" de la session panier   
  } else {
    return 0; // quand il n'y a pas de produit dans le panier
  }
}


//---------------------------------------














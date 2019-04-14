<?php
//-------------------------------------------
// EXERCICE
//-------------------------------------------

/* - Seul l’administrateur doit avoir accès à cette page. 
   - Afficher le nombre de commandes enregistrée sur le site.
   - Afficher dans une table HTML toutes les commandes passées sur le site avec les infos suivantes afin que le commerçant puisse envoyer le colis (y compris celles qui n'auraient plus de membres) :
					-> Le numéro de commande (id_commande) ainsi que la date, le montant et l'état de la commande
					-> Le numéro du membre (id_membre) ainsi que son pseudo, adresse, ville et cp 
   
   - Lorsqu'on clique sur un id_commande, afficher en dessous du tableau le détail de la commande dans une table HTML avec les infos :
					-> L'id_produit, le titre, la photo, la quantité demandée 

   - L’affichage du chiffre d’affaires (somme des montants des commandes) doit également apparaitre sur cette page.
*/	


require_once("../inc/init.inc.php");
// 1- Vérification si Admin :
if(!internauteEstConnecteEtEstAdmin())
{
	header("location:../connexion.php");
	exit();
}


// 2- Affichage des commandes passées sur le site :

$information_sur_les_commandes = $mysqli->query("SELECT c.id_commande, c.id_membre, DATE_FORMAT(c.date_enregistrement, '%d-%m-%Y') AS date_enregistrement, c.montant, c.etat, m.pseudo, m.adresse, m.ville, m.code_postal FROM commande c LEFT JOIN membre m ON  m.id_membre = c.id_membre"); // informations sur les commandes


$contenu .= '<h3> Voici les commandes passées sur le site </h3>';		
$contenu .= "Nombre de commande(s) : " . $information_sur_les_commandes->num_rows;
		
	$contenu .= "<table class='table'> <tr>";
		//  Affichage des entêtes :
		while($colonne = $information_sur_les_commandes->fetch_field())
		{    
			$contenu .= '<th>' . $colonne->name . '</th>';
		}
		$contenu .= "</tr>";
 
		// Affichage des lignes :
		$chiffre_affaire = 0;	// pour calculer le C.A. total 
		while ($commande = $information_sur_les_commandes->fetch_assoc())
		{
			$chiffre_affaire += $commande['montant'];
			
			$contenu .= '<tr>';
				
        $contenu .= '<td><a href="gestion_commande.php?suivi=' . $commande['id_commande'] . '">Voir la commande ' . $commande['id_commande'] . '</a></td>';
				
        
        $contenu .= '<td>' . $commande['id_membre'] . '</td>';
				$contenu .= '<td>' . $commande['date_enregistrement'] . '</td>';
				$contenu .= '<td>' . $commande['montant'] . '</td>';
				$contenu .= '<td>' . $commande['etat'] . '</td>';
				$contenu .= '<td>' . $commande['pseudo'] . '</td>';
				$contenu .= '<td>' . $commande['adresse'] . '</td>';
				$contenu .= '<td>' . $commande['ville'] . '</td>';
				$contenu .= '<td>' . $commande['code_postal'] . '</td>';
			$contenu .= '</tr>	';
			
		}
	$contenu .= '</table><br>';
	
	// 4- Affichage du chiffre d'affaires :
	$contenu .= '<div class="panel panel-default">
			  <div class="panel-body">le chiffre d\'affaires de la société est de : ' . $chiffre_affaire . ' € </div>
		  </div>';
	
	
	// 3- Affichage du détail de la commande :
	if(isset($_GET['suivi']))	// si on cliqué sur le détail d'une commande
	{	
		$contenu .= '<h3> Voici le détail pour une commande</h3>';

		$information_sur_une_commande = $mysqli->query("SELECT dc.id_details_commande, dc.id_commande, dc.id_produit , p.titre, p.photo, dc.quantite FROM details_commande dc, produit p WHERE p.id_produit = dc.id_produit AND id_commande= '$_GET[suivi]'");
				
		$contenu .= "<table class='table'> <tr>";
				while($colonne = $information_sur_une_commande->fetch_field())
				{    
					$contenu .= '<th>' . $colonne->name . '</th>';
				}
			$contenu .= "</tr>";

			while ($details_commande = $information_sur_une_commande->fetch_assoc())
			{
				$contenu .= '<tr>';
					$contenu .= '<td>' . $details_commande['id_details_commande'] . '</td>';
					$contenu .= '<td>' . $details_commande['id_commande'] . '</td>';
					$contenu .= '<td>' . $details_commande['id_produit'] . '</td>';
					$contenu .= '<td>' . $details_commande['titre'] . '</td>';
					$contenu .= '<td><img src="' . $details_commande['photo'] . '" width="50" height="50"></td>';
					$contenu .= '<td>' . $details_commande['quantite'] . '</td>';
					
				$contenu .= '</tr>';
			}
		$contenu .= '</table>';
	}
	







//-------------------------------------------------- Affichage ---------------------------------------------------------//
require_once("../inc/haut.inc.php");
echo $contenu;
require_once("../inc/bas.inc.php");	

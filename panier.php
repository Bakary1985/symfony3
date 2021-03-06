<?php
require_once('inc/init.inc.php');
require_once('mandrill/src/Mandrill.php');
$mandrill = new Mandrill('Qj6u2RXjNKDZTIv90TOSig');
//var_dump($_SESSION['panier']);
//------------------ TRAITEMENT ------------------
$nom =$_SESSION['membre']['nom'];
// 2- Ajout d'un produit au panier :
//debug($_SESSION);

if (isset($_POST['ajout_panier'])) { // si on a cliqué sur le bouton d'ajout au panier
  //debug($_POST);
  
  // Requête de selection des infos du produit ajouté :
  $resultat = $mysqli->query("SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]'");
  
  $produit = $resultat->fetch_assoc(); // pas de while car 1 seul produit possible
  
  // Ajout du produit au panier (via une fonction qui remplie $_SESSION) :
  ajouterProduitDansPanier($produit['titre'], $produit['id_produit'], $_POST['quantite'], $produit['prix']); // je passe en arguments : titre, id_produit, quantité du formulaire et prix
  
  header('location:fiche_produit.php?statut_produit=ajoute&id_produit='. $_POST['id_produit']);
  exit();
}


//3- Vider le panier 
if (isset($_GET['action']) && $_GET['action'] == 'vider' ) { // sin on a cliqué sur le lien "vider le panier" (cf code du lien plus bas)
  unset($_SESSION['panier']);  // supprime l'indice panier de $_SESSION
}

//4- Supprimer un article du panier :
if (isset($_GET['action']) && $_GET['action'] == 'supprimer_article' && isset($_GET['articleASupprimer'])) { // si on a cliqué sur le lien "supprimer artcile" :
    retirerProduitDuPanier($_GET['articleASupprimer']); // j'appelle la fonction retirerProduitDuPanier() à laquelle je passe l'id du produit à supprimer  
}

// 5- Validation du panier :
if (isset($_POST['valider']) && isset($_SESSION['panier']['id'])) { // si on a validé le panier
    
    //5.1 Vérification du stock :
    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
        // On sélectionne en base le stock du produit "$i" :
        $id_produit = $_SESSION['panier']['id_produit'][$i]; // variable utilisée dans la requête SQL
        $resultat = $mysqli->query("SELECT stock FROM produit WHERE id_produit = '$id_produit'");
        $produit = $resultat->fetch_assoc();
        
        if ($produit['stock'] < $_SESSION['panier']['quantite'][$i]) { // si stock insuffisant :
            // 5.2 Est-ce qu'il reste quelques pièces, dans ce cas on les attribue à la commande :
            if ($produit['stock'] > 0) {
              $contenu .='<div class="bg-danger">La quantité du produit '. $_SESSION['panier']['titre'][$i] .' a été réduite à '. $produit['stock'] .' car notre stock était insuffisant. Veuillez vérifier vos achats.</div>';
              $_SESSION['panier']['quantite'][$i] = $produit['stock'];
            } else {
              // 5.3 Il n'y a plus de stock du tout :
              $contenu .= '<div class="bg-danger">Le produit '. $_SESSION['panier']['titre'][$i] .' a été retiré de votre panier car nous sommes en rupture de stock. Veuillez vérifier vos achats.</div>';
              retirerProduitDuPanier($_SESSION['panier']['id_produit'][$i]);
              
              $i--; // on décrémente $i pour supprimer un tour de boucle car on a supprimé un produit
            }
            $erreur_stock = true; // variable pour pouvoir bloquer la validation finale du panier dans la condition suivante.
        }
    } // fin de la boucle for
  
    //5.4 Si le stock est bon, on insère la commande en base :
    if (!isset($erreur_stock)) { // si la variable n'existe pas, c'est qu'il n'y a pas de problème de stock (cf ci-dessus)
    
    $id_membre = $_SESSION['membre']['id_membre'];
    $montant_total = montantTotal();
    $mysqli->query("INSERT INTO commande (id_membre, montant, date_enregistrement) VALUES('$id_membre', '$montant_total', NOW())");  
      
    $id_commande = $mysqli->insert_id; // permet de récupérer le dernier id de commande créé en base, pour pouvoir l'insérer dans la table details_commande
    
    // 5.5 Insertion dans la table details_commande :
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
        $id_produit = $_SESSION['panier']['id_produit'][$i];
        $quantite = $_SESSION['panier']['quantite'][$i];
        $prix = $_SESSION['panier']['prix'][$i];
        
        $mysqli->query("INSERT INTO details_commande (id_commande, id_produit, quantite, prix) VALUES('$id_commande', '$id_produit', '$quantite', '$prix')");
        
        // 5.6 Décrémentation du stock :
        $mysqli->query("UPDATE produit SET stock = stock - '$quantite' WHERE id_produit = '$id_produit'");
    } // fin boucle for
    
    // 5.7 Suppression du panier car celui-ci est entièrement validé :
    unset($_SESSION['panier']);
    $contenu .= '<div class="bg-success">Votre commande a été validée. Votre numéro de suivi est le '. $id_commande .'</div>';
    
    //5.8 Envoi du mail de confirmation à l'internaute :
    // Vous pouvez intervenir temporairement sur le fichier php.ini avec les instructions suivantes :
    //ini_set('SMTP', 'smtp.free.fr'); // mettre en second argument le smtp de sa propre messagerie mail
    //ini_set('sendmail_from', 'vendeur@boutique.com');
    
     
    // variables utilisateur utilisées la fonction mail() :
    $to = $_SESSION['membre']['email'];  // destinataire
    $subject = 'Confirmation de votre commande'; // objet du mail
    $message = 'Merci pour votre commande. Le numéro de suivi est le ' . $id_commande;
    
    // La fonction mail():
    // mail($to, $subject, $message); // mis en commentaire pour que le script puisse continuer de focntionner

        $html = "<div>
            
    <h2>Bonjour  $nom </h2>;
    <p> Votre commande a été validée. Votre numéro de suivi est le  $id_commande  </p>
    </div>";
        $email ="bakarydiarra8509@gmail.com";
        $sendto =$email;
        $mail_to_name = 'bakary';
        $text = "mon text";
        $mail_from ="metropolegrandparis@bliwe.com";
        $mail_from_name = 'bar';
//'noreplay@murinnovation.com', 'noreplay@bliwe.com'
        // Mail
        $mail_content = $html;

        if (! is_array ( $sendto )) {
            $sendto = array (
                array (
                    "email" => $sendto,
                    "name" => $mail_to_name,
                    "type" => "to"
                )
            );
        }

        else {
            if ($sendto ['email'] != '') {
                $sendto = array (
                    $sendto
                );
            }
        }

        $attachments = null;
        $images = null;

        $message = array (
            'html' => $mail_content,
            'text' => $text,
            'subject' => $subject,
            'from_email' => $mail_from,
            'from_name' => $mail_from_name,
            'to' => $sendto,
            'headers' => array (
                'Reply-To' => $mail_from
            ),
            'important' => false,
            'track_opens' => null,
            'track_clicks' => null,
            'auto_text' => null,
            'auto_html' => null,
            'inline_css' => null,
            'url_strip_qs' => null,
            'preserve_recipients' => null,
            'view_content_link' => null,
            'bcc_address' => null, // MANDRILL_SEND_BCC,
            'tracking_domain' => null,
            'signing_domain' => 'www.murinnovation.com',
            'return_path_domain' => null,
            'merge' => true,
            'global_merge_vars' => array (
                array (
                    'name' => 'merge1',
                    'content' => 'merge1 content'
                )
            ),

            'tags' => array (
                'ENOVA'
            ),
            // 'subaccount' => 'testAnd1544',
            'google_analytics_domains' => array (
                'www.murinnovation.com'
            ),
            'google_analytics_campaign' => 'message.from_' . 'www.murinnovation.com',
            'metadata' => array (
                'website' => 'www.murinnovation.com'
            ),
            /*'recipient_metadata' => array(
            array(
                'rcpt' => MANDRILL_METADATA_RCPT,
                'values' => array('user_id' => 145896325)
            )
            ),*/
            'attachments' => $attachments,
            'images' => $images
        );

        $async = false;
        $ip_pool = 'Main Pool';
        $send_at = null;
        $result = $mandrill->messages->send ( $message, $async, $ip_pool, $send_at );

        $return = $result [0];

        // rejected //invalid //reject_reason
        $failed = array (
            'rejected',
            'invalid'
        );

        // "sent", "queued", "scheduled"
        $passed = array (
            'sent',
            'queued',
            'scheduled'
        );

        if (in_array ( $return ['status'], $failed )) {
            echo $return ['reject_reason'];

            return false;
        }
    
    
    
    } // fin du if (!isset($erreur_stock))
} // fin du if (isset($_POST['valider']))


//------------------ AFFICHAGE ------------------
require_once('inc/haut.inc.php');
echo $contenu;

echo '<h2>Votre panier</h2>';

if (empty($_SESSION['panier']['id_produit'])) { // si panier vide
    echo '<p>Votre panier est vide</p>';

} else {
    // On affiche le panier
    echo '<table class="table">';
    echo '<tr class="info"><th>Titre</th><th>Référence</th><th>Quantité</th><th>Prix unitaire</th><th>Action</th></tr>';
    
    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
      echo '<tr>';
        echo '<td>'. $_SESSION['panier']['titre'][$i] .'</td>';
        echo '<td>'. $_SESSION['panier']['id_produit'][$i] .'</td>';
        echo '<td>'. $_SESSION['panier']['quantite'][$i] .'</td>';
        echo '<td>'. $_SESSION['panier']['prix'][$i] .'</td>';
        
        echo '<td><a href="?action=supprimer_article&articleASupprimer=' .$_SESSION['panier']['id_produit'][$i] .'">supprimer article</a></td>';
      
      echo '</tr>';
    }

    echo '<tr class="info"><th colspan="3">Total</th><th colspan="2">'. montantTotal() .'</th></tr>';

    // On affiche le bouton "valider le panier" uniquement si l'internaute est connecté :
    if (internauteEstConnecte()) {
      echo '<form method="post" action="">';  
      echo '<tr class="text-center">
              <td colspan="5">
                <input type="submit" name="valider" value="valider le panier" class="btn">
              </td>
            </tr>';
      echo '</form>';
    } else {
      echo '<tr class="text-center">
              <td colspan="5">
                Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">connecter</a> afin de pouvoir valider le panier
              </td>      
            </tr>';      
    }
    echo '<tr class="text-center">
            <td colspan="5">
              <a href="?action=vider">Vider le panier</a>
            </td>  
          </tr>';

    echo '</table>';
}

require_once('inc/bas.inc.php');
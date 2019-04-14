<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Ma boutique</title>

  <link href="<?php echo RACINE_SITE . 'inc/css/bootstrap.min.css'; ?>" rel="stylesheet">

  <link href="<?php echo RACINE_SITE . 'inc/css/shop-homepage.css'; ?>" rel="stylesheet">
  
  <link href="<?php echo RACINE_SITE . 'inc/css/portfolio-item.css'; ?>" rel="stylesheet">
  
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    
    <div class="container">
      
      <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo RACINE_SITE . 'boutique.php'; ?>">Ma Boutique</a>
      </div>
      
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <?php  
          if (internauteEstConnecteEtEstAdmin()) { // admin connecté
            echo '<li><a href="'. RACINE_SITE .'admin/gestion_boutique.php">Gestion de la boutique</a></li>';
            
            echo '<li><a href="'. RACINE_SITE .'admin/gestion_commande.php">Gestion des commandes</a></li>';
            
     
          }  
            
          if (internauteEstConnecte()) { // membre connecté
              echo '<li><a href="'. RACINE_SITE .'boutique.php">Boutique</a></li>';
              
              echo '<li><a href="'. RACINE_SITE .'profil.php">Profil</a></li>';
              
              echo '<li><a href="'. RACINE_SITE .'panier.php">Panier('. quantiteProduit() .')</a></li>';
              
              echo '<li><a href="'.RACINE_SITE.'connexion.php?action=deconnexion">Se déconnecter</a></li>';
          } else { // simple visiteur
              echo '<li><a href="'. RACINE_SITE .'boutique.php">Boutique</a></li>';
              
              echo '<li><a href="'. RACINE_SITE .'inscription.php">Inscription</a></li>';
              
              echo '<li><a href="'. RACINE_SITE .'connexion.php">Connexion</a></li>';
              
              echo '<li><a href="'. RACINE_SITE .'panier.php">Panier('. quantiteProduit() .')</a></li>';
          } 
          ?>
        </ul>
      </div><!-- fin navbar-collapse -->
    
    </div><!-- fin container -->
  
  </nav>
  <br><br>
 <div class="container" style="min-height: 80vh;"> 

  <!-- Contenu de la page --> 
 
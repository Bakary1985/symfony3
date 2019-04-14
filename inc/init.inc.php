<?php

// Connexion � la BDD :
 $mysqli = new Mysqli('localhost', 'root', '', 'bar_burger');

 $mysqli->set_charset("utf8");  // force les transactions avec la BDD en utf-8


// Session :
session_start();

// Chemin du site :
define('RACINE_SITE', '/Bar_berger/'); // on d�finit le chemin de la racine du site pour pouvoir �tablir des url de fichiers en chemin absolu que l'on soit dans un template admin ou front

// D�claration de variables d'affichage de contenus :
$contenu = '';
$contenu_gauche = '';
$contenu_droite = '';

// Autre inclusion n�cessaire � tous les scripts :
require_once('fonction.inc.php');
























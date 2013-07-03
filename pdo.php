<?php
// Connection au serveur
try {
  $dns = 'mysql:host=localhost;dbname=pixDump';
  $utilisateur = 'root';
  $motDePasse = '';
  $pdo = new PDO( $dns, $utilisateur, $motDePasse );
  $pdo->query("SET NAMES utf8");
} catch ( Exception $e ) {
  echo "Connection à MySQL impossible : ", $e->getMessage();
  die();
}
?>
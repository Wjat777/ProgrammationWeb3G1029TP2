<?php
include_once '../include/config.php'; 
include_once '../include/fonctions.php'; 
header('Content-Type: application/json;');
header('Access-Control-Allow-Origin: *'); 

$mysqli = new mysqli($host, $username, $password, $database); // Établissement de la connexion à la base de données
if ($mysqli -> connect_errno) { // Affichage d'une erreur si la connexion échoue
  echo 'Échec de connexion à la base de données MySQL: ' . $mysqli -> connect_error;
  exit();
} 

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET':  // GESTION DES DEMANDES DE TYPE GET
		if(isset($_GET['hotel_id'])) { 
			if ($requete = $mysqli->prepare("SELECT `hotel_commentaires`.`id`,`hotel_commentaires`.`hotel_id`, `nom`, `usager`, `commentaire`, `note` FROM `hotel_commentaires` inner join `hotels` on `hotels`.`id`=`hotel_commentaires`.`hotel_id` WHERE `hotel_commentaires`.`hotel_id`=?")) {  
				$requete->bind_param("i", $_GET['hotel_id']); 
				$requete->execute(); 
				$resultat = $requete->get_result();
				$donnees_tableau = $resultat->fetch_all(MYSQLI_ASSOC);

				echo json_encode($donnees_tableau, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

				$requete->close(); 
			}
		} else {
			$requete = $mysqli->query("SELECT `hotel_commentaires`.`id`,`hotel_commentaires`.`hotel_id`, `nom`, `usager`, `commentaire`, `note` FROM `hotel_commentaires` inner join `hotels` on `hotels`.`id`=`hotel_commentaires`.`hotel_id`");
			$donnees_tableau = $requete->fetch_all(MYSQLI_ASSOC);
			echo json_encode($donnees_tableau, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
			$requete->close();
			
		}
	break;

	default:
		$reponse = new stdClass();
		$reponse->message = "Opération non supportée";	
		echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

$mysqli->close(); 
?>
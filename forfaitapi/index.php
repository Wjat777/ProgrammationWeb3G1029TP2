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
	if(isset($_GET['id'])) { 
		if ($requeteForfait = $mysqli->prepare("SELECT * FROM forfaits WHERE id=?")) {  
		  $requeteForfait->bind_param("i", $_GET['id']); 
		  $requeteForfait->execute(); 

		  $resultat_requete1 = $requeteForfait->get_result(); 
		  $forfait = $resultat_requete1->fetch_assoc(); 


		  if ($requeteHotel = $mysqli->prepare("SELECT * FROM hotels WHERE id=?")) {  
			$requeteHotel->bind_param("i", $forfait["hotel_id"]); 
			$requeteHotel->execute(); 
  
			$resultat_requete2 = $requeteHotel->get_result(); 
			$hotel = $resultat_requete2->fetch_assoc(); 

		  	$forfaitObj = ACLForfaitFromDB($forfait,$hotel);

		  	echo json_encode($forfaitObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

		  	$requeteHotel->close(); 
		  }
		  $requeteForfait->close(); 
		}
	} else {
		$requeteforfaits = $mysqli->query("SELECT * FROM forfaits");

		$forfaits=[];
		while ($forfait = $requeteforfaits->fetch_assoc()) {
			
			if ($requeteHotel = $mysqli->prepare("SELECT * FROM hotels WHERE id=?")) {  
				$requeteHotel->bind_param("i", $forfait["hotel_id"]); 
				$requeteHotel->execute(); 
	  
				$resultat_requete2 = $requeteHotel->get_result(); 
				$hotel = $resultat_requete2->fetch_assoc(); 
				  $forfaitObj = ACLForfaitFromDB($forfait,$hotel);
				  array_push ($forfaits,$forfaitObj);
				  $requeteHotel->close(); 
			  }
		}

		echo json_encode($forfaits, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		$requeteforfaits->close();
	}
	break;

	case 'POST':  // GESTION DES DEMANDES DE TYPE POST
		$reponse = new stdClass();
		$reponse->message = "Ajout d'un forfait: ";
		
		$corpsJSON = file_get_contents('php://input');
		$data = json_decode($corpsJSON, TRUE); 
	
		$forfaitObj= ForfaitFromJson($data);
		$hotelObj= HotelFromJson($data);

		

		if(is_object($forfaitObj)) {
	
			$SQL="INSERT INTO `forfaits`(`destination`, `villeDepart`, `dateDepart`, `dateRetour`, `prix`, `taxes`, `rabais`, `vedette`, `hotel_id`)
			 VALUES                     (?            , ?            , ?           , ?           , ?     , ?      , ?       , ?        , ?         )";


		  	if ($requete = $mysqli->prepare($SQL)  or die($mysqli->error)) {      
				$requete->bind_param("ssssdsdii", $forfaitObj->destination, $forfaitObj->villeDepart , $forfaitObj->dateDepart, $forfaitObj->dateRetour , $forfaitObj->prix, $forfaitObj->taxes, $forfaitObj->taxes, $forfaitObj->vedette,$forfaitObj->hotel_id);
	
				if($requete->execute()) { 
					$reponse->message .= "Succès";  
				} else {
			  		$reponse->message .=  "Erreur dans l'exécution de la requête " . mysqli_error($mysqli);  
				}
	
				$requete->close(); 
		  	} else  {
				$reponse->message .=  "Erreur dans la préparation de la requête " . mysqli_error($mysqli);  
		  	} 
		} else {
			$reponse->message .=  "Erreur dans le corps de l'objet fourni ";
			
		}
		echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		break;

	case 'PUT':  // GESTION DES DEMANDES DE TYPE PUT
		$reponse = new stdClass();
		$reponse->message = "Modification d'un forfait: ";
		
		$corpsJSON = file_get_contents('php://input');
		$data = json_decode($corpsJSON, TRUE); 
	
		$forfaitObj= ForfaitFromJson($data);
		$hotelObj= HotelFromJson($data);


		if(isset($forfaitObj)) {
	
			$SQL="Update `forfaits` set `destination`=?, `villeDepart`=?, `dateDepart`=?, `dateRetour`=?, `prix`=?, `taxes`=?, `rabais`=?, `vedette`=?, `hotel_id`=? where `id`=?";
		  	if ($requete = $mysqli->prepare($SQL)) {      
				$requete->bind_param("ssssdsdiii", $forfaitObj->destination, $forfaitObj->villeDepart , $forfaitObj->dateDepart, $forfaitObj->dateRetour , $forfaitObj->prix, $forfaitObj->taxes, $forfaitObj->taxes, $forfaitObj->vedette,$forfaitObj->hotel_id,$_GET['id']);
	
				if($requete->execute()) { 
					$reponse->message .= "Succès";  
				} else {
			  		$reponse->message .=  "Erreur dans l'exécution de la requête". mysqli_error($mysqli);  ;  
				}
	
				$requete->close(); 
		  	} else  {
				$reponse->message .=  "Erreur dans la préparation de la requête". mysqli_error($mysqli);  ;  
		  	} 
		} else {
			$reponse->message .=  "Erreur dans le corps de l'objet fourni";  
		}
		echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		break;

	case 'DELETE':  // GESTION DES DEMANDES DE TYPE DELETE
		$reponse = new stdClass();
		if(isset($_GET['id'])) { 
			if ($requete = $mysqli->prepare("DELETE FROM `forfaits` WHERE `id`=?")) {     
				$requete->bind_param("i", $_GET['id']);
	
				if($requete->execute()) { 
				  $reponse->message .= "Succès";  
				} else {
				  $reponse->message .=  "Erreur dans l'exécution de la requête";  
				}
	
				$requete->close(); 
			  } else  {
				$reponse->message .=  "Erreur dans la préparation de la requête";  
			  } 
		} else {
			$reponse->message .=  "Erreur dans les paramètres (aucun identifiant fourni)";  
		}
		echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		break;

	default:
		$reponse = new stdClass();
		$reponse->message = "Opération non supportée";	
		echo json_encode($reponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

$mysqli->close(); 
?>
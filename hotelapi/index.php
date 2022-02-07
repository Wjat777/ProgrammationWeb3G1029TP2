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
		if ($requeteHotel = $mysqli->prepare("SELECT * FROM hotels WHERE id=?")) {  
			$requeteHotel->bind_param("i", $_GET['id']); 
			$requeteHotel->execute(); 
			
			$resultat_requete1 = $requeteHotel->get_result(); 
			$hotel = $resultat_requete1->fetch_assoc(); 
			$hotelObj = ACLHotelFromDB($hotel);

			$requeteCommetaires = $mysqli->prepare("SELECT * FROM `hotel_commentaires` where `hotel_id`=?");
			$requeteCommetaires->bind_param("i", $hotel["id"]); 
			$requeteCommetaires->execute(); 
			$resultat_requete2 = $requeteCommetaires->get_result();
			$commentaires=[];
			while ($commentaire = $resultat_requete2->fetch_assoc()) {
				$commentaireObj = ACLCommentaireFromDB($commentaire);
				array_push ($commentaires,$commentaireObj);
			  }

			$requeteCommetaires->close(); 
  		    array_push ($hotelObj->Commentaires,$commentaires);

			echo json_encode($hotelObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);			

			$requeteHotel->close(); 
		}
	} else {
		$requeteHotels = $mysqli->query("SELECT * FROM hotels");
		$hotels=[];
		while ($hotel = $requeteHotels->fetch_assoc()) {
			$hotelObj = ACLHotelFromDB($hotel);

			$requeteCommetaires = $mysqli->prepare("SELECT * FROM `hotel_commentaires` where `hotel_id`=?");
			$requeteCommetaires->bind_param("i", $hotel["id"]); 
			$requeteCommetaires->execute(); 
			$resultat_requete2 = $requeteCommetaires->get_result();
			$commentaires=[];
			while ($commentaire = $resultat_requete2->fetch_assoc()) {
				$commentaireObj = ACLCommentaireFromDB($commentaire);
				array_push ($commentaires,$commentaireObj);
			  }

			$requeteCommetaires->close(); 
			array_push ($hotelObj->Commentaires,$commentaires);
			array_push ($hotels,$hotelObj);
  		}

		echo json_encode($hotels, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		$requeteHotels->close(); 
	}
	break;

	case 'POST':  // GESTION DES DEMANDES DE TYPE POST
		$reponse = new stdClass();
		$reponse->message = "Ajout d'un hotel: ";
		
		$corpsJSON = file_get_contents('php://input');
		$data = json_decode($corpsJSON, TRUE); 
	
		$hotelObj= HotelFromJson($data);

		echo json_encode($hotelObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				
		if(is_object($hotelObj)) {
	
			$SQL="INSERT INTO `hotels`(`nom`, `coordonnees`, `url`, `imagePath`, `nombreEtoiles`, `nombreChambres`, `caracteristiques`) VALUES (?,?,?,?,?,?,?)";
		  	if ($requete = $mysqli->prepare($SQL)  or die($mysqli->error)) {    
				$requete->bind_param("ssssiis", $hotelObj->nom, $hotelObj->coordonnees , $hotelObj->url, $hotelObj->imagePath, $hotelObj->nombreEtoiles, $hotelObj->nombreChambres, $hotelObj->caracteristiques);
	
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


		if(isset($hotelObj)) {
			$SQL="UPDATE `hotels` SET `nom`=?,`coordonnees`=?,`url`=?,`imagePath`=?,`nombreEtoiles`=?,`nombreChambres`=?,`caracteristiques`=? WHERE `id`=?";
		  	if ($requete = $mysqli->prepare($SQL)) {      
				$requete->bind_param("ssssiisi", $hotelObj->nom, $hotelObj->coordonnees , $hotelObj->url, $hotelObj->imagePath, $hotelObj->nombreEtoiles, $hotelObj->nombreChambres, $hotelObj->caracteristiques,$_GET['id']);
	
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
			if ($requete = $mysqli->prepare("DELETE FROM `hotels` WHERE `id`=?")) {     
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
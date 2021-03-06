<?php

// Cette fonction prend l'object au format tablulaire SQL 
// et retourne un objet dont la structure correspond au format
// devant être retourné par l'API. 
function ACLForfaitFromDB($forfait, $hotel) {
    $forfaitOBJ = new stdClass();
    $forfaitOBJ->id = $forfait["id"];
    $forfaitOBJ->destination = $forfait["destination"];
    $forfaitOBJ->villeDepart = $forfait["villeDepart"];
	$forfaitOBJ->dateDepart = $forfait["dateDepart"];
    $forfaitOBJ->dateRetour = $forfait["dateRetour"];
    $forfaitOBJ->prix = (double)$forfait["prix"];
    $forfaitOBJ->taxes = $forfait["taxes"];
    $forfaitOBJ->rabais =(double)$forfait["rabais"];
    $forfaitOBJ->vedette = (int)$forfait["vedette"];
    
    $forfaitOBJ->hotel= new stdClass();
	$forfaitOBJ->hotel->id = $hotel["id"];
	$forfaitOBJ->hotel->nom = $hotel["nom"];
	$forfaitOBJ->hotel->coordonnees = $hotel["coordonnees"];
	$forfaitOBJ->hotel->url= $hotel["url"];
	$forfaitOBJ->hotel->imagePath= $hotel["imagePath"];
	$forfaitOBJ->hotel->nombreEtoiles= (int)$hotel["nombreEtoiles"];
	$forfaitOBJ->hotel->nombreChambres= (int)$hotel["nombreChambres"];
	$forfaitOBJ->hotel->caracteristiques = explode(",", $hotel["caracteristiques"]);
    
    return $forfaitOBJ;
}   


function ACLHotelFromDB($hotel) {
    $hotelOBJ= new stdClass();
	$hotelOBJ->id = $hotel["id"];
	$hotelOBJ->nom = $hotel["nom"];
	$hotelOBJ->coordonnees = $hotel["coordonnees"];
	$hotelOBJ->url= $hotel["url"];
	$hotelOBJ->imagePath= $hotel["imagePath"];
	$hotelOBJ->nombreEtoiles= $hotel["nombreEtoiles"];
	$hotelOBJ->nombreChambres= $hotel["nombreChambres"];
	$hotelOBJ->caracteristiques = explode(",", $hotel["caracteristiques"]);

    $hotelOBJ->Commentaires= [];

    
    return $hotelOBJ;
}  
function ACLCommentaireFromDB($comments) {
    $commentsOBJ= new stdClass();
	$commentsOBJ->id = $comments["id"];
    $commentsOBJ->hotel_id = $comments["hotel_id"];
    $commentsOBJ->usager = $comments["usager"];
    $commentsOBJ->commentaire = $comments["commentaire"];
    $commentsOBJ->note = $comments["note"];
    
    return $commentsOBJ;
}  

function ForfaitFromJson($data) {
    $forfaitOBJ = new stdClass();
    $forfaitOBJ->destination = $data["destination"];
    $forfaitOBJ->villeDepart = $data["villeDepart"];
    if(!strtotime($data["dateDepart"]))
        return null;
	$forfaitOBJ->dateDepart = strtotime($data["dateDepart"]);
    if(!strtotime($data["dateRetour"]))
        return null;
    $forfaitOBJ->dateRetour = strtotime($data["dateRetour"]);
    if(!is_numeric($data["prix"]))
        return null;
    $forfaitOBJ->prix = (double)$data["prix"];
    $forfaitOBJ->taxes = $data["taxes"];
    if(!is_numeric($data["rabais"]))
        return null;
    $forfaitOBJ->rabais =(double)$data["rabais"];
    
    if(!is_numeric($data["vedette"]))
        return null;
    $forfaitOBJ->vedette = (int)$data["vedette"];
    $forfaitOBJ->hotel_id = $data["hotel"]["id"];
    return $forfaitOBJ;
}  

function HotelFromJson($data) {   
    $hotelOBJ= new stdClass();
	$hotelOBJ->id = $data["id"];
	$hotelOBJ->nom = $data["nom"];
	$hotelOBJ->coordonnees = $data["coordonnees"];
	$hotelOBJ->url= $data["url"];
	$hotelOBJ->imagePath= $data["imagePath"];
	$hotelOBJ->nombreEtoiles= $data["nombreEtoiles"];
	$hotelOBJ->nombreChambres= $data["nombreChambres"];
	$hotelOBJ->caracteristiques = implode(",", $data["caracteristiques"]);
    
    return $hotelOBJ;
}   


function CommentairesFromJson($data) {   
    $commentsOBJ= new stdClass();
	$commentsOBJ->id = $data["id"];
	$commentsOBJ->hotel_id = $data["hotel_id"];
	$commentsOBJ->usager = $data["usager"];
	$commentsOBJ->commentaire= $data["commentaire"];
	$commentsOBJ->note= $data["note"];
    
    return $commentsOBJ;
}   



?>

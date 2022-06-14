<?php 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'matrix.php';
$recommend = file_get_contents('php://input');//lire la requète http
$recommend = json_decode($recommend,true);// décodé la requète json en tableau associative

if(!isset($recommend))
    header('Location: https://educateplus.000webhostapp.com');

$ratingMatrix   = getRatingMatrix();//les notes de tous les articles
$allItems       = getAllItems();// les noms de tous les articles
$allUsers       = getAllUsers();// les noms de tous les utilisateur
$itemsRated     = getItemsRatedByUser($recommend['username']);// les articles noté par l'utilisateur qui demande la recommandation
$meanItemRating = globalItemsMean();//la moyenne des notes de tous les articles
$avgUserRating  = avgUserRatings($recommend['username']);//la moyenne des notes de l'utilisateur qui demande la recommandation

function isValidRecommend($recommend)
{
	return $recommend['recommend']=='true';
}

function getItemsForUser($username,$size)
{
	global $con;

	$sql ='SELECT rating,itemName FROM ratings where userName="'.$username.'" and rating != 0;';
	$result =$con->query($sql);
	while($row = mysqli_fetch_row($result))
	{	
		foreach ($row as $value)
		{
				$ratings[$row[1]]= round($row[0],1);
		}
	}
	arsort($ratings);
	$ratings=array_slice($ratings, 0,$size);
	$ratings=json_encode($ratings);

	print_r($ratings);
}

function predictionForSpeceficUser($size,$ratingMatrix,$user,$allItems,$itemsRated,$allUsers,$meanItemRating,$avgUserRating )
{
	
		$notRatedItems =getItemsNotRatedByUser($user);//les articles non noté par l'utilisateur
		$recommend=array();
		if(count($notRatedItems)==0)//si il n'as noté aucun article on ne peu rien lui recommendé
		{
			getItemsForUser($user,$size);//on lui affiche simplement les articles présent dans notre base de donnée
		}
		else
		{
			for ($i = 0; $i < count($notRatedItems); $i++)
			{
				$prediction = prediction($size,$notRatedItems[$i],$ratingMatrix,$user,$allItems,$itemsRated,$allUsers,$meanItemRating,$avgUserRating );
				$recommend[$notRatedItems[$i]]=round($prediction,1);//on fait une prédiction pour chaque article non noté par l'utilisateur
			}
			arsort($recommend);//on tri les prédictions par ordre décroissant selon la note prédite
            foreach($recommend as $key => $value)
                if ($value < 2.5)//les prédiction sont entre 1-5
                    unset($recommend[$key]);//si la prédiction pour l'article est moin que la moyenne on ne recommande pas l'article
			$recommend=json_encode($recommend);
			print_r($recommend);
		}
		
}
function size($user)
{	
	$numItemsRated    = count(getItemsRatedByUser($user));
	$numItemsNotRated = count(getItemsNotRatedByUser($user));
	return (($numItemsNotRated==0)? $numItemsRated:$numItemsNotRated);
}

//send recommendations as json with this function
predictionForSpeceficUser(size($recommend['username']),$ratingMatrix,$recommend['username'],$allItems,$itemsRated,$allUsers,$meanItemRating,$avgUserRating );


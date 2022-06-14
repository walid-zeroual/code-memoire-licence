<?php 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'cosinesimilarity.php';


function Ksimilar($size,$itemNotRated,$ratingMatrix,$user,$allItems,$itemsRated,$allUsers)
{
	$ratedItems = $itemsRated;
	$similarities=array();
	
	for ($i = 0; $i < count($allItems); $i++)
	{
		$similarities[$allItems[$i]]=cosineSimilarity($itemNotRated,$allItems[$i],$ratingMatrix,$allItems,$allUsers);
	}//similarité d'un article non noté par l'utilisateur avec tous les autres artices

	$similarItemsRatedByUser = array();
	arsort($similarities);
	foreach ($similarities as $key => $value) {
		if(in_array($key, $ratedItems))
			$similarItemsRatedByUser[$key]=$value;
	}
	//remove all negative number
	foreach ($similarItemsRatedByUser as $key => $value) {
		if($value < 0.1)
			unset($similarItemsRatedByUser[$key]);//en enlève les articles qui ne sont pas similaire
	}

	return $similarItemsRatedByUser;
}
function globalBaselineEstimate($itemname,$meanItemRating,$avgUserRating )
{
	$avgItemRating = avgItemRatings($itemname);

	$globalBaselineEstimate = $meanItemRating +($avgItemRating - $meanItemRating) + ($avgUserRating - $meanItemRating);
	return $globalBaselineEstimate;
	
}

//on prédit la note que donnera l'utilisateur pour chaque article dans "Ksimilar" 
function prediction($size,$itemNotRated,$ratingMatrix,$user,$allItems,$itemsRated,$allUsers,$meanItemRating,$avgUserRating )
{
	$similarities=Ksimilar($size,$itemNotRated,$ratingMatrix,$user,$allItems,$itemsRated,$allUsers);//k articles similaires

	if(count($similarities)<1)// si il n'ya pas d'articles similaire on utilisi l'approche de l'estimation de base globale.
		return globalBaselineEstimate($itemNotRated,$meanItemRating,$avgUserRating );

	$itemRatings=array();
	$userRatings = array();
	$similarRating = array();
	$prediction = 0;
	foreach ($similarities as $item => $rating) 
	{
		$itemRatings[$item] =getItemRatings($item);//les notes des articles
	}
	
	foreach ($itemRatings as $item => $rating)
	{
		$userRatings[]=$rating[$user];
	}

	foreach ($similarities as $item => $rating)
	{
		$similarRating[] = $rating;
	}
	$sum = array_sum($similarities);
	for ($i = 0; $i < count($similarities); $i++)
	{
		$prediction+= ( $similarRating[$i] * ( $userRatings[$i] - globalBaselineEstimate(array_keys($similarities)[$i],$meanItemRating,$avgUserRating )) ) ;
		
	}
	
    $prediction /= $sum;
	return $prediction + globalBaselineEstimate($itemNotRated,$meanItemRating,$avgUserRating ) ;//prédiction pour un article non noté par l'utilisateur
}

<?php 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include 'cosinesimilarity.php';
//not in ratedby
//items similar to items 1 rated by 5 , meaning 
//we get all similar items and see who rated them, 
//when we try to predict rating for items 1 not rated by user 5
//we check K items similar to items 1 and rated by user 5
// then we do the predictio for the items not rated by usre 5


//$ratingMatrix =getRatingMatrix();
function Ksimilar($size,$itemNotRated,$ratingMatrix,$user,$allItems,$itemsRated,$allUsers)
{
	//$allItems = getAllItems();
	$ratedItems = $itemsRated;
	//print_r($ratedItems);
	$similarities=array();
	
	
	for ($i = 0; $i < count($allItems); $i++)
	{
		$similarities[$allItems[$i]]=cosineSimilarity($itemNotRated,$allItems[$i],$ratingMatrix,$allItems,$allUsers);
	}

	$similarItemsRatedByUser = array();
	arsort($similarities);
	foreach ($similarities as $key => $value) {
		if(in_array($key, $ratedItems))
			$similarItemsRatedByUser[$key]=$value;
	}
	//remove all negative number
	foreach ($similarItemsRatedByUser as $key => $value) {
		if($value < 0.1)
			unset($similarItemsRatedByUser[$key]);
	}
	//echo "all similar items to item cours9.pdf  ";
	//print_r($similarItemsRatedByUser);
	//array_slice($similarItemsRatedByUser,0,$size);
	//echo "10 items similar to item cours9.pdf and rated by rayzen    ";
	//print_r($similarItemsRatedByUser);
	return $similarItemsRatedByUser;
}
function globalBaselineEstimate($itemname,$meanItemRating,$avgUserRating )
{
	$avgItemRating = avgItemRatings($itemname);

	$globalBaselineEstimate = $meanItemRating +($avgItemRating - $meanItemRating) + ($avgUserRating - $meanItemRating);
	return $globalBaselineEstimate;
	
}
//weighted average prediction
//for each item y in ksimilar, we weight y rating for user i
function prediction($size,$itemNotRated,$ratingMatrix,$user,$allItems,$itemsRated,$allUsers,$meanItemRating,$avgUserRating )
{
	//$items =getAllItems();
	$similarities=Ksimilar($size,$itemNotRated,$ratingMatrix,$user,$allItems,$itemsRated,$allUsers);
	if(count($similarities)<1)
		return globalBaselineEstimate($itemNotRated,$meanItemRating,$avgUserRating );;
	$itemRatings=array();
	$userRatings = array();
	$similarRating = array();
	$prediction = 0;
	foreach ($similarities as $item => $rating) 
	{
		$itemRatings[$item] =getItemRatings($item);
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
	//echo $sum;
	//$prediction /= $sum;
	//echo $prediction;
	for ($i = 0; $i < count($similarities); $i++)
	{
		$prediction+= ( $similarRating[$i] * ( $userRatings[$i] - globalBaselineEstimate(array_keys($similarities)[$i],$meanItemRating,$avgUserRating )) ) ;
		
	}
	
    $prediction /= $sum;
	return $prediction + globalBaselineEstimate($itemNotRated,$meanItemRating,$avgUserRating ) ;
}

//neighbourss('cours9.pdf',$ratingMatrix,'ayoub');
//print_r(cosineSimilarity('cours9.pdf','Chapitre1Espacevectoriel.pdf',$ratingMatrix));
//print_r(getVectorFromItems('cours9.pdf',$ratingMatrix));

//print_r(Ksimilar(10,'Chapitre1Notionsdelogiquemathématiques.pdf',$ratingMatrix,'dogetrainer'));

//print_r(prediction(10,'Chapitre1Structure1.pdf',$ratingMatrix,'dogetrainer'));
function predictionForUsersWhoDidntRate($size,$ratingMatrix)
{
	$users = getAllUsers();
	for ($i = 0; $i < count($users); $i++)
	{
		$notRatedItems =getItemsNotRatedByUser($users[$i]);
		for ($j = 0; $j < count($notRatedItems); $j++)
		{
			$prediction = prediction($size,$notRatedItems[$j],$ratingMatrix,$users[$i]);
			echo 'FOR USER '.$users[$i].' AND ITEM '.$notRatedItems[$j].'  PREDICTION IS '.$prediction;
			echo "\n";
		}
		
	}
}

//predictionForUsersWhoDidntRate(10,$ratingMatrix);

//print_r($ratingMatrix);
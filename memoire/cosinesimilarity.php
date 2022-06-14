<?php 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include 'getDataFromDB.php';

function numberOfRatedItems($ratingMatrix)//calcule des nombres de notes attribué de chaque ligne(utilisateur) de la matrice
{
	$numberOfRatedItems = array();
	foreach ($ratingMatrix as $items => $ratings) 
	{
		$counter =0;
		foreach ($ratings as $user => $rate) 
		{
			if($rate !=0)
			{
				$counter++;
			}
		}
		$numberOfRatedItems[]=$counter;
	}
	return $numberOfRatedItems;
}
function rowMean($ratingMatrix)//calcule de la médiane d'un ligne de la matrice des notes
{
	$numberOfRatedItems =numberOfRatedItems($ratingMatrix);
	$rowMean = array();
	foreach ($ratingMatrix as $items => $ratings)
	{
		$sum =0;
		foreach($ratings as $user => $rate)
		{
			$sum+= $rate;
		}
		$rowMean[] = $sum;
	}
	for($i=0;$i<count($rowMean);$i++)
	{
		$rowMean[$i] = $rowMean[$i]/$numberOfRatedItems[$i];
	}
	
	return $rowMean;
}
function modifiedRatingMatrix($ratingMatrix,$allItems,$allUsers)
{
	$rowMean = rowMean($ratingMatrix);
	$modifiedRatingMatrix = array();
	$i=0;
	foreach ($ratingMatrix as $items => $ratings)
	{
		foreach ($ratings as $user => $rate)
		{
			if($rate !=0)
				$rate-=$rowMean[$i];
			$modifiedRatingMatrix[] = $rate;
		}
		$i++;
	}
	$items = $allItems;
	$users = $allUsers;
	$result = array();
	$usercount = count($users);
	$offset=0;
	for($i=0;$i<count($items);$i++)
	{
		$result[$i] = array_combine($users,array_slice($modifiedRatingMatrix,$offset,$usercount));	
		$offset +=$usercount;
	}
	//for($i=0;$i<count($modifiedRatingMatrix);$i++)
	{
		$result = array_combine($items, $result);
	}
		
	return $result;
}
//calcule de l'angre entre deux vecteurs
function norm($vec)
{
    $norm = 0;
    $components = count($vec);
    if(count(array_unique($vec)) == 1 and array_unique($vec)[0]==0)
    {
        return 1;
    }
    for ($i = 0; $i < $components; $i++)
        $norm += $vec[$i] * $vec[$i];

    return sqrt($norm);
}

function dot($vec1, $vec2)
{
    $prod = 0;
    $components = count($vec1);
    if((count(array_unique($vec1)) == 1 and array_unique($vec1)[0]==0) or (count(array_unique($vec2)) == 1 and array_unique($vec2)[0]==0))
    {
        return 0;
    }
    for ($i = 0; $i < $components; $i++)
        $prod += ($vec1[$i] * $vec2[$i]);

    return $prod;
}
function getVectorFromItems($item,$ratingMatrix,$allItems,$allUsers)
{
	$allItemsVectors = modifiedRatingMatrix($ratingMatrix,$allItems,$allUsers);
	return array_values($allItemsVectors[$item]);
}
function angleBetweenTwoVectors($v1,$v2)
{
	return (dot($v1, $v2) / (norm($v1) * norm($v2)));
}
function cosineSimilarity($item1,$item2,$ratingMatrix,$allItems,$allUsers)
{
	//cosineSimilarity est l'angle entre deux vecteurs ( similarité entre deux articles)
	$v1 = getVectorFromItems($item1,$ratingMatrix,$allItems,$allUsers);
	$v2 = getVectorFromItems($item2,$ratingMatrix,$allItems,$allUsers);
	$angle = angleBetweenTwoVectors($v1,$v2);
	return $angle;
}

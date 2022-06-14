
<?php 
//header('Content-Type: application/json; charset=utf-8');

include '../getDataFromDB.php';
include 'alt_autoload.php';


$query = file_get_contents('php://input');
$query = json_decode($query,true);
$query = $query['search'];
$query = strtolower($query);//on met la recherche on minuscule

$parser = new \Smalot\PdfParser\Parser();

$items = getAllItems();
$C = count($items);//number of documents in collection;

$search = array("\n","\t"," ");
for($i=0;$i < $C ; $i++)
{
	$pdf = $parser->parseFile('../items/'.$items[$i]);//avoir les articles 
	$text[$items[$i]] = $pdf->getText();//avoir le text des articles
	$text[$items[$i]]=str_replace($search," ",$text[$items[$i]]);//on remplace les saut de ligne et tabulation par un espace
	$text[$items[$i]]=strtolower($text[$items[$i]]);//on met le text de l'article en minuscule
}


function queryTokenize($query)
{
	$parts = array();
	$tok = strtok($query, " ");
	while ($tok !== false)
	{
  		$parts[] = $tok;
    	$tok = strtok(" ");
	}
	return $parts;
}

function documentsLength($document,$item)
{
	return str_word_count($document[$item]);
}

$avgDocLength = 0;
for($i=0;$i < $C ; $i++)
{	
	$avgDocLength += documentsLength($text,$items[$i]);
	
}
$avgDocLength /= $C;

function wordFrequencyInDocument($word,$item)//tfwD
{
	return substr_count($item,$word);
}

function numberOfDocumentsContainingWord($document,$word,$items)//dfw
{	
	$dfw =0;
	for($i=0;$i < count($items); $i++)
	{
		if(wordFrequencyInDocument($word,$document[$items[$i]]) > 0 ) $dfw++;
	}
	return $dfw;
}

function SimilarityOfWordInDocument($document,$word,$items,$C,$item,$avgDocLength)
{
	$dfW = numberOfDocumentsContainingWord($document,$word,$items);//nombre d'articles contenant un mot de la recherche

	if($dfW == 0) return 0;//s'il n'y a aucun article alors aucune similarité

	$D = documentsLength($document,$item);//la taille du document (nombre de mots)
	if($D == 0) return 0;//si un article est vide alors aucune similarité

	if($D > $avgDocLength)//si l'article est plus grand que la moyenne de touts les articles
		$k = 200;//constante qui désigne le poid de la première occurence du mot 
				 //si k est grand la première occurence a le même poid que tout les autre occurence

	else $k = 1;//si k est petit la première occurence d'un mot de la recherche a un poid plus signifiant que les occurences qui suivent
	
	$tfwD = wordFrequencyInDocument($word,$document[$item]);//la fréquence d'un mot de la recherche dans l'article

	$SQD =  ( $tfwD / ($tfwD + $k*$D/$avgDocLength)  ) * ( log( $C / $dfW ) );//la similarité d'un mot de la recherche pour un article

	return $SQD;
}
function SimilarityOfWordInQuery($document,$word,$items,$C,$query)
{
	$dfW = numberOfDocumentsContainingWord($document,$word,$items);
	$tfwQ = 0;
	$words = queryTokenize($query);
	foreach($words as $key => $value)
	{
		if($value == $word) $tfwQ++;
	}

	if( $dfW == 0 ) return 0;


	$SQD = $tfwQ * ( $tfwQ / ($tfwQ+1)  ) * ( log( $C / $dfW ) );

	return $SQD;
}

//echo SimilarityOfWordInDocument($text,'equation',$items,$tfwQ,$C,$items[16]);

function recommend($document,$items,$C,$query,$avgDocLength)
{
	$words = queryTokenize($query);//chaque mot de la recherche dans une cellule d'un tableau
	$recommendations = array();
	
	for($i=0;$i < $C; $i++)//C nombre des articles dans la base de donnée
	{
		$SQD = 0;
		for($j=0;$j < count($words); $j++)
		{
			$SQD += SimilarityOfWordInQuery($document,$words[$j],$items,$C,$query)*
					SimilarityOfWordInDocument($document,$words[$j],$items,$C,$items[$i],$avgDocLength);
		}
		$SQ = 0;
		for($j=0;$j < count($words); $j++)
		{
			$SQ +=  pow(SimilarityOfWordInQuery($document,$words[$j],$items,$C,$query),2);
		}
		$SQ = sqrt($SQ);
		$SD =0;
		for($j=0;$j < count($words); $j++)
		{
			$SD+= pow(SimilarityOfWordInDocument($document,$words[$j],$items,$C,$items[$i],$avgDocLength),2);
		}
		$SD = sqrt($SD);
		if($SQ == 0 )$SQ = -1;
		if($SD == 0 )$SD = -1;
		$SQD /= ($SQ * $SD);
		if($SQD > 0.5)//on prend seulement les article avec bon score
		    $recommendations[$items[$i]] = 100*round($SQD,4)."%";
	}
	arsort($recommendations);
	
	return $recommendations;
}


print_r(json_encode(recommend($text,$items,$C,$query,$avgDocLength)));






?>

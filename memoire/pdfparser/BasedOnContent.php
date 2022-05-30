
<?php 
//header('Content-Type: application/json; charset=utf-8');

include '../getDataFromDB.php';
include 'alt_autoload.php';

//error_reporting(0);
$query = file_get_contents('php://input');
$query = json_decode($query,true);
$query = $query['search'];
$query = strtolower($query);





$parser = new \Smalot\PdfParser\Parser();

$items = getAllItems();
$C = count($items);//number of documents in collection;

$search = array("\n","\t"," ");
for($i=0;$i < $C ; $i++)
{
	$pdf = $parser->parseFile('../items/'.$items[$i]);//str_replace("é","e",$items[$i]));
	$text[$items[$i]] = $pdf->getText();
	$text[$items[$i]]=str_replace($search," ",$text[$items[$i]]);
	$text[$items[$i]]=strtolower($text[$items[$i]]);
}
//
//$query = 'vecteur espace';



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
/*echo numberOfDocumentsContainingWord($text,'ordre',$items)."\n";
for($i=0;$i < count($items); $i++)
echo wordFrequencyInDocument('ordre',$text[$items[$i]])."\n";*/
//print_r($text);
function SimilarityOfWordInDocument($document,$word,$items,$C,$item,$avgDocLength)
{
	$dfW = numberOfDocumentsContainingWord($document,$word,$items);
	//echo 'numberOfDocumentsContainingWord = ' . $dfW."\n";


	if($dfW == 0) return 0;
	$D = documentsLength($document,$item);
	if($D > $avgDocLength)
		$k = 200;
	else $k = 1;
	//echo 'documentsLength = '.$D."\n";
	if($D == 0) return 0;
	$tfwD = wordFrequencyInDocument($word,$document[$item]);
	//echo 'wordFrequencyInDocument  =  '.$tfwD."\n";
	$SQD =  ( $tfwD / ($tfwD + $k*$D/$avgDocLength)  ) * ( log( $C / $dfW ) );

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
	$words = queryTokenize($query);
	$recommendations = array();
	
	for($i=0;$i < $C; $i++)
	{
		$SQD = 0;
		for($j=0;$j < count($words); $j++)
		{
			$SQD += SimilarityOfWordInQuery($document,$words[$j],$items,$C,$query)*SimilarityOfWordInDocument($document,$words[$j],$items,$C,$items[$i],$avgDocLength);
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
		if($SQD > 0.5)
		    $recommendations[$items[$i]] = 100*round($SQD,4)."%";
	}
	arsort($recommendations);
	
	return $recommendations;
}


print_r(json_encode(recommend($text,$items,$C,$query,$avgDocLength)));





/*foreach ($recommend as $key => $value) 
	 echo "<p><a href=".'../items/'.$key.">'".$key."'</a></p>";*/ //remove header application from getdatafromDB for this to works



?>

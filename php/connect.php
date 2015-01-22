<?php

$user='root';
$password='mysql';
$database='ntgc';

function engtohin_issue($eng)
{
	if(intval($eng) == 0)
	{
		return "";
	}
	$hin = $eng;

	$hin = preg_replace("/0/", "०", $hin);
	$hin = preg_replace("/1/", "१", $hin);
	$hin = preg_replace("/2/", "२", $hin);
	$hin = preg_replace("/3/", "३", $hin);
	$hin = preg_replace("/4/", "४", $hin);
	$hin = preg_replace("/5/", "५", $hin);
	$hin = preg_replace("/6/", "६", $hin);
	$hin = preg_replace("/7/", "७", $hin);
	$hin = preg_replace("/8/", "८", $hin);
	$hin = preg_replace("/9/", "९", $hin);
	
	$hin = preg_replace("/^०/", "", $hin);
	$hin = preg_replace("/^०/", "", $hin);
	$hin = preg_replace("/\-०/", "-", $hin);
	$hin = preg_replace("/\-०/", "-", $hin);
	
	return $hin;
}

function engtohin_month($eng)
{
	if(intval($eng) == 0)
	{
		return "";
	}
	
	$hin = $eng;
	
	$hin = preg_replace("/00/", "", $hin);
	$hin = preg_replace("/01/", "जनवरी", $hin);
	$hin = preg_replace("/02/", "फ़रवरी", $hin);
	$hin = preg_replace("/03/", "मार्च", $hin);
	$hin = preg_replace("/04/", "अप्रैल", $hin);
	$hin = preg_replace("/05/", "मई", $hin);
	$hin = preg_replace("/06/", "जून", $hin);
	$hin = preg_replace("/07/", "जुलाई", $hin);
	$hin = preg_replace("/08/", "अगस्त", $hin);
	$hin = preg_replace("/09/", "सितम्बर", $hin);
	$hin = preg_replace("/10/", "अक्टूबर", $hin);
	$hin = preg_replace("/11/", "नवम्बर", $hin);
	$hin = preg_replace("/12/", "दिसम्बर", $hin);
	
	return $hin;
}
function get_ymvi($volume, $issue, $year, $month)
{
	$ymvi = '';
	$ymvi = $ymvi . "वर्ष ".engtohin_issue($volume);
	$ymvi = $ymvi . " अंक ".engtohin_issue($issue);
	
	if(($month != "00") || ($year != "00"))
	{
		$ymvi = $ymvi . ", ".engtohin_month($month)." ".engtohin_issue($year);
	}
	$ymvi = preg_replace("/  /", " ", $ymvi);
	return $ymvi;
}

?>

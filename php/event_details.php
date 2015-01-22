<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Natarang</title>
<link href="../php/style/reset.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../php/style/indexstyle.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../php/style/style.css" media="screen" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../php/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../php/js/jquery-ui.js"></script>
<script type="text/javascript" src="../php/js/nav.js"></script>
</head>

<body>
<div class="page">
	<div class="header">		
		<div class="flow">
			<img src="../php/images/nt1.png" alt="" />
		</div>
		<div class="sa">
			<a href="../index.html"><img src="images/logo.png" alt="Natarang Logo" class="logo"/></a>
		</div>
		<div class="title">
			<p>नटरंग</p>
			<p class="sml">भारतीय रंगमंच का त्रैमासिक</p>
		</div>
	</div>
	<div class="mainpage">
		
<?php

$event_type = $_GET['event_type'];
$event_type = trim(addslashes($event_type));

include("connect.php");

$db = new mysqli('localhost', "$user", "$password", "$database");

if($db->connect_errno > 0){
    die('Not connected to database [' . $db->connect_error . ']');
}

$media_type = array("1"=>"Drama","2"=>"Dance","99"=>"Other");
	
echo "<div style=\"font-size: 2em;font-weight: bold;text-align: center;\">$media_type[$event_type]</div>";

$query1 = "select * from media_details where aid='$event_type'"; 

$result1 = $db->query($query1); 
$num_rows1 = $result1->num_rows;
		
if($num_rows1)
{
	for($j=1;$j<=$num_rows1;$j++)
	{
		$row1 = $result1->fetch_assoc();
		
		$media_title=$row1['media_title'];
		$aid=$row1['aid'];
		$cid=$row1['cid'];
		$media_id=$row1['media_id'];
				
		$media_title =  preg_replace("/;([0-9]+)$/",'',$media_title);
		$entries =  preg_split('/;/',$media_title);				
				
		foreach($entries as $k => $value)
		{
			$entries1 = preg_split('/!!/',$value);
			
			foreach($entries1 as $m => $value1)
			{
				if($m == 0)
				{
					echo "<div class=\"entry\"><span class=\"media_type\">". trim($entries1[$m]) . "</span>";
				}
				else
				{	
					//echo "&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"media_value\"><a href=\"display_all.php?data=" . trim($entries1[$m]). "\">" . trim($entries1[$m]) . "</a></span></div>";								
					$entries2 = preg_split('/,/',trim($entries1[$m]));
					$allresults = "";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"media_value\">";
					foreach($entries2 as $n => $value2)
					{
						$allresults = $allresults . "<a href=\"display_all.php?data=" . trim($entries2[$n]) . "\">" . trim($entries2[$n]) . "</a>, ";
					}
					$allresults = preg_replace('/, $/','',$allresults);
					echo $allresults;
					echo "</span></div>";
				}
			}
		}
				
		echo "<p style=\"margin-bottom: 1em;margin-top:20px;\"><a href=\"event_in_detail.php?media_id=$media_id\">More....</a></p>";
		echo "<hr />";		
	}			
}
else
{
	echo "No data in the database";
}
$result1->free();	
$db->close();

?>

	</div>
	<div class="footer_inside">
		<p style="float: left;">
			नटरंग प्रतिष्ठान<br />
			७०६, सुमॆरु अपार्टमेंट<br />
			इ डि एम् माल के समीप, कौशाम्बि<br />
			ग़ाज़ियाबाद, उत्तर प्रदेश २०१ ०१०<br />
			भारत.<br /><br />
			&copy; २०१४, नटरंग प्रतिष्ठान
		</p>
		<p style="float: right;">
			Natarang Pratishthan<br />
			706, Sumeru Apartments<br />
			Near EDM mall, Kaushambi<br />
			Ghaziabad, Uttar Pradesh 201 010<br />
			INDIA.<br /><br />
			&copy; 2014, Natarang Pratishthan
		</p>
	</div>
</div>
</body>

</html>

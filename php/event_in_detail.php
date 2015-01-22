<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Natarang</title>
<link href="style/reset.css" media="screen" rel="stylesheet" type="text/css" />
<link href="style/indexstyle.css" media="screen" rel="stylesheet" type="text/css" />
<link href="style/screen.css" media="screen" rel="stylesheet" type="text/css" />
<link href="style/lightbox.css" media="screen" rel="stylesheet" type="text/css" />
<link href="style/style.css" media="screen" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/lightbox.min.js"></script>

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
include("connect.php");
$media_id = $_GET['media_id'];

$media_id = trim(addslashes($media_id));

$db = new mysqli('localhost', "$user", "$password", "$database");

if($db->connect_errno > 0){
    die('Not connected to database [' . $db->connect_error . ']');
}

$query = "select * from media_details where media_id='$media_id'"; 

$result = $db->query($query); 
$num_rows = $result->num_rows;

if($num_rows)
{
	for($i=1;$i<=$num_rows;$i++)
	{
		$row = $result->fetch_assoc();
				
		$media_title=$row['media_title'];
		$aid=$row['aid'];
		$cid=$row['cid'];
		
		$event_type = "0" . $aid;
				
		$media_title =  preg_replace("/;([0-9]+)$/",'',$media_title);
		$entries =  preg_split('/;/',$media_title);
				
		//~ echo "<div><img src=\"images/". $event_type . ".png\" alt=\"\"/></div>";		
		foreach($entries as $k => $value)
		{
			$entries1 = preg_split('/!!/',$value);
					
			foreach($entries1 as $m => $value1)
			{
				if($m == 0)
				{
					echo "<div class=\"entry\"><span class=\"media_type\">". trim($entries1[$m]) . ":</span>";
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
		
		$query1 = "select * from photo_details where media_id='$media_id'";
		$result1 = $db->query($query1); 
		$num_rows1 = $result1->num_rows;
		
		if($num_rows1)
		{
			echo "<div class=\"image-set\">\n";
			for($j=1;$j<=$num_rows1;$j++)
			{
				$row1 = $result1->fetch_assoc();
				
				$photo_id = $row1['photo_id'];
				$photo_type = $row1['photo_type'];
				$photo_size = $row1['photo_size'];
				$description = $row1['description'];
				
				//~ echo "<div class=\"photo\"><img src=\"../photos/thumbs/". $photo_id .".jpg\" alt=\"". $description ."\"></div>";				
				echo "<div class=\"disp_photo\"><a class=\"example-image-link\" href=\"../photos/main/". $photo_id .".jpg\" data-lightbox=\"example-set\" data-title=\"". $description ."\"><img class=\"example-image\" src=\"../photos/thumbs/".$photo_id.".jpg\" alt=\"\"/></a><br />". $photo_id ."</div>\n";
			}
			echo "</div>";
		}
		else
		{
			echo "No Photos for this card";
		}
		$result1->free();
				
	}
}
else
{
	echo "No data in the database";
}

$result->free();
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

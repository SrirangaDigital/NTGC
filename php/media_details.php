<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style/style.css" media="screen" rel="stylesheet" type="text/css" />
<title>Natarang Cards</title>
</head>

<body>

<?php
include("connect.php");

$db = new mysqli('localhost', "$user", "$password", "$database");

if($db->connect_errno > 0){
    die('Not connected to database [' . $db->connect_error . ']');
}

$media_type = array("1"=>"Drama","2"=>"Dance","99"=>"Other");

$query = "select distinct aid from media_details"; 

$result = $db->query($query); 
$num_rows = $result->num_rows;

if($num_rows)
{
	for($i=1;$i<=$num_rows;$i++)
	{
		$row = $result->fetch_assoc();		
		$aid=$row['aid'];
		
		echo "<div style=\"font-size: 2em;font-weight: bold;text-align: center;\">$media_type[$aid]</div>";

		$query1 = "select * from media_details where aid='$aid'"; 

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
				
				echo "<p style=\"margin-bottom: 1em;\">Photos will go here....</p>";
				echo "<hr />";		
			}			
		}
		else
		{
			echo "No data in the database";
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

</body>
</html>

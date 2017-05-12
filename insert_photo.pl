#!/usr/bin/perl

$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];

#~ $host = "localhost";
#~ $db = "ntgc";
#~ $usr = "root";
#~ $pwd = "mysql";


use DBI();

open(IN,"<:utf8","natarang-cards.xml") or die "can't open natarang-cards.xml\n";

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$dbh->{'mysql_enable_utf8'} = 1;
$dbh->do('set names utf8');

$sth11=$dbh->prepare("CREATE TABLE photo_details(photo_id varchar(20), photo_type int(2), photo_size varchar(20), description varchar(10000), miscellaneous varchar(10000), media_id int(6)) ENGINE=MyISAM character set utf8 collate utf8_general_ci;");
$sth11->execute();
$sth11->finish(); 

$line = <IN>;

while($line)
{
	chop($line);
	if($line =~ /<media aid="(.*)" cid="(.*)">/)
	{
		$aid = $1;
		$cid = $2;		
	}
	elsif($line =~ /<mdetails>/)
	{
		$media_title = "";
		$line = <IN>;
		chop($line);
		while(!($line =~ /<\/mdetails>/))
		{			
			if($line =~ /<entry type="(.*)">(.*)<\/entry>/)
			{
				$media_title = $media_title . ";" . $1 . "!!" . $2;				
			}
			$line = <IN>;
			chop($line);
		}
		$media_title =~ s/^;//;
	}
	elsif($line =~ /<photo>|<video>/)
	{
		$line = <IN>;
		chop($line);
		if($line =~ /<entry id="(.*)" type="(.*)" size="(.*)">/)
		{
			$id = $1;
			if($id ne "")
			{
				$media_title = $media_title . ";" . $id;
			}
			else
			{
				$media_title = $media_title . ";" . "999999";
			}
		}
		$media_id = get_media_id($media_title);
		while(!($line =~ /<\/photo>|<\/video>/))
		{
			if($line =~ /<entry id="(.*)" type="(.*)" size="(.*)">/)
			{
				$photo_id = $1;
				$photo_type = $2;
				$size = $3;
			}
			elsif($line =~ /<desc>(.*)<\/desc>/)
			{
				$desc = $1;
			}			
			elsif($line =~ /<misc>(.*)<\/misc>/)
			{
				$misc = $1;
			}
			elsif($line =~ /<\/entry>/)
			{
				insert_photo($photo_id,$photo_type,$size,$desc,$misc,$media_id,$aid,$cid);	
				$desc = "";
				$misc = "";
			}
			$line = <IN>;
			chop($line);
		}
		#print $media_id . "\n";
	}
	elsif($line =~ /<\/media>/)
	{
		$media_title= "";		
	}
	$line = <IN>;
}

close(IN);
$dbh->disconnect();


sub get_media_id()
{
	my($media_title) = @_;	
	$media_title =~ s/'/\\'/g;
	my($sth,$ref);	
	$sth = $dbh->prepare("select media_id from media_details where media_title='$media_title'");
	$sth->execute();
	$ref=$sth->fetchrow_hashref();	
	if($sth->rows() > 0)
	{
		$media_id = $ref->{'media_id'};
		$sth->finish();
		return($media_id);
	}
	else
	{
		print "No media id for $media_title\n";
		$sth->finish();
		return(0);
	}
}

sub insert_photo()
{
	my($photo_id,$photo_type,$size,$desc,$misc,$media_id,$aid,$cid) = @_;
	#print $photo_id . "\n";

	$desc =~ s/'/\\'/g;
	$misc =~ s/'/\\'/g;
	$size =~ s/'/\\'/g;
	
	my($sth,$ref,$sth1);
	$sth = $dbh->prepare("select * from photo_details where photo_id='$photo_id' and media_id='$media_id' and description='$desc'");
	$sth->execute();
	$ref=$sth->fetchrow_hashref();
	if($sth->rows()==0)
	{
		$sth1=$dbh->prepare("insert into photo_details values('$photo_id','$photo_type','$size','$desc','$misc','$media_id')");
		$sth1->execute();
		$sth1->finish();
	}
	else
	{
		print $photo_id . "-->" . $media_id . "-->card id(" . $cid . ")\n";
	}
	$sth->finish();	
}


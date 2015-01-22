#!/usr/bin/perl

$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];

#~ $host = "localhost";
#~ $db = "ntg";
#~ $usr = "root";
#~ $pwd = "mysql";



use DBI();

open(IN,"<:utf8","natarang-cards.xml") or die "can't open natarang-cards.xml\n";

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

#~ $dbh->{'mysql_enable_utf8'} = 1;
#~ $dbh->do('set names utf8');

$sth11=$dbh->prepare("CREATE TABLE media_details(media_title varchar(10000), aid int(4), cid int(8), media_id int(6) auto_increment,  primary key(media_id))auto_increment=10001 ENGINE=MyISAM character set utf8 collate utf8_general_ci;");
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
		#print $media_title . "\n";
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
	}
	elsif($line =~ /<\/media>/)
	{
		insert_media($media_title,$aid,$cid);
		$media_title = "";		
		$aid = "";		
		$cid = "";		
	}
	$line = <IN>;
}

close(IN);
$dbh->disconnect();


sub insert_media()
{
	my($media_title,$aid,$cid) = @_;

	$media_title =~ s/'/\\'/g;
	
	my($sth,$ref,$sth1);
	$sth = $dbh->prepare("select media_id from media_details where media_title='$media_title'");
	$sth->execute();
	$ref=$sth->fetchrow_hashref();
	if($sth->rows()==0)
	{
		$sth1=$dbh->prepare("insert into media_details values('$media_title','$aid','$cid','')");
		$sth1->execute();
		$sth1->finish();
	}
	else
	{
		print $media_title . "\n";
	}
	$sth->finish();	
}


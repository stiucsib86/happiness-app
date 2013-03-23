#!/usr/bin/perl

# author: angad

use strict;
use warnings;
use List::MoreUtils 'first_index';
use Scalar::Util qw(looks_like_number);

# Column names in CSV
# 0 - id, 
# 1 - Restaurant name, 
# 2 - Address,
# 3 - Phone,
# 4 - Cuisine,
# 5 - Price,
# 6 - ,
# 7 - MRT,
# 8 - Hours,
# 9 - Website,
# 10 - Menu?,
# 11 - Votes,
# 12 - % Recommend,
# 13 - Email,
# ,,

# Column names in SQL
# restaurant_id name picture address postal_code website contact_number description price_range category cuisine location nearest_mrt reservation_policy created_by created_on updated_on is_approved is_saved is_deleted

# Mapping
# CSV Index - SQL Column
# 1 - name
# 2 - address
# 3 - contact_number
# 4 - price_range
# 5 - cuisine
# 6 - 
# 7 - nearest_mrt
# 8 - 
# 9 - website

# Cuisine table 
# 8, 'American'
# 9, 'Australian'
# 10, 'Chinese'
# 11, 'English'
# 12, 'French'
# 13, 'Fusion'
# 14, 'German'
# 15, 'Greek'
# 16, 'Indian'
# 17, 'Indonesian'
# 18, 'International'
# 19, 'Italian'
# 20, 'Japanese'
# 21, 'Korean'
# 22, 'Latin American'
# 23, 'Malay/Indonesian'
# 24, 'Mediterranean'
# 25, 'Mexican', '2013-01'
# 26, 'Middle Eastern'
# 27, 'Nonya/Peranakan'
# 28, 'Other'
# 29, 'Asian - Other'
# 30, 'Western - Other'
# 31, 'Singaporean'
# 32, 'Spanish'
# 33, 'Thai'
# 34, 'Vietnamese'

# price range table
# 1 - <$15
# 2 - $15-30
# 3 - $30-45
# 4 - $45-60
# 5 - 0
# 6 - >$60


my @cuisines = (
'American','Australian','Chinese','English','French','Fusion','German','Greek',
'Indian','Indonesian','International','Italian','Japanese','Korean','Latin American',
'Malay/Indonesian','Mediterranean','Mexican','Middle Eastern','Nonya/Peranakan',
'Other','Asian - Other','Western - Other','Singaporean','Spanish','Thai','Vietnamese'
	);

my $cuisine_db_offset = 8;



my @locations = (
'Admiralty',
'Aljunied',
'Ang Mo Kio',
'Bartley',
'Bayfront',
'Bedok',
'Bishan',
'Boon Keng',
'Boon Lay',
'Botanic Gardens',
'Braddell',
'Bras Basah',
'Buangkok',
'Bugis',
'Bukit Batok',
'Bukit Gombak',
'Buona Vista',
'Caldecott',
'Changi Airport',
'Chinatown',
'Chinese Garden',
'Choa Chu Kang',
'City Hall',
'Clarke Quay',
'Clementi',
'Commonwealth',
'Dakota',
'Dhoby Ghaut',
'Dover',
'Esplanade',
'Eunos',
'Expo',
'Farrer Park',
'Farrer Road',
'HarbourFront',
'Haw Par Villa',
'Holland Village',
'Hougang',
'Joo Koon',
'Jurong East',
'Kallang',
'Kembangan',
'Kent Ridge',
'Khatib',
'Kovan',
'Kranji',
'Labrador Park',
'Lakeside',
'Lavender',
'Little India',
'Lorong Chuan',
'MacPherson',
'Marina Bay',
'Marsiling',
'Marymount',
'Mountbatten',
'Newton',
'Nicoll Highway',
'Novena',
'one-north',
'Orchard',
'Outram Park',
'Pasir Panjang',
'Pasir Ris',
'Paya Lebar',
'Pioneer',
'Potong Pasir',
'Promenade',
'Punggol',
'Queenstown',
'Raffles Place',
'Redhill',
'Sembawang',
'Sengkang',
'Serangoon',
'Simei',
'Somerset',
'Stadium',
'Tai Seng',
'Tampines',
'Tanah Merah',
'Tanjong Pagar',
'Telok Blangah',
'Tiong Bahru',
'Toa Payoh',
'Woodlands',
'Woodleigh',
'Yew Tee',
'Yio Chu Kang',
'Yishun'
	);

my @price_range = (
'15', '15-30', '30-45', '45-60', '0', '60'
	);

my $cuisine_id = first_index {/Singaporean/} @cuisines;

open (SQL_OUT, '>>sql.txt');

# my $price_id = first_index {}

my $query = "INSERT INTO restaurants (name, address, contact_number, nearest_mrt, cuisine, price_range, website) VALUES";

# print SQL_OUT "INSERT INTO restaurants (name, address, contact_number, nearest_mrt, cuisine, price_range, website) VALUES";

my $file = $ARGV[0] or die "Need to get CSV file on the command line\n";
my @fields;

open(my $data, '<', $file) or die "Couldnt open '$file' $!\n";
local $/ = "ANGAD";
my $count = 0;

while(my $line = <$data>) {
	chomp $line;
	# print $line, "\n";
	my @fields = split ";" , $line;
	
	# index
	if(length($fields[1])) {
		# print $fields[1] , " ";
	}

	my $cuisine = "";
	my $location = "";
	my $price = "";
	my $name = "";
	my $address = "";
	my $contact = "";
	my $website = "";


	# # cuisine
	if(length($fields[5])) {
		my $cuisine_id = first_index {/$fields[5]/} @cuisines;
		# print $cuisine_id + $cuisine_db_offset, " ";
		# print $fields[5] , "\n";
		$cuisine = $cuisine_id + $cuisine_db_offset;
	}

	# location
	if(length($fields[8])) {
		my $location_id = first_index {/$fields[8]/} @locations;
		# print $location_id + 1,  " ";
		# print $fields[8] , "\n";
		$location = $location_id + 1;
	}

	# price
	if(length($fields[6])) {
		$fields[6] =~ tr/<>\$//d;
		my $price_id = first_index {/$fields[6]/} @price_range;
		# print $price_id + 1,  " ";
		# print $fields[6] , "\n";
		$price = $price_id + 1;
	}

	# name
	if(length($fields[2])) {
	 	# print $fields[2] , "\n";
	 	$name = $fields[2];
	 	$name =~ s/\n//g;
		$name =~ s/\"//g;

	}

	# address
	if(length($fields[3])) {
	 	# print $fields[3] , "\n";
	 	$address = $fields[3];
	 	# $address =~ s/\n/angad/;
	 	$address =~ s/\n/\\n/g;
	 	$address =~ s/\"//g;
	 	# print $address, "\n";
	 	# print "-----------\n";
	}

	# contact number
	if(length($fields[4])) {
		$contact = $fields[4];
		$contact =~ s/\n//g;
		$contact =~ s/\"//g;
	 	# print $fields[4] , "\n";
	}
	
	# website
	if(length($fields[10])) {
		# $contact = $fields[4];
	 	# print $fields[10] , "\n";
	 	if($fields[10] ne "N/A") {
	 		$website = $fields[10];
	 		$website =~ s/\n//g;
			$website =~ s/\"//g;
	 	}
	 	# print $website , "\n";
	}

	# print "INSERT INTO restaurants (name, address, contact_number, nearest_mrt, cuisine, price_range, website) VALUES (\"$name\", \"$address\", \"$contact\", \"$location\", \"$cuisine\", \"$price\", \"$website\")";
	# print "\n";
	chomp($name);
	chomp($contact);
	chomp($location);
	chomp($cuisine);
	chomp($price);
	chomp($website);

	if($count % 1000 == 0) {
		print SQL_OUT "\n\n\n\n\n\n";
		print SQL_OUT "-------------\n";
		print SQL_OUT "INSERT INTO restaurants (name, address, contact_number, nearest_mrt, cuisine, price_range, website) VALUES";
	}
	$count++;

	print SQL_OUT "(\"$name\", \"$address\", \"$contact\", \"$location\", \"$cuisine\", \"$price\", \"$website\"),";
	print SQL_OUT "\n";
	# print "(\"$name\", \"$address\", \"$contact\", \"$location\", \"$cuisine\", \"$price\", \"$website\"),";
	# print "\n";

}

<?php
	$con = mysqli_connect("localhost", "root", "Hassan2015") or die(mysql_error()); 
	mysqli_query($con, "DROP DATABASE worldcup");
	if( mysqli_query($con, "CREATE DATABASE worldcup"))
       	echo "db created";
        else echo "error in db";
       mysqli_select_db($con, "worldcup");
 	
	mysqli_query($con, "CREATE TABLE team(team_id int ,team_name varchar(20),competed_count int ,won_count int,group_description varchar(20),scode char(1),coach varchar(20),wikipedia_url varchar(100),country_flag_large varchar(100),country_flag varchar(100), PRIMARY KEY(team_id))");
	mysqli_query($con, "CREATE TABLE player(player_id int ,player_name varchar(20),position ENUM('defender','goal_keeper','forward','mid_field') ,team_id int ,PRIMARY KEY(player_id),FOREIGN KEY (team_id) REFERENCES team(team_id))");
	mysqli_query($con, "CREATE TABLE stadium(stadium_name varchar(20), seat_capacity int,
		google_maps_url varchar(100),wikipedia_url varchar(100) , city varchar(20), PRIMARY KEY(stadium_name))");
	mysqli_query($con, "CREATE TABLE game(game_id int PRIMARY KEY,game_description varchar(30),play_date date ,	play_time time,result varchar(20) , score varchar(20) ,is_champion boolean,iUTC_offset int ,game_name varchar(20),team1_id int,team2_id int,FOREIGN KEY (team1_id) REFERENCES team(team_id),FOREIGN KEY (team2_id) REFERENCES team(team_id),FOREIGN key game_name references stadium(stadium_name)");
	mysqli_query($con, "CREATE TABLE card(card_id int PRIMARY KEY,card_color ENUM('yellow','red'),iminutes int,player_id int,game_id INT,FOREIGN KEY (player_id) REFERENCES player(player_id),FOREIGN KEY (game_id) REFERENCES game(game_id))");
	mysqli_query($con, "CREATE TABLE goal(goal_id int PRIMARY KEY,iminutes int,player_id int,game_id INT,FOREIGN KEY (player_id) REFERENCES player(player_id),FOREIGN KEY (game_id) REFERENCES game(game_id))");
?>
<?php 
header("Location: table_fill.php");
?>
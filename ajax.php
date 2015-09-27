<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worldcup";
if ( isset( $_POST['action'] ) ) {
    switch ( $_POST['action'] ) {
    case 'AllPlayerNames':AllPlayerNames( $_POST['inputs'] );break;
    case 'AllDefenders':AllDefenders( $_POST['inputs'] );break;
    case 'AllGoalKeepers':AllGoalKeepers( $_POST['inputs'] );break;
    case 'AllForwards':AllForwards( $_POST['inputs'] );break;
    case 'AllMidFields':AllMidFields( $_POST['inputs'] );break;
    case 'TopGoalScorers':TopGoalScorers( $_POST['inputs'] );break;
    case  'AllPlayersWithYellowOrRedCards':AllPlayersWithYellowOrRedCards( $_POST['inputs'] );break;
    case  'AllPlayersWithYellowCards':AllPlayersWithYellowCards( $_POST['inputs'] );break;
    case  'AllPlayersWithRedCards':AllPlayersWithRedCards( $_POST['inputs'] );break;
    case  'AllCards':AllCards( $_POST['inputs'] );break;
    case  'Cities':Cities( $_POST['inputs'] );break;
    case  'StadiumNames':StadiumNames( $_POST['inputs'] );break;
    case  'StadiumURL':StadiumURL( $_POST['inputs'] );break;
    case  'StadiumInfo':StadiumInfo( $_POST['inputs'] );break;
    case  'AllStadiumInfo':AllStadiumInfo( $_POST['inputs'] );break;
    case  'FullTeamInfo':FullTeamInfo( $_POST['inputs'] );break;
    case  'Teams':Teams( $_POST['inputs'] );break;
    case  'GroupCount':GroupCount( $_POST['inputs'] );break;
    case  'Groups':Groups( $_POST['inputs'] );break;
    case  'GroupCompetitors':GroupCompetitors( $_POST['inputs'] );break;
    case  'AllGroupCompetitors':AllGroupCompetitors( $_POST['inputs'] );break;
    case  'GoalsScored':GoalsScored( $_POST['inputs'] );break;
    case  'GameInfo':GameInfo( $_POST['inputs'] );break;
    case  'AllGames':AllGames( $_POST['inputs'] );break;
    case  'CountryNames':CountryNames( $_POST['inputs'] );break;
    case  'GamesPlayed':GamesPlayed( $_POST['inputs'] );break;
    case  'GamesPerCity':GamesPerCity( $_POST['inputs'] );break;
    case  'YellowCardsTotal':YellowCardsTotal( $_POST['inputs'] );break;
    case  'RedCardsTotal':RedCardsTotal( $_POST['inputs'] );break;
    case  'YellowAndRedCardsTotal':YellowAndRedCardsTotal( $_POST['inputs'] );break;
    case  'Coaches':Coaches( $_POST['inputs'] );break;
    case  'PlayedAtWorldCup':PlayedAtWorldCup( $_POST['inputs'] );break;
    case  'TeamsCompeteList':TeamsCompeteList( $_POST['inputs'] );break;
    case  'DateOfFirstGame':DateOfFirstGame( $_POST['inputs'] );break;
    case  'DateOfLastGame':DateOfLastGame( $_POST['inputs'] );break;
    case  'DateLastGroupGame':DateLastGroupGame( $_POST['inputs'] );break;
    }
    exit();
}
function AllPlayerNames() {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select player_id, player_name, team_name , country_flag , country_flag_large from player inner join team on player.team_id = team.team_id ORDER BY player_id";
    $result = $conn->query( $sql );
    showInTable( $result );

    //---------------------------------------------------
    $conn->close();
}
function AllDefenders( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $country_name = $json[0]['sCountryName'];
    if ( $country_name == '' )
        $sql = "SELECT player_name FROM player where position = 'defender'";
    else
        $sql = "select player_name , team_name from player inner join team on player.team_id = team.team_id where position = 'defender' and team_name = '$country_name'";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function AllGoalKeepers( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $country_name = $json[0]['sCountryName'];
    if ( $country_name == '' )
        $sql = "SELECT player_name FROM player where position = 'goal_keeper'";
    else
        $sql = "select player_name , team_name from player inner join team on player.team_id = team.team_id where position = 'goal_keeper' and team_name = '$country_name'";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function AllForwards( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------

    $json = json_decode( $inputs, true );
    $country_name = $json[0]['sCountryName'];
    if ( $country_name == '' )
        $sql = "SELECT player_name FROM player where position = 'forward'";
    else
        $sql = "select player_name , team_name from player inner join team on player.team_id = team.team_id where position = 'forward' and team_name = '$country_name'";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function AllMidFields( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $country_name = $json[0]['sCountryName'];
    if ( $country_name == '' )
        $sql = "SELECT player_name FROM player where position = 'mid_field'";
    else
        $sql = "select player_name , team_name from player inner join team on player.team_id = team.team_id where position = 'mid_field' and team_name = '$country_name'";
    $result = $conn->query( $sql );
    showInTable( $result );

    //---------------------------------------------------
    $conn->close();
}
function TopGoalScorers( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $TopN = $json[0]['iTopN'];

    $sql = "";
    $sql .= "SELECT  p.player_name, count(goal_id) as goals_count , t.team_name , t.country_flag , t.country_flag_large ";
    $sql .= "FROM player p ";
    $sql .= "INNER JOIN goal g ";
    $sql .= "ON p.player_id = g.player_id ";
    $sql .= "INNER JOIN team t ";
    $sql .= "ON p.team_id = t.team_id ";
    
    $sql .= "GROUP BY p.player_id ";
    $sql .= "ORDER BY goals_count DESC " ;
    if ( $TopN != '' )
        $sql .= 'LIMIT '.intval( $TopN ) ;
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function showInTable( $result ) {
    if ( $result->num_rows > 0 ) {
        // output data of each row
        echo "<table class = 'table table-hover tablesorter '>";
        echo "<thead><tr>";
        while ( $finfo = $result->fetch_field() ) {
            $string = $finfo->name;
            $string = str_replace("_","\n",$string);
            echo "<th>".$string."</th>";
        }
        echo "</thead></tr><tbody>";
        while ( $row = $result->fetch_assoc() ) {
            echo "<tr>";
            foreach ( $row as $key => $value ) {
                if (is_null( $row[$key] ) )
                    echo "<td>"."-NA-"."</td>";
                else if (substr($row[$key],0,4)=="http" && (substr($row[$key],strlen($row[$key])-4,4)==".png"||substr($row[$key],strlen($row[$key])-4,4)==".gif"))
                    echo "<td><img src=".$row[$key]."></td>";
                else if (substr($row[$key],0,4)=="http")
                    echo "<td><a href=".$row[$key]." target='_blank'>link</a></td>";
                else if ($row[$key]=="yellow")
                    echo "<td style ='background-color:#FFFF66;'>".$row[$key]."</td>";
                else if ($row[$key]=="red")
                    echo "<td style ='background-color:#FF0000;'>".$row[$key]."</td>";
                
                else
                    echo "<td>".$row[$key]."</td>";
                    
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo " 0 results";
    }
}
function AllPlayersWithYellowOrRedCards( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "";
    $sql .= "select distinct p.player_name , t.team_name , IFNULL(ycards.y_count, 0) as y_count , IFNULL(rcards.r_count, 0) as r_count,t.country_flag , t.country_flag_large ";
    $sql .= "from card c inner join player p on c.player_id = p.player_id inner join team t on t.team_id = p.team_id ";
    $sql .= "left join (select p1.player_id ,count(*) as y_count from player p1 inner join card c1 on p1.player_id = c1.player_id ";
    $sql .= " and c1.card_color = 'yellow'  group by p1.player_id) as ycards on ycards.player_id = p.player_id ";
    $sql .= "left join (select p2.player_id ,count(*) as r_count from player p2 inner join card c2 on p2.player_id = c2.player_id ";
    $sql .= " and c2.card_color = 'red'  group by p2.player_id) as rcards on rcards.player_id = p.player_id;" ;
    
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function AllPlayersWithYellowCards( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "";
    $sql .= "select distinct p.player_name , t.team_name , IFNULL(ycards.y_count, 0) as y_count , IFNULL(rcards.r_count, 0) as r_count,t.country_flag , t.country_flag_large ";
    $sql .= "from card c inner join player p on c.player_id = p.player_id inner join team t on t.team_id = p.team_id ";
    $sql .= "inner join (select p1.player_id ,count(*) as y_count from player p1 inner join card c1 on p1.player_id = c1.player_id ";
    $sql .= " and c1.card_color = 'yellow'  group by p1.player_id) as ycards on ycards.player_id = p.player_id ";
    $sql .= "left join (select p2.player_id ,count(*) as r_count from player p2 inner join card c2 on p2.player_id = c2.player_id ";
    $sql .= " and c2.card_color = 'red'  group by p2.player_id) as rcards on rcards.player_id = p.player_id;" ;
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function AllPlayersWithRedCards( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "";
    $sql .= "select distinct p.player_name , t.team_name , IFNULL(ycards.y_count, 0) as y_count , IFNULL(rcards.r_count, 0) as r_count,t.country_flag , t.country_flag_large ";
    $sql .= "from card c inner join player p on c.player_id = p.player_id inner join team t on t.team_id = p.team_id ";
    $sql .= "left join (select p1.player_id ,count(*) as y_count from player p1 inner join card c1 on p1.player_id = c1.player_id ";
    $sql .= " and c1.card_color = 'yellow'  group by p1.player_id) as ycards on ycards.player_id = p.player_id ";
    $sql .= "inner join (select p2.player_id ,count(*) as r_count from player p2 inner join card c2 on p2.player_id = c2.player_id ";
    $sql .= " and c2.card_color = 'red'  group by p2.player_id) as rcards on rcards.player_id = p.player_id;" ;
    
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function AllCards( $inputs ) {

    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "";
    $sql .= "select g.play_date , c.iminutes , t1.team_name as team1_name , t2.team_name as team2_name, p.player_name ,t1.country_flag as team1flag,t1.country_flag_large as team1flagL,t2.country_flag as team2flag,t2.country_flag_large as team2flagl, c.card_color from card c ";
    $sql .= "inner join game g on c.game_id = g.game_id   inner join team t1 on t1.team_id = g.team1_id ";
    $sql .= "inner join team t2 on t2.team_id = g.team2_id  inner join player p on p.player_id = c.player_id;" ;
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function Cities( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select city from stadium;";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function StadiumNames( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = " select stadium_name from stadium;";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function StadiumURL( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $stadium_name = $json[0]['sStadiumName'];
    if ( $stadium_name == '' ) {
        echo "please insert a stadium_name";
        return;
    }
    $sql = "select google_maps_url from stadium where stadium_name = '$stadium_name';";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function StadiumInfo( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $stadium_name = $json[0]['sStadiumName'];
    if ( $stadium_name == '' ) {
        echo "please insert a stadium_name";
        return;
    }
    $sql = "select * from stadium where stadium_name = '$stadium_name';";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function AllStadiumInfo( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select stadium_name from stadium order by stadium_name;";
    $result = $conn->query( $sql );
    if ( $result->num_rows > 0 ) {
        while ( $row = $result->fetch_assoc() ) {
            foreach ( $row as $key => $value ) {

                $inputs = '[{"sStadiumName":"'.$value.'"}]';
                StadiumInfo( $inputs );
                echo "<hr class='hr-black' >";
            }
        }
    }
    //---------------------------------------------------
    $conn->close();
}
function FullTeamInfo( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $team_name = $json[0]['sTeamName'];
    if ( $team_name == '' ) {
        echo "you must enter a team name";
        return;
    }
    echo "<h4>team information<br></h4>";
    
    $sql = " select team_id, team_name, country_flag,wikipedia_url,country_flag_large,coach , competed_count from team where team_name = '$team_name';";
    $result = $conn->query( $sql );
    showInTable( $result );

    $sql = "select team_id from team t where t.team_name = '$team_name';";
    $team_id = intval( $conn->query( $sql )->fetch_row()[0] );

    echo "<h4>goal_keepers<br></h4>";
    $sql = " select player_name from player p  where p.team_id = $team_id and p.position = 'goal_keeper';";
    $result = $conn->query( $sql );
    showInTable( $result );

    echo "<h4>defenders<br></h4>";
    $sql = " select player_name from player p  where p.team_id = $team_id and p.position = 'defender';";
    $result = $conn->query( $sql );
    showInTable( $result );

    echo "<h4>mid_fields<br></h4>";
    $sql = " select player_name from player p  where p.team_id = $team_id and p.position = 'mid_field';";
    $result = $conn->query( $sql );
    showInTable( $result );

    echo "<h4>forwards<br></h4>";
    $sql = " select player_name from player p  where p.team_id = $team_id and p.position = 'forward';";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function Teams( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select team_name from team order by team_name;";
    $result = $conn->query( $sql );
    if ( $result->num_rows > 0 ) {
        while ( $row = $result->fetch_assoc() ) {
            foreach ( $row as $key => $value ) {
                $inputs = '[{"sTeamName":"'.$value.'"}]';
                FullTeamInfo( $inputs );
                echo "<hr class='hr-black' >";
            }
        }
    }
    //---------------------------------------------------
    $conn->close();
}
function GroupCount( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select count(distinct group_description) as group_name  from team;";
    $result = $conn->query( $sql )->fetch_row()[0];
    echo "number of groups is $result";
    //---------------------------------------------------
    $conn->close();
}
function Groups( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select distinct group_description as group_name , scode as code  from team;";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function GroupCompetitors( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $group_code = $json[0]['sPoule'];
    if ( $group_code == '' ) {
        echo "please insert a Group code";
        return;
    }
    $sql = "select team_id,team_name,country_flag,wikipedia_url,country_flag_large,scode , group_description from team where scode = '$group_code';";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function AllGroupCompetitors( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select distinct scode as group_name from team;";
    $result = $conn->query( $sql );
    if ( $result->num_rows > 0 ) {
        while ( $row = $result->fetch_assoc() ) {
            foreach ( $row as $key => $value ) {
                $inputs = '[{"sPoule":"'.$value.'"}]';
                GroupCompetitors( $inputs );
                echo "<hr class='hr-black' >";
            }
        }
    }
    //---------------------------------------------------
    $conn->close();
}
function GameInfo( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $game_id = $json[0]['iGameId'];
    if ( $game_id == '' ) {
        echo "you must enter an ID";
        return;
    }
    $sql = "select count(*) from card c where game_id = $game_id and card_color = 'yellow';";
    $yCardsCount = $conn->query( $sql )->fetch_row()[0];

    $sql = "select count(*) from card c where game_id = $game_id and card_color = 'red';";
    $rCardsCount = $conn->query( $sql )->fetch_row()[0];

    $sql = "select game_id,game_description,play_time,play_date,iUTC_offset,result,score";
    $sql.= ",'false' as is_champion ,$yCardsCount as yellow_cards,$rCardsCount as red_cards   from game where game_id = '$game_id';";
    $result = $conn->query( $sql );
    showInTable( $result );

    $sql = "select team1_id,team2_id , game_name from game where game_id = '$game_id';";
    $result = $conn->query( $sql )->fetch_row();
    $team1_id = $result[0];
    $team2_id = $result[1];
    $stadium_name = $result[2];

    echo "<h4>team 1<br></h4>";
    $sql = "select team_id,team_name,wikipedia_url,country_flag,country_flag_large from team where team_id = $team1_id;";
    $result = $conn->query( $sql );
    showInTable( $result );

    echo "<h4>team 2<br></h4>";
    $sql = "select team_id,team_name,wikipedia_url,country_flag,country_flag_large from team where team_id = $team2_id;";
    $result = $conn->query( $sql );
    showInTable( $result );

    echo "<h4>yellow cards<br></h4>";
    $sql = "select iminutes,card_color from card where game_id = $game_id and card_color = 'yellow';";
    $result = $conn->query( $sql );
    showInTable( $result );

    echo "<h4>red cards<br></h4>";
    $sql = "select iminutes,card_color from card where game_id = $game_id and card_color = 'red';";
    $result = $conn->query( $sql );
    showInTable( $result );

    echo "<h4>stadium<br></h4>";
    $sql = "select * from stadium where stadium_name = '$stadium_name';";
    $result = $conn->query($sql);
    showInTable( $result );

    echo "<h4>goals<br></h4>";
    $sql = "select game.play_date,goal.iminutes ,p.player_name,t.team_name,t.country_flag , t.country_flag_large  from goal inner join game on goal.game_id = game.game_id  ";
    $sql .="inner join player p on p.player_id = goal.player_id inner join team t on t.team_id = p.team_id";
    $sql .=" where goal.game_id = $game_id;";
    $result = $conn->query($sql);
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();;
}
function AllGames( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select game_id from game order by game_id;";
    $result = $conn->query( $sql );
    if ( $result->num_rows > 0 ) {
        while ( $row = $result->fetch_assoc() ) {
            foreach ( $row as $key => $value ) {
                $inputs = '[{"iGameId":"'.$value.'"}]';
                GameInfo( $inputs );
                echo "<hr class='hr-black' >";
            }
        }
    }
    //---------------------------------------------------
    $conn->close();
}
function CountryNames( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select team_id as country_id ,team_name as country_name from team ;";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function GamesPlayed( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select count(game_id) as count from game ;";
    $result = $conn->query( $sql );
    $count = $result->fetch_row()[0];
    echo "Games Played so far is $count";
    $conn->close();
}
function GamesPerCity( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $city_name = $json[0]['sCityName'];
    if ( $city_name == '' ) {
        echo "you must enter an ID";
        return;
    }
    echo "$city_name";
    $sql = "select game_id from game g inner join stadium s on g.game_name = s.stadium_name where city = '$city_name';";
    $result = $conn->query( $sql );
    if ( $result->num_rows > 0 ) {
        while ( $row = $result->fetch_assoc() ) {
            foreach ( $row as $key => $value ) {
                $inputs = '[{"iGameId":"'.$value.'"}]';
                GameInfo( $inputs );
                echo "<hr class='hr-black' >";
            }
        }
    }
}
function YellowCardsTotal( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select count(card_id) from card where card.card_color = 'yellow' ;";
    $result = $conn->query( $sql );
    $count = $result->fetch_row()[0];
    echo "Number of yellow cards is $count";
    //---------------------------------------------------
    $conn->close();
}
function RedCardsTotal( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select count(card_id) from card where card.card_color = 'red' ;";
    $result = $conn->query( $sql );
    $count = $result->fetch_row()[0];
    echo "Number of red cards is $count";
    //---------------------------------------------------
    $conn->close();
}
function YellowAndRedCardsTotal( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    YellowCardsTotal("");
    echo "<br>";
    RedCardsTotal("");
    /*$sql = "select count(card_id) from card;";
    $result = $conn->query( $sql );
    $count = $result->fetch_row()[0];
    echo "Number of yellow and red cards is $count";*/
    //---------------------------------------------------
    $conn->close();
}
function Coaches( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select coach,team_id ,team_name,wikipedia_url,country_flag_large , country_flag from team ;";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function PlayedAtWorldCup( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $json = json_decode( $inputs, true );
    $team_name = $json[0]['sTeamName'];
    if ( $team_name != '' ) {
        $sql = "select competed_count from team where team_name = '$team_name';";
        $result = $conn->query( $sql );
        showInTable( $result );
    }
    else
        echo "please insert a country name";
    //---------------------------------------------------
    $conn->close();
}
function TeamsCompeteList( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select  competed_count,won_count,coach,team_id,team_name,wikipedia_url,country_flag,country_flag_large from team ;";
    $result = $conn->query( $sql );
    showInTable( $result );
    //---------------------------------------------------
    $conn->close();
}
function DateOfFirstGame( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select play_date from game where game_id = 1;";
    $result = $conn->query( $sql )->fetch_row()[0];
    echo "data of first game is $result";
    //---------------------------------------------------
    $conn->close();
}
function DateOfLastGame( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select play_date from game where game_id = 64;";
    $result = $conn->query( $sql )->fetch_row()[0];
    echo "data of last game is $result";
    //---------------------------------------------------
    $conn->close();
}
function DateLastGroupGame( $inputs ) {
    $conn = new mysqli( $GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname'] );
    //----------------------------------------------------
    $sql = "select play_date from game where game_id = 48;";
    $result = $conn->query( $sql )->fetch_row()[0];
    echo "data of last group game is $result";
    //---------------------------------------------------
    $conn->close();
}
?>

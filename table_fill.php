<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

$con = mysqli_connect( "localhost", "root", "Hassan2015", "worldcup" ) or die( mysql_error() );
$client = new SoapClient( "http://footballpool.dataaccess.eu/data/info.wso?wsdl" );
//------------------------AllPlayerNames------------------------------------
$result = $client->AllPlayerNames( array( "bSelected" => 1 ) );
$AllPlayerNames = $result->AllPlayerNamesResult->tPlayerNames;
foreach ( $AllPlayerNames as $k=>$v ) {
  $sql = "insert into player(player_id,player_name) values('$v->iId','$v->sName')";
  mysqli_query( $con, $sql );
}
//------------------------AllDefenders------------------------------------
$result = $client->AllDefenders( array( "sCountryName" => "" ) );
$Alldefenders = $result->AllDefendersResult -> string;
foreach ( $Alldefenders as $k=>$v ) {
  $sql ="UPDATE player SET position='defender'WHERE player_name='$v'";
  mysqli_query( $con, $sql );
}
//------------------------AllGoalKeepers------------------------------------
$result = $client->AllGoalKeepers( array( "sCountryName" => "" ) );
$Allgoalk = $result->AllGoalKeepersResult -> string;
foreach ( $Allgoalk as $k=>$v ) {
  $sql ="UPDATE player SET position='goal_keeper'WHERE player_name='$v'";
  mysqli_query( $con, $sql );
}
//------------------------AllForwards------------------------------------
$result = $client->AllForwards( array( "sCountryName" => "" ) );
$AllForwards = $result->AllForwardsResult -> string;
foreach ( $AllForwards as $k=>$v ) {
  $sql ="UPDATE player SET position='forward'WHERE player_name='$v'";
  mysqli_query( $con, $sql );
}
//------------------------AllMidFields------------------------------------
$result = $client->AllMidFields( array( "sCountryName" => "" ) );
$AllMidFields = $result->AllMidFieldsResult -> string;
foreach ( $AllMidFields as $k=>$v ) {
  $sql ="UPDATE player SET position='mid_field'WHERE player_name='$v'";
  mysqli_query( $con, $sql );
}
//---------------------------TeamsCompeteList---------------------------------
$team = $client->TeamsCompeteList( array() );
$teamresult =$team ->TeamsCompeteListResult->tTeamCompete;

foreach ( $teamresult as $k =>$v ) {
  $arr = $v->CoachInfo;
  $x=$arr->TeamInfo;
  $sql = "insert into team(team_id,team_name,Country_flag,Country_flag_Large,wikipedia_URL,coach,competed_count,won_count) values($x->iId,'$x->sName','$x->sCountryFlag','$x->sCountryFlagLarge','$x->sWikipediaURL','$arr->sName',$v->iCompeted,$v->iWon)";

  if ( !mysqli_query( $con, $sql ) ) {
    echo mysqli_error( $con );
    die( mysqli_error( $con ) );
  }
}
//---------------------------GroupCompetitors-----------------------------------
$array = array( "A", "B", "C", "D", "E", "F", "G", "H" );
foreach ( $array as $i => $value ) {
  $result = $client->GroupCompetitors( array( "sPoule" =>"$value" ) );
  $GroupTeamsA = $result->GroupCompetitorsResult->tTeamInfo ;
  foreach ( $GroupTeamsA as $k =>$v ) {
    $result = $client->FullTeamInfo( array( "sTeamName" => $v->sName ) );
    $team = $result->FullTeamInfoResult ;

    $sql="UPDATE team set sCode='$value' where team_id=$v->iId";
    $result = mysqli_query( $con, $sql );

    $sql="UPDATE team set group_description='group $value' where team_id=$v->iId ";

    $result = mysqli_query( $con, $sql );
    $players= $team->sGoalKeepers->string;
    foreach ( $players as $n =>$m ) {
      $sql = "UPDATE player SET team_id =$v->iId where player_name='$m'";
      mysqli_query( $con, $sql );
    }
    $players= $team->sDefenders->string;
    foreach ( $players as $n =>$m ) {
      $sql = "UPDATE player SET team_id =$v->iId where player_name='$m'";
      mysqli_query( $con, $sql );
    }
    $players= $team->sMidFields->string;
    foreach ( $players as $n =>$m ) {
      $sql = "UPDATE player SET team_id =$v->iId where player_name='$m'";
      mysqli_query( $con, $sql );
    }
    $players= $team->sForwards->string;
    foreach ( $players as $n =>$m ) {
      $sql = "UPDATE player SET team_id =$v->iId where player_name='$m'";
      mysqli_query( $con, $sql );
    }
  }
}
//---------------------------AllGames-----------------------------------
$result = $client->AllGames( array() );
$games = $result->AllGamesResult->tGameInfo ;
foreach ( $games as $k =>$v ) {
  $StadiumName = $v->StadiumInfo->sStadiumName;
  $team1 = $v->Team1->iId;
  $team2 = $v->Team2->iId;
  $sql="insert into game (game_id,game_description,play_date,play_time,result,score,is_champion,iUTC_offset,game_name,team1_id ,team2_id) values
                      ($v->iId,'$v->sDescription','$v->dPlayDate','$v->tPlayTime','$v->sResult','$v->sScore',false,$v->iUTCOffset,'$StadiumName',$team1,$team2)";
  if ( !mysqli_query( $con, $sql ) ) {
    echo mysqli_error( $con );
    die( mysqli_error( $con ) );
  }
}
//---------------------------AllCards-----------------------------------
$result = $client->AllCards( array() );
$cards = $result->AllCardsResult->tCardInfo;

foreach ( $cards as $k =>$v ) {
  if ( $v->bYellowCard == 'true' ) {
    $sql="insert into card (card_id,iminutes,card_color) value($k+1,$v->iMinute,'yellow')";
    mysqli_query( $con, $sql );
  }
  else {
    $sql="insert into card (card_id,iminutes,card_color) value($k+1,$v->iMinute,'red')";
    mysqli_query( $con, $sql );
  }
  $playerid = "select player_id from player where player_name='$v->sPlayerName'";
  $playerID = $con->query( $playerid );
  if ( $playerID->num_rows > 0 ) {
    while ( $row =$playerID->fetch_assoc() ) {
      $id = $row["player_id"] ;
      $cardId = $k+1;
      $sql="update card SET player_id = $id WHERE card_id='$cardId'";
      mysqli_query( $con, $sql );
    }
  }
  $teamid = "select team_id from team where team_name='$v->sGameTeam1'";
  $teamID = $con->query( $teamid );
  if ( $teamID->num_rows > 0 ) {
    while ( $row =$teamID->fetch_assoc() ) {
      $t_id = $row["team_id"] ;
      $gameid = "select game_id from game where play_date='$v->dGame' AND team1_id=$t_id";
      $gameID = $con->query( $gameid );
      if ( $gameID->num_rows > 0 ) {
        while ( $row1 =$gameID->fetch_assoc() ) {
          $id = $row1["game_id"] ;
          $cardId = $k+1;
          $sql="update card SET game_id = $id WHERE card_id='$cardId'";
          mysqli_query( $con, $sql );
        }
      }
    }
  }
}
//---------------------------GoalsScored----------------------------------
$result = $client->GoalsScored( array( "iGameId"=>"" ) );

$goals = $result->GoalsScoredResult->tGoal ;
foreach ( $goals as $k =>$v ) {
  $sql="insert into goal (goal_id,iminutes) value($k+1,$v->iMinute)";
  mysqli_query( $con, $sql );
  $playerid = "select player_id from player where player_name='$v->sPlayerName'";
  $playerID = $con->query( $playerid );
  if ( $playerID->num_rows > 0 ) {
    while ( $row =$playerID->fetch_assoc() ) {
      $id = $row["player_id"] ;
      $goalId = $k+1;
      $sql="update goal SET player_id = $id WHERE goal_id='$goalId'";
      mysqli_query( $con, $sql );
    }
  }

  $teamid = "select team_id from team where team_name='$v->sTeamName'";
  $teamID = $con->query( $teamid );
  if ( $teamID->num_rows > 0 ) {
    while ( $row =$teamID->fetch_assoc() ) {
      $t_id = $row["team_id"] ;
      /*fe mshkla f haza elsatr ell3en*/$gameid = "select game_id from game where play_date='$v->dGame' AND (team1_id=$t_id OR team2_id=$t_id)";
      $gameID = $con->query( $gameid );
      if ( $gameID->num_rows > 0 ) {
        while ( $row1 =$gameID->fetch_assoc() ) {
          $id = $row1["game_id"] ;
          $goalID = $k+1;
          $sql="update goal SET game_id = $id WHERE goal_id='$goalId'";
          mysqli_query( $con, $sql );
        }
      }
    }
  }
}

//---------------------------AllStadiumInfo-----------------------------------
$result = $client->AllStadiumInfo( array() );
$Stadiuminfo = $result->AllStadiumInfoResult->tStadiumInfo;

foreach ($Stadiuminfo as $k =>$v ) {
  $sql="insert into stadium(stadium_name,seat_capacity,city,wikipedia_URL)values('$v->sStadiumName','$v->iSeatsCapacity','$v->sCityName','$v->sWikipediaURL')";
  mysqli_query( $con, $sql );
  $result = $client->StadiumURL( array( "sStadiumName" => $v->sStadiumName ) );
  $Stadiumurl = $result->StadiumURLResult;
  $sql="UPDATE stadium SET google_maps_url='$Stadiumurl' WHERE stadium_name='$v->sStadiumName'";
  mysqli_query( $con, $sql );
}
?>
<?php
//header( "Location: tables.php" );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Example of Bootstrap 3 Accordion</title>
        <link rel="stylesheet" href="css/bootstrap.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="jquery.tablesorter.js"></script> 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="jquery.tablesorter.js"></script> 
        <script type="text/javascript" src='script.js'></script>
        <style type="text/css">
        .bs-example{
            margin: 20px;
        }
        </style>
    </head>
    <body>
<?php
	$text = file_get_contents("queries.json");
	$json = json_decode($text, true);//parse Json data
    echo "<div class='col-md-3 pre-scrollable sidebar-offcanvas' id='sidebar' style='position: fixed';><ul class='nav'>";
    foreach ($json['queries'] as $key => $value)
    {
        echo "<li><a href='#$value[queryName]'>$value[queryName]</a></li><br>";
    }
    echo "</ul></div>";

    echo "<div class='col-md-3'></div>";
    
    //echo "<div id='accordion' class='col-md-6 panel-group'>";
    $text = "";
    $text.='<div class="col-md-8 panel-group" id="accordion">';
    foreach ($json['queries'] as $key => $value)
    {
        /*echo "<a name='$value[queryName]'></a><br>";
        echo "<h3></h3>";
    	echo "<div id = div$value[queryName]>";
        echo "<br><hr>";
        echo "</div>";*/
        
            $text.='<div class="panel panel-default">';
                
                $text.='<div class="panel-heading">';
                    $text.='<h4 class="panel-title">';
                    $text.='<a name='.$value['queryName'].'></a>';

                    $text.='<a data-toggle="collapse" data-parent="#accordion" title="'.$value['discription'].'" class="masterTooltip" href="#collapse'.$key.'">' .$key .' - '.$value['queryName'].'</a>';
                    $text.='</h4>';
                $text.='</div>';

                $text.='<div id="collapse'.$key.'" class="panel-collapse collapse">';
                
                    $text.='<div class="panel-body" id = div'.$value['queryName'].'> ';
                        foreach ($value['inputs'] as $key2 => $inputField)
                        {
                             $text.="<br>$inputField";
                             $text.="<input class = 'control-label' type='text' name='$value[queryName]' id='$inputField'><br>";
                        }
                        $text.=" <button  class=' invoke btn btn-primary' type='button' name = $value[queryName] value =$value[queryName] >invoke</button> ";
                        $text.=" <button  class='reset btn btn-primary' type='button' name = $value[queryName] value =$value[queryName] >reset</button> ";
                    $text.='</div>';
                $text.='<div id = divr'.$value['queryName'].'></div>';
                $text.='</div>';
                $text.='</div>';
    }
    $text.='</div>';
    echo "$text";
?>
</body>
</html>
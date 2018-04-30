<?php 
$search = Lot::getAllLots(null);
$grid="";
while ($row = $search->fetch_assoc()){
	$grid.='<h2 style="display: inline-block;">'.$row["name"].'</h2><a href="'.SERVERROOT.'/add.php?LID='.$row['ID'].'/"><div class="confirmButton" style="display: inline-block;">Add Ticket</div></a><br>';
}

echo $grid;



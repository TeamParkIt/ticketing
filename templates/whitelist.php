<h2>Modify WhiteList</h2>
<table>
	<thead>
		<td>Plate Number</td>
		<td></td>
	</thead>
	<?php
	$whitelisted = WhiteList::getWhiteList(null);
	while ($row = $whitelisted->fetch_assoc()) {
		echo "<tr>";
		if($row['licensePlate']){
			echo "<td>".$row['licensePlate']."</td>";
			echo "<td>";
			echo '<form method="post">  
				 				<input type="hidden" value="'.$row['ID'].'" name="WhiteListID"/>
  								<input type="submit" value="Delete" name="deleteWhiteList" class="confirmButton"/>
  							</form>';
			echo "</td>";
		}else{
			echo "[No Plate Found]";
		};
		echo"</tr>";
	}
	if(isset($_POST['deleteWhiteList'])){
		WhiteList::deleteWhiteListItem($_POST['WhiteListID'], null);
	}
	?>
</table>
<h2>Add To WhiteList</h2>
<form method="POST">
	<input type="text" name="licensePlate" placeholder="Plate Number">
	<input type="submit" name="submitPlate" value="Add Plate">
</form>

<?php
	if(isset($_POST['submitPlate'])){
		WhiteList::insertWhiteListItem($_POST['licensePlate'], null);
	}
?>
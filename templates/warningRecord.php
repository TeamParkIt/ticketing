<?php
$warning = Warning::getWarningsByLicensePlate($plateNumber, null);
while ($row = $warning->fetch_assoc()) {
	echo "Warning Date: ";
	if($row['dateTime']){echo $row['dateTime'];}else{echo "[No Date Recorded]";};
	echo"<br>";
}

?>
<?php
$warning = Ticket::getTicketsByLicensePlate($plateNumber, null);
while ($row = $warning->fetch_assoc()) {
	echo "Ticket Date: ";
	if($row['dateTime']){echo $row['dateTime'];}else{echo "[No Date Recorded]";};
	echo"<br>";
}

?>
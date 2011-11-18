<?
	//Sync Devices to Roomes
	// ---------------------
	echo "--- Default Settings -------------------------------------------------------------------\n";
	include_once "Entertainment_Source.ips.php";
	Entertainment_SyncAllRoomControls();

	include_once "Entertainment_Room.ips.php";
	$RoomIds = IPS_GetChildrenIDs(c_ID_Roomes);
	foreach ($RoomIds as $RoomId) {
		$RoomPowerId = get_ControlIdByRoomId($RoomId, c_Control_RoomPower);
		Entertainment_SetRoomVisible($RoomPowerId, GetValue($RoomPowerId));
	}
?>
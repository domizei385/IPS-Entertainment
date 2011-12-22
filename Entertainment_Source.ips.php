<?
	include_once "IPSLogger.ips.php";
	include_once "Entertainment_Configuration.ips.php";
	include_once "Entertainment_Control.ips.php";
	include_once "Entertainment_Power.ips.php";
	include_once "Entertainment_Communication.ips.php";

	function Entertainment_SyncAllRoomControls() {
		$RoomIds = IPS_GetChildrenIDs(c_ID_Roomes);
		foreach ($RoomIds as $RoomId) {
			Entertainment_SyncRoomControls($RoomId);
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SyncRoomControls($RoomId) {
		$RoomName = IPS_GetName($RoomId);
		$RoomConfig = get_RoomConfiguration();
		$ControlTypes = $RoomConfig[$RoomName];
		foreach ($ControlTypes as $ControlType=>$ControlData) {
			if ($ControlType==c_Control_Muting or
				 $ControlType==c_Control_Volume or
				 $ControlType==c_Control_Program or
				 $ControlType==c_Control_RemoteVolume or
				 $ControlType==c_Control_iRemoteVolume or
				 $ControlType==c_Control_RemoteSource or
				 $ControlType==c_Control_iRemoteSource or
				 $ControlType==c_Control_Mode) {
				$RoomControlId   = get_ControlIdByRoomId($RoomId, $ControlType);
				$DeviceControlId = get_DeviceControlIdByRoomControlId($RoomControlId);
				
				if ($DeviceControlId===false and $ControlType==c_Control_iRemoteVolume) {
					$DeviceControlId = get_DeviceControlIdByRoomControlId($RoomControlId, c_Control_RemoteVolume);
				} else if ($DeviceControlId===false and $ControlType==c_Control_iRemoteSource) {
					$DeviceControlId = get_DeviceControlIdByRoomControlId($RoomControlId, c_Control_RemoteSource);
				} else {
				  //
				}
				
				IPSLogger_Trc(__file__,'Sync Room="'.$RoomName.'", Control="'.$ControlType.'", DeviceControlId='.$DeviceControlId);
				if ($DeviceControlId!==false) {
					SetValue($RoomControlId, GetValue($DeviceControlId));
				} else {
					IPSLogger_Err(__file__, 'DeviceControl of "'.$RoomName.'" of type "'.$ControlType.'" could NOT be found for RoomControlId='.$RoomControlId);
				}
			}
	   }
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetSource($SourceId, $Value, $MessageType=c_MessageType_Action) {
		$RoomId = IPS_GetParent($SourceId);
		$IsRoomPoweredOn = IsRoomPoweredOn($RoomId);
		if (GetValue($SourceId) <> $Value || !$IsRoomPoweredOn) {
			$SourceName = get_SourceName($RoomId, $Value);
			IPSLogger_Inf(__file__, 'Set Source "'.$SourceName.'" of Room '.IPS_GetName($RoomId));
			SetValue($SourceId, $Value);
			if (!$IsRoomPoweredOn) {
				Entertainment_SetRoomPowerByRoomId($RoomId, true, false);
			}
			Entertainment_SetDeviceControlByRoomId($RoomId, c_Control_Muting, false);
			Entertainment_SetDevicePowerByRoomId($RoomId, true);
			Entertainment_SendDataBySourceIdx($RoomId, $Value, $MessageType);
			Entertainment_SyncRoomControls($RoomId);
			Entertainment_PowerOffUnusedDevices();
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetSourceByRoomId($RoomId, $SourceIdx) {
		$SourceId = get_ControlIdByRoomId($RoomId, c_Control_Source);
		Entertainment_SetSource($SourceId, $SourceIdx);
	}

?>
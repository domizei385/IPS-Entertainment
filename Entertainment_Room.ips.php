<?
	include_once "IPSLogger.ips.php";
	include_once "Entertainment_Constants.ips.php";
	include_once "Entertainment_Configuration.ips.php";
	include_once "Entertainment_Communication.ips.php";
	include_once "Entertainment_Power.ips.php";
	include_once "Entertainment_Source.ips.php";

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetRoomVisible($PowerId, $Value) {
		$RoomId        = IPS_GetParent($PowerId);
		$RoomName      = IPS_GetName($RoomId);
		$LinkedRoomIds = IPS_GetChildrenIDs(c_ID_WebFrontRoomes);
		foreach ($LinkedRoomIds as $LinkedRoomId) {
			if (IPS_GetName($LinkedRoomId)==$RoomName) {
				$ChildrenIds = IPS_GetChildrenIDs($LinkedRoomId);
				foreach($ChildrenIds as $ChildrenIdx => $ChildrenId) {
					$LinkData = IPS_GetLink($ChildrenId);
					$LinkedChildId = $LinkData["LinkChildID"];
					if ($LinkedChildId <> $PowerId) {
						IPSLogger_Trc(__file__, 'Set Control "'.IPS_GetName($ChildrenId).'" of Room "'.IPS_GetName($RoomId).'" Visible='.bool2OnOff($Value));
						IPS_SetHidden($ChildrenId, !$Value);
					}
				}
			}
		}
		//WFC_Reload(c_ID_WebfrontConfiguration);
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetRoomPower($PowerId, $Value, $PowerOnDevices = true) {
		if (GetValue($PowerId) <> $Value) {
			IPSLogger_Inf(__file__, 'Set Power for Room "'.IPS_GetName(IPS_GetParent($PowerId)).'" '.bool2OnOff($Value));
			SetValue($PowerId, $Value);
			Entertainment_SetRoomVisible($PowerId, $Value);
			if ($PowerOnDevices) {
				Entertainment_SetDevicePowerByRoomId(IPS_GetParent($PowerId), $Value);
			}
			Entertainment_SyncRoomControls(IPS_GetParent($PowerId));
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetRoomPowerByRoomId($RoomId, $Value, $PowerOnDevices = true) {
		$PowerId = get_ControlIdByRoomId($RoomId, c_Control_RoomPower);
		Entertainment_SetRoomPower($PowerId, $Value, $PowerOnDevices);
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function IsRoomPoweredOn($RoomId) {
		$PowerId = get_ControlIdByRoomId($RoomId, c_Control_RoomPower);
		return GetValue($PowerId);
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_PowerOffUnusedRoomes() {
		IPSLogger_Dbg(__file__, 'PowerOff unused Roomes ...');
		$RoomIds =  get_ActiveRoomIds();
		foreach ($RoomIds as $RoomId) {
			$RoomActice = false;
			$DeviceNames = get_DeviceNamesByRoomId($RoomId);
			foreach ($DeviceNames as $DeviceName) {
				$RoomActive = isDevicePoweredOnByDeviceName($DeviceName) or $RoomActice;
			}
			if (!$RoomActive) {
			   Entertainment_SetRoomPowerByRoomId($RoomId, false);
			}
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetRoomPowerByDeviceName($DeviceName, $Value) {
		if ($Value) {
			$SourceList = get_SourceListByDeviceName($DeviceName, $Value);
			if (count($SourceList)==1) {
				$RoomKeys  = array_keys($SourceList);
				$RoomId    = $RoomKeys[0];
				$SourceIdx = $SourceList[$RoomId];
				if (!IsRoomPoweredOn($RoomId)) {
					Entertainment_SetRoomPowerByRoomId($RoomId, true, false);
					Entertainment_SetSourceByRoomId($RoomId, $SourceIdx);
				}
			}
		} else {
			$RoomId = get_RoomIdByOutputDevice($DeviceName);
			if ($RoomId!==false and IsRoomPoweredOn($RoomId)) {
				Entertainment_SetRoomPowerByRoomId($RoomId, false);
			}
		}
	}




?>
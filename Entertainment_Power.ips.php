<?
	include_once "IPSLogger.ips.php";
	include_once "Entertainment_Constants.ips.php";
	include_once "Entertainment_Configuration.ips.php";
	include_once "Entertainment_Communication.ips.php";
	include_once "Entertainment_Connect.ips.php";
	include_once "Entertainment_Source.ips.php";

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_TurnOffAllRoomesAndDevices() {
		IPSLogger_Inf(__file__, 'Turn Off all Roomes and Devices');
		$RoomIds =  get_ActiveRoomIds();
		foreach ($RoomIds as $RoomId) {
			Entertainment_SetRoomPowerByRoomId($RoomId, false);
		}
		Entertainment_PowerOffUnusedDevices();
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_PowerOffUnusedDevices() {
		IPSLogger_Trc(__file__, 'Power Off unused Devices ...');
		$ActiveDeviceNames = get_ActiveDeviceNames();
		$DeviceIds = IPS_GetChildrenIDs(c_ID_Devices);
		foreach ($DeviceIds as $DeviceId) {
			$DeviceName = IPS_GetName($DeviceId);
			$PowerId = get_ControlIdByDeviceName($DeviceName, c_Control_DevicePower, false);
			if ($PowerId !== false) {
				if (GetValue($PowerId) and !array_key_exists($DeviceName, $ActiveDeviceNames)) {
					Entertainment_SetDevicePowerByDeviceName($DeviceName, false);
				}
			}
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetDevicePower($PowerId, $Value, $MessageType=c_MessageType_Action) {
		$DeviceName = IPS_GetName(IPS_GetParent($PowerId));
		Entertainment_SetDevicePowerByDeviceName($DeviceName, $Value, $MessageType);
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetDevicePowerByDeviceName($DeviceName, $Value, $MessageType=c_MessageType_Action) {
		IPSLogger_Trc(__file__, 'Handle Device Power for "'.$DeviceName.'" '.bool2OnOff($Value));
		$PowerId = get_ControlIdByDeviceName($DeviceName, c_Control_DevicePower, false);
		if ($PowerId !== false) {
			if (!is_bool($Value)) { 												/*Toggle Power Value*/
				$Value = !GetValue($PowerId);
				IPSLogger_Dbg(__file__, "Toogle Device Power for '$DeviceName' to ".bool2OnOff($Value));
			}
			if (GetValue($PowerId) <> $Value) {
				IPSLogger_Inf(__file__, 'Set Device Power for "'.$DeviceName.'" '.bool2OnOff($Value));
				if ($Value) {
					Entertainment_SendDataByDeviceName($DeviceName, c_Control_DevicePower,
																	array(c_Property_CommPowerOn, c_Property_CommPower), $MessageType);
				} else {
					Entertainment_SendDataByDeviceName($DeviceName, c_Control_DevicePower,
																	array(c_Property_CommPowerOff, c_Property_CommPower), $MessageType);
				}
				SetValue($PowerId, $Value);
				Entertainment_Connect($DeviceName, $Value, true);
				Entertainment_SetDeviceControlByDeviceName($DeviceName, c_Control_Muting, false);
				Entertainment_SetRoomPowerByDeviceName($DeviceName, $Value);
			}
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Entertainment_SetDevicePowerByRoomId($RoomId, $Value=true) {
		if ($Value) {
			$RoomName    = IPS_GetName($RoomId);
			$DeviceNames = get_DeviceNamesByRoomId($RoomId);
			$DeviceData = get_DeviceConfiguration();
			foreach ($DeviceNames as $DeviceName) {
				if(isset($DeviceData[$DeviceName][c_Control_DevicePower])) {
					$devicePower = $DeviceData[$DeviceName][c_Control_DevicePower];
					if(isset($devicePower[c_Property_PowerDelay])) {
						usleep($devicePower[c_Property_PowerDelay] * 1000);
					}
				}
				Entertainment_SetDevicePowerByDeviceName($DeviceName, true);
			}
		} else {
			Entertainment_PowerOffUnusedDevices();
		}
	}
?>
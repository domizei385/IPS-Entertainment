<?
	include_once "Entertainment.ips.php";
	include_once "NetPlayer_Constants.ips.php";
	include_once "NetPlayer_ListFiles.ips.php";

	// ---------------------------------------------------------------------------------------------------------------------------
	function NetPlayer_GetDeviceConfigValue($Property) {
		return get_DeviceConfigValue(c_Device_NetPlayer, $Property);
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function NetPlayer_RefreshRemoteControl() {
      Entertainment_RefreshRemoteControlByDeviceName(c_Device_NetPlayer);
	}
	
	// ---------------------------------------------------------------------------------------------------------------------------
	function NetPlayer_ReceiveData_WebFront($Control, $Command) {
     	IPSLogger_Com(__file__, "Received Data from NetPlayer-Webfront, Control='$Control', Command='$Command'");
		Entertainment_ReceiveData(array(c_Comm_NetPlayer, $Control, $Command), c_MessageType_Info);
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function NetPlayer_SendData($Data) {
	   $Control = $Data[1];
	   $Command = $Data[2];
     	IPSLogger_Com(__file__, "Send Data to NetPlayer, Control='$Control', Command='$Command'");
		if ($Command=='poweron') {
		   if (WAC_GetPlaylistLength(c_ID_Mediaplayer) > 0){
				WAC_Play(c_ID_Mediaplayer);
				if (GetValue(c_ID_MediaplayerCDSelectList)=="") {
					$Directory = NetPlayer_GetDeviceConfigValue('Directory');
					NetPlayer_ListFiles($Directory, 0);
				}
			}
		} else if ($Command=='poweroff') {
		   if (WAC_GetPlaylistLength(c_ID_Mediaplayer) > 0){
				WAC_Stop(c_ID_Mediaplayer);
			}
		} else {
		   IPSLogger_Err(__file__, "Received unknown Command '$Command' from Entertainment-->Check Configuration!");
		}
	}
?>
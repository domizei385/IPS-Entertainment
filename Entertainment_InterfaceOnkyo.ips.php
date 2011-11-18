<?
	include_once "Entertainment.ips.php";

	function Onkyo_SendData_Socket ($command, $param)
	{
		$msg_data = '!1';
		$msg_data.= $command;
		$msg_data.= $param;
		$msg_data.= chr(13);

		$msg_header  ='ISCP'; /* Header Prefix*/
		$msg_header.= chr(0).chr(0).chr(0).chr(16); /* Header Length*/
		$msg_header.= chr(0).chr(0).chr(0).chr(strlen($msg_data)); /* Data Length*/
		$msg_header.= chr(1).chr(0).chr(0).chr(0); /* Version, ...*/

		$msg        = $msg_header.$msg_data;

		IPSLogger_Com(__file__, 'Send Message to Onkyo: '.$msg_data.' (Command='.$command.', Param='.$param.')');
     	$ModuleId = Entertainment_Connect_WaitForSocketOpen(c_Comm_Onkyo);
     	if ($ModuleId!==false) {
			CSCK_SendText($ModuleId , $msg);
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Onkyo_SendData ($Parameters)	{
	   $command = $Parameters[1];
		$param   = $Parameters[2];

		if ($command=='MVL' or $command=='ZVL') {
		   $param = str_pad(strtoupper(dechex($param)), 2, '0', STR_PAD_LEFT );
		}
		// Special Handling for Tuner: One Tuner, but Control is seperated to Main, Zone2 and Zone3
		// --> Send Tuning Command to all Devices
		if ($command=='TUN') {
			Onkyo_SendData_Socket('TUN', $param);
			usleep(100000);
			Onkyo_SendData_Socket('TUZ', $param);
			usleep(100000);
			Onkyo_SendData_Socket('TU3', $param);
		} else if ($command=='PRS') {
			Onkyo_SendData_Socket('PRZ', $param);
			usleep(100000);
			Onkyo_SendData_Socket('PRS', $param);
			usleep(100000);
			Onkyo_SendData_Socket('PR3', $param);
		} else {
			Onkyo_SendData_Socket($command, $param);
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Onkyo_ReceiveData_Register($RegisterId, $Value) {
		$Data    = substr($Value,18);
		$Command = substr($Data,0,3);
		$Param   = substr($Data,3,2);

		IPSLogger_Com(__file__, 'Received Message from Onkyo: '.$Data.' (Command='.$Command.', Param='.$Param.')');

		if ($Command=='MVL' or $Command=='ZVL') {
		   $Param = hexdec($Param);
		}

      Entertainment_ReceiveData(array(c_Comm_Onkyo, $Command, $Param), c_MessageType_Info);
	}
?>
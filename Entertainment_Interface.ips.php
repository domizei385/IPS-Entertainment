<?
include_once "Entertainment.ips.php";

if(Entertainment_requestSemaphore(sem_ENTERTAINMENT)) {
	try {
		// ---------------------------------------------------------------------------------------------------------------------------
		// Variable
		// ---------------------------------------------------------------------------------------------------------------------------
		if($IPS_SENDER == "Variable") {
			$CommConfig = get_CommunicationConfiguration();
			foreach ($CommConfig as $CommInterface => $CommProperties) {
			   if (array_key_exists(c_Property_VariableId, $CommProperties) and $CommProperties[c_Property_VariableId] == $IPS_VARIABLE) {
					$FunctionName   = $CommConfig[$CommInterface][c_Property_FunctionVariable];
					$FunctionScript = $CommConfig[$CommInterface][c_Property_Script];
					try {
						include_once $FunctionScript;
						$Function       = new ReflectionFunction($FunctionName);
						$Function->invoke($IPS_VARIABLE, $IPS_VALUE);
					} catch (Exception $e) {
						IPSLogger_Err(__file__, 'Error Executing Function '.$FunctionName.':'.$e->getMessage());
					}
				}
			}
		}
		
		// ---------------------------------------------------------------------------------------------------------------------------
		// RegisterVariable
		// ---------------------------------------------------------------------------------------------------------------------------
		if($IPS_SENDER == "RegisterVariable") {
			$CommConfig = get_CommunicationConfiguration();
			foreach ($CommConfig as $CommInterface => $CommProperties) {
				if (array_key_exists(c_Property_RegisterId, $CommProperties) and $CommProperties[c_Property_RegisterId] == $IPS_INSTANCE) {
					$FunctionName   = $CommConfig[$CommInterface][c_Property_FunctionRegister];
					$FunctionScript = $CommConfig[$CommInterface][c_Property_Script];
					try {
						include_once $FunctionScript;
						$Function       = new ReflectionFunction($FunctionName);
						$Function->invoke($IPS_INSTANCE, $IPS_VALUE);
					} catch (Exception $e) {
						IPSLogger_Err(__file__, 'Error Executing Function '.$FunctionName.':'.$e->getMessage());
					}
				}
			}
		}
		
		// ---------------------------------------------------------------------------------------------------------------------------
		// WebFront
		// ---------------------------------------------------------------------------------------------------------------------------
		if ($IPS_SENDER == "WebFront") {
			$ControlType = get_ControlType($IPS_VARIABLE);
			switch ($ControlType) {
				case c_Control_RoomPower:
					Entertainment_SetRoomPower($IPS_VARIABLE, $IPS_VALUE);
					break;
				case c_Control_DevicePower:
					Entertainment_SetDevicePower($IPS_VARIABLE, $IPS_VALUE);
					break;
				case c_Control_Source:
					Entertainment_SetSource($IPS_VARIABLE, $IPS_VALUE);
					break;
				case c_Control_Muting:
				case c_Control_Volume:
				case c_Control_Mode:
				case c_Control_Program:
					Entertainment_SetControl($IPS_VARIABLE, $IPS_VALUE);
					break;
				default:
					IPSLogger_Err(__file__, 'Unknown Control with ID='.$IPS_VARIABLE.' !');
			}
		}
	}
	catch (Exception $e) {
		IPSLogger_Err(__file__, 'Error in Entertainment_Interface');
	}
	Entertainment_freeSemaphore(sem_ENTERTAINMENT);
}
?>
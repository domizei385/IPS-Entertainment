<?

include_once "IPSLogger.ips.php";

function HomeMaticSwitch_setPower($deviceID, $powerOn) {
	HM_WriteValueBoolean($deviceID, "STATE", $powerOn);
}

if(isset($IPS_SENDER)) {
	if ($IPS_SENDER == "RunScript") {
		if($action == "poweroff") {
			HomeMaticSwitch_setPower($IPS_VARIABLE, false);
		} else if($action == "poweron") {
			HomeMaticSwitch_setPower($IPS_VARIABLE, true);
		} else {
			// toggle
			IPSLogger_Inf(__file__, "Unknown action ".$action.". Toggleing power.");
			HomeMaticSwitch_setPower($IPS_VARIABLE, !GetValue($IPS_VARIABLE));
		}
	} else {
		IPSLogger_Wrn(__file__, "Unhandled IPS_SENDER: ".$IPS_SENDER);
	}
}

?>
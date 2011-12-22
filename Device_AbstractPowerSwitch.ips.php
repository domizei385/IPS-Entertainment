<?
	function PowerSwitch_getSupportedCommands() {
		return array("PWR"	=> array(null, "ON", "OFF"));
	}
	
	function PowerSwitch_checkCommand($command, $parameter) {
		$supportedCommands = PowerSwitch_getSupportedCommands();
		
		if($command != null && !isset($supportedCommands[$command])) {
			IPSLogger_Err(__file__, "Unsupported command [".$command."]");
			return false;
		}
		if($parameter != null && !in_array($parameter, $supportedCommands[$command])) {
			IPSLogger_Err(__file__, "Unsupported parameter [".$parameter."] for command [".$command."]");
			return false;
		}
		return true;
	}
?>
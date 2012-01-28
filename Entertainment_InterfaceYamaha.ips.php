<?
	include_once "Entertainment.ips.php";
	include_once "Yamaha_Constants.ips.php";
	
	define("cmd_PREFIX", "PREFIX");
	define("cmd_SUFFIX", "SUFFIX");
	define("MAPPING_FUNCTION", "__function__");
	define("Message_Command_Paramter_Zone", '(Command=%s, Param=%s, Zone=%s) => %s');
	define("Message_Commands_Execution", 'Executing %d command%s took %.4f seconds.');

	function Yamaha_SendData_FPUTS($ip, $command, $zone = "Main_Zone", $type = "PUT") {
		$msg  = "<YAMAHA_AV cmd=\"$type\">";
		$msg .= "<$zone>";
		$msg .= $command;
		$msg .= "</$zone>";
		$msg .= '</YAMAHA_AV>';
		
		//IPSLogger_Com(__file__, 'Send Message to Yamaha: '.$msg.' (Command='.$command.')');
		return post_request('http://'.$ip.'/YamahaRemoteControl/ctrl', $msg);
	}
	
	function buildCommand($cmd, $parameter, $cmdMapping, $parameterMapping) {
		$command = $cmdMapping[$cmd][cmd_PREFIX];
		//IPSLogger_Com(__file__, "rawcmd:".print_r($cmd, true));
		//IPSLogger_Com(__file__, "rawMap:".print_r($cmdMapping[$cmd], true));
		if(isset($parameterMapping[$cmd])) {
			$thisParameterMapping = $parameterMapping[$cmd];
			if(isset($thisParameterMapping['__function__'])) {
				$command .= $parameterMapping[$cmd][MAPPING_FUNCTION]($parameter);
			} else {
				$command .= $parameterMapping[$cmd][$parameter];
			}
		}
		else {
			$command .= $parameter;
		}
		$command .= $cmdMapping[$cmd][cmd_SUFFIX];
		return $command;
	}

	function Yamaha_SendData($Parameters) {
		IPSLogger_Com(__file__, print_r($Parameters, true));
		
		$cmdMapping = array(
			"MUTE"		=> array(cmd_PREFIX => "<Volume><Mute>", cmd_SUFFIX => "</Mute></Volume>"),
			"PWR"		=> array(cmd_PREFIX => "<Power_Control><Power>", cmd_SUFFIX => "</Power></Power_Control>"),
			"VOL"		=> array(cmd_PREFIX => "<Volume><Lvl>", cmd_SUFFIX => "</Lvl></Volume>"),
			"INP"		=> array(cmd_PREFIX => "<Input><Input_Sel>", cmd_SUFFIX => "</Input_Sel></Input>"),
			"CHAN"		=> array(cmd_PREFIX => "<List_Control><Direct_Sel>", cmd_SUFFIX => "</Direct_Sel></List_Control>"),
			"xNETRADIO_LIST_PAGE"		=> array(cmd_PREFIX => "<List_Control><Jump_Line>", cmd_SUFFIX => "</Jump_Line></List_Control>"),
		);
		$parameterMapping = array(
			"PWR"	=> array("ON"	=> "On", "OFF" => "Standby"),
			"MUTE"	=> array("ON" => "On", "OFF" => "Off"),
			"VOL"	=> array(MAPPING_FUNCTION => function($val) {
				$rVal = $val * 10;
				return "<Val>$rVal</Val><Exp>1</Exp><Unit>dB</Unit>";
			}),
			"CHAN"	=> array(MAPPING_FUNCTION => function($val) {
				$iVal = intval($val);
				$item = $val % 8;
				return "Line_$item";
			}),
			"xNETRADIO_LIST_PAGE"	=> array(MAPPING_FUNCTION => function($val) {
				$iVal = intval($val);
				$page = $iVal;
				return $page;
			}),
		);
		
		$requiresSubCommands = array(
			'CHAN'	=> function($parameter, $cmdMapping, $parameterMapping) {
				$cmdArray = array();
				//IPSLogger_Com(__file__, "1st Command:".print_r($parameter, true));
				$command = buildCommand('xNETRADIO_LIST_PAGE', $parameter, $cmdMapping, $parameterMapping);
				$cmdArray[] = $command;
				// IPSLogger_Com(__file__, "2nd Command:".print_r($cmdArray, true));
				$command = buildCommand('CHAN', $parameter, $cmdMapping, $parameterMapping);
				$cmdArray[] = $command;
				return $cmdArray;
			}
		);
		
		$zoneMapping = array(
			"SYSTEM"	=> "System",
			"MAIN"		=> "Main_Zone",
			"ZONE2"		=> "Zone_2",
			"ZONE3"		=> "Zone_3",
			"ZONE4"		=> "Zone_4",
		);
		
		$hasArray = is_array($Parameters[1]);
		if($hasArray) {
			$parameterSet = $Parameters[1];
			if(isset($Parameters[2])) {
				$zone = $zoneMapping[$Parameters[2]];
			}
		} else {
			$parameterSet = array($Parameters[1] => $Parameters[2]);
			if(isset($Parameters[3])) {
				$zone = $zoneMapping[$Parameters[3]];
			}
		}
		
		$Devices = get_CommunicationConfiguration();
		$DeviceProperties = $Devices[$Parameters[0]];
		$ip = $DeviceProperties[c_Property_IPAddress];
		
		$timeStart = microtime(true);
		foreach($parameterSet as $cmd => $parameter) {
			if(!isset($zone)) {
				if($cmd == 'CHAN') {
					$zone = 'NET_RADIO';
				} else {
					$zone = $zoneMapping["MAIN"];
				}
			}
			
			$commandArray = array();
			if(isset($requiresSubCommands[$cmd])) {
				$func = $requiresSubCommands[$cmd];
				// TODO: determine why we need to resolve to 'CHAN'
				$cmds = $func($parameterSet['CHAN'], $cmdMapping, $parameterMapping);
				foreach($cmds as $newCmd) {
					array_push($commandArray, $newCmd);
				}
			} else {
				$command = $cmdMapping[$cmd][cmd_PREFIX];
				
				if(isset($parameterMapping[$cmd])) {
					$thisParameterMapping = $parameterMapping[$cmd];
					if(isset($thisParameterMapping['__function__'])) {
						$command .= $parameterMapping[$cmd][MAPPING_FUNCTION]($parameter);
					} else {
						$command .= $parameterMapping[$cmd][$parameter];
					}
				}
				else {
					$command .= $parameter;
				}
				$command .= $cmdMapping[$cmd][cmd_SUFFIX];
				$commandArray[] = $command;
			}
			
			foreach($commandArray as $commandItem) {
				IPSLogger_Com(__file__, sprintf(Message_Command_Paramter_Zone, $cmd, $parameter, $zone, $commandItem));
				$result = Yamaha_SendData_FPUTS($ip, $commandItem, $zone);
				// TODO: evaluate the return status
				IPSLogger_Com(__file__, print_r($result, true));
			}
		}
		$timeEnd = microtime(true);
		$elapsedSeconds = $timeEnd - $timeStart;
		$totalCommands = count($parameterSet);
		
		IPSLogger_Com(__file__, sprintf(Message_Commands_Execution, $totalCommands, $totalCommands <> 1 ? 's' : '', $elapsedSeconds));
	}
	
	function post_request($url, $data, $referer = '') {
		// parse the given URL
		$url = parse_url($url);
		
		if ($url['scheme'] != 'http') { 
			die('Error: Only HTTP request are supported !');
		}
		
		// extract host and path:
		$host = $url['host'];
		$path = $url['path'];
		
		// open a socket connection on port 80 - timeout: 30 sec
		$fp = fsockopen($host, 80, $errno, $errstr, 5);
		
		if ($fp) {
			fputs($fp, "POST $path HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");
			
			if ($referer != '')
				fputs($fp, "Referer: $referer\r\n");
			
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ". strlen($data) ."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $data);
			
			$result = ''; 
			while(!feof($fp)) {
				// receive the results of the request
				$result .= fgets($fp, 128);
			}
		}
		else { 
			return array(
				'status' => 'err', 
				'error' => "$errstr ($errno)"
			);
		}
		
		// close the socket connection:
		fclose($fp);
		
		// split the result header from the content
		$result = explode("\r\n\r\n", $result, 2);
	 
		$header = isset($result[0]) ? $result[0] : '';
		$content = isset($result[1]) ? $result[1] : '';
	 
		// return as structured array:
		return array(
			'status' => 'ok',
			'header' => $header,
			'content' => $content
		);
	}
	
		// ---------------------------------------------------------------------------------------------------------------------------
	function Yamaha_ReceiveData($RemoteControl, $Button, $MessageType) {
		//WinLIRC_ReceiveData_Translation(&$RemoteControl, &$Button);
		$Parameters = array(c_Comm_Yamaha, $RemoteControl, $Button);
		if (!Entertainment_ReceiveData($Parameters, $MessageType)) {
			if ($MessageType == c_MessageType_Action) {
				Yamaha_SendData($Parameters);
			}
		}
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Yamaha_ReceiveData_Webfront($RemoteControl, $Button) {
		IPSLogger_Com(__file__, "Received Data from WebFront, Control='$RemoteControl', Command='$Button'");
		Yamaha_ReceiveData($RemoteControl, $Button, c_MessageType_Action);
	}

	// ---------------------------------------------------------------------------------------------------------------------------
	function Yamaha_ReceiveData_Program($Program, $DeviceName) {
		IPSLogger_Com(__file__, "Received Program '$Program' from Webfront, Device='$DeviceName'");
		$ControlId = get_ControlIdByDeviceName($DeviceName, c_Control_Program);
		if ($Program == 'next') {
			Entertainment_SetProgramNext($ControlId);
		} else if ($Program == 'prev') {
			Entertainment_SetProgramPrev($ControlId);
		} else {
			Entertainment_SetProgram($ControlId, $Program);
		}
		return GetValue($ControlId);
	}

?>
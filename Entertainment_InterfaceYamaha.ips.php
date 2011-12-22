<?
	include_once "Entertainment.ips.php";
	include_once "Yamaha_Constants.ips.php";
	
	define("cmd_PREFIX", "PREFIX");
	define("cmd_SUFFIX", "SUFFIX");
	define("MAPPING_FUNCTION", "__function__");

	function Yamaha_SendData_FPUTS($ip, $command, $zone = "Main_Zone", $type = "PUT") {
		$msg  = "<YAMAHA_AV cmd=\"$type\">";
		$msg .= "<$zone>";
		$msg .= $command;
		$msg .= "</$zone>";
		$msg .= '</YAMAHA_AV>';
		
		//IPSLogger_Com(__file__, 'Send Message to Yamaha: '.$msg.' (Command='.$command.')');
		return post_request('http://'.$ip.'/YamahaRemoteControl/ctrl', $msg);
	}

	function Yamaha_SendData($Parameters) {
		//IPSLogger_Com(__file__, print_r($Parameters, true));
		
		$cmdMapping = array(
			"MUTE"		=> array(cmd_PREFIX => "<Volume><Mute>", cmd_SUFFIX => "</Mute></Volume>"),
			"PWR"		=> array(cmd_PREFIX => "<Power_Control><Power>", cmd_SUFFIX => "</Power></Power_Control>"),
			"VOL"		=> array(cmd_PREFIX => "<Volume><Lvl>", cmd_SUFFIX => "</Lvl></Volume>"),
			"INP"		=> array(cmd_PREFIX => "<Input><Input_Sel>", cmd_SUFFIX => "</Input_Sel></Input>"),
		);
		$parameterMapping = array(
			"PWR"	=> array("ON"	=> "On", "OFF" => "Standby"),
			"MUTE"	=> array("ON" => "On", "OFF" => "Off"),
			"VOL"	=> array(MAPPING_FUNCTION => function($val) {
				$rVal = $val * 10;
				return "<Val>$rVal</Val><Exp>1</Exp><Unit>dB</Unit>";
			}),
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
		
		if(!isset($zone)) {
			$zone = $zoneMapping["MAIN"];
		}
		
		$Devices = get_CommunicationConfiguration();
		$DeviceProperties = $Devices[$Parameters[0]];
		$ip = $DeviceProperties[c_Property_IPAddress];
		
		foreach($parameterSet as $cmd => $parameter) {
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
			
			IPSLogger_Com(__file__, '(Command='.$command.', Param='.$parameter.', Zone='.$zone.')');
			Yamaha_SendData_FPUTS($ip, $command, $zone);
		}
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
		$fp = fsockopen($host, 80, $errno, $errstr, 30);
		
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

?>
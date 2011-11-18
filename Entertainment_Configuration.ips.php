<?
	include_once "Entertainment_Constants.ips.php";

	define ("c_Comm_WinLIRC",							"WinLIRC");
	define ("c_Comm_Onkyo",								"Onkyo");
	define ("c_Comm_NetPlayer",						"NetPlayer");

	define ("c_Room_Wellness",							"Wellness");
	define ("c_Room_Sauna",								"Sauna");
	define ("c_Room_LivingRoom",						"Wohnzimmer");

	define ("c_Device_NetPlayer",						"NetPlayer");
	define ("c_Device_YamahaMain",					"YamahaMain");
	define ("c_Device_YamahaTuner",					"YamahaTuner");
	define ("c_Device_OnkyoMain",						"OnkyoMain");
	define ("c_Device_OnkyoZone2",					"OnkyoZone2");
	define ("c_Device_OnkyoTuner",					"OnkyoTuner");
	define ("c_Device_PanasonicVCR",					"PanasonicVCR");
	define ("c_Device_PanasonicBD",					"PanasonicBD");
	define ("c_Device_TopfieldSat",					"TopfieldSat");
	define ("c_Device_PhilipsTV",						"PhilipsTV");
	define ("c_Device_SanyoBeamer",					"SanyoBeamer");

	define ("c_ID_WebfrontConfiguration", 57215 );

	// ========================================================================================================================
	// Defintion of Communication Data
	// ========================================================================================================================
	function get_CommunicationConfiguration () {
	   return array (
			c_Comm_WinLIRC => array (
				c_Property_Script 				=> 'Entertainment_InterfaceWinLIRC.ips.php',
				c_Property_FunctionSend 		=> 'WinLIRC_SendData',
				c_Property_FunctionVariable 	=> 'WinLIRC_ReceiveData_Variable',
				c_Property_VariableId         => "",
				c_Property_ModuleId     		=> "",
				c_Property_MessageTypes       => array('philipstv' 						=> c_MessageType_Info, /*Default is MessageType Action*/
																	),
				c_Property_InpTranslationList	=> array('sauna' 								=> 'onkyotuner',
																	'sauna.volumeplus'				=> 'onkyoreceiver.volumeplusz2',
																	'sauna.volumeminus'				=> 'onkyoreceiver.volumeminusz2',
																	'sauna.mute'						=> 'onkyoreceiver.mutez2',
																	'sauna.power'     				=> 'onkyoreceiver.zone2power',
																	),
				c_Property_OutTranslationList => array('yamahareceiver.volplus' 		=> array('*.*','*.*','*.*'),
																	'yamahareceiver.volminus' 		=> array('*.*','*.*','*.*'),
																	'onkyoreceiver.volumeplusz2' 	=> array('*.*','*.*','*.*'),
																	'onkyoreceiver.volumeminusz2' => array('*.*','*.*','*.*'),
																	'philipstv2'						=> array('philipstv.*'),
																	'philipstv.p1'						=> array('*.1', '*.ok'),
																	'philipstv.p2'						=> array('*.2', '*.ok'),
																	'philipstv.p3'						=> array('*.3', '*.ok'),
																	'philipstv.p4'						=> array('*.4', '*.ok'),
																	'philipstv.p5'						=> array('*.5', '*.ok'),
																	'philipstv.p6'						=> array('*.6', '*.ok'),
																	'philipstv.p7'						=> array('*.7', '*.ok'),
																	'philipstv.p8'						=> array('*.8', '*.ok'),
																	'philipstv.p9'						=> array('*.9', '*.ok'),
																	'philipstv.p10'					=> array('*.1','*.0','*.ok'),
																	'philipstv.p11'					=> array('*.1','*.1','*.ok'),
																	'philipstv.p12'					=> array('*.1','*.2','*.ok'),
																	'topfieldsat.p1'					=> array('*.1', '*.ok'),
																	'topfieldsat.p2'					=> array('*.2', '*.ok'),
																	'topfieldsat.p3'					=> array('*.3', '*.ok'),
																	'topfieldsat.p4'					=> array('*.4', '*.ok'),
																	'topfieldsat.p5'					=> array('*.5', '*.ok'),
																	'topfieldsat.p6'					=> array('*.6', '*.ok'),
																	'topfieldsat.p7'					=> array('*.7', '*.ok'),
																	'topfieldsat.p8'					=> array('*.8', '*.ok'),
																	'topfieldsat.p9'					=> array('*.9', '*.ok'),
																	'topfieldsat.p10'					=> array('*.1','*.0','*.ok'),
																	'topfieldsat.p11'					=> array('*.1','*.1','*.ok'),
																	'topfieldsat.p12'					=> array('*.1','*.2','*.ok'),
																	),
				c_Property_PowerDelay         => array('yamahareceiver'  => 2
																	),
			),
			c_Comm_Onkyo => array (
				c_Property_Script 				=> 'Entertainment_InterfaceOnkyo.ips.php',
				c_Property_FunctionSend 		=> 'Onkyo_SendData',
				c_Property_FunctionRegister 	=> 'Onkyo_ReceiveData_Register',
				c_Property_RegisterId     		=> "",
				c_Property_ModuleId     		=> "",
				c_Property_IPAddress          => '',
				c_Property_Timeout				=> 30,
			),
			c_Comm_NetPlayer => array (
				c_Property_Script 				=> 'Entertainment_InterfaceNetPlayer.ips.php',
				c_Property_FunctionSend 		=> 'NetPlayer_SendData',
			),
		);
	}

	// ========================================================================================================================
	// Defintion of Room Configuration
	// ========================================================================================================================
	function get_RoomConfiguration () {
	   return array (
	      // -------------------------------------------------------------------------------------------------------
			c_Room_LivingRoom => array(
				c_Control_RoomPower 		=> array(c_Property_Name 	=> 'Power'),
			   c_Control_Muting 			=> array(c_Property_Name 	=> 'Mute'),
			   c_Control_RemoteVolume 	=> array(c_Property_Name 	=> 'Volume Control'),
			   c_Control_iRemoteVolume => array(c_Property_Name 	=> 'Volume iPhone'),
		      c_Control_Source 			=> array(c_Property_Name 	=> 'Source'),
			   c_Control_RemoteSource 	=> array(c_Property_Name 	=> 'Source Control'),
			   c_Control_iRemoteSource => array(c_Property_Name 	=> 'Remote iPhone'),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Room_Wellness => array(
				c_Control_RoomPower 		=> array(c_Property_Name 	=> 'Power'),
		      c_Control_Muting 			=> array(c_Property_Name 	=> 'Mute'),
		      c_Control_Volume 			=> array(c_Property_Name 	=> 'Volume'),
		      c_Control_Source 			=> array(c_Property_Name 	=> 'Source'),
		      c_Control_RemoteSource 	=> array(c_Property_Name 	=> 'Source Control'),
		      c_Control_iRemoteSource => array(c_Property_Name 	=> 'Remote iPhone'),
		      c_Control_Mode 			=> array(c_Property_Name 	=> 'Listening Mode'),
		   ),
	      // -------------------------------------------------------------------------------------------------------
			c_Room_Sauna => array(
				c_Control_RoomPower 		=> array(c_Property_Name 	=> 'Power'),
		      c_Control_Muting 			=> array(c_Property_Name 	=> 'Mute'),
		      c_Control_Volume 			=> array(c_Property_Name 	=> 'Volume'),
	         c_Control_Source 			=> array(c_Property_Name 	=> 'Source'),
		      c_Control_RemoteSource 	=> array(c_Property_Name 	=> 'Source Control'),
		      c_Control_iRemoteSource => array(c_Property_Name 	=> 'Remote iPhone'),
			),
	      // -------------------------------------------------------------------------------------------------------
		);
	}
	
	// ========================================================================================================================
	// Defintion of Device Configuration
	// ========================================================================================================================
	function get_DeviceConfiguration () {
		return array (
	      // -------------------------------------------------------------------------------------------------------
			c_Device_YamahaMain 	=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_PowerDelay   => 2,
					c_Property_CommPower		=> array(c_Comm_WinLIRC, 'yamahareceiver', 'power'),
					c_Property_CommPowerOff	=> array(c_Comm_WinLIRC, 'yamahareceiver', 'poweroff'),
					c_Property_CommPowerOn	=> array(c_Comm_WinLIRC, 'yamahareceiver', 'poweron'),
				),
				c_Control_RemoteVolume 	=> array(
					c_Property_Name    		=> 'Volume Control',
					c_Property_Names       	=> array('src="../user/Entertainment/Remote_YamahaVolume.php"  height=38px'),
				),
				c_Control_Muting 			=> array(
					c_Property_Name 			=> 'Mute',
					c_Property_CommMuteOn 		=> array(c_Comm_WinLIRC, 'yamahareceiver', 'muteon'),
					c_Property_CommMuteOff 		=> array(c_Comm_WinLIRC, 'yamahareceiver', 'muteoff'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_YamahaTuner 	=> array(
				c_Property_PowerDelay   => c_Device_YamahaMain,
				c_Control_RemoteSource 	=> array(
					c_Property_Name 			=> 'Source Control',
					c_Property_Names       	=> array('src="../user/Entertainment/Remote_YamahaTuner.php"  height=110px'),
				),
				c_Control_iRemoteSource 	=> array(
					c_Property_Name 			=> 'iPhone Source Control',
					c_Property_Names       	=> array('src="../user/Entertainment/iRemote_YamahaTuner.php"'),
				),
				c_Control_Program 	=> array(
					c_Property_Name 			=> 'Program',
					c_Property_CommPrg		=> array(c_Comm_WinLIRC, 'yamahareceiver', c_Template_Code),
					c_Property_CommPrgPrev	=> array(c_Comm_WinLIRC, 'yamahareceiver', 'presetlast'),
					c_Property_CommPrgNext	=> array(c_Comm_WinLIRC, 'yamahareceiver', 'presetnext'),
					c_Property_Codes			=> array('0', '1', '2', '3', '4', '5', '6', '7'),
					c_Property_Names			=> array('Arabella','Antenne','Kronehit Radio','Radio Wien','Radio Noe','OE 3','88.6','Hit FM'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_PhilipsTV 		=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_CommPower		=> array(c_Comm_WinLIRC, 'philipstv', 'power'),
					c_Property_CommPower2	=> array(c_Comm_WinLIRC, 'philipstv2', 'power'),
					c_Property_CommPowerOn	=> array(c_Comm_WinLIRC, 'philipstv', 'poweron'),
					c_Property_CommPowerOff	=> array(c_Comm_WinLIRC, 'philipstv', 'poweroff'),
				),
				c_Control_Muting 			=> array(
					c_Property_Name 			=> 'Mute',
					c_Property_CommMute 		=> array(c_Comm_WinLIRC, 'philipstv', 'mute'),
					c_Property_CommMute2 	=> array(c_Comm_WinLIRC, 'philipstv2', 'mute'),
				),
				c_Control_RemoteVolume 	=> array(
					c_Property_Name 			=> 'Volume Control',
					c_Property_Names       	=> array('src="../user/Entertainment/Remote_PhilipsTVVolume.php"  height=38px'),
				),
				c_Control_RemoteSource 	=> array(
					c_Property_Name 			=> 'Source Control',
					c_Property_CommRemSrc	=> array(c_Comm_WinLIRC, 'philipstvtype', c_Template_Value),
					c_Property_Names       	=> array('src="../user/Entertainment/Remote_PhilipsTVProgram_Simple.php"  height=182px',
					                                 'src="../user/Entertainment/Remote_PhilipsTVProgram_Advanced.php"  height=208px'),
				),
				c_Control_RemoteSourceType => array(
					c_Property_Name 			=> 'Source ControlType',
				),
				c_Control_iRemoteSource 	=> array(
					c_Property_Name 			=> 'iPhone Source Control',
					c_Property_Names       	=> array('src="../user/Entertainment/iRemote_PhilipsTV.php"'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_SanyoBeamer 		=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_CommPower		=> array(c_Comm_WinLIRC, 'sanyobeamer', 'power'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_PanasonicVCR 	=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_CommPower		=> array(c_Comm_WinLIRC, 'panasonicrc2', 'power'),
				),
				c_Control_RemoteSource 	=> array(
					c_Property_Name 			=> 'Source Control',
					c_Property_CommRemSrc	=> array(c_Comm_WinLIRC, 'panasonicrc2type', c_Template_Value),
					c_Property_Names       	=> array('src="../user/Entertainment/Remote_PanasonicVCR_Simple.php"  height=170px',
					                                 'src="../user/Entertainment/Remote_PanasonicVCR_Watch.php"   height=170px',
					                                 'src="../user/Entertainment/Remote_PanasonicVCR_Guide.php"   height=170px'),
				),
				c_Control_RemoteSourceType => array(
					c_Property_Name 			=> 'Source Control Type',
				),
				c_Control_iRemoteSource 		=> array(
					c_Property_Name 				=> 'iPhone Source Control',
					c_Property_Names       		=> array('src="../user/Entertainment/iRemote_PanasonicVCR.php"'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_PanasonicBD 	=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_CommPower		=> array(c_Comm_WinLIRC, 'panasonicrc', 'power'),
				),
				c_Control_RemoteSource 	=> array(
					c_Property_Name 			=> 'Source Control',
					c_Property_Names       	=> array('src="../user/Entertainment/Remote_PanasonicBD.php"  height=170px'),
				),
				c_Control_iRemoteSource 		=> array(
					c_Property_Name 				=> 'iPhone Source Control',
					c_Property_Names       		=> array('src="../user/Entertainment/iRemote_PanasonicBD.php"'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_TopfieldSat 	=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_CommPower		=> array(c_Comm_WinLIRC, 'topfieldsat', 'power'),
				),
				c_Control_RemoteSource 	=> array(
					c_Property_Name 			=> 'Source Control',
					c_Property_CommRemSrc	=> array(c_Comm_WinLIRC, 'topfieldsattype', c_Template_Value),
					c_Property_Names       	=> array('src="../user/Entertainment/Remote_TopfieldSat_Simple.php" height=148px',
					                                 'src="../user/Entertainment/Remote_TopfieldSat_Advanced.php" height=185px'),
				),
				c_Control_RemoteSourceType => array(
					c_Property_Name 			=> 'Source Control Type',
				),
				c_Control_iRemoteSource 		=> array(
					c_Property_Name 				=> 'iPhone Source Control',
					c_Property_Names       		=> array('src="../user/Entertainment/iRemote_TopfieldSat.php"'),
				),
				c_Control_Program 	=> array(
					c_Property_Name 			=> 'Program',
					c_Property_CommPrg		=> array(c_Comm_WinLIRC, 'topfieldsat', c_Template_Code),
					c_Property_CommPrg2		=> array(c_Comm_WinLIRC, 'topfieldsat', c_Template_Code2),
					c_Property_CommPrgPrev	=> array(c_Comm_WinLIRC, 'topfieldsat', 'programprev'),
					c_Property_CommPrgNext	=> array(c_Comm_WinLIRC, 'topfieldsat', 'programnext'),
					c_Property_Codes			=> array('p1','p2','p3','p4','p5','p6','p7','p8','p9','p10','p11','p12'),
					c_Property_Names			=> array('ORF 1','ORF 2','ARD','ZDF','RTL','Sat 1','Pro 7','RTL 2','Kabel 1','VOX','N24','Sat 1'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_OnkyoMain 		=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 				=> 'Power',
					c_Property_CommPowerOn		=> array(c_Comm_WinLIRC, 'onkyoreceiver', 'mainpoweron'),
					c_Property_CommPowerOff		=> array(c_Comm_WinLIRC, 'onkyoreceiver', 'mainpoweroff'),
					c_Property_ConnectSocket	=> c_Comm_Onkyo,
					c_Property_CommPowerOn2		=> array(c_Comm_Onkyo, 'PWR', '01'),
					c_Property_CommPowerOff2	=> array(c_Comm_Onkyo, 'PWR', '00'),
				),
				c_Control_Muting => array(
					c_Property_Name 				=> 'Mute',
					c_Property_CommMute 			=> array(c_Comm_WinLIRC, 'onkyoreceiver', 'mute'),
					c_Property_CommMuteOn 		=> array(c_Comm_Onkyo, 'AMT', '01'),
					c_Property_CommMuteOff 		=> array(c_Comm_Onkyo, 'AMT', '00'),
				),
				c_Control_Volume => array(
					c_Property_Name 				=> 'Volume',
					c_Property_MinValue        => 0,
					c_Property_MaxValue        => 100,
					c_Property_Limit        	=> 80,
					c_Property_CommVol			=> array(c_Comm_Onkyo, 'MVL', c_Template_Value),
				),
				c_Control_Mode => array(
					c_Property_Name 				=> 'Listening Mode',
					c_Property_CommMode			=> array(c_Comm_Onkyo, 'LMD', c_Template_Code),
					c_Property_Names				=> array(
						"Stereo","Direct", "Pure Audio","Surround", "Musical", "Orchestra", "Unplugged", "Studio-Mix",
						"TV Logic","All Ch Stereo","Theater-Dimensional", "Enhanced 7/Enhance","Mono","Full Mono","DTS Surround Sens",
						"Audyssey DSX","Straight Decode*1","Dolby EX*2","Dolby EX+DSX","THX Cinema","THX Surround EX","THX Music",
						"THX Games","PLIIx Movie","PLIIx Music","PLIIx Game","PLIIx THX Cinema","PLIIx THX Games","PLIIx THX Music",
					),
					c_Property_Codes				=> array(
						"UP" , "DOWN", "MOVIE", "MUSIC" , "GAME", "00", "01",
						"11", "02", "03", "04", "05", "06", "08", "09", "0A",
						"0B", "0C", "0D", "0E", "0F", "13", "15",
						"16", "40", "41", "A7", "42", "43", "44",
						"45", "80", "81", "86", "84", "89", "8B",
						"A0", "A1", "A2", "8A", "85", "82",
						"83", "8C", "91", "92", "A3", "A4",
						"90", "94", "95", "96", "97",
						"98", "99", "50", "51", "52"
					),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_OnkyoZone2 => array(
				c_Control_DevicePower 		=> array(
					c_Property_Name 				=> 'Power',
					c_Property_CommPower			=> array(c_Comm_WinLIRC, 'onkyoreceiver', 'zone2power'),
					c_Property_CommPowerOn		=> array(c_Comm_WinLIRC, 'onkyoreceiver', 'zone2poweron'),
					c_Property_CommPowerOff		=> array(c_Comm_WinLIRC, 'onkyoreceiver', 'zone2poweroff'),
					c_Property_ConnectSocket	=> c_Comm_Onkyo,
					c_Property_CommPowerOn2		=> array(c_Comm_Onkyo, 'ZPW', '01'),
					c_Property_CommPowerOff2	=> array(c_Comm_Onkyo, 'ZPW', '00'),
				),
				c_Control_Muting 				=> array(
					c_Property_Name 				=> 'Mute',
					c_Property_CommMuteOn 		=> array(c_Comm_Onkyo, 'ZMT', '01'),
					c_Property_CommMuteOff 		=> array(c_Comm_Onkyo, 'ZMT', '00'),
					c_Property_CommMute        => array(c_Comm_WinLIRC, 'onkyoreceiver', 'mutez2'),
				),
				c_Control_Volume 				=> array(
					c_Property_Name 				=> 'Volume',
					c_Property_MinValue        => 0,
					c_Property_MaxValue        => 100,
					c_Property_Limit        	=> 75,
					c_Property_CommVol 			=> array(c_Comm_Onkyo, 'ZVL', c_Template_Value),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_OnkyoTuner 			=> array(
				c_Control_RemoteSource 		=> array(
					c_Property_Name 				=> 'Source Control',
					c_Property_Names       		=> array('src="../user/Entertainment/Remote_OnkyoTuner.php"  height=110px'),
				),
				c_Control_iRemoteSource 		=> array(
					c_Property_Name 				=> 'iPhone Source Control',
					c_Property_Names       		=> array('src="../user/Entertainment/iRemote_OnkyoTuner.php"'),
				),
				c_Control_Program 	=> array(
					c_Property_Name 			=> 'Program',
					c_Property_CommPrg		=> array(c_Comm_Onkyo, 'PRS', c_Template_Code),
					c_Property_CommPrg2		=> array(c_Comm_WinLIRC, 'onkyotuner', c_Template_Code2),
					c_Property_CommPrgPrev	=> array(c_Comm_Onkyo, 'PRS', 'DOWN'),
					c_Property_CommPrgPrev2	=> array(c_Comm_WinLIRC, 'onkyotuner', 'up'),
					c_Property_CommPrgNext	=> array(c_Comm_Onkyo, 'PRS', 'UP'),
					c_Property_CommPrgNext2	=> array(c_Comm_WinLIRC, 'onkyotuner', 'down'),
					c_Property_Codes			=> array('01', '02', '03', '04', '05', '06', '07', '08'),
					c_Property_Codes2			=> array('p1','p2','p3','p4','p5','p6','p7','p8'),
					c_Property_Names			=> array('Arabella','Antenne','Kronehit Radio','Radio Wien','Radio Noe','OE 3','88.6','Hit FM'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_NetPlayer 		=> array(
			   c_Property_Installation => 'NetPlayer_Installation.ips.php',
			   c_Property_Directory 	=> 'D:/Music/',
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_CommPowerOn		=> array(c_Comm_NetPlayer, 'netplayer', 'poweron'),
					c_Property_CommPowerOff		=> array(c_Comm_NetPlayer, 'netplayer', 'poweroff'),
				),
				c_Control_RemoteSource 	=> array(
					c_Property_Name 			=> 'Source Control',
					c_Property_CommRemSrc	=> array(c_Comm_NetPlayer, 'netplayertype', c_Template_Value),
					c_Property_Names       	=> array('src="../user/Entertainment/NetPlayer_MP3Control.php"    height=255px',
					                                 'src="../user/Entertainment/NetPlayer_MP3Selection.php"  height=250px',
					                                 'src="../user/Entertainment/NetPlayer_RadioControl.php"  height=250px'),
				),
				c_Control_RemoteSourceType => array(
					c_Property_Name 			=> 'Source Control Type',
				),
			),
	      // -------------------------------------------------------------------------------------------------------
		);
	}
	
	// ========================================================================================================================
	// Defintion of Source Configuration
	// ========================================================================================================================
	function get_SourceConfiguration() {
	   return array (
	      // -------------------------------------------------------------------------------------------------------
			c_Room_LivingRoom => array(
				0 	=> array(
					c_Property_Name 	=> 'TV',
					c_Property_Output => 	array(c_Property_Device 	=> c_Device_PhilipsTV,
														 	c_Property_CommSrc	=> array(c_Comm_WinLIRC, 'philipstv', 'tv'),
														 	c_Property_CommSrc2	=> array(c_Comm_WinLIRC, 'philipstv2', 'tv')),
				),
				1 	=> array(
					c_Property_Name 	=> 'VCR',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_PanasonicVCR),
					c_Property_Output => 	array(c_Property_Device 	=> c_Device_PhilipsTV,
														 	c_Property_CommSrc	=> array(c_Comm_WinLIRC, 'philipstv', 'hdmiside'),
														 	c_Property_CommSrc2	=> array(c_Comm_WinLIRC, 'philipstv2', 'hdmiside')),
				),
				2 	=> array(
					c_Property_Name 	=> 'Tuner',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_YamahaTuner),
		 			c_Property_Output => 	array(c_Property_Device 	=> c_Device_YamahaMain,
															c_Property_CommSrc	=> array(c_Comm_WinLIRC, 'yamahareceiver', 'tuner')),
				),
				3 	=> array(
					c_Property_Name 	=> 'Net Player',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_NetPlayer),
		 			c_Property_Output => 	array(c_Property_Device 	=> c_Device_YamahaMain,
															c_Property_CommSrc	=> array(c_Comm_WinLIRC, 'yamahareceiver', 'cd')),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Room_Wellness => array(
				0 	=> array(
					c_Property_Name 	=> 'Kabel',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_TopfieldSat),
		 			c_Property_Switch => 	array(c_Property_Device 	=> c_Device_OnkyoMain,
															c_Property_CommSrc	=> array(c_Comm_Onkyo, 'SLI', '01')),
					c_Property_Output => 	array(c_Property_Device 	=> c_Device_SanyoBeamer)
					),
				1 	=> array(
					c_Property_Name 	=> 'Bluray',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_PanasonicBD),
		 			c_Property_Switch => 	array(c_Property_Device 	=> c_Device_OnkyoMain,
															c_Property_CommSrc	=> array(c_Comm_Onkyo, 'SLI', '10')),
					c_Property_Output => 	array(c_Property_Device 	=> c_Device_SanyoBeamer)
					),
				2 	=> array(
					c_Property_Name 	=> 'VCR',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_PanasonicVCR),
		 			c_Property_Switch => 	array(c_Property_Device 	=> c_Device_OnkyoMain,
															c_Property_CommSrc	=> array(c_Comm_Onkyo, 'SLI', '00')),
					c_Property_Output => 	array(c_Property_Device 	=> c_Device_SanyoBeamer)
					),
				3 	=> array(
					c_Property_Name 	=> 'OnkyoTuner',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_OnkyoTuner),
		 			c_Property_Output => 	array(c_Property_Device 	=> c_Device_OnkyoMain,
															c_Property_CommSrc	=> array(c_Comm_Onkyo, 'SLI', '24')),
				),
				4 	=> array(
					c_Property_Name 	=> 'Net Player',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_NetPlayer),
		 			c_Property_Output => 	array(c_Property_Device 	=> c_Device_OnkyoMain,
															c_Property_CommSrc	=> array(c_Comm_Onkyo, 'SLI', '23')),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Room_Sauna => array(
				0 	=> array(
					c_Property_Name 	=> 'OnkyoTuner',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_OnkyoTuner),
		 			c_Property_Output => 	array(c_Property_Device 	=> c_Device_OnkyoZone2,
															c_Property_CommSrc	=> array(c_Comm_Onkyo, 'SLZ', '24')),
				),
				1 	=> array(
					c_Property_Name 	=> 'Net Player',
					c_Property_Input => 		array(c_Property_Device 	=> c_Device_NetPlayer),
		 			c_Property_Output => 	array(c_Property_Device 	=> c_Device_OnkyoZone2,
															c_Property_CommSrc	=> array(c_Comm_Onkyo, 'SLZ', '23')),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
		);
	}

?>
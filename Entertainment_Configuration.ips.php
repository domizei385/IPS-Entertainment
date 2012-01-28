<?
	include_once "Entertainment_Constants.ips.php";
	include_once "Yamaha_Constants.ips.php";

	define ("c_Comm_Yamaha",							"Yamaha");
	define ("c_Comm_PowerSwitch",						"SamsungTVPower");
	define ("c_Comm_SubwooferBack",						"SubwooferBackPower");

	define ("c_Room_LivingRoom",						"Wohnzimmer");
	define ("c_Room_Kitchen",							"Küche");

	define ("c_Device_YamahaMain",						"YamahaMain");
	define ("c_Device_YamahaZone2",						"YamahaZone2");
	define ("c_Device_Yamaha_NetRadio",					"YamahaNetRadio");
	
	define ("c_Device_SubwooferBack",					"YamahaSubWoofer");
	define ("c_Device_SamsungTV",						"SamsungTV");

	define ("c_Device_SubwooferBack_ID",				23914);
	define ("c_Device_SamsungTV_ID",					12814);

	define ("c_ID_WebfrontConfiguration", 57374 );

	// ========================================================================================================================
	// Defintion of Communication Data
	// ========================================================================================================================
	function get_CommunicationConfiguration () {
	   return array (
			c_Comm_Yamaha => array (
				c_Property_Script				=> 'Entertainment_InterfaceYamaha.ips.php',
				c_Property_FunctionSend 		=> 'Yamaha_SendData',
				c_Property_FunctionRegister 	=> '',
				c_Property_VariableId 			=> '',
				c_Property_ModuleId     		=> 37529,
				c_Property_IPAddress			=> '192.168.178.21',
				c_Property_Timeout				=> 1,
			),
			c_Comm_HomeMaticSwitch => array (
				c_Property_Script				=> 'Entertainment_InterfaceHomeMaticSwitch.ips.php',
				c_Property_FunctionSend 		=> 'HomeMaticSwitch_TogglePower',
			),
			c_Comm_NetIO230 => array (
				c_Property_Script				=> 'Entertainment_InterfaceNetIO230.ips.php',
				c_Property_FunctionSend 		=> 'NetIO230_TogglePower',
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
				c_Control_Volume			=> array(c_Property_Name	=> 'Volume'),
				// c_Control_RemoteVolume		=> array(c_Property_Name 	=> 'Volume Control'),
				// c_Control_iRemoteVolume		=> array(c_Property_Name 	=> 'Volume iPhone'),
				c_Control_Source 			=> array(c_Property_Name 	=> 'Source'),
				c_Control_RemoteSource		=> array(c_Property_Name 	=> 'Source Control'),
				//c_Control_iRemoteSource		=> array(c_Property_Name 	=> 'Remote iPhone'),
			),
			// -------------------------------------------------------------------------------------------------------
			c_Room_Kitchen => array(
				c_Control_RoomPower 		=> array(c_Property_Name 	=> 'Power'),
				c_Control_Muting 			=> array(c_Property_Name 	=> 'Mute'),
				c_Control_Volume			=> array(c_Property_Name	=> 'Volume'),
				c_Control_Source 			=> array(c_Property_Name 	=> 'Source'),
				//c_Control_RemoteSource		=> array(c_Property_Name 	=> 'Source Control'),
				//c_Control_iRemoteSource		=> array(c_Property_Name 	=> 'Remote iPhone'),
			),
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
					c_Property_PowerDelay		=> 150,
					c_Property_CommPower		=> array(c_Comm_Yamaha, 'PWR', '?'),
					c_Property_CommPowerOff		=> array(c_Comm_Yamaha, 'PWR', 'OFF'),
					c_Property_CommPowerOn		=> array(c_Comm_Yamaha, 'PWR', 'ON'),
				),
				c_Control_Volume		=> array(
					c_Property_Name				=> 'Volume',
					c_Property_CommVol			=> array(c_Comm_Yamaha, 'VOL', c_Template_Value),
					c_Property_MinValue			=> -60,
					c_Property_MaxValue			=> -15,
				),
				c_Control_RemoteVolume 	=> array(
					c_Property_Name    		=> 'Volume Control',
					c_Property_Names       	=> array('src="../user/Entertainment/Remote_YamahaVolume.php"  height=38px'),
				),
				c_Control_RemoteSource	=> array(
					c_Property_Name 			=> 'Source Control',
					c_Property_Names       		=> array('src="../user/Entertainment/Remote_YamahaEmpty.php" height=10px'),
				),
				c_Control_Muting 			=> array(
					c_Property_Name 			=> 'Mute',
					c_Property_CommMuteOn 		=> array(c_Comm_Yamaha, 'MUTE', 'ON'),
					c_Property_CommMuteOff 		=> array(c_Comm_Yamaha, 'MUTE', 'OFF'),
				),
			),
			// -------------------------------------------------------------------------------------------------------
			c_Device_YamahaZone2 	=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_PowerDelay		=> 150,
					c_Property_CommPowerOff	=> array(c_Comm_Yamaha, 'PWR', 'OFF', 'ZONE2'),
					c_Property_CommPowerOn	=> array(c_Comm_Yamaha, 'PWR', 'ON', 'ZONE2'),
				),
				c_Control_Volume		=> array(
					c_Property_Name				=> 'Volume',
					c_Property_CommVol			=> array(c_Comm_Yamaha, 'VOL', c_Template_Value, 'ZONE2'),
					c_Property_MinValue			=> -20,
					c_Property_MaxValue			=> 10,
				),
				c_Control_Muting 			=> array(
					c_Property_Name 			=> 'Mute',
					c_Property_CommMuteOn 		=> array(c_Comm_Yamaha, 'MUTE', 'ON', 'ZONE2'),
					c_Property_CommMuteOff 		=> array(c_Comm_Yamaha, 'MUTE', 'OFF', 'ZONE2'),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
			c_Device_Yamaha_NetRadio 	=> array(
				c_Property_PowerDelay	=> c_Device_YamahaMain,
				c_Control_RemoteSource	=> array(
					c_Property_Name 			=> 'Source Control',
					c_Property_Names       		=> array('src="../user/Entertainment/Remote_YamahaNetRadio.php" height=150px'),
				),
				c_Control_iRemoteSource 	=> array(
					c_Property_Name 			=> 'iPhone Source Control',
					c_Property_Names       		=> array('src="../user/Entertainment/iRemote_YamahaNetRadio.php"'),
				),
				c_Control_Program 	=> array(
					c_Property_Name 			=> 'Program',
					c_Property_CommPrg			=> array(c_Comm_Yamaha, 'CHAN', c_Template_Code),
					c_Property_CommPrgPrev		=> array(c_Comm_Yamaha, 'CHAN', 'prev'),
					c_Property_CommPrgNext		=> array(c_Comm_Yamaha, 'CHAN', 'next'),
					c_Property_Codes			=> array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13'),
					c_Property_Names			=> array('NDR2', 'Technobase FM', '104.6 RTL', 'The End 107.9FM', 'NRJ Berlin', 'Fritz','1.fm Top 40','KISS FM 102.7','Ambient Meditation Music','Calm Radio - Sleep','Nirvana Radio Relaxation','Chroma Radio Nature','Healing Music Radio'),
				),
			),
			// -------------------------------------------------------------------------------------------------------
			c_Device_SubwooferBack 	=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_PowerDelay		=> 150,
					c_Property_CommPowerOff		=> array(c_Comm_HomeMaticSwitch, c_Device_SubwooferBack_ID, 'PWR', 'OFF'),
					c_Property_CommPowerOn		=> array(c_Comm_HomeMaticSwitch, c_Device_SubwooferBack_ID, 'PWR', 'ON'),
				),
			),
			// -------------------------------------------------------------------------------------------------------
			c_Device_SamsungTV 		=> array(
				c_Control_DevicePower 	=> array(
					c_Property_Name 			=> 'Power',
					c_Property_PowerDelay		=>150,
					c_Property_CommPowerOn		=> array(c_Comm_NetIO230, c_Device_SamsungTV_ID, 'PWR', 'ON'),
					c_Property_CommPowerOff		=> array(c_Comm_NetIO230, c_Device_SamsungTV_ID, 'PWR', 'OFF'),
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
					c_Property_Name 	=> 'NetRadio',
					c_Property_Input	=> array(c_Property_Device 	=> c_Device_Yamaha_NetRadio),
		 			c_Property_Output	=> 	array(
												array(
													c_Property_Device 	=> c_Device_YamahaMain,
													c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_NET_RADIO, 'VOL' => -45)),
												),
												array(
													c_Property_Device 	=> c_Device_SubwooferBack,
												),
											),
				),
				1 	=> array(
					c_Property_Name 	=> 'TV',
					c_Property_Input	=> array(
											c_Property_Device 	=> c_Device_SamsungTV,
												),
		 			c_Property_Output	=> array(
											c_Property_Device 	=> c_Device_YamahaMain,
											c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_AUDIO1, 'VOL' => -40))
					),
				),
				2 	=> array(
					c_Property_Name 	=> 'BluRay',
		 			c_Property_Switch	=> array(
												array(
													c_Property_Device 	=> c_Device_YamahaMain,
													c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_AV1, 'VOL' => -37))
												),
												array(
													c_Property_Device 	=> c_Device_SubwooferBack,
												),
											),
					c_Property_Output	=> array(
											c_Property_Device 	=> c_Device_SamsungTV,
					),
				),
				3 	=> array(
					c_Property_Name 	=> 'AppleTV',
		 			c_Property_Switch	=> array(
												array(
													c_Property_Device 	=> c_Device_YamahaMain,
													c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_AV2, 'VOL' => -35))
												),
												array(
													c_Property_Device 	=> c_Device_SubwooferBack,
												),
											),
					c_Property_Output	=> array(
											c_Property_Device 	=> c_Device_SamsungTV,
					),
				),
				4 	=> array(
					c_Property_Name 	=> 'AppleSound',
		 			c_Property_Switch	=> array(
												array(
													c_Property_Device 	=> c_Device_SubwooferBack,
												),
											),
					c_Property_Output	=> array(
												c_Property_Device 	=> c_Device_YamahaMain,
												c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_AV2, 'VOL' => -35))
											),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
		  c_Room_Kitchen => array(
				0 	=> array(
					c_Property_Name 	=> 'NetRadio',
					c_Property_Input	=> array(c_Property_Device 	=> c_Device_Yamaha_NetRadio),
		 			c_Property_Output	=> array(
											c_Property_Device 	=> c_Device_YamahaZone2,
											c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_NET_RADIO, 'VOL' => -5), 'ZONE2')
					),
				),
				1 	=> array(
					c_Property_Name 	=> 'TV',
					c_Property_Input	=> array(c_Property_Device 	=> c_Device_SamsungTV),
					c_Property_Output	=> array(
											c_Property_Device 	=> c_Device_YamahaZone2,
											c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_AUDIO1, 'VOL' => 0), 'ZONE2')
					),
				),
				2 	=> array(
					c_Property_Name 	=> 'BluRay',
					//c_Property_Switch	=> array(c_Property_Device 	=> c_Device_SamsungTV),
					c_Property_Output	=> array(
											c_Property_Device 	=> c_Device_YamahaZone2,
											c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_AV1, 'VOL' => -3), 'ZONE2')
					),
				),
				3 	=> array(
					c_Property_Name 	=> 'AppleTV',
					//c_Property_Switch	=> array(c_Property_Device 	=> c_Device_SamsungTV),
					c_Property_Output	=> array(
											c_Property_Device 	=> c_Device_YamahaZone2,
											c_Property_CommSrc	=> array(c_Comm_Yamaha, array('INP' => ya_AV2, 'VOL' => -5), 'ZONE2')
					),
				),
			),
	      // -------------------------------------------------------------------------------------------------------
		);
	}

?>
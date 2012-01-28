<?
	include_once "IPSInstaller.ips.php";
	include_once "Entertainment_Configuration.ips.php";
	include_once "Entertainment_Control.ips.php";

	$ProgramPath            = 'Program.Entertainment';

	$WebfrontPath           = 'WebFront.Entertainment';
	$WebFrontConfigId       = 57374;
	$WebFrontTabPaneOrderId = 20;
	$WebFrontTabPaneName    = '';

	$iPhonePath             = 'iPhone.Entertainment';

	// ----------------------------------------------------------------------------------------------------------------------------
	// Program Installation
	// ----------------------------------------------------------------------------------------------------------------------------
	$CategoryIdEntertainment = CreateCategoryPath($ProgramPath);
	$CategoryIdControl	    = CreateCategory('Control',    $CategoryIdEntertainment, 10);
	$CategoryIdInterfaces    = CreateCategory('Interfaces', $CategoryIdEntertainment, 20);
	$CategoryIdDevices       = CreateCategory('Devices',    $CategoryIdEntertainment, 30);
	$CategoryIdRoomes        = CreateCategory('Roomes',     $CategoryIdEntertainment, 40);
	IPS_SetHidden($CategoryIdInterfaces, true);
	IPS_SetHidden($CategoryIdControl, true);

	// Add Scripts
	echo "--- Add Scripts ------------------------------------------------------------------------ \n";
	IPS_SetParent($IPS_SELF, $CategoryIdControl);
   CreateScript('Entertainment',                  'Entertainment.ips.php', $CategoryIdControl, 10);
   CreateScript('Entertainment_Constants',        'Entertainment_Constants.ips.php', $CategoryIdControl,20);
   CreateScript('Entertainment_Configuration',    'Entertainment_Configuration.ips.php', $CategoryIdControl,30);
   CreateScript('Entertainment_Control',  		  'Entertainment_Control.ips.php', $CategoryIdControl,40);
   CreateScript('Entertainment_Room',             'Entertainment_Room.ips.php', $CategoryIdControl,50);
   CreateScript('Entertainment_Power',            'Entertainment_Power.ips.php', $CategoryIdControl,60);
   CreateScript('Entertainment_Source',           'Entertainment_Source.ips.php', $CategoryIdControl,70);
   CreateScript('Entertainment_Device',           'Entertainment_Device.ips.php', $CategoryIdControl,80);
   CreateScript('Entertainment_RemoteControl',    'Entertainment_RemoteControl.ips.php', $CategoryIdControl,90);
   CreateScript('Entertainment_Communication',    'Entertainment_Communication.ips.php', $CategoryIdControl,100);
   CreateScript('Entertainment_Custom',           'Entertainment_Custom.ips.php', $CategoryIdControl,110);
   CreateScript('Entertainment_Connect',  		  'Entertainment_Connect.ips.php', $CategoryIdControl,120);
   $ScriptIdAllOff      = CreateScript('Entertainment_AllRoomesOff',     'Entertainment_AllRoomesOff.ips.php', $CategoryIdControl,130);
   $ScriptIdConnASyn    = CreateScript('Entertainment_ConnectAsynchron', 'Entertainment_ConnectAsynchron.ips.php', $CategoryIdControl,140);
   $ScriptIdPostInstall = CreateScript('Entertainment_PostInstallation', 'Entertainment_PostInstallation.ips.php', $CategoryIdControl,0);

   $ScriptIdInterface = CreateScript('Entertainment_Interface',  'Entertainment_Interface.ips.php', $CategoryIdInterfaces,10);
   CreateScript('Entertainment_InterfaceYamaha',    'Entertainment_InterfaceYamaha.ips.php', $CategoryIdInterfaces,20);

	// Generate Roomes and Controls
	echo "--- Create Roomes and Controls ---------------------------------------------------------\n";
	$RoomData          = get_RoomConfiguration();
	$RoomOrder         = 100;
	foreach($RoomData as $RoomName => $RoomProperties) {
		$RoomId       = CreateCategory($RoomName, $CategoryIdRoomes, $RoomOrder);
		$ControlOrder = 10;
		foreach($RoomProperties as $ControlType => $ControlData) {
			$ControlId = CreateControl ($ControlType, $ControlData, $RoomId, $ScriptIdInterface, false, $ControlOrder);
			$ControlOrder = $ControlOrder + 10;
		}
		$RoomOrder = $RoomOrder + 100;
	}

	// Generate Devices and Controls
	echo "--- Create Devices and Controls --------------------------------------------------------\n";
	$DeviceData          = get_DeviceConfiguration();
	$DeviceOrder         = 100;
	foreach($DeviceData as $DeviceName => $DeviceProperties) {
		$DeviceId     = CreateCategory($DeviceName, $CategoryIdDevices, $DeviceOrder);
		$ControlOrder = 10;
		foreach($DeviceProperties as $ControlType => $ControlData) {
         CreateControl ($ControlType, $ControlData, $DeviceId, $ScriptIdInterface, $ControlOrder);
         $ControlOrder = $ControlOrder + 10;
		}
      $DeviceOrder = $DeviceOrder + 100;
      // Process Installation Script of Device
      if (array_key_exists(c_Property_Installation, $DeviceProperties)) {
         $InstallScript = $DeviceProperties[c_Property_Installation];
			try {
			   echo 'EXECUTE Device specific Installation Procedure: '.$InstallScript."\n";
				include_once $InstallScript;
				$Function       = new ReflectionFunction('Installation');
				$Function->invoke($DeviceId);
			} catch (Exception $e) {
		     	echo 'Error Executing Function '.$FunctionName.':'.$e->getMessage()."\n";
		     	exit;
			}
      }
	}

	// ----------------------------------------------------------------------------------------------------------------------------
	// Webfront Definition
	// ----------------------------------------------------------------------------------------------------------------------------
	echo "--- Create WebFront Interface ----------------------------------------------------------\n";
	$WebFrontId               = CreateCategoryPath($WebfrontPath);
	$ID_CategoryWebFrontOverview            = CreateCategory(  'Overview',      $WebFrontId,         100);
	$ID_CategoryWebFrontOverviewLeft        = CreateCategory(    'Left',        $ID_CategoryWebFrontOverview,  10);
	$ID_CategoryWebFrontOverviewRightTop    = CreateCategory(    'RightTop',    $ID_CategoryWebFrontOverview,  10);
	$ID_CategoryWebFrontOverviewRightBottom = CreateCategory(    'RightBottom', $ID_CategoryWebFrontOverview,  20);

	CreateWFCItemTabPane   ($WebFrontConfigId, 'EntertainmentTP',  'roottp', $WebFrontTabPaneOrderId, $WebFrontTabPaneName, 'Speaker');
	CreateWFCItemSplitPane ($WebFrontConfigId, 'EntertainmentTP_OvSPLeft',   'EntertainmentTP',            0, 'Übersicht', 'Speaker', 1 /*Vertical*/, 40 /*Width*/, 0 /*Target=Pane1*/, 0/*Percent*/, 'true');
	CreateWFCItemCategory  ($WebFrontConfigId, 'EntertainmentTP_OvCatLeft',  'EntertainmentTP_OvSPLeft',  10, '', '', $ID_CategoryWebFrontOverviewLeft /*BaseId*/, 'false' /*BarBottomVisible*/);
	CreateWFCItemSplitPane ($WebFrontConfigId, 'EntertainmentTP_OvSPRight',  'EntertainmentTP_OvSPLeft',  20, '', '', 0 /*Horizontal*/, 50 /*Width*/, 0 /*Target=Pane1*/, 0/*Percent*/, 'true');
	CreateWFCItemCategory  ($WebFrontConfigId, 'EntertainmentTP_OvCatTop',   'EntertainmentTP_OvSPRight', 10, '', '', $ID_CategoryWebFrontOverviewRightTop /*BaseId*/, 'false' /*BarBottomVisible*/);
	CreateWFCItemCategory  ($WebFrontConfigId, 'EntertainmentTP_OvCatBottom','EntertainmentTP_OvSPRight', 20, '', '', $ID_CategoryWebFrontOverviewRightBottom /*BaseId*/, 'false' /*BarBottomVisible*/);

	$iPhoneId  = CreateCategoryPath($iPhonePath, 20, 'Speaker');

	// Link to Roomes and Room Controls
	$RoomOrder = 1;
	foreach($RoomData as $RoomName => $RoomProperties) {
	   $RoomId          = IPS_GetCategoryIDByName($RoomName, $CategoryIdRoomes);

	   // Create Link to Room
		//CreateLink($RoomName,  $RoomId,  $ID_CategoryWebFrontRoomes, $Order);
		$ID_RoomiPhone   = CreateCategory($RoomName, $iPhoneId,    $RoomOrder);
		$ID_RoomWebfront = CreateCategory($RoomName, $WebFrontId,  $RoomOrder);
		$RoomOrder       = $RoomOrder + 1;
		$DeviceOrder     = 10;
		CreateWFCItemCategory  ($WebFrontConfigId, 'EntertainmentTP_'.$RoomOrder,'EntertainmentTP', $RoomOrder,$RoomName, '', $ID_RoomWebfront /*BaseId*/, 'false' /*BarBottomVisible*/);
		foreach($RoomProperties as $ControlType => $ControlData) {
		   $ControlName = $ControlData[c_Property_Name];
			$SwitchId = IPS_GetVariableIDByName($ControlName, $RoomId);

			// Create Link to RoomPower Switch
		   if ($ControlType == c_Control_RoomPower) {
				CreateLink($RoomName,     $SwitchId,  $ID_CategoryWebFrontOverviewRightTop, $RoomOrder);
				CreateLink($RoomName,     $SwitchId,  $iPhoneId,                            $RoomOrder);
				CreateLink($ControlName,  $SwitchId,  $ID_RoomiPhone,                       $DeviceOrder);
				CreateLink($ControlName,  $SwitchId,  $ID_RoomWebfront,                     $DeviceOrder);

			// Create Link to RoomSource Switch
			} else if ($ControlType == c_Control_Source) {
				CreateLink($RoomName,  $SwitchId,  $ID_CategoryWebFrontOverviewRightBottom, $RoomOrder);
				CreateLink($ControlName,  $SwitchId,  $ID_RoomiPhone,                       $DeviceOrder);
				CreateLink($ControlName,  $SwitchId,  $ID_RoomWebfront,                     $DeviceOrder);

			} else if ($ControlType == c_Control_RemoteSource or $ControlType == c_Control_RemoteVolume) {
				CreateLink($ControlName,  $SwitchId,  $ID_RoomWebfront,                     $DeviceOrder);

			} else if ($ControlType == c_Control_iRemoteSource or $ControlType == c_Control_iRemoteVolume) {
				CreateLink($ControlName,  $SwitchId,  $ID_RoomiPhone,                       $DeviceOrder);

			} else {
				CreateLink($ControlName,  $SwitchId,  $ID_RoomiPhone,                       $DeviceOrder);
				CreateLink($ControlName,  $SwitchId,  $ID_RoomWebfront,                     $DeviceOrder);
			}
			$DeviceOrder = $DeviceOrder + 10;
		}
	}
	CreateLink('Alle Räume Ausschalten',  $ScriptIdAllOff,  $ID_CategoryWebFrontOverviewRightTop, 1000);
	CreateLink('Alle Räume Ausschalten',  $ScriptIdAllOff,  $iPhoneId,                            1000);

	// Link to Devices and Device Controls
	$Order = 100;
	$ID_iPhoneDevices = CreateDummyInstance("Geräte", $iPhoneId, 2000);
	foreach($DeviceData as $DeviceName => $DeviceProperties) {
		$DeviceId        = IPS_GetCategoryIDByName($DeviceName, $CategoryIdDevices);

		foreach($DeviceProperties as $ControlType => $ControlData) {
		   if ($ControlType == c_Control_DevicePower) {
				$SwitchId = IPS_GetVariableIDByName($ControlData[c_Property_Name], $DeviceId);
				// Create Link to DevicePower Switch
				CreateLink($DeviceName,  $SwitchId,  $ID_CategoryWebFrontOverviewLeft,    $Order);
				CreateLink($DeviceName,  $SwitchId,  $ID_iPhoneDevices,                   $Order);
				$Order = $Order + 10;
			}
		}
	}


	// Register Variables
	// -------------------
	echo "--- Register Variable Constants --------------------------------------------------------\n";
	SetVariableConstant ("c_ID_Devices",                 $CategoryIdDevices,  'Entertainment_Constants.ips.php');
	SetVariableConstant ("c_ID_Roomes",                  $CategoryIdRoomes,   'Entertainment_Constants.ips.php');
	SetVariableConstant ("c_ID_ConnectAsynchronScript",  $ScriptIdConnASyn,   'Entertainment_Constants.ips.php');
	SetVariableConstant ("c_ID_WebFrontRoomes",          $WebFrontId,         'Entertainment_Constants.ips.php');

	// Post Installation
	// -----------------
	IPS_RunScript($ScriptIdPostInstall);
	echo "--- Installation successfully finished !!! ----------------------------------------------\n";



   // ------------------------------------------------------------------------------------------------
	function get_DevicePropertybyParent($ParentId, $ControlType, $Property) {
		$Data = false;
		$DeviceConfig = get_DeviceConfiguration();
		$Name = IPS_GetName($ParentId);
		$ParentName = IPS_GetName(IPS_GetParent($ParentId));
		if ($ParentName == 'Devices') {
			$Data = $DeviceConfig[$Name][$ControlType][$Property];
		} else if ($ParentName == 'Roomes') {
			$SourceConfig = get_SourceConfiguration();
			$DeviceRoomName = $Name;
			foreach ($SourceConfig as $RoomName => $RoomData) {
				if($RoomName != $DeviceRoomName) {
					continue;
				}
				foreach ($RoomData as $SourceIdx => $SourceIdxData) {
					if (is_array($SourceIdxData)) {
						foreach ($SourceIdxData as $SourceType => $SourceTypeData) {
							if ($SourceType == c_Property_Input or $SourceType == c_Property_Switch or $SourceType == c_Property_Output) {
								
								// ensure compatibilty to older or simpler configurations
								if(isset($SourceTypeData[c_Property_Device])) {
									$SourceTypeData = array($SourceTypeData);
								}
								
								$DeviceNames = array();
								foreach($SourceTypeData as $SourceTypeDataX) {
									$DeviceName = $SourceTypeDataX[c_Property_Device];
									$DeviceNames[] = $DeviceName;
								}
								
								foreach($DeviceNames as $DeviceName) {
									$DeviceControls = $DeviceConfig[$DeviceName];
									if (array_key_exists($ControlType, $DeviceControls)) {
										$Data = $DeviceControls[$ControlType][$Property];
									}
								}
							}
						}
					}
				}
			}
		}
		if ($Data===false) {
			if ($ControlType==c_Control_iRemoteVolume) {
				return get_DevicePropertybyParent($ParentId, c_Control_RemoteVolume, $Property);
			}
			if ($ControlType==c_Control_iRemoteSource) {
				return get_DevicePropertybyParent($ParentId, c_Control_RemoteSource, $Property);
			}
			echo $Name.'.'.$ControlType.'.'.$Property." could NOT be found !!!/n";
			exit;
		}
		return $Data;
	}

   // ------------------------------------------------------------------------------------------------
	function CreateControl ($ControlType, $ControlData, $ParentId, $ActionScriptId, $Order) {
		$Name  = $ControlData[c_Property_Name];
		switch ($ControlType) {
			case c_Control_RoomPower:
				$ControlId  = CreateVariable($Name,  0 /*Boolean*/, $ParentId, $Order, '~Switch', $ActionScriptId, null, 'Power');
				break;
			case c_Control_DevicePower:
				$ControlId  = CreateVariable($Name,  0 /*Boolean*/, $ParentId, $Order, '~Switch', $ActionScriptId, null, 'Power');
				break;
			case c_Control_Muting:
				$ControlId  = CreateVariable($Name,  0 /*Boolean*/, $ParentId, $Order, '~Switch', $ActionScriptId, null, 'Speaker');
				break;
			case c_Control_Source:
				$Profile = 'Entertainment_Source'.$ParentId;
				CreateProfile_Source($Profile, $ParentId);
				$ControlId  = CreateVariable($Name,  1 /*Integer*/, $ParentId, $Order, $Profile, $ActionScriptId, null, 'Information');
				SetValue($ControlId, 0);
				break;
			case c_Control_Volume:
				$Profile = 'Entertainment_Volume'.$ParentId;
				$MinValue = get_DevicePropertybyParent($ParentId, $ControlType, c_Property_MinValue);
				$MaxValue = get_DevicePropertybyParent($ParentId, $ControlType, c_Property_MaxValue);
				CreateProfile_Volume($Profile, $MinValue, $MaxValue, 2);
				$ControlId  = CreateVariable($Name,  2 /*Float*/, $ParentId, $Order, $Profile, $ActionScriptId, 0, 'Intensity');
				SetValue($ControlId, $MinValue);
				break;
			case c_Control_Mode:
				$Profile = 'Entertainment_Mode'.$ParentId;
				$Names   = get_DevicePropertybyParent($ParentId, $ControlType, c_Property_Names);
				CreateProfile_Names($Profile, $Names);
				$ControlId  = CreateVariable($Name,  1 /*Integer*/, $ParentId, $Order, $Profile, $ActionScriptId, null, 'Gear');
				SetValue($ControlId, 0);
				break;
			case c_Control_Program:
				$Profile = 'Entertainment_Program'.$ParentId;
				$Names   = get_DevicePropertybyParent($ParentId, $ControlType, c_Property_Names);
				CreateProfile_Names($Profile, $Names);
				$ControlId  = CreateVariable($Name,  1 /*Integer*/, $ParentId, $Order, $Profile, $ActionScriptId, null, 'Image');
				SetValue($ControlId, 0);
				break;
			case c_Control_RemoteVolumeType:
			case c_Control_RemoteSourceType:
				$ControlId  = CreateVariable($Name,  1 /*Integer*/, $ParentId, $Order, '', $ActionScriptId);
				SetValue($ControlId, 0);
				break;
			case c_Control_iRemoteVolume:
			case c_Control_RemoteVolume:
				$ControlId  = CreateVariable($Name, 3 /*String*/,  $ParentId, $Order,   '~HTMLBox', null, null, 'Intensity');
				$Names   = get_DevicePropertybyParent($ParentId, $ControlType, c_Property_Names);
				SetValue($ControlId, c_RemoteControlHtmlPrefix.$Names[0].c_RemoteControlHtmlSuffix);
				break;
			case c_Control_iRemoteSource:
			case c_Control_RemoteSource:
				$ControlId  = CreateVariable($Name, 3 /*String*/,  $ParentId, $Order,   '~HTMLBox', null, null, 'Notebook');
				$Names   = get_DevicePropertybyParent($ParentId, $ControlType, c_Property_Names);
				SetValue($ControlId, c_RemoteControlHtmlPrefix.$Names[0].c_RemoteControlHtmlSuffix);
				break;
			default;
				$ControlId = false;
				break;
		}
		return $ControlId;
	}

   // ------------------------------------------------------------------------------------------------
	function CreateProfile_Volume ($Name, $MinValue, $MaxValue, $Type = 1) {
	   @IPS_DeleteVariableProfile($Name);
		IPS_CreateVariableProfile($Name, $Type);
		IPS_SetVariableProfileText($Name, "", "%");
		IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, 1);
		IPS_SetVariableProfileDigits($Name, 1);
		IPS_SetVariableProfileIcon($Name, "");
	}

   // ------------------------------------------------------------------------------------------------
	function CreateProfile_Names ($Name, $Names) {
	   @IPS_DeleteVariableProfile($Name);
		IPS_CreateVariableProfile($Name, 1);
		IPS_SetVariableProfileText($Name, "", "");
		IPS_SetVariableProfileValues($Name, 0, 0, 0);
		IPS_SetVariableProfileDigits($Name, 0);
		IPS_SetVariableProfileIcon($Name, "");
		foreach($Names as $Idx => $IdxName) {
			IPS_SetVariableProfileAssociation($Name, $Idx, $IdxName, "", 0xaaaaaa);
		}
	}

   // ------------------------------------------------------------------------------------------------
	function CreateProfile_Source ($Name, $RoomId) {
	   @IPS_DeleteVariableProfile($Name);
		IPS_CreateVariableProfile($Name, 1);
		IPS_SetVariableProfileText($Name, "", "");
		IPS_SetVariableProfileValues($Name, 0, 0, 0);
		IPS_SetVariableProfileDigits($Name, 0);
		IPS_SetVariableProfileIcon($Name, "");
	   $SourceData = get_SourceConfiguration();
		$SourceItems = $SourceData[IPS_GetName($RoomId)];
		foreach($SourceItems as $SourceId => $SourceData) {
			IPS_SetVariableProfileAssociation($Name, $SourceId, $SourceData[c_Property_Name], "", 0xaaaaaa);
		}
	}


?>
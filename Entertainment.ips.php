<?
	include_once "IPSLogger.ips.php";
	include_once "Entertainment_Constants.ips.php";
	include_once "Entertainment_Configuration.ips.php";
	include_once "Entertainment_Communication.ips.php";
	include_once "Entertainment_Control.ips.php";
	include_once "Entertainment_Room.ips.php";
	include_once "Entertainment_Power.ips.php";
	include_once "Entertainment_Device.ips.php";
	include_once "Entertainment_Source.ips.php";
	include_once "Entertainment_RemoteControl.ips.php";
	include_once "Entertainment_DeviceSemaphore.ips.php";


/*
Control
	function get_CommandListKey($KeyValues) {
	function get_CommandList() {
	function bool2OnOff($bool) {
	function isDeviceControl($ControlId) {
	function isRoomControl($ControlId) {
	function isDevicePoweredOnByDeviceName($DeviceName)
	function get_RoomControlIdByDeviceControlId($DeviceControlId) {
	function get_DeviceControlIdByRoomControlId($RoomControlId) {
	function get_ControlType($ControlId) {
	function get_ControlNameByDeviceName($DeviceName, $ControlType) {
	function get_ControlIdByDeviceName($DeviceName, $ControlType) {
	function get_ControlNameByRoomName($RoomName, $ControlType) {
	function get_ControlIdByRoomId($RoomId, $ControlType) {
	function get_ActiveRoomIds () {
	function get_ActiveDeviceNames() {
	function get_DeviceNamesByRoomId($RoomId) {
	function get_SourceIdxByRoomId($RoomId) {
	function get_SourceDeviceTypes($RoomId, $SourceIdx) {
	function get_SourceListByDeviceName($DeviceName) {
	function get_SourceName($RoomId, $SourceIdx) {
	function get_RoomId($RoomName) {
	function get_TemplateIndex($DeviceName, $ControlType, $CommType, $Template) {


Communication
	function Entertainment_ReceiveData($Data) {
	function Entertainment_SendData($DeviceName, $ControlType, $CommParams, $CommType) {
	function Entertainment_SendDataByDeviceName($DeviceName, $ControlType, $CommTypeList) {
	function Entertainment_SendDataBySourceIdx($RoomId, $SourceIdx) {
Power
	function Entertainment_SetDevicePowerByDeviceName($DeviceName, $Value) {
	function Entertainment_SetDevicePowerByRoomId($RoomId, $Value) {
	function Entertainment_SetDevicePower($PowerId, $Value) {
	function Entertainment_PowerOffUnusedDevices() {
Room
	function Entertainment_SetRoomVisible($PowerId, $Value) {
	function Entertainment_SetRoomPower($PowerId, $Value) {
	function Entertainment_SetRoomPowerByDeviceName($DeviceName, $Value) {
	function Entertainment_PowerOffUnusedRoomes() {
	function IsRoomPoweredOn($RoomId) {
	function Entertainment_SetRoomPowerByRoomId($RoomId, $Value) {
Source
	function Entertainment_SyncAllRoomControls() {
	function Entertainment_SyncRoomControls($RoomId) {
	function Entertainment_SetSource($SourceId, $Value, $MessageType) {
	function Entertainment_SetSourceByRoomId($RoomId, $SourceIdx) {
Device
	function get_MaxValueByControlId($ControlId) {
	function Entertainment_SetProgramPrev($Id, $MessageType=c_MessageType_Action) {
	function Entertainment_SetProgramNext($Id, $MessageType=c_MessageType_Action) {
	function Entertainment_SetProgram($Id, $Value, $MessageType=c_MessageType_Action) {
	function Entertainment_SetMode($Id, $Value, $MessageType=c_MessageType_Action) {
	function Entertainment_SetVolume($Id, $Value) {
	function Entertainment_SetMuting($Id, $Value) {
	function Entertainment_SetControl ($ControlId, $Value) {
	function Entertainment_SetDeviceControl($DeviceControlId, $Value) {
	function Entertainment_SetRoomControlByDeviceControlId($DeviceControlId, $Value) {
	function Entertainment_SetDeviceControlByRoomControlId($RoomControlId, $Value) {
	function Entertainment_SetDeviceControlByRoomId($RoomId, $ControlType, $Value) {
	function Entertainment_SetDeviceControlByDeviceName($DeviceName, $ControlType, $Value) {



-------------------------------------------------------------------------------------------------------------------------

	ToDos:
	   * Deployment
		   + Configuration: 	TunerPower,
									WinLIRC Translation
		                     Volume, Limit
		   + WinLIRC Interface
		   + Constants, Device
	
		* Installation
			Doku WebFront Configuration
			Doku Constants

		* Send Delay for PowerOn
			Entertainment_WaitForPowerOn($DeviceName);

	IPSLogger
	   * Add MessageType Status
	   * Add Output Echo
	   * Add Output Prowl
	   * Add Output Notification
		* Possibility of optional Outputs ...
		* Additional WebFront GUI (OnOff+Params)
		* Mode for asynch. Log Processing
		   Use EventScript (1 Sec), Add Messages to Buffer


*/






?>
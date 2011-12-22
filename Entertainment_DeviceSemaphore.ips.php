<?
	include_once "IPSLogger.ips.php";
	
	define("sem_ENTERTAINMENT", "EntertainmentDeviceChange");
	
	function executeGuarded($function, $name, $time = 1000) {
		if(IPS_SemaphoreEnter($name, $time)) {
			try {
				$function();
			}
			catch (Exception $e) {
			}
			IPS_SemaphoreLeave($name);
			return true;
		}
		IPSLogger_Wrn(__file__, "Unable to acquire guard ".$name);
		return false;
	}
	
	function Entertainment_requestSemaphore($name = sem_ENTERTAINMENT, $time = 7000) {
		$result = IPS_SemaphoreEnter($name, $time);
		IPSLogger_Trc(__file__, "Requesting semaphore (".$name."): ".($result ? "SUCCESS" : "FAILED").($name == "" ? "" : " from ".$name));
		return $result;
	}
	
	function Entertainment_freeSemaphore($name = sem_ENTERTAINMENT) {
		IPSLogger_Trc(__file__, "Freeing Semaphore (".$name.")");
		return IPS_SemaphoreLeave($name);
	}
?>
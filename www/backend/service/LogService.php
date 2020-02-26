<?php
class LogService
{
	public function logSql($log)
	{
		//file_put_contents("log/sql.log", "\r\n".($this->timeStamp())." ".$log, FILE_APPEND | LOCK_EX);
		if($GLOBALS["logDb"]) echo "Info:  ".$log;
		error_log($log);
	}

	public function logError($log)
	{
		//file_put_contents("log/error.log", "\r\n".($this->timeStamp())." ".$log, FILE_APPEND | LOCK_EX);
		if($GLOBALS["logDb"]) echo "Error: ".$log;
		error_log($log);
	}

	private function timeStamp()
	{
		return date('Y-m-d H:i:s');
	}
}	
?>
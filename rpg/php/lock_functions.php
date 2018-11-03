<?php

include_once(__DIR__ . '/../../common.php');

class sqlLock
{
    var $lockname;
    var $timeout;
    var $locked;
 
    function sqlLock($name, $timeout = 0)
    {
        $this->lockname = $name;
        $this->timeout = $timeout;
        $this->locked = -1;
    }
 
    function lock()
    {
		if(!$this->isFree()) return false;
		
		global $db;
		
		$sql = "SELECT GET_LOCK('".$this->lockname."', ".$this->timeout.")";
        $result = $db->sql_query($sql, 0);
		
		$info = $db->sql_fetchrow($result);
		
		foreach($info as $key => $value) {
			if($value == 1) $this->locked = true;
			else $this->locked = false;
		}
		
		$db->sql_freeresult($result);
		
		return $this->locked;
    }
 
    function release()
    {
		if($this->isFree()) return true;
		
		global $db;
		
		$sql = "SELECT RELEASE_LOCK('".$this->lockname."')";
		$result = $db->sql_query($sql, 0);
		
		$info = $db->sql_fetchrow($result);
		
		foreach($info as $key => $value) {
			if($value == 1) $this->locked = false;
			else $this->locked = true;
		}
		
		$db->sql_freeresult($result);
	   
	   return !$this->locked;
    }
 
    function isFree()
    {
		global $db;
		
		$sql = "SELECT IS_FREE_LOCK('".$this->lockname."')";
		$result = $db->sql_query($sql, 0);
		
		$info = $db->sql_fetchrow($result);
		
		$lock = false;
		
		foreach($info as $key => $value) {
			if($value == 1) $lock = true;
			else $lock = false;
		}
		
		$db->sql_freeresult($result);
 
        return $lock;
    }
}
 
?>
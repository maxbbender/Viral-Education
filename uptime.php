<?php
 error_reporting(0);
    function sec2human($time) {
        $seconds = $time%60;
        $mins = floor($time/60)%60;
        $hours = floor($time/60/60)%24;
        $days = floor($time/60/60/24);
        return $days > 0 ? $days . ' day'.($days > 1 ? 's' : '') : $hours.':'.$mins.':'.$seconds;
    }
    
    
    function getPing($hostaddress){
        if(!isset($hostaddress)){ $hostaddress = "google.com"; }
        if(DIRECTORY_SEPARATOR == '/'){
            //System is Linux, call the shell and execute a ping command
            $exec = exec("ping -c 1 -s 64 -t 64 ".$hostaddress);
            $array = explode("/", end(explode("=", $exec )) );
            return ceil($array[1]);
        }else{
            //System is NOT Linux, lets assume its windows and use it's function to call the ping command
            $starttime = microtime(true);
            $file      = fsockopen ($hostaddress, 80, $errno, $errstr, 10);
            $stoptime  = microtime(true);
            $status    = 0;
            if (!$file){
                $status = -1;  // Site is down
            }else{
                fclose($file);
                $status = ($stoptime - $starttime) * 1000;
                $status = floor($status);
            }
            return $status;
        }
    }
    
    
    function uptime(){
        if(DIRECTORY_SEPARATOR == '/'){
            //System is Linux, call the shell and see what the uptime is
            $array = array();
            $fh = fopen('/proc/uptime', 'r');
            $uptime = fgets($fh);
            fclose($fh);
            $uptime = explode('.', $uptime, 2);
            return sec2human($uptime[0]);
        }else{
             //System is NOT Linux, lets assume its windows and use it's function to read the sysinfo file
            exec('systeminfo', $retval);
            $time = substr($retval[11] ,17);
            $uparr = explode(",",$time);
            $uptime = strtotime($uparr[0].$uparr[1]);
            $ut = time() - $uptime;
            return sec2human($ut);
        }
    }
    
    
    function memory(){
        if(DIRECTORY_SEPARATOR == '/'){
            //System is Linux, call the shell and see what memory usage is
            $fh = fopen('/proc/meminfo', 'r');
            $mem = 0;
            while ($line = fgets($fh)) {
                $pieces = array();
                if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                    $memtotal = $pieces[1];
                }
                if (preg_match('/^MemFree:\s+(\d+)\skB$/', $line, $pieces)) {
                    $memfree = $pieces[1];
                }
                if (preg_match('/^Cached:\s+(\d+)\skB$/', $line, $pieces)) {
                    $memcache = $pieces[1];
                }
                if (preg_match('/^SwapTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                    $swaptotal= $pieces[1];
                }
                if (preg_match('/^SwapFree:\s+(\d+)\skB$/', $line, $pieces)) {
                    $swapfree = $pieces[1];
                    break;
                }
            }
            fclose($fh);
            return round((($memtotal-($memfree+$memcache))/$memtotal)*100);
        }else{
            //System is NOT Linux, lets assume its windows and use it's php_win32_ps.dll file to read the system RAM load
			if(extension_loaded('win32ps')){
				$memory = win32_ps_stat_mem();
				return round($memory['load']); 
			}else{
				return "-1";
			}
        }
 
    }
    
    
    function hdd(){
         if(DIRECTORY_SEPARATOR == '/'){
             //System is Linux, call the shell and see what memory usage is
             $hddtotal = disk_total_space("/");
             $hddfree = disk_free_space("/");
             $hddmath = ($hddtotal-$hddfree) / $hddtotal * 100;
             return round($hddmath); 
         }else{
             //System is NOT Linux, lets assume its windows and use php to scan for the drives and get the overall usage of the root dirve (OS)   
             $hddtotal = disk_total_space("C:");
             $hddfree = disk_free_space("C:");
             $hddmath = ($hddtotal-$hddfree) / $hddtotal * 100;
             return round($hddmath);             
         }
    }
    
    
    function cpu(){
        if(DIRECTORY_SEPARATOR == '/'){
            //System is Linux, check what the load is
            $load = sys_getloadavg();
            return $load[0];        
        }else{
            //System is NOT Linux, lets assume its windows .... Windows is broken for load averages :(
            return "n/a";   
        } 
    }
	
	
	function cpu_cores(){
		if(DIRECTORY_SEPARATOR == '/'){
            //System is Linux, check what the CPU Count is
            return intval(trim(shell_exec('cat /proc/cpuinfo | grep processor | wc -l')));
        }else{
            //System is NOT Linux, lets assume its windows .... Windows is broken for reading cores :(
            return "-1";   
        } 

	}

    
    
    function compile(){
        //function calls all of the other functions and puts everything togeather for runtime
        $output = ""; $ping = getPing($_GET['ip']); $uptime = uptime(); $memory = memory(); $hdd = hdd(); $cpu = cpu(); $cpu_count = cpu_cores();
        $output .= "<uptime>".$uptime."</uptime>";
        $output .= "<memory>".$memory."%</memory>";
        $output .= "<hdd>".$hdd."%</hdd>";
        $output .= "<cpu>".$cpu."</cpu>";
		$output .= "<cpucount>".$cpu_count."</cpucount>";
        $output .= "<ping>".$ping." ms</ping>";
		$output .= "<status>Up</status>";
        return $output;  
    }

echo compile();
?>
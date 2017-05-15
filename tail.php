<?php
error_reporting(0);
function follow($file){
    $size = 0;
    $last = 0;
    $ip   = 0;
    $alt  = 0;
    while (true) {
        clearstatcache();
        $currentSize = filesize($file);
        if ($size == $currentSize) {
            usleep(100);
            continue;
        }
        $fh = fopen($file, "r");
        fseek($fh, $size);
        while ($d = fgets($fh)) {
          preg_match_all('/(.*?) - - (.*?) "(.*?) (.*?) (.*?)" (.*?) (.*?) "(.*?)" "(.*?)"/', $d , $info);
          $in = array(
            'ip' => $info[1][0],'date' => $info[2][0], 'rpage' => $info[4][0],
          );
          if(empty($last) && empty($ip)){
            $last = $in['date'];
            $ip   = $in['ip'];
            echo "Last : ".$last."\r\n";
          }else{
            if($last == $in['date'] && $ip == $in['ip']){
              echo "Ip :  ".$ip. " | ".$in['date']." --- DDOS\r\n";
              $alt++; // plus alert
            }else{
              echo "Ip :  ".$ip. " | ".$in['date']." --- Normal\r\n";
              $last = $in['date'];
            }
          }
        }
        if($alt >= 5){ 
          echo "CLOSE CONNECTION\r\n";
          // Command Block IP In here
           //shell_exec("");
        }
        fclose($fh);
        $size = $currentSize;
    }
}
follow('C:\xampp\apache\logs\access.log');

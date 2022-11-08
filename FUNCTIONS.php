<?php

/**
 * FUNCTIONS
 */
class FUNCTIONS
{
    public $email;

    public function array_flatten($ar) {
        $toflat = array($ar);
        $res = array();

        while (($r = array_shift($toflat)) !== NULL) {
            foreach ($r as $v) {
                if (is_array($v)) {
                    $toflat[] = $v;
                } else {
                    $res[] = $v;
                }
            }
        }

        return $res;

    }




    //TO RESIZE IMAGES
    public function img_resize($target, $newcopy, $w, $h, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $scale_ratio = $w_orig / $h_orig;
        if (($w / $h) > $scale_ratio) {
               $w = $h ;
        } else {
               $h = $w;
        }
        $img = "";
        $ext = strtolower($ext);
        if ($ext == "gif"){ 
          $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
          $img = imagecreatefrompng($target);
        } else { 
          $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        imagejpeg($tci, $newcopy, 84);
    }



    //TO GET PERCENTAGE
    public function progress($current_data,$max_data) {
        $pcnt_full = 0;
       if ($current_data > 0 && $max_data > 0) {
        $pcnt_full = round(($current_data/$max_data) * 100,2);
        if ($pcnt_full > 100) {
            $pcnt_full = 100;
        }

        if ($pcnt_full < 0) {
            $pcnt_full = 0;
        }

       }

        return $pcnt_full;
    }

    public function percentage($x,$y)
    {
        $p = 0;
        if ($x > 0 && $y > 0) {
            $p = round(($x/$y)*100,2);
        }
        return $p;
    }



    //TO GET USER LOCATION
    public function user_location($ip_data) {
        $geo = $ip_data;
        $country = $geo["geoplugin_countryName"];
        $city = $geo["geoplugin_city"];

        return array('country' => $country, 'city' => $city);
    }



    public function convert_timestamp($timestamp) {
        $array['sec'] = $timestamp;
        $array['mins'] = $array['sec'] / 60;
        $array['hr'] = $array['mins'] / 60;
        $array['days'] = $array['hr'] / 24;
        $array['weeks'] = $array['days'] / 7;
        $array['months'] = $array['weeks'] / 4;
        $array['years'] = $array['months'] / 12;

        foreach ($array as $a) {
            if ($a < 0) {
                $array[$a] = 0;
            }
        }
        return $array;
    }

    public function ago($time) { 
        $timediff=time()-$time; 

        $days=intval($timediff/86400);
        $remain=$timediff%86400;
        $hours=intval($remain/3600);
        $remain=$remain%3600;
        $mins=intval($remain/60);
        $secs=$remain%60;

        if ($secs>=0) $timestring = "0m ".$secs."s";
        if ($mins>0) $timestring = $mins."m ".$secs."s";
        if ($hours>0) $timestring = $hours."u ".$mins."m ";
        if ($days>0) $timestring = $days."d ".$hours."u ";

        return $timestring; 
    }

   


    public function myUrlEncode($string) {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, urlencode($string));
    }


    public function format_text($text,$limit)
    {
        $text = html_entity_decode($text->body);
        $start = strpos( $text, '>' ) + 1;
        echo substr( $text, $start, $limit );
    }

    public function int_numify($int,$min,$max)
    {
        if ($int < $min) {
            $num = $min;
        }

        if ($int > $max) {
            $num = $max;
        }

        return $num;
    }


    public function shuffle_assoc($array) {
            $keys = array_keys($array);

            shuffle($keys);

            foreach($keys as $key) {
                $new[$key] = $array[$key];
            }

            $array = $new;

            return $array;
    }


    public function purify_array($AR)
    {
        $AR = array_unique($this->array_flatten($AR));
        $i = 0;
        $data[] = null;
        foreach ($AR as $ar) {
            if ($ar != "") {
                $data[$i] = $ar;
                $i++;
            }
        }
        return $data;
    }

    public function ip_data()
    {
        $ip = $this->UserIP();
        $geo = [];
        try {
            $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $geo;
    }

    public function UserIP()
    {
        $ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        if (!filter_var($ip, FILTER_VALIDATE_IP) || $ip == '::1') {
            $ip = '197.210.227.208';
        }
        return $ip;
    }


    //COLUMNS TO ARRAY
    public function col2array($row,$col) {
        $array[] = null;
        $i = 0;

        foreach ($row as $r) {
            if (is_object($r)) {
                $array["$i"] = $row->$col;
                $i++;
            }elseif (is_array($r)) {
                $array["$i"] = $row["$col"];
                $i++;
            }
            
        }
        
        return $array;

    }


    function does_url_exists($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    function count_object($obj,$no_null = false) {
        $i = 0;
        if ($obj !== null && (is_array($obj) || is_object($obj))) {
            foreach ($obj as $key => $value) {
                if ($no_null === true) {
                    if ($value !== null) {
                        $i++;
                    }
                }else {
                    $i++;
                }
                
            }
        }
        return $i;
    }

    function object2Array($obj) {
        $data = [];
        if ($this->count_object($obj) > 0) {
            foreach ($obj as $key => $value) {
                $data[$key] = $value;
            }
        }
        return $data;
    }


    function formatBytes($size, $precision = 0)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');   

        return round(pow(1024, $base - floor($base)), $precision);
    }

    function formatBytes2($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }

    function formatBytes3($bytes, $precision = 0) {
        $unit = ["B", "KB", "MB", "GB"];
        $exp = floor(log($bytes, 1024)) | 0;
        return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
    }    

    function compareFiles($file_a, $file_b)
    {

        if ((file_exists($file_a) && file_exists($file_b)) && (filesize($file_a) == filesize($file_b)))
        {
            $fp_a = fopen($file_a, 'rb');
            $fp_b = fopen($file_b, 'rb');

            while (($b = fread($fp_a, 4096)) !== false)
            {
                $b_b = fread($fp_b, 4096);
                if ($b !== $b_b)
                {
                    fclose($fp_a);
                    fclose($fp_b);
                    return false;
                }
            }

            fclose($fp_a);
            fclose($fp_b);

            return true;
        }

        return false;
    }

    function compareFiles2($file_a, $file_b)
    {

        if ((file_exists($file_a) && file_exists($file_b)) && (filesize($file_a) == filesize($file_b)))
        {
            
            return true;
        }

        return false;
    }




    public function updateLogByDay($day)
    {
        $sql = (DB::select("SELECT * FROM update_log ORDER BY id DESC"));

        if (!empty($sql)) {
            
            foreach ($sql as $sql) {
                $d = strtotime(date('m/d/Y', $sql->time));
                if ($d == $day) {
                    return $sql;
                }

                if ($d < $day) {
                    return null;
                }
            }
        }
        return null;
    }
  


    public function files_identical($fn1, $fn2) {
        //define('READ_LEN', 4096);
        $r = 4096;
        $same = false;
        if (is_file($fn1) && is_file($fn2)) {
            if(filetype($fn1) !== filetype($fn2))
                return FALSE;
        
            if(filesize($fn1) !== filesize($fn2))
                return FALSE;
        
            if(!$fp1 = fopen($fn1, 'rb'))
                return FALSE;
        
            if(!$fp2 = fopen($fn2, 'rb')) {
                fclose($fp1);
                return FALSE;
            }
        
            $same = TRUE;
            while (!feof($fp1) and !feof($fp2))
                if(fread($fp1, $r) !== fread($fp2, $r)) {
                    $same = FALSE;
                    break;
                }
        
            if(feof($fp1) !== feof($fp2))
                $same = FALSE;
        
            fclose($fp1);
            fclose($fp2);
        }
    
        return $same;
    }


    function partition( $list, $p ) {
        $listlen = count( $list );
        $partlen = floor( $listlen / $p );
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        $pn = ($p > $listlen) ? $listlen : $p; 
        for ($px = 0; $px < $pn; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice( $list, $mark, $incr );
            $mark += $incr;
        }
        return $partition;
    }
    

    public function dateValidate($t)
    {
        if ($t > 0) {
            return $t;
        }else {
            return null;
        }
    }

    function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

   

    
    function time_elapsed_string($datetime, $full = false) {
        $datetime = '@'.$datetime;
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function getRandomWeightedElement(array $weightedValues) {
        $rand = mt_rand(1, (int) array_sum($weightedValues));
    
        foreach ($weightedValues as $key => $value) {
          $rand -= $value;
          if ($rand <= 0) {
            return $key;
          }
        }
    }

    public function LimitedNumbersGenerator ($x,$y,$mx,$my) {
        $data = []; 
        $data[] = $x;
        for ($i=$x; $i < $y;) { 

            $mt = $data[count($data) - 1] + rand($mx,$my);
            if ($mt < $y) {
                $data[] = $mt;
            }
            $i = $mt;
        }
        return $data;
    } 

    public function convert_bool($bool) {
        if ($bool === true) {
            return 'yes';
        }
        return 'no';
    }

    public function sortArrayBasedOnSharedKey($array,$key,$order = 'desc')
    {
        if (!empty($array)) {
            uasort($array, function($a, $b) use ($key) {
                $retval = $a[$key] <=> $b[$key];
                return $retval;
            });

            $array = ($order == 'desc') ? array_reverse($array) : $array;

        }
        return $array;
    }

    public function files_from_dir($dir)
    {
        $data = null;
        if ($handle = opendir(public_path($dir))) {
            $i = 0;
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $data[$i] = $entry;
                    $i++;
                }
            }
            closedir($handle);
        }

        return $data;
    }
    

    public function originalNumberFromPercent($p,$n)
    {
        $x = 0;
        if ($p > 0 && $n > 0) {
            $x = ($n/($p/100));
        }
        return $x;
    }

    public function percentOf($p,$n)
    {
        $x = 0;
        if ($p > 0 && $n > 0) {
            $x = ($p * $n)/100;
        }
        return $x;
    }

    public function paginate($items,$perPage = 10,$page = null,$options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items: Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page,$perPage),$items->count(),$perPage,$page,$options);
    
    }   
    

    public function getProp($obj,$key)
    {
        if (is_array($obj)) {
            return $obj[$key];
        }else if(is_object($obj)) {
            return $obj->$key;
        }
    }

    public function snakeString($str)
    {
        $str = preg_replace('/\W+/','-',strtolower(trim($str)));
        return $str;
    }


    
    public function csv2Array($csv)
    {
        return explode(",",$csv);
    }

    public function array2Csv($arr)
    {
        $str = "";

        if (!empty($arr)) {
            $c = count($arr);
            $i = 0;
            foreach ($arr as $ar) {
                $s = "";
                if (($i+1) < $c) {
                    $s = ",";
                }
                $str += $ar.$s;
                $i++;
            }
        }
        return $str;
    }


    public function random_strings($length_of_string) 
    { 
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result), 0, $length_of_string); 
    } 

    public function generateReference()
    {
        return $this->random_strings(11);
        
    }


    /*
    * function to encode string
    * accepts a string
    * returns encoded string
    */
    function safe_string_encode($string) {
        return strtr(base64_encode($string), '+/=', '-_-');
    }
    
    /*
    * function to decode the encoded string
    * accepts encoded string
    * returns the original string
    */
    function safe_string_decode($string) {
        return base64_decode(strtr($string, '-_-', '+/='));
    }
    



public function weiToBNB($wei)
{
    $bnb = $wei/(10**18);
    return $bnb;
}

public function filter()
{
    return (new FILTER());
}

public function timestampToHuman($time,$text)
{
    $t = $this->timeAgo($time);
    if ($text == "created") {
        $t = $text." ".$t;
    }
    if ($text == "updated") {
        $t = $text." ".$t;
    }
    if ($text == "ends") {
        if ($time > 0 && $time <= time()){
            $text =  "ended";
        }else {
            $text = "ends in";
        }
        $t = $text." ".$t;
    }
    return $t;
}

public function timeAgo($time)
{
    if (!empty($time)) {
        $now = time();
        if ($time > $now) {
            $s = "";
        }elseif ($time == $now) {
            $s = "just now";
        }else {
            $s = "ago";
        }
        $diff = abs($time - $now);
    
        $years = intval($diff/(60 * 60 * 24 * 365));
        $rem = $diff%(60 * 60 * 24 * 365);
    
        $months = intval($rem/(60 * 60 * 24 * 30));
        $rem = $rem%(60 * 60 * 24 * 30);
        
        $days = intval($rem/(60 * 60 * 24));
        $rem = $rem%(60 * 60 * 24);
    
        $hours = intval($rem/(60 * 60));
        $rem = $rem%(60 * 60);
        
        $minutes = intval($rem/(60));
        $rem = $rem%(60);
    
        $seconds = intval($rem);
        $rem = $rem;
    
    
        
        if ($years > 0) {
            $timestring = $this->generateTimeAgoString($years,"year");
        }else {
            
            if ($months > 0) {
                $timestring = $this->generateTimeAgoString($months,"month");
            }else {
    
                if ($days > 0) {
                    $timestring = $this->generateTimeAgoString($days,"day");
                }else {
                    if ($hours > 0) {
                        $timestring = $this->generateTimeAgoString($hours,"hour");
                    }else {
                        
                        if ($minutes > 0) {
                            $timestring = $this->generateTimeAgoString($minutes,"minute");
                        }else {
                            if ($seconds> 0) {
                                $timestring = $this->generateTimeAgoString($seconds,"second");
                            }else {
                                $timestring = "just now";
                            }
                        }
                    }
                }
            }
        }
    
    
        if ($diff >= 1) {
            return $timestring;
        }
    }

    return "__:__:__";
}


public function generateTimeAgoString($val,$str)
{
    $x = "";
    if ($val == 1) {
        $x = "a";
        $val = "";
        $str .= " ago";
    }
    if ($val > 1) {
        $str .= "s ago";
    }
    return $x." ".$val." ".$str;
}


public function socialMediaCounter($val)
{
    if ( $val > 1000) {
        $x = round($val);
        $x_number_format = number_format($x);
        $x_array = explode(',',$x_number_format);
        $x_parts = array('k','m','b','t');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0].((int) $x_array[1][0] !== 0 ? '.'. $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];

        return $x_display;

    }
    return $val;
}

public function verifySignature($message,$signature,$address)
{
    $messageLength = strlen($message);
    $hash = Keccak::hash("\x19Ethereum Signed Message:\n{$messageLength}{$message}",256);
    $sign = [
        "r" => substr($signature,2,64),
        "s" => substr($signature,66,64),
    ];

    $recId = ord(hex2bin(substr($signature,130,2))) - 27;

    if ($recId != ($recId & 1)) {
        return false;
    }

    $publicKey = (new EC('secp256k1'))->recoverPubKey($hash,$sign,$recId);
    return $this->pubKeyToAddress($publicKey) === Str::lower($address);
}

public function pubKeyToAddress($publicKey)
{
    return "0x". substr(Keccak::hash(substr(hex2bin($publicKey->encode("hex")),1),256),24);
}


public function allArrayKeysExist($array,$keys)
{
    if (is_string($array)) {
        $array = json_decode($array,true);
    }
    if (is_array($array) && is_array($keys)) {
        if (empty($array) && empty($keys)) {
            return true;
        }

        foreach ($keys as $key) {
            if (!array_key_exists($key,$array)) {
                return false;
            }
        }

        return true;
    }

    return false;
}



    public function transverseMatrix($x,$y,$i,$dir)
    {

        //dx=  max positions
        //dy= max level
        //dp=number of participants required
        //rx=range from
        //ry=range to
        if ($i > 0) {
            if ($dir == 1 && $y>0 && ($y-$i) >= 0) {
                if ($x%2 > 0) {
                    $x = $x+1;
                }
                $rx = $x/(2**$i);
                $ry = $y - $i;
            }
            
            if ($dir == 0) {
                $dx = $x*(2**$i);
                $dy = $y + $i;
                $rx = ($dx - ($dx/$x)) + 1; 
                $ry = $dx; 
            }
        }

        return ['rx' => $rx,'ry' => $ry];
    }


    public function signMessage($pkey,$messageHash)
    {
        $ec = new EC('secp256k1');
        $ecPrivateKey = $ec->keyFromPrivate($pkey,'hex');

        $signature = $ecPrivateKey->sign($messageHash, ['canonical' => true]);
        $r = str_pad($signature->r->toString(16), 64, '0', STR_PAD_LEFT);
        $s = str_pad($signature->s->toString(16), 64, '0', STR_PAD_LEFT);
        $v = dechex($signature->recoveryParam + 27);
        $sig = "0x$r$s$v";
        return ['messageHash' => $messageHash,'signature' => $sig];
    }



    
    






    
}






?>

<?php
/**
 * Formats the given text and return the result. For example, if 'avionics_filed_no12' is given,
 *		it will return 'Avionics Filed No 12'.
 * Argument: $value - The string that should be formated
 * Returns : The formated string
 */
function format($value) {
	$value = preg_replace(
		array(	"/_/",				//Changes 'hello_world' to 'hello world'	
				"/([a-zA-Z])(\d)/", //Changes 'no1' to 'no 1'
				"/([a-z])([A-Z])/"	//Changes 'helloWorld' to 'hello World'
		),
		array(" ","$1 $2","$1 $2"),
		$value);
	return ucwords($value);
}

/**
 * Removes all the formating from the given text and returns a string that could be used in an URL. 
 *		This fucntion lowercases the string and replaces all the special chars with '_'
 * Argument: $value - The string that should be un-formated
 * Returns : The unformated string
 */
function unformat($value) {
	$value = preg_replace('/\W/','_',$value);	//Replace all special chars with an '_'
	$value = preg_replace('/__+/','_',$value);	//Replace multiple '_' with a single one.
	$value = preg_replace(
		array('/^_/','/_$/'), //Removes the '_' towards the beginning and the end of the string.
		array('_','_'),
		$value);
	return strtolower($value);
}
/** 
 * Prints a array, an object or a scalar variable in an easy to view format.
 * Arguments  : $data - the variable that must be displayed
 * Link : http://www.bin-co.com/php/scripts/dump/
 */
function dump() {
	$args = func_get_args();
	$count = count($args) - 1;
	
	print "<pre>";
	if($count) print "-------------------------------------------------------------------------------------------------------------------\n";
	foreach($args as $data) {
		if(is_array($data) or is_object($data)) { //If the given variable is an array, print using the print_r function.
			if(!$count) print "-----------------------\n";
			if(is_array($data)) print_r($data);
			else var_export($data);
			if(!$count) print "-----------------------\n";
			else print "=======================================================\n";
		} else {
			print "</pre>=========&gt;";
			print var_dump($data);
			print "&lt;=========<pre>\n";
		}
	}
	if($count) print "-------------------------------------------------------------------------------------------------------------------";
	print "</pre>\n";
}

function getById($query, $db) {
	$data = $db->query($query)->result();
	$return = array();
	foreach($data as $row) {
		$return[$row->id] = stripslashes($row->name);
	}
	
	return $return;
}

function colFormat($data) {
	$return = array();
	foreach($data as $row) $return[] = current($row);
	
	return $return;
}

/**
 * Returns the given array after making it into a key format. If an element of the array has a key called 'id', that will be set as the key of that element.*
 * array('0'=>array('id'=>1,'name'=>'Binny'), '1'=>array('id'=>30,'name'=>'Bijoy')) will become...
 * array('1'=>array('id'=>1,'name'=>'Binny'), '30'=>array('id'=>30,'name'=>'Bijoy')) 
 */
function keyFormat($data, $primary_field='id') {
	$return = [];
	foreach($data as $row) {
		if(is_array($primary_field) and count($primary_field) == 2) {
			if(is_object($row) and isset($row->{$primary_field[0]})) $return[$row->{$primary_field[0]}] = $row->{$primary_field[1]};
			elseif(isset($row[$primary_field[0]])) $return[$row[$primary_field[0]]] = $row[$primary_field[1]];
			
		} else if(is_array($primary_field) and count($primary_field)) {
			if(is_object($row)) $return[$row->{$primary_field[0]}] = $row;
			else $return[$row[$primary_field[0]]] = $row;
			
		} else {
		    $return[$row[$primary_field]] = $row;
		}
	}
	
	return $return;
}

function idNameFormat($data, $fields = false) {
	if(!$fields) $fields = ['id', 'name'];
	return keyFormat($data, $fields);
}

function oneFormat($data) {
	return current($data);
}

/**
 * The index function - Created this to avoid the extra isset() check. This will return false 
 *		if the specified index of the specified function is not set. If it there,
 *		this function will return that element.
 * Arguments:	$array - The array in which the item must be checked for
 *				$index - The index to be seached.
 *				$default_value - The value that must be returned if the item is not set
 * Example:
 *	if(i($_REQUEST, 'item')) {
 *		instead of 
 *	if(isset($_REQUEST['item']) and $_REQUEST['item']) {
 */
function i($array, $index=false, $default_value=false) {
	if($index === false) {
		if(isset($array)) return $array;
		return $default_value;
	}
	
	if(!isset($array[$index])) return $default_value;
	
	return $array[$index];
}

/// Returns just the first name of the person.
function short_name($name) {
	return reset(explode(' ', $name));
}

function get_year() {
	$this_month = intval(date('m'));
	$months = array();
	$start_month = 6; // June
	$start_year = date('Y');
	if($this_month < $start_month) $start_year = date('Y')-1;
	return $start_year;
}

/// Get the starting date of the current MAD year...
function get_mad_year_starting_date() {
	$start_month = 6; // June
	$start_year = get_year();
	return date('Y-m-d', mktime(0,0,0, $start_month, 1, $start_year));
}

/// Our Year starts on April - so get the list of months.
function get_month_list() {
	$starting_day = strtotime(get_mad_year_starting_date());
	for($i = 0; $i < 12; $i++) {
		$months[] = date('Y-m', mktime(0,0,0, date('m', $starting_day) + $i, 1, date('Y', $starting_day)));
	}
	return $months;
}

/// Set the City and Year if someone changes it. Save it to a cookie.
function set_city_year($that) {
	if($that->input->post('city_id') and $that->user_auth->check_permission('change_city')) {
		$city_id = $that->input->post('city_id');
		$that->session->set_userdata('city_id', $city_id);
		if(isset($that->center_model)) $that->center_model->city_id = $city_id;
		if(isset($that->users_model)) $that->users_model->city_id = $city_id;
		if(isset($that->city_model)) $that->city_model->city_id = $city_id;
	}

	if($that->input->post('year') and $that->user_auth->check_permission('change_city')) {
		$year = $that->input->post('year');
		$that->session->set_userdata('year', $year);
		if(isset($that->center_model)) $that->center_model->year = $year;
		if(isset($that->batch_model)) $that->batch_model->year = $year;
		if(isset($that->level_model)) $that->level_model->year = $year;
		if(isset($that->users_model)) $that->users_model->year = $year;
	}

	if($that->input->post('project_id')) {
		$project_id = $that->input->post('project_id');
		$that->session->set_userdata('project_id', $project_id);
		if(isset($that->center_model)) $that->center_model->project_id = $project_id;
		if(isset($that->batch_model)) $that->batch_model->project_id = $project_id;
		if(isset($that->level_model)) $that->level_model->project_id = $project_id;
		if(isset($that->users_model)) $that->users_model->project_id = $project_id;
	}
}

/// Set the city, year and project from session if its not already set. 
function set_city_year_from_session(&$that) {
	if(isset($that->class_model)) {
		if(!$that->class_model->project_id) $that->class_model->project_id = 1;
		if(!$that->class_model->year) $that->class_model->year = get_year();
		if(!$that->class_model->city_id and isset($_SESSION['city_id'])) $that->class_model->city_id = $_SESSION['city_id'];
	}

	if(isset($that->batch_model)) {
		if(!$that->batch_model->project_id) $that->batch_model->project_id = 1;
		if(!$that->batch_model->year) $that->batch_model->year = get_year();
		if(!$that->batch_model->city_id and isset($_SESSION['city_id'])) $that->batch_model->city_id = $_SESSION['city_id'];
	}

	if(isset($that->user_model)) {
		if(!$that->user_model->project_id) $that->user_model->project_id = 1;
		if(!$that->user_model->year) $that->user_model->year = get_year();
		if(!$that->user_model->city_id and isset($_SESSION['city_id'])) $that->user_model->city_id = $_SESSION['city_id'];
	}
}

function get_all_cycles() {
	$year = date('Y', strtotime(get_mad_year_starting_date()));
	return array(
		array(),
		array('start' => "$year-04-01", 'end' => "$year-09-14"),
		array('start' => "$year-09-15", 'end' => "$year-10-26"),
		array('start' => "$year-10-27", 'end' => "$year-12-07"),
		array('start' => "$year-12-08", 'end' => ($year+1) . "-01-18"),
		array('start' => ($year+1) . "-01-18", 'end' => ($year+1) . "-03-31")
	);
}

function get_cycle($date = false) {
	if(!$date) $date = date('Y-m-d');
	else $date = date('Y-m-d', strtotime($date));

	$all_cycles = get_all_cycles();
	foreach($all_cycles as $cycle => $cycle_data) {
		if(isset($cycle_data['start'])) {
			if($date >= $cycle_data['start'] and $date <= $cycle_data['end']) return $cycle;
		}
	}

	return 0;
}

function getTeacherGroupId($project_id) {
	$project_teacher_group_mapping = [
		1 	=> 9,
		2	=> 376,
		4	=> 349,
		5	=> 348,
		6	=> 365
	];

	return i($project_teacher_group_mapping, $project_id, 0);
}

function getMentorGroupId($project_id) {
	$project_mentor_group_mapping = [
		1 	=> 8,
		2	=> 375,
		4	=> 272,
		5	=> 348,
		6	=> 378
	];

	return i($project_mentor_group_mapping, $project_id, 0);
}

/**
 * Workaround for PHP < 5.3.0
 */
if(!function_exists('date_diff')) {
    class DateInterval {
        public $y;
        public $m;
        public $d;
        public $h;
        public $i;
        public $s;
        public $invert;
       
        public function format($format) {
            $format = str_replace('%R%y', ($this->invert ? '-' : '+') . $this->y, $format);
            $format = str_replace('%R%m', ($this->invert ? '-' : '+') . $this->m, $format);
            $format = str_replace('%R%d', ($this->invert ? '-' : '+') . $this->d, $format);
            $format = str_replace('%R%h', ($this->invert ? '-' : '+') . $this->h, $format);
            $format = str_replace('%R%i', ($this->invert ? '-' : '+') . $this->i, $format);
            $format = str_replace('%R%s', ($this->invert ? '-' : '+') . $this->s, $format);
           
            $format = str_replace('%y', $this->y, $format);
            $format = str_replace('%m', $this->m, $format);
            $format = str_replace('%d', $this->d, $format);
            $format = str_replace('%h', $this->h, $format);
            $format = str_replace('%i', $this->i, $format);
            $format = str_replace('%s', $this->s, $format);
           
            return $format;
        }
    }

    function date_diff(DateTime $date1, DateTime $date2) {
        $diff = new DateInterval();
        if($date1 > $date2) {
            $tmp = $date1;
            $date1 = $date2;
            $date2 = $tmp;
            $diff->invert = true;
        }
       
        $diff->y = ((int) $date2->format('Y')) - ((int) $date1->format('Y'));
        $diff->m = ((int) $date2->format('n')) - ((int) $date1->format('n'));
        if($diff->m < 0) {
            $diff->y -= 1;
            $diff->m = $diff->m + 12;
        }
        $diff->d = ((int) $date2->format('j')) - ((int) $date1->format('j'));
        if($diff->d < 0) {
            $diff->m -= 1;
            $diff->d = $diff->d + ((int) $date1->format('t'));
        }
        $diff->h = ((int) $date2->format('G')) - ((int) $date1->format('G'));
        if($diff->h < 0) {
            $diff->d -= 1;
            $diff->h = $diff->h + 24;
        }
        $diff->i = ((int) $date2->format('i')) - ((int) $date1->format('i'));
        if($diff->i < 0) {
            $diff->h -= 1;
            $diff->i = $diff->i + 60;
        }
        $diff->s = ((int) $date2->format('s')) - ((int) $date1->format('s'));
        if($diff->s < 0) {
            $diff->i -= 1;
            $diff->s = $diff->s + 60;
        }
       
        return $diff;
    }
}


function sendEmailWithAttachment($to_email, $subject, $body, $from=false, $login_details=false, $attachements=array(), $html_body='', $embedded_images=array()) {
	require_once("Mail.php");
	require_once('Mail/mime.php');

    $crlf = "\n";
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = new Mail_mime($crlf);
    $mime->setTXTBody($body);

    if($embedded_images) {
        foreach ($embedded_images as $key => $value) {
            $mime->addHTMLImage($value, finfo_file($finfo, $value), $key);
            //print "Embedded $key\n";
        }
    }

    if($attachements) {
        if(!is_array($attachements)) $attachements = array($attachements);

        foreach ($attachements as $attachement_file) {
            if($attachement_file and file_exists($attachement_file)) {
                $mime->addAttachment($attachement_file, finfo_file($finfo, $attachement_file));
                //print "Attached $attachement_file\n";
            }
        }
    } 

    if($html_body) {
        if($embedded_images) {
        	$index = 0;
        	foreach ($embedded_images as $key => $value) {
	            $cid = $mime->_html_images[$index]['cid'];
	            $html_body = str_replace("%CID-$key%", $cid, $html_body);
	            $index++;
	        }
	        // print "Total Embedded images : " . count($mime->_html_images)-1;
        }
        $mime->setHTMLBody($html_body);
    }

    
    if(!$from) $from = '"Binny V A" <me@binnyva.com>';
    if(!$login_details) $login_details = array(
        'host'      => "smtp.gmail.com",
        'username'  => "madapp@makeadiff.in",
        'password'  => "madappgonemad"
    );
    
    //do not ever try to call these lines in reverse order
    $body = $mime->get();
    $headers = $mime->headers(array(
        'From'    => $from,
        'Subject' => $subject
    ));
    
    $login_details['auth'] = true;
    $smtp = Mail::factory('smtp', $login_details);
    $send = $smtp->send($to_email, $headers, $body);

    if(PEAR::isError($send)) echo $send->getMessage();
    finfo_close($finfo);
}

/**
 * Link: http://www.bin-co.com/php/scripts/load/
 * Version : 2.00.A
 */
function load($url,$options=array()) {
	$default_options = array(
		'method'		=> 'get',
		'post_data'		=> array(),		// The data that must be send to the URL as post data.
		'return_info'	=> false,		// If true, returns the headers, body and some more info about the fetch.
		'return_body'	=> true,		// If false the function don't download the body - useful if you just need the header or last modified instance.
		'cache'			=> false,		// If true, saves a copy to a local file - so that the file don't have multiple times.
		'cache_folder'	=> '/tmp/php-load-function/', // The folder to where the cache copy of the file should be saved to.
		'cache_timeout'	=> 0,			// If the cached file is older that given time in minutes, it will download the file again and cache it.
		'referer'		=> '',			// The referer of the url.
		'headers'		=> array(),		// Custom headers
		'session'		=> false,		// If this is true, the following load() calls will use the same session - until load() is called with session_close=true.
		'session_close'	=> false,
	);
	// Sets the default options.
	foreach($default_options as $opt=>$value) {
		if(!isset($options[$opt])) $options[$opt] = $value;
	}

	$url_parts = parse_url($url);
	$ch = false;
	$info = array(//Currently only supported by curl.
		'http_code'	=> 200
	);
	$response = '';
	
	
	$send_header = array(
		'User-Agent' => 'BinGet/1.00.A (http://www.bin-co.com/php/scripts/load/)'
	) + $options['headers']; // Add custom headers provided by the user.
	
	if($options['cache']) {
		$cache_folder = $options['cache_folder'];
		if(!file_exists($cache_folder)) {
			$old_umask = umask(0); // Or the folder will not get write permission for everybody.
			mkdir($cache_folder, 0777);
			umask($old_umask);
		}
		
		$cache_file_name = md5($url) . '.cache';
		$cache_file = joinPath($cache_folder, $cache_file_name); //Don't change the variable name - used at the end of the function.
		
		if(file_exists($cache_file) and filesize($cache_file) != 0) { // Cached file exists - return that.
			$timedout = false;
			if($options['cache_timeout']) {
				if(((time() - filemtime($cache_file)) / 60) > $options['cache_timeout']) $timedout = true;  // If the cached file is older than the timeout value, download the URL once again.
			}
			
			if(!$timedout) {
				$response = file_get_contents($cache_file);
				
				//Seperate header and content
				$seperator_charector_count = 4;
				$separator_position = strpos($response,"\r\n\r\n");
				if(!$separator_position) {
					$separator_position = strpos($response,"\n\n");
					$seperator_charector_count = 2;
				}
				// If the real seperator(\r\n\r\n) is NOT found, search for the first < char.
				if(!$separator_position) {
					$separator_position = strpos($response,"<"); //:HACK:
					$seperator_charector_count = 0;
				}
				
				$body = '';
				$header_text = '';
				if($separator_position) {
					$header_text = substr($response,0,$separator_position);
					$body = substr($response,$separator_position+$seperator_charector_count);
				}
				
				foreach(explode("\n",$header_text) as $line) {
					$parts = explode(": ",$line);
					if(count($parts) == 2) $headers[$parts[0]] = chop($parts[1]);
				}
				$headers['cached'] = true;
				
				if(!$options['return_info']) return $body;
				else return array('headers' => $headers, 'body' => $body, 'info' => array('cached'=>true));
			}
		}
	}

	///////////////////////////// Curl /////////////////////////////////////
	//If curl is available, use curl to get the data.
	if(function_exists("curl_init") 
				and (!(isset($options['use']) and $options['use'] == 'fsocketopen'))) { //Don't use curl if it is specifically stated to use fsocketopen in the options
		
		if(isset($options['post_data']) and $options['post_data']) { //There is an option to specify some data to be posted.
			$page = $url;
			$options['method'] = 'post';
			
			if(is_array($options['post_data'])) { //The data is in array format.
				$post_data = array();
				foreach($options['post_data'] as $key=>$value) {
					if($value)  $post_data[] = "$key=" . urlencode($value);
					else $post_data[] = $key;
				}
				$url_parts['query'] = implode('&', $post_data);
			
			} else { //Its a string
				$url_parts['query'] = $options['post_data'];
			}
		} else {
			if(isset($options['method']) and $options['method'] == 'post') {
				$page = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
			} else {
				$page = $url;
			}
		}

		if($options['session'] and isset($GLOBALS['_binget_curl_session'])) $ch = $GLOBALS['_binget_curl_session']; //Session is stored in a global variable
		else $ch = curl_init($url_parts['host']);
		
		curl_setopt($ch, CURLOPT_URL, $page) or die("Invalid cURL Handle Resouce");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Just return the data - not print the whole thing.
		curl_setopt($ch, CURLOPT_HEADER, true); //We need the headers
		curl_setopt($ch, CURLOPT_NOBODY, !($options['return_body'])); //The content - if true, will not download the contents. There is a ! operation - don't remove it.
		if(isset($options['encoding'])) curl_setopt($ch, CURLOPT_ENCODING, $options['encoding']); // Used if the encoding is gzip.
		if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $url_parts['query']);
		}
		//Set the headers our spiders sends
		curl_setopt($ch, CURLOPT_USERAGENT, $send_header['User-Agent']); //The Name of the UserAgent we will be using ;)
		unset($send_header['User-Agent']);
		
		$custom_headers = array();
		foreach($send_header as $key => $value) $custom_headers[] = "$key: $value";
		if(isset($options['modified_since']))
			$custom_headers[] = "If-Modified-Since: ".gmdate('D, d M Y H:i:s \G\M\T',strtotime($options['modified_since']));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);
		if($options['referer']) curl_setopt($ch, CURLOPT_REFERER, $options['referer']);

		curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/binget-cookie.txt"); //If ever needed...
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if(isset($url_parts['user']) and isset($url_parts['pass'])) {
			// $custom_headers[] = "Authorization: Basic ".base64_encode($url_parts['user'].':'.$url_parts['pass']);
			curl_setopt($ch, CURLOPT_USERPWD, $url_parts['user'].':'.$url_parts['pass']);
			dump($url_parts);
		}
	   
		if($custom_headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);
		$response = curl_exec($ch);
		$info = curl_getinfo($ch); //Some information on the fetch
		
		if($options['session'] and !$options['session_close']) $GLOBALS['_binget_curl_session'] = $ch; //Dont close the curl session. We may need it later - save it to a global variable
		else curl_close($ch);  //If the session option is not set, close the session.

	//////////////////////////////////////////// FSockOpen //////////////////////////////
	} else { //If there is no curl, use fsocketopen - but keep in mind that most advanced features will be lost with this approch.
		if(isset($url_parts['query'])) {
			if(isset($options['method']) and $options['method'] == 'post')
				$page = $url_parts['path'];
			else
				$page = $url_parts['path'] . '?' . $url_parts['query'];
		} else {
			$page = $url_parts['path'];
		}
		
		if(!isset($url_parts['port'])) $url_parts['port'] = 80;
		$fp = fsockopen($url_parts['host'], $url_parts['port'], $errno, $errstr, 30);
		if ($fp) {
			$out = '';
			if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
				$out .= "POST $page HTTP/1.1\r\n";
			} else {
				$out .= "GET $page HTTP/1.0\r\n"; //HTTP/1.0 is much easier to handle than HTTP/1.1
			}
			$out .= "Host: $url_parts[host]\r\n";
			if(isset($send_header['Accept'])) $out .= "Accept: $send_header[Accept]\r\n";
			$out .= "User-Agent: {$send_header['User-Agent']}\r\n";
			if(isset($options['modified_since']))
				$out .= "If-Modified-Since: ".gmdate('D, d M Y H:i:s \G\M\T',strtotime($options['modified_since'])) ."\r\n";

			$out .= "Connection: Close\r\n";
			
			//HTTP Basic Authorization support
			if(isset($url_parts['user']) and isset($url_parts['pass'])) {
				$out .= "Authorization: Basic ".base64_encode($url_parts['user'].':'.$url_parts['pass']) . "\r\n";
			}

			//If the request is post - pass the data in a special way.
			if(isset($options['method']) and $options['method'] == 'post' and $url_parts['query']) {
				$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
				$out .= 'Content-Length: ' . strlen($url_parts['query']) . "\r\n";
				$out .= "\r\n" . $url_parts['query'];
			}
			$out .= "\r\n";

			fwrite($fp, $out);
			while (!feof($fp)) {
				$response .= fgets($fp, 128);
			}
			fclose($fp);
		}
	}

	//Get the headers in an associative array
	$headers = array();

	if($info['http_code'] == 404) {
		$body = "";
		$headers['Status'] = 404;
	} else {
		//Seperate header and content
		$header_text = '';
		$body = $response;
		if(isset($info['header_size'])) {
		  $header_text = substr($response, 0, $info['header_size']);
		  $body = substr($response, $info['header_size']);
		} else {
			$header_text = reset(explode("\r\n\r\n", trim($response)));
			$body = str_replace($header_text."\r\n\r\n", '', $response);
		}		
		
		// If there is a redirect, there will be multiple headers in the response. We need just the last one.
		$header_parts = explode("\r\n\r\n", trim($header_text));
		$header_text = end($header_parts);
		
		foreach(explode("\n",$header_text) as $line) {
			$parts = explode(": ",$line);
			if(count($parts) == 2) $headers[$parts[0]] = chop($parts[1]);
		}
		
		// :BUGFIX: :UGLY: Some URLs(IMDB has this issue) will do a redirect without the new Location in the header. It will be in the url part of info. If we get such a case, set the header['Location'] as info['url']
		if(!isset($header['Location']) and isset($info['url'])) {
			$header['Location'] = $info['url'];
			$header_text .= "\r\nLocation: $header[Location]";
		}
		
		$response = $header_text . "\r\n\r\n" . $body;
	}
	
	if(isset($cache_file)) { //Should we cache the URL?
		file_put_contents($cache_file, $response);
	}

	if($options['return_info']) return array('headers' => $headers, 'body' => $body, 'info' => $info, 'curl_handle'=>$ch);
	return $body;
} 

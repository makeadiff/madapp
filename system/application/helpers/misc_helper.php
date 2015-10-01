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

function idNameFormat($data, $fields=array('id','name')) {
	$return = array();
	foreach($data as $row) {
		if(isset($fields[1])) $return[$row->$fields[0]] = stripslashes($row->$fields[1]);
		else $return[$row->$fields[0]] = $row;
	}
	
	return $return;
}

function colFormat($data) {
	$return = array();
	foreach($data as $row) $return[] = current($row);
	
	return $return;
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
	$start_month = 4; // April
	$start_year = date('Y');
	if($this_month < $start_month) $start_year = date('Y')-1;
	return $start_year;
}

/// Get the starting date of the current MAD year...
function get_mad_year_starting_date() {
	$start_month = 4; // April
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
	if($that->input->post('city_id') and $that->input->post('year') 
			and $that->user_auth->check_permission('change_city')) {
		$city_id = $that->input->post('city_id');
		$that->session->set_userdata('city_id', $city_id);
		if(isset($that->center_model)) $that->center_model->city_id = $city_id;
		if(isset($that->users_model)) $that->users_model->city_id = $city_id;
		if(isset($that->city_model)) $that->city_model->city_id = $city_id;

		$year = $that->input->post('year');
		$that->session->set_userdata('year', $year);
		if(isset($that->center_model)) $that->center_model->year = $year;
		if(isset($that->batch_model)) $that->batch_model->year = $year;
		if(isset($that->level_model)) $that->level_model->year = $year;
		if(isset($that->users_model)) $that->users_model->year = $year;
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
        'username'  => "ecs@makeadiff.in",
        'password'  => "mad4ever"
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

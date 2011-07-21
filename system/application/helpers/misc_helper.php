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
		$return[$row->$fields[0]] = stripslashes($row->$fields[1]);
	}
	
	return $return;
}

function colFormat($data) {
	$return = array();
	foreach($data as $row) $return[] = current($row);
	
	return $return;
}

/// Returns just the first name of the person.
function short_name($name) {
	return reset(explode(' ', $name));
}


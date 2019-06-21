<?php

class MY_Input extends CI_Input {
	/**
	* Clean Keys
	*
	* This is a helper function. To prevent malicious users
	* from trying to exploit keys we make sure that keys are
	* only named with alpha-numeric text and a few other items.
	*
	* @access   private
	* @param    string
	* @return   string
	*/
	function _clean_input_keys($str)
	{
	    // UPDATE: Now includes comprehensive Regex that can process escaped JSON
        if (!preg_match("/^[a-z 0-9\:\;\.\,\?\!\@\#\$%\^\*\"\~\'+=\\\ &_\/\.\[\]\-\}\{]+$/iu", $str)) {
            /**
             * Check for Development enviroment - Non-descriptive 
             * error so show me the string that caused the problem 
             */
            // if (getenv('ENVIRONMENT') && getenv('ENVIRONMENT') == 'DEVELOPMENT') {
                var_dump($str);
            // }
            exit('Disallowed Key Characters.');
        }
        
	    return $str;
	}
}
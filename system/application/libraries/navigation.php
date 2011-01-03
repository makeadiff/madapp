<?php
/**
 * Class for handling navigation
 * @author Sajith
 */
Class Navigation
{
        /**
         * Check whether any post or file upload variable
         * @return <type>
         * @author Sajith
         */
	function isPost()
	{
		if ( count ($_POST) == 0   &&  count ($_FILES) == 0 )
				return 	false;

		return true;
	}

	function banIf($expression)
	{
		//if the expression is true
		if($expression)
		{
			Navigation::showFlash();
			redirect('banned/index');
			exit();
		}
	}

	function redirect404If($expression)
	{
		if($expression)
			redirect('404');
	}

	function logout()
	{
		//write here the code for clear all the user logged session
	}

	/**
	* function for displaying not authorized flash
	* @author Sajith
	**/
	private function showFlash($message = FALSE )
	{
		if($message === FALSE)
			$message = 'You are not authorised to view this page';
		$this->session->set_flashdata('message', $message);
	}
      public function isAjax() {

      return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&

        ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));

        }
}


?>
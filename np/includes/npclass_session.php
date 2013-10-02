<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

class session
{

	var $vars = array (NULL);

	function set($variable, $value)
	{
		$_SESSION[SESSION_PREFIX.$variable] = $value;
		$this->vars[$variable] = $value;
	}

	function unregister($variable)
	{
		if (isset($_SESSION[SESSION_PREFIX.$variable]))
		{
			unset($_SESSION[SESSION_PREFIX.$variable]);
			$this->vars[$variable] = NULL;
		}
	}

	function destroy()
	{
		session_destroy();
	}

	function value($variable)
	{
		return $_SESSION[SESSION_PREFIX.$variable];
	}

	function is_set($variable)
	{
		return (!empty($_SESSION[SESSION_PREFIX.$variable])) ? TRUE : FALSE;
	}

	function set_cookie($variable, $value)
	{
		$exp_date = 30 * 24 * 60 * 60; // 30 days
		
		setcookie(SESSION_PREFIX.$variable, $value, time() + $exp_date);
	}
	
	function unset_cookie($variable)
	{
		setcookie(SESSION_PREFIX.$variable, '');		
	}
	
	function cookie_value($variable)
	{
		return (isset($_COOKIE[SESSION_PREFIX.$variable]))?$_COOKIE[SESSION_PREFIX.$variable]:'';
	}
}
?>

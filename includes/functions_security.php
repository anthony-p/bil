<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

function cleanData()
{
	define(ENT_QUOTES, 1); ## so that ' => &#039;
	
   foreach($_GET as $k => $v)
	{
   	if (is_array($_GET[$k]))
		{
			$count_get = count($_GET[$k]);
			for ($i=0; $i<$count_get; $i++)
			{
				if (!get_magic_quotes_gpc()) $_GET[$k][$i] = addslashes(htmlspecialchars($_GET[$k][$i], ENT_QUOTES));
				else $_GET[$k][$i] = htmlspecialchars($_GET[$k][$i], ENT_QUOTES);
			}
		}
		else
		{
			if (!get_magic_quotes_gpc()) $_GET[$k] = addslashes(htmlspecialchars($v, ENT_QUOTES));
			else $_GET[$k] = htmlspecialchars($v, ENT_QUOTES);
		}
	}
   foreach($_POST as $k => $v)
	{
   	if (is_array($_POST[$k]))
		{
			$count_post = count($_POST[$k]);
			for ($i=0; $i<$count_post; $i++)
			{
				if (!isset($_POST[$k][$i]))
                    continue;
                if (!get_magic_quotes_gpc()) $_POST[$k][$i] = addslashes(htmlspecialchars($_POST[$k][$i], ENT_QUOTES));
				else $_POST[$k][$i] = htmlspecialchars($_POST[$k][$i], ENT_QUOTES);
			}
		}
		else
		{
			if (!get_magic_quotes_gpc()) $_POST[$k] = addslashes(htmlspecialchars($v, ENT_QUOTES));
			else $_POST[$k] = htmlspecialchars($v, ENT_QUOTES);
		}
	}
   foreach($_REQUEST as $k => $v)
	{
   	if (is_array($_REQUEST[$k]))
		{
			$count_request = count($_REQUEST[$k]);
			for ($i=0; $i<$count_request; $i++)
			{
                if (!isset($_REQUEST[$k][$i]))
                    continue;
				if (!get_magic_quotes_gpc()) $_REQUEST[$k][$i] = addslashes(htmlspecialchars($_REQUEST[$k][$i], ENT_QUOTES));
				else $_REQUEST[$k][$i] = htmlspecialchars($_REQUEST[$k][$i], ENT_QUOTES);
			}
		}
		else
		{
			if (!get_magic_quotes_gpc()) $_REQUEST[$k] = addslashes(htmlspecialchars($v, ENT_QUOTES));
			else $_REQUEST[$k] = htmlspecialchars($v, ENT_QUOTES);
		}
	}
}

function ppb_mcrypt_encode($decrypted_value)
{
	global $setts;
	(string) $encrypted_value = null;

	if ($setts['mcrypt_enabled'])
	{
		## using the tripledes encryption algorythm
		$td = mcrypt_module_open('tripledes', '', 'ofb', '');

		srand();
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);

		$key = substr(md5($setts['mcrypt_key']), 0, $ks);

		mcrypt_generic_init($td, $key, $iv);
		$encrypted_value = mcrypt_generic($td, $decrypted_value);
		mcrypt_generic_deinit($td);
	}
	else
	{
		$encrypted_value = $decrypted_value;
	}

	return $encrypted_value;
}

function ppb_mcrypt_decode($encrypted_value)
{
	global $setts;
	(string) $decrypted_value = null;

	if ($setts['mcrypt_enabled'])
	{
		## using the tripledes encryption algorythm
		$td = mcrypt_module_open('tripledes', '', 'ofb', '');

		srand();
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);

		$key = substr(md5($setts['mcrypt_key']), 0, $ks);

		mcrypt_generic_init($td, $key, $iv);
		$decrypted_value = mdecrypt_generic($td, $encrypted_value);
		mcrypt_generic_deinit($td);
	}
	else
	{
		$decrypted_value = $encrypted_value;
	}

	return $decrypted_value;
}

function intval_abs($value)
{
	return abs(intval($value));
}

?>

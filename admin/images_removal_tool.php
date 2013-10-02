<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_ADMIN', 1);

include_once ('../includes/global.php');

if ($session->value('adminarea')!='Active')
{
	header_redirect('login.php');
}
else
{
	include_once ('header.php');

	$deletion = FALSE;
	$total_files_size = 0;
	
	$dir = substr($_SERVER['SCRIPT_FILENAME'],0,-29);
	
	$template->set('header_section', AMSG_AUCTIONS_MANAGEMENT);
	$template->set('subpage_title', AMSG_OLD_IMAGES_REMOVAL_TOOL);
	
	function filter_unused( $all, $used, $prefix_all = null, $prefix_used = null) 
	{
		$unused = array();
	
		// prefixes are not needed for sorting
		sort( $all );
		sort( $used );
	
		$a = 0;
		$u = 0;
	
		$maxa = sizeof($all)-1;
		$maxu = sizeof($used)-1;
	
		while( true ) 
		{
			if( $a > $maxa ) 
			{
				// done; rest of $used isn't in $all
				break;
			}
			if( $u > $maxu ) 
			{
				// rest of $all is unused
				for( ; $a <= $maxa; $a++ ) 
				{
					$unused[] = $all[$a];
				}
				break;
			}
	
			if( $prefix_all.$all[$a] > $prefix_used.$used[$u] ) 
			{
				// $used[$u] isn't in $all?
				$u++;
				continue;
			}
	
			if( $prefix_all.$all[$a] == $prefix_used.$used[$u] ) 
			{
				// $all[$a] is used
				$a++;
				$u++;
				continue;
			}
	
			$unused[] = $all[$a];
	
			$a++;
		}
	
		return $unused;
	}
	
	
	if (isset($_POST['form_proceed'])) 
	{
		
		$msg_changes_saved = '<br><table width="100%" border="0" cellspacing="3" cellpadding="3" class="border">'; 	
		
		$deletion=TRUE;
		$exit_loop = FALSE;
		$time_start1 = getmicrotime();
			
		$rep = opendir($dir . '/uplimg/');
		$counter = 0;
		
		## - users ('shop_logo_path' field)
		## - auction_media ('media_url' field)
		## - categories ('image_path' field)
		## gather all in an array
				
		$files = array();		
		while (($file = readdir($rep)) && (!$exit_loop))
		{
			if($file != '..' && $file !='.' && $file !='' && $file !='index.htm')
			{
				$files[] = $file;
				//echo $file."<br>";
				$total_files_size += filesize($dir . '/uplimg/' . $file);			
			
				
			}			
		}
		
		$db_media = array();
		//SELECT picpath AS imgremoval_url FROM " . DB_PREFIX . "auctions WHERE picpath!='' UNION 
		$sql_select_media = $db->query("SELECT media_url AS imgremoval_url FROM " . DB_PREFIX . "auction_media WHERE media_url!='' 
			UNION
			SELECT shop_logo_path AS imgremoval_url FROM " . DB_PREFIX . "users WHERE shop_logo_path!='' 
			UNION
			SELECT image_path AS imgremoval_url FROM " . DB_PREFIX . "categories WHERE image_path!='' 
			UNION 
			SELECT advert_img_path AS imgremoval_url FROM " . DB_PREFIX . "adverts WHERE advert_img_path!='' 
			UNION 
			SELECT logo_url AS imgremoval_url FROM " . DB_PREFIX . "payment_options WHERE logo_url!='' 
			UNION 
			SELECT site_logo_path AS imgremoval_url FROM " . DB_PREFIX . "gen_setts WHERE site_logo_path!='' ");
		
		while ($img_removal = $db->fetch_array($sql_select_media)) 
		{
			$db_media[] = eregi_replace('uplimg/', '', $img_removal['imgremoval_url']);
		}
		
		natcasesort($files);
		natcasesort($db_media);
		$obsolete_files = filter_unused($files, $db_media);
				
		$nb_obs = count($obsolete_files);
	
		$ending = ($nb_obs>100) ? 100 : $nb_obs;
		
		$exit_loop = ($nb_obs>100) ? TRUE : FALSE;
		
		if ($nb_obs) 
		{
			$msg_changes_saved .= '<tr class="c1"><td>';
		}
	
		for ($i=0; $i<$ending; $i++) 
		{
			$obsolete_file = trim($obsolete_files[$i]);
			if (!empty($obsolete_file)) 
			{ 
				$msg_changes_saved .= '<strong>' . AMSG_PROCESSING . '</strong> ' . $obsolete_file;
				
				$file_size = filesize($dir . '/uplimg/' . $obsolete_file);			
				$is_deleted = @unlink($dir . '/uplimg/' . $obsolete_file);
				
				if ($is_deleted)
				{
					$counter++;
					$deleted_files_size += $file_size;
				}
				
				$msg_changes_saved .= ' -> <font color="' . (($is_deleted) ? 'green' : 'red') . '"><strong>' . (($is_deleted) ? AMSG_DELETED : AMSG_NOT_DELETED) . '</strong></font><br>';
			}
		}
	
		if ($nb_obs) 
		{
			$msg_changes_saved .= '</td></tr>';
		}
		
		$msg_changes_saved .= '<tr class="c1"> '.
			'	<td>'.
			'		<strong>Total Files</strong>: ' . count($files) . '. '.
			'		<strong>Total Size</strong>: ' . number_format($total_files_size/1024, 2, '.', ',') . ' KB.'.
			'		<br><strong>Total Media in Database</strong>: ' . count($db_media) .
			'		<br><strong>Total Obsolete Files</strong>: ' . count($obsolete_files) . 
			'		<br><strong>Obsolete Files Erased in this Session</strong>: ' . $counter . 
			'	</td> ' .
			'</tr></table><br>';
			
		closedir($rep);
		clearstatcache(); 
		
		$time_end1 = getmicrotime();
		$time_passed = $time_end1- $time_start1;
	}
	
	if ($deletion) 
	{ 
		$management_box = '<table width="100%" border="0" cellspacing="3" cellpadding="3" class="border"> '.
	  		'	<tr class="c2"> '.
	    	'		<td><b>' . AMSG_CLEANUP_SUCCESS . '</b>.<br> <strong>' . $counter . '</strong> ' . AMSG_IMGS_HAVE_BEEN_DELETED_TOTALING . 
	    	'			<strong>' . number_format($deleted_files_size/1024, 2, '.', ',') . '</strong> KB. '.
	    	AMSG_OPERATION_LASTED . ' <strong>' . number_format($time_passed, 4) . '</strong> ' . GMSG_SECONDS;
	   
	   if ($exit_loop)
	   {
	   	$management_box .= '<br><strong>' . AMSG_IMPORTANT . ':</strong> ' . AMSG_IMAGES_STALL_NOTICE;
	   }
	   
	   $management_box .= '</td></tr></table><br>';
	}

	$template->set('msg_changes_saved', $msg_changes_saved);
	$template->set('management_box', $management_box);
	
	$template_output .= $template->process('images_removal_tool.tpl.php');

	include_once ('footer.php');

	echo $template_output;
}
?>

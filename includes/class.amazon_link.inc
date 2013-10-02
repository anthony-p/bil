<?
/**
 *
 *
CREATE TABLE `amazon_tracking_links` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tracking_id` mediumtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `amazon_url_id` int(4) NOT NULL,
  `click_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `target_url` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
 *
 */
class AmazonTrackingURL {
    
    private static $_fields = array(
	'id','tracking_id','user_id','np_userid','amazon_url_id','dt_modify','click_date','target_url'
    );
    private static $TABLE = 'amazon_tracking_links';
    
    /**
     * use this to get the first free Amazon tracking code (between 1 and 100);
     * NOTE: this function doesn't update nothing on tracking table! the update part
     * is in parseUrl function, where a current tracking link is generated
     *
     * usage: if needed, use that in a static way: after including this file, call it like
     *
     * AmazonTrackingURL::getFirstFree();
     *
     */
    public static function getFirstFree() {
        global $db;
        
        $_q = "SELECT MAX(amazon_url_id) as last_id from ".self::$TABLE." WHERE click_date = (SELECT MAX(click_date) from ".self::$TABLE.")";
	try {
	    $first = $db->get_sql_field($_q,"last_id");
	    if(empty($first) || $first == 100) {
		return 1;
	    }
	    else {
		return $first + 1;
	    }    
	}
	catch(Error $e) {
	    return 1;
	}
        
    }
    
    /**
     * actually parses some Amazon URL to replace simple tracking tag with extended tag.
     * on a successful call, this function does update Amazon tracking table and returns
     * modified URL.
     * 
     * usage: use that in a static way: after including this file, call it like
     *
     * AmazonTrackingURL::parseUrl([amazon_url]);
     *
     * params:
     *
     *  - $url [string, requested] the simple Amazon url to be extended
     *  - $main_tag [string, optional] template of tracking tag in url;
     *      should always be in format [keyname]-[main amazon code].
     *      key will be rewritten as [keyname][link sequence]-[main amazon code].
     *
     */
    public static function parseUrl($url, $main_tag = 'mainsailstore-20') {
        global $db, $session;
        
        $uid = $session->value('user_id');
	if(!empty($_COOKIE['np_userid']) && is_numeric($_COOKIE['np_userid'])) {
	    // we got a preference in cookies...
	    
	     if(!empty($url) && !empty($main_tag)) {
                $sequence = self::getFirstFree();
                if(!is_numeric($sequence) || $sequence < 0 || $sequence > 100) {
                    $sequence = 1;
                }
                $keys = explode('-',$main_tag);
                $new_tag = "{$keys[0]}{$sequence}-{$keys[1]}";
                
                $new_url = str_ireplace($main_tag,$new_tag,$url);
		
		if(empty($uid))
		    $uid = session_id();
                
                // update db...
                $_q  = "INSERT INTO
			    ".self::$TABLE."
				(tracking_id, user_id, np_userid, amazon_url_id,target_url)
			VALUES
			    ('{$new_tag}','{$uid}','{$_COOKIE['np_userid']}',{$sequence},'".addslashes($new_url)."')
		";
                $db->query($_q);
                
                return $new_url;
            }
	}
        else if(is_numeric($uid)) {
	    // user is logged...
            if(!empty($url) && !empty($main_tag)) {
                $sequence = self::getFirstFree();
                if(!is_numeric($sequence) || $sequence < 0 || $sequence > 100) {
                    $sequence = 1;
                }
                $keys = explode('-',$main_tag);
                $new_tag = "{$keys[0]}{$sequence}-{$keys[1]}";
                
                $new_url = str_ireplace($main_tag,$new_tag,$url);        
                
		$np_query = $db->query("SELECT npuser_id FROM " . DB_PREFIX . "users WHERE user_id={$uid}");
		$np = $db->fetch_array($np_query);
		
		if(is_array($np) && count($np) > 0) {
		    $np_selected = $np[0];
		}
		
		if(!empty($np_selected)) {
		    // we got a preference in user account...
		    // get NP ID
		    $np_userid = $np_selected;
		    
		}
		else {
		    // user logged but no preference...quick select??
		    if(empty($_REQUEST['SKIP_NP_SELECTION'])) {
			// first time, let's ask for a pref...
			return $_SERVER['REQUEST_URI']."&np_selection=true";
		    }
		    else {
			// user already declined selection, let's go to URL
			$np_userid = '';
		    }
		}
		
		// update db...
		$_q  = "INSERT INTO ".self::$TABLE."(tracking_id, user_id, np_userid, amazon_url_id,target_url) VALUES('{$new_tag}','{$uid}',{$np_userid},{$sequence},'".addslashes($new_url)."') ";
		$db->query($_q);
                
                return $new_url;
            }
        }
        else {
	    // user not logged and no cookie: we have to ask if she/he wants to select one...
	    if(empty($_REQUEST['SKIP_NP_SELECTION'])) {
		// first time, let's ask for a pref...
		return $_SERVER['REQUEST_URI']."&np_selection=true";
	    }
	    else {
		// user already declined selection, let's go to URL
		$np_userid = '';
		$sequence = self::getFirstFree();
                if(!is_numeric($sequence) || $sequence < 0 || $sequence > 100) {
                    $sequence = 1;
                }
                $keys = explode('-',$main_tag);
                $new_tag = "{$keys[0]}{$sequence}-{$keys[1]}";
		
		if(empty($uid))
		    $uid = session_id();
		if(empty($np_userid))
		    $np_userid = 'NULL';
                
                $new_url = str_ireplace($main_tag,$new_tag,$url);
		$_q  = "INSERT INTO ".self::$TABLE."(tracking_id, user_id, np_userid, amazon_url_id,target_url) VALUES('{$new_tag}','{$uid}',{$np_userid},{$sequence},'".addslashes($new_url)."') ";
		$db->query($_q);
                
                return $new_url;
	    }
        }
    }
    
    /**
     * takes some Amazon url and gives back switched version, to use in banners.
     * 
     * usage: use that in a static way: after including this file, call it like
     *
     * AmazonTrackingURL::generateSwitchingUrl([amazon_destination_url]);
     *
     * params:
     *
     *  - $amazon_url [string, requested] Amazon destination URL
     *
     */
    public static function generateSwitchingUrl($amazon_url) {
        if(!empty($amazon_url)) {
	    return '/amazon_switch.php?amazon_url='.urlencode($amazon_url);
        }
        else {
            return $url;
        }
    }
    
    
    /**
     * function prints a simple report table on amazon url clicks;
     * 
     * usage: use that in a static way: after including this file, call it like
     *
     * AmazonTrackingURL::printReport([tracking number]);
     *
     * params:
     *
     *  - $tracking_sequence_number [integer, requested] Amazon tracking sequence number (1 to 100)
     *
     */
    public static function printReport($tracking_sequence_number = 1) {
        global $db, $session;
        
        if(is_numeric($tracking_sequence_number) and $tracking_sequence_number > 0 and $tracking_sequence_number < 101) {
            $_q  = "
                SELECT
                    t.*, u.username
                FROM
                    ".self::$TABLE." t
                    join probid_users u on t.user_id = u.user_id
                WHERE
                    t.amazon_url_id = {$tracking_sequence_number}
                ORDER BY
                    t.click_date DESC, t.id DESC
            ";
            $results = $db->query($_q);
            if(mysql_numrows($results) > 0) {
                $table = '
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>tracking tag</th>
                            <th>user_id</th>
                            <th>npuser_id</th>
                            <th>user_name</th>
                            <th>amazon tracking sequence</th>
                            <th>click date</th>
                            <th>url</th>
                        </tr>
                ';
                while($data = mysql_fetch_array($results,MYSQL_ASSOC)) {
                    $table.=  "
                        <tr>
                            <td>{$data['id']}</td>
                            <td>{$data['tracking_id']}</td>
                            <td>{$data['user_id']}</td>
                            <td>{$data['npuser_id']}</td>
                            <td>{$data['username']}</td>
                            <td>{$data['amazon_url_id']}</td>
                            <td>".date("d/m/Y h:i:s A",strtotime($data['click_date']))."</td>
                            <td><a href='".stripslashes($data['target_url'])."'>".stripslashes($data['target_url'])."</a></td>
                        </tr>";
                }
                $table.= "</table>";
                
                return $table;
            }
            else {
                // no results
                return "no results for selected tracking ID [{$tracking_sequence_number}]";
            }
        }
        else {
            return "invalid sequence number [{$tracking_sequence_number}], should be between 1 and 100";
        }
    }
}
?>
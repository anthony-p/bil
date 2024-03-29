<?php


include_once("npfunctions_login.php");
include_once("npclass_custom_field.php");

class npuser extends npcustom_field
{
    var $item;

    function create_salt()
    {
        $rand = md5(rand(2, 99999999));
        $output = substr($rand, 0, 3);

        return $output;
    }

    function delete_campaign($id)
    {
        $select_query = $sql_select_query = "SELECT banner, logo FROM np_users " .
            "WHERE user_id=" . $id;

        $sql_select_result = $this->query($select_query);

        $result_array = array();

        while ($result =  mysql_fetch_array($sql_select_result)) {
            if (isset($result['logo']) && file_exists('../..' . $result['logo'])) {
                unlink('../..' . $result['logo']);
            }
            if (isset($result['banner']) && file_exists('../..' . $result['banner'])) {
                unlink('../..' . $result['banner']);
            }
        }

        $delete_query = "DELETE FROM " . NPDB_PREFIX . "users WHERE user_id=" . $id;
        $this->query($delete_query);
    }

    function selectAll()
    {
        if (isset($_GET["search"]) && $_GET["search"]) {
            $search = mysql_real_escape_string($_GET["search"]);
            $sql_select_query = "SELECT " . NPDB_PREFIX . "users.banner, " . NPDB_PREFIX . "users.name, " .
                NPDB_PREFIX . "users.description, " . NPDB_PREFIX . "users.city, " .
                NPDB_PREFIX . "users.username, " . NPDB_PREFIX . "users.payment, " .
                NPDB_PREFIX . "users.founddrasing_goal, " .
                NPDB_PREFIX . "users.payment, " .
                NPDB_PREFIX . "users.price, " . NPDB_PREFIX . "users.end_date, bl2_users.first_name, " .
                " bl2_users.last_name, bl2_users.organization, bl2_users.email " .
                " FROM " . NPDB_PREFIX . "users, bl2_users " .
                "WHERE " . NPDB_PREFIX . "users.probid_user_id=bl2_users.id AND (name LIKE '%" .
                $search . "%' OR description LIKE '%" .
                $search . "%' OR project_title LIKE '%" .
                $search . "%' OR campaign_basic LIKE '%" .
                $search . "%' OR orgtype LIKE '%" .
                $search . "%' OR np_users.tax_company_name LIKE '%" .
                $search . "%' OR pitch_text LIKE '%" .
                $search . "%') ORDER BY reg_date DESC";
        } elseif (isset($_GET["keyword"]) && $_GET["keyword"]) {
            $order = "DESC";
            if (isset($_GET["names"]) && in_array($_GET["names"], array("ASC", "DESC"))) {
                $order = $_GET["names"];
            }
            $search = mysql_real_escape_string($_GET["keyword"]);
            $sql_select_query = "SELECT " . NPDB_PREFIX. "users.username," . NPDB_PREFIX . "users.banner, " . NPDB_PREFIX . "users.name, " .
                NPDB_PREFIX . "users.description, " . NPDB_PREFIX . "users.city, " . NPDB_PREFIX . "users.price, " .
                NPDB_PREFIX . "users.end_date, bl2_users.first_name, " .
                NPDB_PREFIX . "users.founddrasing_goal, " .
                NPDB_PREFIX . "users.project_title, " .
                NPDB_PREFIX . "users.payment, " .
                " bl2_users.last_name, bl2_users.organization, bl2_users.email " .
                " FROM " . NPDB_PREFIX . "users, bl2_users " .
                "WHERE " . NPDB_PREFIX . "users.probid_user_id=bl2_users.id AND name LIKE '%" .
                $search . "%' ORDER BY reg_date " . $order;
        } elseif (isset($_GET["category"]) && $_GET["category"]) {
            $search = mysql_real_escape_string($_GET["category"]);
            $sql_select_query = "SELECT " . NPDB_PREFIX. "users.username," . NPDB_PREFIX . "users.banner, " . NPDB_PREFIX . "users.name, " .
                NPDB_PREFIX . "users.description, " . NPDB_PREFIX . "users.city, " . NPDB_PREFIX . "users.price, " .
                NPDB_PREFIX . "users.end_date, bl2_users.first_name, bl2_users.organization, " .
                NPDB_PREFIX . "users.founddrasing_goal, " .
                NPDB_PREFIX . "users.project_title, " .
                NPDB_PREFIX . "users.payment, " .
                " bl2_users.last_name, bl2_users.email " .
                " FROM " . NPDB_PREFIX . "users, bl2_users " .
                "WHERE " . NPDB_PREFIX . "users.probid_user_id=bl2_users.id AND " . NPDB_PREFIX .
                "users.project_category=" . $search . " ORDER BY reg_date DESC";
        } else {
            $order = "DESC";
            if (isset($_GET["names"]) && in_array($_GET["names"], array("ASC", "DESC"))) {
                $order = $_GET["names"];
            }
            $sql_select_query = "SELECT " . NPDB_PREFIX. "users.username," . NPDB_PREFIX . "users.banner, " . NPDB_PREFIX . "users.name, " .
                NPDB_PREFIX . "users.description, " . NPDB_PREFIX . "users.city, " . NPDB_PREFIX . "users.price, " .
                NPDB_PREFIX . "users.end_date, " .
                NPDB_PREFIX . "users.founddrasing_goal, " .
                NPDB_PREFIX . "users.project_title, " .
                NPDB_PREFIX . "users.payment, " .
                " bl2_users.first_name, bl2_users.last_name, bl2_users.organization, bl2_users.email FROM " .
                NPDB_PREFIX . "users, bl2_users WHERE " . NPDB_PREFIX .
                "users.probid_user_id=bl2_users.id ORDER BY reg_date " . $order;
        }

        $sql_select_result = $this->query($sql_select_query);

        $result_array = array();

        while ($result =  mysql_fetch_array($sql_select_result)) {
            if (isset($result["end_date"]) && $result["end_date"]) {
                $result["days_left"] = round(($result["end_date"] - time()) / 86400);
                if ($result["days_left"] < 0)
                    $result["days_left"] = 0;
            } else {
                $result["days_left"] = 0;
            }
            $end_time = $result['end_date'] ? $result['end_date'] : 0;
            $create_time = $result['reg_date'] ? $result['reg_date'] : 1;
            $current_time = time();
            $completed = round((($current_time - $create_time) / ($end_time- $create_time)) * 100);
            $percent=$completed*100/194;

            $result["percent"] = $percent;

            $result_array[] = $result;
        }

        return $result_array;
    }

    function selectAllLive()
    {
        $time = time();
        $ordering = "  ORDER BY start_date DESC, reg_date DESC";
        $group = " GROUP BY np_users.user_id ";
        $fields = NPDB_PREFIX . "users.banner, " . NPDB_PREFIX . "users.name, " .
            NPDB_PREFIX . "users.description, " . NPDB_PREFIX . "users.city, " .
            NPDB_PREFIX . "users.username, " . NPDB_PREFIX . "users.payment, " .
            NPDB_PREFIX . "users.price, " . NPDB_PREFIX . "users.end_date, bl2_users.first_name, " .
            NPDB_PREFIX . "users.founddrasing_goal, " .
            NPDB_PREFIX . "users.project_title, " .
            NPDB_PREFIX . "users.votes, " .
            " bl2_users.last_name, bl2_users.organization, bl2_users.email, bl2_users.id,
            (SELECT COUNT(*)
            FROM project_votes
            WHERE project_votes.campaign_id = np_users.user_id
            AND MONTH(FROM_UNIXTIME(project_votes.date)) = MONTH(NOW())
            ) AS votes ";
        $join = " FROM " . NPDB_PREFIX . "users INNER JOIN bl2_users " .
            " ON " . NPDB_PREFIX . "users.probid_user_id=bl2_users.id";
        if (isset($_GET["order_by"]) && $_GET["order_by"] &&
            isset($_GET["order_type"]) && $_GET["order_type"] &&
            in_array($_GET["order_type"], array("ASC", "DESC"))) {
            $ordering = " ORDER BY " . $_GET["order_by"] . " " . $_GET["order_type"];
			$ordering = $ordering.", reg_date DESC";
        }
        if (isset($_GET["search"]) && $_GET["search"]) {
            $search = mysql_real_escape_string($_GET["search"]);
            $sql_select_query = "SELECT " . $fields .
                $join .
                " WHERE np_users.active=1 AND np_users.disabled=0 AND np_users.end_date>" . $time .
                " AND (name LIKE '%" .
                $search . "%' OR description LIKE '%" .
                $search . "%' OR project_title LIKE '%" .
                $search . "%' OR campaign_basic LIKE '%" .
                $search . "%' OR orgtype LIKE '%" .
                $search . "%' OR np_users.tax_company_name LIKE '%" .
                $search . "%' OR pitch_text LIKE '%" .
                $search . "%') " . $group . $ordering;
        } elseif (isset($_GET["keyword"]) && $_GET["keyword"]) {
            if (isset($_GET["names"]) && in_array($_GET["names"], array("ASC", "DESC"))) {
                $order = $_GET["names"];
                $ordering = " ORDER BY start_date ".$order.",reg_date " . $order;
            }
            $search = mysql_real_escape_string($_GET["keyword"]);
            $sql_select_query = "SELECT " . $fields .
                $join .
                " WHERE np_users.active=1 AND np_users.disabled=0 AND np_users.end_date>" . $time .
                " AND (name LIKE '%" .
                $search . "%' OR description LIKE '%" .
                $search . "%' OR project_title LIKE '%" .
                $search . "%' OR campaign_basic LIKE '%" .
                $search . "%' OR orgtype LIKE '%" .
                $search . "%' OR np_users.tax_company_name LIKE '%" .
                $search . "%' OR pitch_text LIKE '%" .
                $search . "%') " . $group . $ordering;
        } elseif (isset($_GET["category"]) && $_GET["category"]) {
            $search = mysql_real_escape_string($_GET["category"]);
            $sql_select_query = "SELECT " . $fields .
                $join .
                " WHERE np_users.disabled=0  AND " . NPDB_PREFIX .
                "users.project_category=" . $search . " AND np_users.active=1 AND np_users.end_date>" .
                $time . $group . $ordering;
        } elseif (isset($_GET["city"]) && $_GET["city"]) {
            $search = mysql_real_escape_string($_GET["city"]);
            $sql_select_query = "SELECT " . $fields .
                $join .
                " WHERE np_users.disabled=0  AND " . NPDB_PREFIX .
                "users.city='" . $search . "' AND (np_users.active=1 OR np_users.end_date>" .
                $time .') '. $group . $ordering;
        }
        else {
            if (isset($_GET["names"]) && in_array($_GET["names"], array("ASC", "DESC"))) {
                $order = $_GET["names"];
                $ordering = " ORDER BY start_date ".$order.",reg_date " . $order;
            }
            $sql_select_query = "SELECT " . $fields . $join .
                " WHERE np_users.disabled=0  AND np_users.active=1 AND np_users.end_date>" .
                $time . $group . $ordering;
        }

        $sql_select_result = $this->query($sql_select_query);

        $result_array = array();

        while ($result =  mysql_fetch_array($sql_select_result)) {
            if (isset($result["end_date"]) && $result["end_date"]) {
                $result["days_left"] = round(($result["end_date"] - time()) / 86400);
                if ($result["days_left"] < 0)
                    $result["days_left"] = 0;
            } else {
                $result["days_left"] = 0;
            }
            $end_time = (isset($result['end_date']) && $result['end_date']) ?
                $result['end_date'] : 0;
            $create_time = (isset($result['reg_date']) && $result['reg_date']) ?
                $result['reg_date'] : 1;
            $current_time = time();
            $completed = round((($current_time - $create_time) / ($end_time- $create_time)) * 100);
            $percent=$completed*100/194;

            $result["percent"] = $percent;

            $result_array[] = $result;
        }

        return $result_array;
    }

    /**
     * @return array
     */
    function get_closed_campaigns()
    {
        $current_time = strtotime('today');
        $closed_campaigns = array();
        $select_query_result = $this->query(
            "SELECT user_id, reg_date, clone_campaign, deadline_type_value, time_period, certain_date, end_date, keep_alive_days
            FROM " . NPDB_PREFIX . "users WHERE end_date<=" . $current_time . " AND active = 1"
        );

        while ($query_result =  mysql_fetch_assoc($select_query_result)) {
            $closed_campaigns[] = $query_result;
        }

        return $closed_campaigns;
    }

    function close_campaigns($closed_campaigns) {

        if (is_array($closed_campaigns)) {

            print_r("Closed campaigns id's:");

            foreach ($closed_campaigns as $closed_campaign) {
                
                $this->query(
                    "UPDATE np_users SET active = 2 WHERE user_id = " . $closed_campaign['user_id']
                );
                print_r($closed_campaign['user_id'] . ', ');

            }

        }
    }

    function new_renew_campaigns() {

        $autorenew_result = $this->query("SELECT * FROM " . NPDB_PREFIX . "users WHERE autorenew > 0 AND active = 1");

        while ($query_result =  mysql_fetch_assoc($autorenew_result)) {

            $old_campaigns[] = $query_result;

        }

        if (!$old_campaigns) {
            return "No campaigns effected.";
        }

        $cfc_flag = 0;
        foreach ($old_campaigns as $old_campaign) {
            if (date('d F Y', $old_campaign['end_date']) == date('d F Y', strtotime('today'))) {

                $new_campaign = $old_campaign;

                $old_campaign['active'] = 2;
                $old_id = $old_campaign['user_id'];
                unset($old_campaign['user_id']);

                if (is_null($old_campaign['start_date'])) {
                    $new_campaign['end_date'] = time() + (time() - $old_campaign['reg_date']);
                } else
                    $new_campaign['end_date'] = time() + ($old_campaign['end_date'] - $old_campaign['start_date']);

                $new_campaign['active'] = 1;
                $new_campaign['start_date'] = time();
                $new_campaign['payment'] = 0;
                $new_campaign['autorenew'] = ($old_campaign['autorenew'] >= 1) ? $old_campaign['autorenew'] - 1 : 0;
                $new_campaign['keep_comments'] = $old_campaign['keep_comments'];
                $new_campaign['keep_updates'] = $old_campaign['keep_updates'];
                $new_campaign['keep_rewards'] = $old_campaign['keep_rewards'];
                unset($new_campaign['user_id']);

                $suffix = $this->query("SELECT COUNT(*) FROM  `np_users` WHERE  `username` REGEXP '{$old_campaign['username']}'");
                $suffix = mysql_fetch_row($suffix);
                $new_campaign['username'] = $old_campaign['username'];
                $old_campaign['username'] = $old_campaign['username'] . '_' . $suffix[0];

                if ($old_campaign['cfc'] == 1 && !$cfc_flag) {
                    $old_campaign['cfc'] = 0;
                    $new_campaign['cfc'] = 1;
                    $new_campaign['active'] = 0;
                    $cfc_flag = 1;
                    if (date('d F Y', time()) != date('d F Y', strtotime('last day of this month')))
                        $new_campaign['start_date'] = strtotime('tomorrow');
                    else 
                        $new_campaign['start_date'] = strtotime('first day of next month');
                    $new_campaign['end_date'] = strtotime('last day of next month');
                }

                $update_query = "UPDATE " . NPDB_PREFIX . "users SET";
                foreach ($old_campaign as $key => $value) {
                    $keys[] = $key;
                    $values[] = $value;
                    $keys_old = implode(', ', $keys);
                    $values_old = implode(', ', $values);
                    $update_query .= " " . $key . "='" . $value . "',";
                }
                $update_query = substr($update_query, 0, -1);
                $update_query .= " WHERE user_id=$old_id";            

                $this->query($update_query);

                unset($keys);
                unset($values);  

                foreach ($new_campaign as $key => $value) {
                    $keys[] = $key;
                    $values[] = "'" . $value . "'";
                }

                $keys_new = implode(', ', $keys);
                $values_new = implode(', ', $values);

                $this->query("INSERT INTO " . NPDB_PREFIX . "users ($keys_new) VALUES ($values_new)");
                $new_id = mysql_insert_id();

                if ($old_campaign['keep_rewards'] == 1) {
                    
                    $this->query("
                        INSERT INTO project_rewards (`project_id`, `amount`, `name`, `short_description`, `description`, `estimated_delivery_date`, `shipping_address_required`, `available_number`, `given_number`)
                        SELECT $new_id, `amount`, `name`, `short_description`, `description`, `estimated_delivery_date`, `shipping_address_required`, `available_number`, `given_number` 
                        FROM project_rewards 
                        WHERE `project_id`=$old_id
                    ");

                }

                if ($old_campaign['keep_comments'] == 1) {
                    
                    $this->query("
                        INSERT INTO project_comment (`user_id`, `project_id`, `parrent_id`, `comment`, `create_at`)
                        SELECT `user_id`, $new_id, `parrent_id`, `comment`, `create_at` 
                        FROM project_comment 
                        WHERE `project_id`=$old_id
                    ");

                }

                if ($old_campaign['keep_updates'] == 1) {
                    
                    $this->query("
                        INSERT INTO project_updates (`user_id`, `project_id`, `parrent_id`, `comment`, `create_at`)
                        SELECT `user_id`, $new_id, `parrent_id`, `comment`, `create_at` 
                        FROM project_updates 
                        WHERE `project_id`=$old_id
                    ");

                }

                $list[] = $old_campaign;
            }
        }
        return $list;
    }

    /**
     * @param $campagns
     */
    function clone_campaigns($campagns)
    {
        if (count($campagns)) {
            foreach ($campagns as $_campaign_id) {
                $sql_clone_record_query = "INSERT INTO " . NPDB_PREFIX . "users(username, password, email,
                    reg_date, payment_mode, balance, max_credit,
                    salt,  tax_account_type, tax_company_name, tax_reg_number, tax_apply_exempt,
                    name, address, city, country, state, zip_code, phone, birthdate, birthdate_year, newsletter,
                    pg_paypal_email, pg_paypal_first_name, pg_paypal_last_name, confirmed_paypal_email,
                    pg_worldpay_id, pg_checkout_id, pg_nochex_email,
                    pg_ikobo_username, pg_ikobo_password, pg_protx_username, pg_protx_password,
                    pg_authnet_username, pg_authnet_password, pg_mb_email, pg_paymate_merchant_id,
                    pg_gc_merchant_id, pg_gc_merchant_key, pg_amazon_access_key, pg_amazon_secret_key,
                    pg_alertpay_id, pg_alertpay_securitycode,orgtype,lat,lng, logo, banner,
                    user_submitted, npverified, affiliate, pitch_text, url, facebook_url, twitter_url, project_category,
                    project_title, campaign_basic, description, founddrasing_goal, funding_type,
                    deadline_type_value, time_period, certain_date, probid_user_id, end_date, active,
                    cfc, clone_campaign, votes, disabled, parrent_id)
                SELECT username, password, email, end_date+'1', payment_mode, 0, max_credit,
                    salt,  tax_account_type, tax_company_name, tax_reg_number, tax_apply_exempt,
                    name, address, city, country, state, zip_code, phone, birthdate, birthdate_year, newsletter,
                    pg_paypal_email, pg_paypal_first_name, pg_paypal_last_name, confirmed_paypal_email,
                    pg_worldpay_id, pg_checkout_id, pg_nochex_email,
                    pg_ikobo_username, pg_ikobo_password, pg_protx_username, pg_protx_password,
                    pg_authnet_username, pg_authnet_password, pg_mb_email, pg_paymate_merchant_id,
                    pg_gc_merchant_id, pg_gc_merchant_key, pg_amazon_access_key, pg_amazon_secret_key,
                    pg_alertpay_id, pg_alertpay_securitycode,orgtype,lat,lng, logo, banner,
                    user_submitted, npverified, affiliate, pitch_text, url, facebook_url, twitter_url, project_category,
                    project_title, campaign_basic, description, founddrasing_goal, funding_type,
                    deadline_type_value, time_period, certain_date, probid_user_id, end_date+'2592000', active,
                    cfc, clone_campaign, 0, disabled, user_id
                FROM ".NPDB_PREFIX."users WHERE user_id={$_campaign_id}";

                $this->query($sql_clone_record_query);

                $campaign_old = $this->get_sql_row("SELECT user_id, username, probid_user_id FROM " .
                NPDB_PREFIX . "users WHERE user_id=" . $_campaign_id);

                $old_campaign_title = $campaign_old['username'] . $campaign_old['user_id'] . '_old';
                $sql_update_campaign_query = "UPDATE " . NPDB_PREFIX .
                    "users SET active=0, clone_campaign=0, username ='{$old_campaign_title}' WHERE user_id={$_campaign_id}";
                $this->query($sql_update_campaign_query);

//                $sql_update_clone_campaign_query = "UPDATE " . NPDB_PREFIX . "users
//                SET parrent_id ='{$_campaign_id}' WHERE username='{$campaign_old['username']}'
//                AND probid_user_id={$campaign_old['probid_user_id']}
//                AND user_id <>{$_campaign_id}";
//                $this->query($sql_update_clone_campaign_query);
            }
        }
    }

    /**
     * @param $campaigns
     */
    function renew_cloned_campaigns($campaigns)
    {
        if (count($campaigns)) {
            foreach ($campaigns as $_campaign_id) {
                $username = $this->get_sql_field("SELECT username FROM np_users WHERE user_id={$_campaign_id}", "username");
                $sql_update_cloned_campaign_query = "UPDATE " . NPDB_PREFIX . "users
                SET username='{$username}',
                    clone_campaign=0 WHERE parrent_id={$_campaign_id} AND clone_campaign=4";

                $this->query($sql_update_cloned_campaign_query);

                $campaign_old = $this->get_sql_row("SELECT user_id, username, probid_user_id FROM " .
                NPDB_PREFIX . "users WHERE user_id=" . $_campaign_id);

                $old_campaign_title = $campaign_old['username'] . '_old_' . $campaign_old['user_id'];
                $sql_update_campaign_query = "UPDATE " . NPDB_PREFIX .
                    "users SET active=2, clone_campaign=0, username ='{$old_campaign_title}' WHERE user_id={$_campaign_id}";
                $this->query($sql_update_campaign_query);
            }
        }
    }

    /**
     * @param $campagns
     */
    function clone_campaign($campaign_id)
    {
        $generated_id = 0;
        if ($campaign_id) {
            $cloned = mysql_fetch_assoc($this->query("SELECT clone_campaign FROM " . NPDB_PREFIX . "users WHERE user_id=" . $campaign_id));
            if ($cloned['clone_campaign'] == 3) {
                $cloned = mysql_fetch_assoc($this->query("SELECT user_id FROM " . NPDB_PREFIX . "users WHERE parrent_id=" . $campaign_id));
                if ($cloned) {
                    header("location: /campaigns,page,edit,section," . $cloned['user_id'] . ",campaign_id,members_area");
                    exit;
                }
            }
            $sql_clone_record_query = "INSERT INTO " . NPDB_PREFIX . "users(username, password, email,
                    reg_date, payment_mode, balance, max_credit,
                    salt,  tax_account_type, tax_company_name, tax_reg_number, tax_apply_exempt,
                    name, address, city, country, state, zip_code, phone, birthdate, birthdate_year, newsletter,
                    pg_paypal_email, pg_paypal_first_name, pg_paypal_last_name, confirmed_paypal_email,
                    pg_worldpay_id, pg_checkout_id, pg_nochex_email,
                    pg_ikobo_username, pg_ikobo_password, pg_protx_username, pg_protx_password,
                    pg_authnet_username, pg_authnet_password, pg_mb_email, pg_paymate_merchant_id,
                    pg_gc_merchant_id, pg_gc_merchant_key, pg_amazon_access_key, pg_amazon_secret_key,
                    pg_alertpay_id, pg_alertpay_securitycode,orgtype,lat,lng, logo, banner,
                    user_submitted, npverified, affiliate, pitch_text, url, facebook_url, twitter_url, project_category,
                    project_title, campaign_basic, description, founddrasing_goal, funding_type,
                    deadline_type_value, time_period, certain_date, probid_user_id, end_date, active,
                    cfc, clone_campaign, votes, disabled, parrent_id, inheritance_type)
                SELECT CONCAT(username, '_cloned_', '{$campaign_id}'), password, email, end_date+'1', payment_mode, 0, max_credit,
                    salt,  tax_account_type, tax_company_name, tax_reg_number, tax_apply_exempt,
                    name, address, city, country, state, zip_code, phone, birthdate, birthdate_year, newsletter,
                    pg_paypal_email, pg_paypal_first_name, pg_paypal_last_name, confirmed_paypal_email,
                    pg_worldpay_id, pg_checkout_id, pg_nochex_email,
                    pg_ikobo_username, pg_ikobo_password, pg_protx_username, pg_protx_password,
                    pg_authnet_username, pg_authnet_password, pg_mb_email, pg_paymate_merchant_id,
                    pg_gc_merchant_id, pg_gc_merchant_key, pg_amazon_access_key, pg_amazon_secret_key,
                    pg_alertpay_id, pg_alertpay_securitycode,orgtype,lat,lng, logo, banner,
                    user_submitted, npverified, affiliate, pitch_text, url, facebook_url, twitter_url, project_category,
                    project_title, campaign_basic, description, founddrasing_goal, funding_type,
                    deadline_type_value, time_period, certain_date, probid_user_id, end_date+'2592000', active,
                    cfc, 4, 0, disabled, user_id, 'clone'
                FROM ".NPDB_PREFIX."users WHERE user_id={$campaign_id}";

//            var_dump($sql_clone_record_query); exit;

            $this->query($sql_clone_record_query);

            $generated_id = mysql_insert_id();

            $sql_update_campaign_query = "UPDATE " . NPDB_PREFIX .
                "users SET clone_campaign=3, cloned_times=IFNULL(cloned_times, 0)+1 WHERE user_id={$campaign_id}";
            $this->query($sql_update_campaign_query);
        }
        return $generated_id;
    }

    /**
     * @param int $campaign_id
     * @return int
     */
    function copy_campaign($campaign_id = 0)
    {
        $generated_id = 0;
        $time_plus_30_days = time() + (3600*24*30);
        if ($campaign_id && is_numeric($campaign_id)) {
            $sql_update_campaign_query = "UPDATE " . NPDB_PREFIX .
                "users SET copied_times=IFNULL(copied_times, 0)+1 WHERE user_id={$campaign_id}";
            $this->query($sql_update_campaign_query);

            $sql_copy_query = "INSERT INTO " . NPDB_PREFIX . "users(username, password, email,
                    reg_date, payment_mode, balance, max_credit,
                    salt,  tax_account_type, tax_company_name, tax_reg_number, tax_apply_exempt,
                    name, address, city, country, state, zip_code, phone, birthdate, birthdate_year, newsletter,
                    pg_paypal_email, pg_paypal_first_name, pg_paypal_last_name, confirmed_paypal_email,
                    pg_worldpay_id, pg_checkout_id, pg_nochex_email,
                    pg_ikobo_username, pg_ikobo_password, pg_protx_username, pg_protx_password,
                    pg_authnet_username, pg_authnet_password, pg_mb_email, pg_paymate_merchant_id,
                    pg_gc_merchant_id, pg_gc_merchant_key, pg_amazon_access_key, pg_amazon_secret_key,
                    pg_alertpay_id, pg_alertpay_securitycode,orgtype,lat,lng, logo, banner,
                    user_submitted, npverified, affiliate, pitch_text, url, facebook_url, twitter_url, project_category,
                    project_title, campaign_basic, description, founddrasing_goal, funding_type,
                    deadline_type_value, time_period, certain_date, probid_user_id, end_date, active,
                    cfc, clone_campaign, votes, disabled, parrent_id, inheritance_type)
                SELECT '', password, email, reg_date, payment_mode, 0, max_credit,
                    salt,  tax_account_type, tax_company_name, tax_reg_number, tax_apply_exempt,
                    name, address, city, country, state, zip_code, phone, birthdate, birthdate_year, newsletter,
                    pg_paypal_email, pg_paypal_first_name, pg_paypal_last_name, confirmed_paypal_email,
                    pg_worldpay_id, pg_checkout_id, pg_nochex_email,
                    pg_ikobo_username, pg_ikobo_password, pg_protx_username, pg_protx_password,
                    pg_authnet_username, pg_authnet_password, pg_mb_email, pg_paymate_merchant_id,
                    pg_gc_merchant_id, pg_gc_merchant_key, pg_amazon_access_key, pg_amazon_secret_key,
                    pg_alertpay_id, pg_alertpay_securitycode,orgtype,lat,lng, logo, banner,
                    user_submitted, npverified, affiliate, pitch_text, url, facebook_url, twitter_url, project_category,
                    CONCAT(project_title, ' (copy ', copied_times, ')'), campaign_basic, description, founddrasing_goal, funding_type,
                    deadline_type_value, time_period, certain_date, probid_user_id, {$time_plus_30_days}, 0,
                    cfc, clone_campaign, 0, disabled, parrent_id, 'copy'
                FROM ".NPDB_PREFIX."users WHERE user_id={$campaign_id}";

            $this->query($sql_copy_query);

            $generated_id = mysql_insert_id();
        }
        return $generated_id;
    }

    /**
     * @param $campaigns
     */
    function renew_campaigns($campaigns)
    {
        if (count($campaigns)) {
            $update = false;
            $sql_update_query = "UPDATE " . NPDB_PREFIX . "users SET end_date= CASE ";

            foreach ($campaigns as $campaign) {
                $sql_update_query .= " WHEN user_id=" . $campaign["user_id"] . " THEN " . $campaign["end_date"] . " ";
                $update = true;
            }

            $sql_update_query .= " ELSE end_date END ";


//        echo '<br>==========================================================================</br>';
//        echo '<pre>';
//        var_dump($sql_update_query);
//        echo '</pre>';

            if ($update) {
                $this->query($sql_update_query);
            }

//        $sql_update_query = rtrim($sql_update_query, ",");
        }
    }

    function insert ($user_details, $page_handle = 'register')
    {
        $salt = $this->create_salt();
        if (!isset($user_details['password'])) {
            $user_details['password'] = "password";
        }
        $password_hashed = password_hash($user_details['password'], $salt);

        $payment_mode = ($this->setts['account_mode_personal'] == 1) ? $this->setts['init_acc_type'] : $this->setts['account_mode'];
        $balance = ($payment_mode == 2) ? ((-1) * $this->setts['init_credit']) : 0;
        $max_credit = ($payment_mode == 2) ? $this->setts['max_credit'] : 0;

        $user_details = $this->rem_special_chars_array($user_details);

        $phone =  (isset($user_details['phone_a']) || isset($user_details['phone_b'])) ? $user_details['phone_a'] . $user_details['phone_b'] : $user_details['phone'];

        if ($this->setts['birthdate_type'] == 1)
        {
            if (!isset($user_details['birthdate_year'])) {
                $user_details['birthdate_year'] = "1970";
            }
            $birthdate = $user_details['birthdate_year'] . '-01-01'; // defaulted to jan 1st of the birthdate year.
            $birthdate_year = $user_details['birthdate_year'];
        }
        else
        {
            if (!isset($user_details['dob_year'])) {
                $user_details['dob_year'] = "1970";
            }
            if (!isset($user_details['dob_month'])) {
                $user_details['dob_month'] = "01";
            }
            if (!isset($user_details['dob_day'])) {
                $user_details['dob_day'] = "01";
            }
            $birthdate = $user_details['dob_year'] . '-' . $user_details['dob_month'] . '-' . $user_details['dob_day'];
            $birthdate_year = $user_details['dob_year'];
        }
        $tax_apply_exempt = (!empty($user_details['tax_reg_number'])) ? 1 : 0;
        $row = $user_details;
        if ($_POST['address'] && $_POST['city'] && $_POST['zip_code']) {
            include 'includes/npgeocode_user.php';
        }
        if (!isset($user_details['email'])) {
            $user_details['email'] = '';
        }
        if (!isset($user_details['tax_reg_number'])) {
            $user_details['tax_reg_number'] = '';
        }
        if (!isset($user_details['newsletter'])) {
            $user_details['newsletter'] = '';
        }
        if (!isset($user_details['pg_worldpay_id'])) {
            $user_details['pg_worldpay_id'] = '';
        }
        if (!isset($user_details['pg_checkout_id'])) {
            $user_details['pg_checkout_id'] = '';
        }
        if (!isset($user_details['pg_nochex_email'])) {
            $user_details['pg_nochex_email'] = '';
        }
        if (!isset($user_details['pg_ikobo_username'])) {
            $user_details['pg_ikobo_username'] = '';
        }
        if (!isset($user_details['pg_ikobo_password'])) {
            $user_details['pg_ikobo_password'] = '';
        }
        if (!isset($user_details['pg_protx_username'])) {
            $user_details['pg_protx_username'] = '';
        }
        if (!isset($user_details['pg_protx_password'])) {
            $user_details['pg_protx_password'] = '';
        }
        if (!isset($user_details['pg_authnet_username'])) {
            $user_details['pg_authnet_username'] = '';
        }
        if (!isset($user_details['pg_authnet_password'])) {
            $user_details['pg_authnet_password'] = '';
        }
        if (!isset($user_details['pg_mb_email'])) {
            $user_details['pg_mb_email'] = '';
        }
        if (!isset($user_details['pg_paymate_merchant_id'])) {
            $user_details['pg_paymate_merchant_id'] = '';
        }
        if (!isset($user_details['pg_gc_merchant_id'])) {
            $user_details['pg_gc_merchant_id'] = '';
        }
        if (!isset($user_details['pg_gc_merchant_key'])) {
            $user_details['pg_gc_merchant_key'] = '';
        }
        if (!isset($user_details['pg_amazon_access_key'])) {
            $user_details['pg_amazon_access_key'] = '';
        }
        if (!isset($user_details['pg_amazon_secret_key'])) {
            $user_details['pg_amazon_secret_key'] = '';
        }
        if (!isset($user_details['pg_alertpay_id'])) {
            $user_details['pg_alertpay_id'] = '';
        }
        if (!isset($user_details['pg_alertpay_securitycode'])) {
            $user_details['pg_alertpay_securitycode'] = '';
        }
        if (!isset($user_details['user_submitted'])) {
            $user_details['user_submitted'] = '';
        }
        if (!isset($user_details['logo'])) {
            $user_details['logo'] = '';
        }
        if (!isset($user_details['banner'])) {
            $user_details['banner'] = '';
        }
        if (!isset($user_details['npverified'])) {
            $user_details['npverified'] = '';
        }
        if (!isset($user_details['affiliate'])) {
            $user_details['affiliate'] = '';
        }
        if (!isset($user_details['pitch_text'])) {
            $user_details['pitch_text'] = '';
        }
        if (!isset($user_details['url'])) {
            $user_details['url'] = '';
        }
        if (!isset($user_details['facebook_url'])) {
            $user_details['facebook_url'] = '';
        }
        if (!isset($user_details['twitter_url'])) {
            $user_details['twitter_url'] = '';
        }
        if (!isset($user_details['project_category'])) {
            $user_details['project_category'] = '';
        }
        if (!isset($user_details['project_title'])) {
            $user_details['project_title'] = '';
        }
        if (!isset($user_details['campaign_basic'])) {
            $user_details['campaign_basic'] = '';
        }
        if (!isset($user_details['project_short_description'])) {
            $user_details['project_short_description'] = '';
        }
        if (!isset($user_details['founddrasing_goal'])) {
            $user_details['founddrasing_goal'] = '';
        }
        if (!isset($user_details['funding_type'])) {
            $user_details['funding_type'] = '';
        }
        if (!isset($user_details['deadline_type_value'])) {
            $user_details['deadline_type_value'] = '';
        }
        if (!isset($user_details['time_period'])) {
            $user_details['time_period'] = '';
        }
        if (!isset($user_details['certain_date'])) {
            $user_details['certain_date'] = '';
        }
//        if (!isset($user_details['probid_user_id'])) {
//            $user_details['probid_user_id'] = '';
//        }
        if (!isset($user_details['end_date'])) {
            $user_details['end_date'] = '';
        }

        $sql_insert_user = $this->query("INSERT INTO " . NPDB_PREFIX . "users
			(username, password, email, reg_date, payment_mode, balance, max_credit,
			salt,  tax_account_type, tax_company_name, tax_reg_number, tax_apply_exempt,
			name, address, city, country, state, zip_code, phone, birthdate, birthdate_year, newsletter,
			pg_paypal_email, pg_paypal_first_name, pg_paypal_last_name, confirmed_paypal_email,
			pg_worldpay_id, pg_checkout_id, pg_nochex_email,
			pg_ikobo_username, pg_ikobo_password, pg_protx_username, pg_protx_password,
			pg_authnet_username, pg_authnet_password, pg_mb_email, pg_paymate_merchant_id,
			pg_gc_merchant_id, pg_gc_merchant_key, pg_amazon_access_key, pg_amazon_secret_key,
			pg_alertpay_id, pg_alertpay_securitycode, orgtype, lat, lng, logo, banner,
			user_submitted, npverified, affiliate, pitch_text, url, facebook_url, twitter_url, project_category,
			project_title, campaign_basic, description, founddrasing_goal, funding_type,
			deadline_type_value, time_period, certain_date, probid_user_id, end_date, active) VALUES
			('" . $user_details['username'] . "', '" . $password_hashed . "',	'" . $user_details['email'] . "',
			" . CURRENT_TIME . ", " . $payment_mode . ",	'" . $balance . "', '" . $max_credit . "',
			'" . $salt . "', '" . $user_details['tax_account_type'] . "', '" . $user_details['tax_company_name'] . "',
			'" . $user_details['tax_reg_number'] . "', '" . $tax_apply_exempt . "', '" . $user_details['name'] . "',
			'" . $user_details['address'] . "', '" . $user_details['city'] . "',
			'" . $user_details['country'] . "', '" . $user_details['state'] . "', '" . $user_details['zip_code'] . "',
			'" . $phone . "', '" . $birthdate . "', '" . $birthdate_year . "', '" . $user_details['newsletter'] . "',
			'" . $user_details['pg_paypal_email'] . "', '" . $user_details['pg_paypal_first_name'] . "',
			'" . $user_details['pg_paypal_last_name'] . "', '" . $user_details['confirmed_paypal_email'] . "',
			'" . $user_details['pg_worldpay_id'] . "', '" . $user_details['pg_checkout_id'] . "',
			'" . $user_details['pg_nochex_email'] . "', '" . $user_details['pg_ikobo_username'] . "',
			'" . $user_details['pg_ikobo_password'] . "', '" . $user_details['pg_protx_username'] . "',
			'" . $user_details['pg_protx_password'] . "', '" . $user_details['pg_authnet_username'] . "',
			'" . $user_details['pg_authnet_password'] . "', '" . $user_details['pg_mb_email'] . "',
			'" . $user_details['pg_paymate_merchant_id'] . "', '" . $user_details['pg_gc_merchant_id'] . "',
			'" . $user_details['pg_gc_merchant_key'] . "',
			'" . $user_details['pg_amazon_access_key'] . "', '" . $user_details['pg_amazon_secret_key'] . "',
			'" . $user_details['pg_alertpay_id'] . "','" . $user_details['pg_alertpay_securitycode'] . "',
			'" . $user_details['orgtype'] . "','" . $user_details['lat'] . "','" . $user_details['lng'] . "',
            '" . $user_details['logo'] . "','" . $user_details['banner'] . "','" . $user_details['user_submitted'] . "',
            '" . $user_details['npverified']	. "', '" . 	$user_details['affiliate']	.
        "', '" . $user_details['pitch_text']	. "', '" . urlencode($user_details['url'])	.
        "', '" . urlencode($user_details['facebook_url'])	. "', '" . urlencode($user_details['twitter_url'])	.
        "', '" . $user_details['project_category'] . "', '". $user_details['project_title'] .
        "', '" . html_entity_decode($user_details['campaign_basic']) . "', '" . $user_details['project_short_description'] .
        "', '" . $user_details['founddrasing_goal'] . "', '" . $user_details['funding_type'] .
        "', '" . $user_details['deadline_type_value'] . "', '" . $user_details['time_period'] .
        "', '" . $user_details['certain_date'] . "', '" . $user_details['probid_user_id'] .
        "', '" . $user_details['end_date']. "', 0)");


        $user_id = $this->insert_id();

        if ($tax_apply_exempt && IN_ADMIN != 1) ## if not in admin, notify admin of a tax exempt request.
        {
            $mail_input_id = $user_id;
            include('language/' . $this->setts['site_lang'] . '/mails/tax_apply_exempt_notification.php');
        }

        $this->insert_page_data($user_id, $page_handle, $user_details);


        if (isset($_POST['newsletter'])) {
            //begin constant contact api
            // Include the header at the top
            include('includes/ctctWrapper.php');

// Connect to the Contacts URI
            $data = new ContactsCollection();

// Create a new Contact Object
            $contact = new Contact();

// This Contact's email address is...
#$contact->setEmailAddress("newContact222@test.com");
            $contact->setEmailAddress($user_details['email']);
            $contact->setFirstName($user_details['name']);
            $contact->setCompanyName($user_details['tax_company_name']);


            $contact->setCity($user_details['city']);
            $contact->setPostalCode($user_details['zip_code']);
            $contact->setCustomField1($user_details['orgtype']);





#$contact->setFirstName = $user_details['fname'];

// This Contact will belong to this list - Replace where {username} is with your username
// Also remember to set listID to list number such as 1, which is typically your general interest
            $contact->setLists("http://api.constantcontact.com/ws/customers/bringitlocal/lists/2");

// create the contact with the paremeters of the previous information

            $contacts = $data->createContact($contact);

//end constant contact api

        }






        return $user_id;
    }

    function update ($user_id, $user_details, $new_password = null, $page_handle = 'register', $admin_edit = false)
    {
        include 'includes/npgeocode_user.php';
        $user_details = $this->rem_special_chars_array($user_details);

        $sql_update_query = "UPDATE " . NPDB_PREFIX . "users SET
			name='" . $user_details['name'] . "', address='" . $user_details['address'] . "',
			city='" . $user_details['city'] . "', country='" . $user_details['country'] . "',
			state='" . $user_details['state'] . "', zip_code='" . $user_details['zip_code'] . "',
			phone='" . $user_details['phone'] . "', email='" . $user_details['email'] . "',
			tax_account_type='" . $user_details['tax_account_type'] . "',
			tax_company_name='" . $user_details['tax_company_name'] . "',
			tax_reg_number='" . $user_details['tax_reg_number'] . "',
			newsletter='" . $user_details['newsletter'] . "',
			pg_paypal_email = '" . $user_details['pg_paypal_email'] . "',
			pg_worldpay_id = '" . $user_details['pg_worldpay_id'] . "',
			pg_checkout_id = '" . $user_details['pg_checkout_id'] . "',
			pg_nochex_email = '" . $user_details['pg_nochex_email'] . "',
			pg_ikobo_username = '" . $user_details['pg_ikobo_username'] . "',
			pg_ikobo_password = '" . $user_details['pg_ikobo_password'] . "',
			pg_protx_username = '" . $user_details['pg_protx_username'] . "',
			pg_protx_password = '" . $user_details['pg_protx_password'] . "',
			pg_authnet_username = '" . $user_details['pg_authnet_username'] . "',
			pg_authnet_password = '" . $user_details['pg_authnet_password'] . "',
			pg_mb_email = '" . $user_details['pg_mb_email'] . "',
			pg_paymate_merchant_id = '" . $user_details['pg_paymate_merchant_id'] . "',
			pg_gc_merchant_id = '" . $user_details['pg_gc_merchant_id'] . "',
			pg_gc_merchant_key = '" . $user_details['pg_gc_merchant_key'] . "',
			pg_amazon_access_key = '" . $user_details['pg_amazon_access_key'] . "',
			pg_amazon_secret_key = '" . $user_details['pg_amazon_secret_key'] . "',
			pg_alertpay_id = '" . $user_details['pg_alertpay_id'] . "',
			pg_alertpay_securitycode = '". $user_details['pg_alertpay_securitycode'] . "',
			orgtype = '". $user_details['orgtype'] . "',
			lat = '". $user_details['lat'] . "', lng = '" . $user_details['lng'] . "',
			logo = '" . $user_details['logo'] . "', banner = '" . $user_details['banner'] . "'";


        $user_old = $this->get_sql_row("SELECT balance, payment_mode, tax_apply_exempt FROM
			" . NPDB_PREFIX . "users WHERE user_id=" . $user_id);

        if (!$user_old['tax_apply_exempt'] && !empty($user_dlogoetails['tax_reg_number']))
        {
            $sql_update_query .= ", tax_apply_exempt=1";
        }

        if ($admin_edit)
        {
            $sql_update_query .= ", payment_mode='" . $user_details['payment_mode'] . "'";

            if ($user_old['payment_mode'] == 2)
            {
                $sql_update_query .= ", max_credit='" . $user_details['max_credit'] . "'";
                // We can change here the balance and max_credit values. If the balance is changed, we will also create a line in the invoices table
                $new_balance = $user_details['balance_type'] * $user_details['balance'];

                if ($user_old['balance'] != $new_balance)
                {
                    $sql_update_query .= ", balance='" .  $new_balance . "'";

                    // now we create the invoice row
                    $invoice_amount = $new_balance - $user_old['balance'];

                    $fee_name = GMSG_ADMIN_CREDIT_ADJUSTMENT . ' [ ' . (($invoice_amount>=0) ? GMSG_DEBIT : GMSG_CREDIT) . ' ] ' .
                        ((!empty($user_details['adjustment_reason'])) ? ' - ' . $this->rem_special_chars($user_details['adjustment_reason']) : '');

                    $sql_insert_invoice = $this->query("INSERT INTO " . DB_PREFIX . "invoices
						(user_id, name, amount, invoice_date, current_balance, live_fee, credit_adjustment) VALUES
						('" . $user_id . "', '" . $fee_name . "', '" . $invoice_amount . "',
						'" . CURRENT_TIME . "', '" . $new_balance . "', '1', '1')");
                }
            }
        }

        if ($new_password)
        {
            $salt = $this->create_salt();
            $password_hashed = password_hash($new_password, $salt);
            $sql_update_query .= ", password='" . $password_hashed . "', salt='" . $salt . "'";
        }

        $sql_update_query .= " WHERE user_id=" . $user_id;

        $sql_update_user = $this->query($sql_update_query);

        if (!$user_old['tax_apply_exempt'] && !empty($user_details['tax_reg_number']) && IN_ADMIN != 1)
        {
            $mail_input_id = $user_id;
            include('language/' . $this->setts['site_lang'] . '/mails/tax_apply_exempt_notification.php');
        }

        $this->update_page_data($user_id, $page_handle, $user_details);
    }

    function delete ($user_id, $page_handle = 'register')
    {
        ## delete user and all the related fields including custom fields
        $this->delete_data($user_id, $page_handle);

        $this->item = new item();
        $this->item->setts = $this->setts;

        ## now select all auctions that the user has listed
        $sql_select_auctions = $this->query("SELECT auction_id FROM " . DB_PREFIX . "auctions WHERE owner_id=" . $user_id);

        $delete_ids = null;

        while ($deleted_details = $this->fetch_array($sql_select_auctions))
        {
            $delete_ids[] = $deleted_details['auction_id'];
        }

        $delete_array = $this->implode_array($delete_ids);

        ## delete all auctions the user has listed
        $this->item->delete($delete_array, 0, true, true);

        ## now select all wanted ads that the user has listed
        $sql_select_wanted_ads = $this->query("SELECT wanted_ad_id FROM " . DB_PREFIX . "wanted_ads WHERE owner_id=" . $user_id);

        $delete_ids = null;

        while ($deleted_details = $this->fetch_array($sql_select_wanted_ads))
        {
            $delete_ids[] = $deleted_details['wanted_ad_id'];
        }

        $delete_array = $this->implode_array($delete_ids);

        ## delete all wanted ads the user has listed
        $this->item->delete_wanted_ad($delete_array, 0, true);

        ## delete the rest of the data
        $this->query("DELETE u, fs, i, ip, r  FROM " . NPDB_PREFIX . "users u
			LEFT JOIN " . DB_PREFIX . "favourite_stores fs ON fs.user_id=u.user_id
			LEFT JOIN " . DB_PREFIX . "invoices i ON i.user_id=u.user_id
			LEFT JOIN " . DB_PREFIX . "iphistory ip ON ip.memberid=u.user_id
			LEFT JOIN " . DB_PREFIX . "reputation r ON r.user_id=u.user_id
		 	WHERE u.user_id=" . $user_id);
    }

    function account_status ($active, $approved)
    {
        (string) $display_output = null;

        $display_output = ($approved) ? (($active) ? GMSG_ACTIVE : GMSG_SUSPENDED) : GMSG_NOT_APPROVED;

        return $display_output;
    }

    function payment_mode_desc ($payment_mode)
    {
        (string)	$display_output = null;

        $payment_mode = ($this->setts['account_mode_personal'] == 1) ? (($payment_mode) ? $payment_mode : 1) : $this->setts['account_mode'];

        switch ($payment_mode)
        {
            case 1:
                $display_output = GMSG_LIVE;
                break;
            case 2:
                $display_output = GMSG_ACCOUNT;
                break;
            default:
                $display_output = GMSG_NA;
        }

        return $display_output;
    }

    function show_balance ($balance, $currency)
    {
        (string)	$display_output = null;

        $display_output = fees_main::display_amount(abs($balance), $currency, true) . ' ' . (($balance>0) ? GMSG_DEBIT : GMSG_CREDIT);

        return $display_output;
    }

    function user_manage_direct_payment_methods ($user_details)
    {

    }

    function direct_payment_methods_edit($user_details)
    {
        (string) $display_output = null;

        $sql_select_pg = $this->query("SELECT pg_id, name, logo_url FROM
			" . DB_PREFIX . "payment_gateways WHERE dp_enabled=1");

        $background = 'c1';
        while ($pg_details = $this->fetch_array($sql_select_pg))
        {
            switch ($pg_details['name'])
            {
                case 'PayPal':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_PAYPAL_EMAIL . '</td> '.
                        '	<td><input name="pg_paypal_email" type="text" value="' . $user_details['pg_paypal_email'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_PAYPAL_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_paypal.php</b></td> '.
                        '</tr> ';
                    break;
                case 'Worldpay':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_WORLDPAY_ID . '</td> '.
                        '	<td><input name="pg_worldpay_id" type="text" value="' . $user_details['pg_worldpay_id'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_WORLDPAY_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_worldpay.php</b></td> '.
                        '</tr> ';
                    break;
                case '2Checkout':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_CHECKOUT_ID . '</td> '.
                        '	<td><input name="pg_checkout_id" type="text" value="' . $user_details['pg_checkout_id'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_CHECKOUT_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_checkout.php</b></td> '.
                        '</tr> ';
                    break;
                case 'Nochex':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_NOCHEX_EMAIL . '</td> '.
                        '	<td><input name="pg_nochex_email" type="text" value="' . $user_details['pg_nochex_email'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_NOCHEX_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_nochex.php</b></td> '.
                        '</tr> ';
                    break;
                case 'Ikobo':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_IKOBO_USERNAME . '</td> '.
                        '	<td><input name="pg_ikobo_username" type="text" value="' . $user_details['pg_ikobo_username'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_IKOBO_PASSWORD . '</td> '.
                        '	<td><input name="pg_ikobo_password" type="text" value="' . $user_details['pg_ikobo_password'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_IKOBO_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_ikobo.php</b></td> '.
                        '</tr> ';
                    break;
                case 'Protx':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_PROTX_USERNAME . '</td> '.
                        '	<td><input name="pg_protx_username" type="text" value="' . $user_details['pg_protx_username'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_PROTX_PASSWORD . '</td> '.
                        '	<td><input name="pg_protx_password" type="text" value="' . $user_details['pg_protx_password'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_PROTX_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_protx.php</b></td> '.
                        '</tr> ';
                    break;
                case 'Authorize.net':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_AUTHNET_USERNAME . '</td> '.
                        '	<td><input name="pg_authnet_username" type="text" value="' . $user_details['pg_authnet_username'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td>' . GMSG_AUTHNET_PASSWORD . '</td> '.
                        '	<td><input name="pg_authnet_password" type="text" value="' . $user_details['pg_authnet_password'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_AUTHNET_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_authnet.php</b></td> '.
                        '</tr> ';
                    break;
                case 'Moneybookers':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td width="250">' . GMSG_MB_EMAIL . '</td> '.
                        '	<td><input name="pg_mb_email" type="text" value="' . $user_details['pg_mb_email'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_MB_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_moneybookers.php</b></td> '.
                        '</tr> ';
                    break;
                case 'Paymate':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td width="250">' . GMSG_PAYMATE_MERCHANT_ID . '</td> '.
                        '	<td><input name="pg_paymate_merchant_id" type="text" value="' . $user_details['pg_paymate_merchant_id'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_PAYMATE_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_paymate.php</b></td> '.
                        '</tr> ';
                    break;
                case 'Google Checkout':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td width="250">' . GMSG_GC_MERCHANT_ID . '</td> '.
                        '	<td><input name="pg_gc_merchant_id" type="text" value="' . $user_details['pg_gc_merchant_id'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td width="250">' . GMSG_GC_MERCHANT_KEY . '</td> '.
                        '	<td><input name="pg_gc_merchant_key" type="text" value="' . $user_details['pg_gc_merchant_key'] . '" size="50"></td> '.
                        '</tr> ';
                    if ($user_details['user_id'] > 0)
                    {
                        $display_output .= '<tr> '.
                            '	<td></td> '.
                            '	<td class="' . $background . '">' . GMSG_GC_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_gc.php?user_id=' . $user_details['user_id'] . '</b></td> '.
                            '</tr> ';
                    }
                    break;
                case 'Amazon':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td width="250">' . GMSG_AMAZON_ACCESS_KEY . '</td> '.
                        '	<td><input name="pg_amazon_access_key" type="text" value="' . $user_details['pg_amazon_access_key'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td width="250">' . GMSG_AMAZON_SECRET_KEY . '</td> '.
                        '	<td><input name="pg_amazon_secret_key" type="text" value="' . $user_details['pg_amazon_secret_key'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_AMAZON_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_amazon.php</b></td> '.
                        '</tr> ';
                    break;
                case 'AlertPay':
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td width="250">' . GMSG_ALERTPAY_ID . '</td> '.
                        '	<td><input name="pg_alertpay_id" type="text" value="' . $user_details['pg_alertpay_id'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr class="' . $background . '"> '.
                        '	<td width="250">' . GMSG_ALERTPAY_SECURITY_CODE . '</td> '.
                        '	<td><input name="pg_alertpay_securitycode" type="text" value="' . $user_details['pg_alertpay_securitycode'] . '" size="50"></td> '.
                        '</tr> ';
                    $display_output .= '<tr> '.
                        '	<td></td> '.
                        '	<td class="' . $background . '">' . GMSG_ALERTPAY_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_alertpay.php</b></td> '.
                        '</tr> ';
                    break;
            }
        }

        return $display_output;
    }

    function birthdate_box($variables_array)
    {
        (string) $display_output = null;

        $months_array = array('01' => GMSG_MTH_JANUARY, '02' => GMSG_MTH_FEBRUARY, '03' => GMSG_MTH_MARCH, '04' => GMSG_MTH_APRIL,
            '05' => GMSG_MTH_MAY, '06' => GMSG_MTH_JUNE, '07' => GMSG_MTH_JULY, '08' => GMSG_MTH_AUGUST,
            '09' => GMSG_MTH_SEPTEMBER, '10' => GMSG_MTH_OCTOBER, '11' => GMSG_MTH_NOVEMBER, '12' => GMSG_MTH_DECEMBER);

        $days_array = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',
            '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24',
            '25', '26', '27', '28', '29', '30', '31');

        if ($this->setts['birthdate_type'] == 1)
        {
            $dob_text = MSG_YEAR_OF_BIRTH;
            $dob_expl = MSG_YEAR_OF_BIRTH_EXPL;

            $birthdate_box = '<input name="birthdate_year" type="text" id="birthdate_year" value="' . $variables_array['birthdate_year'] . '" size="8" maxlength="4" /> ';
        }
        else
        {
            $dob_text = MSG_DATE_OF_BIRTH;
            $dob_expl = MSG_DATE_OF_BIRTH_EXPL;

            $birthdate_box .= '<select name="dob_month" id="dob_month" class="contentfont"> '.
                '<option> </option> ';
            foreach ($months_array as $key => $value)
            {
                $birthdate_box .= '<option value="' . $key . '" ' . (($key == $variables_array['dob_month']) ? 'selected' : '') . '>' . $value . '</option> ';
            }
            $birthdate_box .= '</select> ';

            $birthdate_box .= '<select name="dob_day" id="dob_day" class="contentfont"> '.
                '<option> </option> ';
            foreach ($days_array as $value)
            {
                $birthdate_box .= '<option value="' . $value . '" ' . (($value == $variables_array['dob_day']) ? 'selected' : '') . '>' . $value . '</option> ';
            }
            $birthdate_box .= '</select> ';

            $birthdate_box .= '<input name="dob_year" type="text" id="dob_year" value="' . $variables_array['dob_year'] . '" size="8" maxlength="4" /> ';
        }

        $display_output = '<br /> '.
            '<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border"> '.
            '	<tr class="c5"> '.
            '		<td><img src="themes/' . $this->setts['default_theme'] . '/img/pixel.gif" width="1" height="1" /></td> '.
            '		<td><img src="themes/' . $this->setts['default_theme'] . '/img/pixel.gif" width="1" height="1" /></td> '.
            '	</tr> '.
            '	<tr class="c1"> '.
            '		<td width="150" align="right" class="contentfont">' . $dob_text . '</td> '.
            '		<td class="contentfont">' . $birthdate_box . '</td> '.
            '	</tr> '.
            '	<tr class="reguser"> '.
            '		<td>&nbsp;</td> '.
            '		<td>' . $dob_expl . '</td> '.
            '	</tr> '.
            '</table> ';

        return $display_output;
    }

    function can_sell($is_seller)
    {
        $output = ($is_seller || $this->setts['enable_private_site'] == 0) ? true : false;

        return $output;
    }

    function full_address($user_details)
    {
        (string) $display_output = null;

        $state = ($user_details['state_name']) ? $user_details['state_name'] : $user_details['state'];
        // state_name and country_name are presumed to be taken from the countries table from the initial query.
        $display_output = $user_details['address'] . '<br>'.
            $user_details['zip_code'] . ', ' . $user_details['city'] . '<br>'.
            $state . ', ' . $user_details['country_name'];
        $display_output = $user_details['address'] . '<br>'.
            $user_details['city'] . ', ' . $state . '<br>'.
            $user_details['zip_code'] . '<br>'.
            $user_details['country_name'];

        return $display_output;
    }

    function show_birthdate($user_details)
    {
        (string) $display_output = null;

        if ($this->setts['birthdate_type'] == 1)
        {
            $display_output = $user_details['birthdate_year'] . ' ' . GMSG_DOB_YEAR;
        }
        else
        {
            $display_output = $user_details['birthdate'] . ' ' . GMSG_DOB_FULL;
        }

        return $display_output;
    }

    function postage_calc_save($postage_details, $user_id)
    {
        $user_id = intval($user_id);

        $pc_postage_type = (in_array($postage_details['pc_postage_type'], array('item', 'weight', 'amount', 'flat'))) ? $postage_details['pc_postage_type'] : 'item';
        $pc_postage_calc_type = (in_array($postage_details['pc_postage_calc_type'], array('default', 'custom'))) ? $postage_details['pc_postage_calc_type'] : 'default';
        $pc_shipping_locations = (in_array($postage_details['pc_shipping_locations'], array('local', 'global'))) ? $postage_details['pc_shipping_locations'] : 'global';

        $this->query("UPDATE " . NPDB_PREFIX . "users SET
			pc_free_postage='" . intval($postage_details['pc_free_postage']) . "',
			pc_free_postage_amount='" . doubleval($postage_details['pc_free_postage_amount']) . "',
			pc_postage_type='" . $pc_postage_type . "',
			pc_weight_unit='" . $this->rem_special_chars($postage_details['pc_weight_unit']) . "',
			pc_postage_calc_type='" . $pc_postage_calc_type . "',
			pc_shipping_locations='" . $pc_shipping_locations . "',
			pc_flat_first='" . doubleval($postage_details['pc_flat_first']) . "',
			pc_flat_additional='" . doubleval($postage_details['pc_flat_additional']) . "'
			WHERE user_id='" . $user_id . "'");

        /*
        if ($postage_details['pc_shipping_locations'])
        {
            $this->query("UPDATE " . DB_PREFIX . "shipping_locations SET pc_default=0 WHERE user_id='" . $user_id . "'");
            $this->query("UPDATE " . DB_PREFIX . "shipping_locations SET
                pc_default='1' WHERE id='" . intval($postage_details['pc_default']) . "' AND user_id='" . $user_id . "'");
        }
        */

        if ($postage_details['pc_postage_type'] != 'item' && $postage_details['pc_postage_calc_type'] == 'custom')
        {
            $postage_details = convert_amount($postage_details, 'STN');

            if (count($postage_details['tier_id']))
            {
                foreach ($postage_details['tier_id'] as $key => $value)
                {
                    $sql_update_tiers = $this->query("UPDATE " . DB_PREFIX . "postage_calc_tiers SET
						tier_from='" . $postage_details['tier_from'][$key] . "', tier_to='" . $postage_details['tier_to'][$key] . "',
						postage_amount='" . $postage_details['postage_amount'][$key] . "' WHERE tier_id=" . $value . " AND user_id=" . $user_id);
                }
            }

            if ($postage_details['new_tier_from'] >= 0 && $postage_details['new_tier_to'] > 0 && $postage_details['new_postage_amount'] > 0)
            {
                $sql_insert_tier = $this->query("INSERT INTO " . DB_PREFIX . "postage_calc_tiers
					(tier_from, tier_to, postage_amount, user_id, tier_type) VALUES
					('" . $postage_details['new_tier_from'] . "', '" . $postage_details['new_tier_to'] . "', '" . $postage_details['new_postage_amount'] . "',
					'" . $user_id . "', '" . $pc_postage_type . "')");
            }

            if (count($postage_details['delete'])>0)
            {
                $delete_array = $this->implode_array($postage_details['delete']);

                $sql_delete_increments = $this->query("DELETE FROM " . DB_PREFIX . "postage_calc_tiers WHERE
					tier_id IN (" . $delete_array . ") AND user_id=" . $user_id);
            }
        }
    }


}
?>

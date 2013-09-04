/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `amazon_report_time` */

CREATE TABLE `amazon_report_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

/*Table structure for table `amazon_tracking_links` */

CREATE TABLE `amazon_tracking_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '1',
  `isfree` tinyint(1) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;

/*Table structure for table `bl2_users` */

CREATE TABLE `bl2_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `probid_users_id` int(11) DEFAULT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(45) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `postal_code` varchar(200) NOT NULL,
  `state` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `birthdate` date DEFAULT '1970-01-01',
  `birthdate_year` year(4) DEFAULT '1970',
  `tax_account_type` int(11) NOT NULL DEFAULT '0',
  `tax_reg_number` varchar(32) DEFAULT NULL COMMENT 'Tax Registration Number',
  `tax_company_name` varchar(255) DEFAULT NULL,
  `extended_registration` tinyint(1) NOT NULL DEFAULT '0',
  `pg_paypal_email` varchar(32) DEFAULT NULL COMMENT 'PayPal Email Address',
  `pg_paypal_first_name` varchar(50) NOT NULL,
  `pg_paypal_last_name` varchar(50) NOT NULL,
  `active` int(2) DEFAULT '0',
  `create_date` int(11) DEFAULT '0',
  `last_login` int(11) DEFAULT '0',
  `is_subscribe_news` int(2) DEFAULT '0',
  `about_me` text NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `facebook_link` varchar(255) NOT NULL,
  `twitter_link` varchar(255) NOT NULL,
  `google_link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `active` (`active`),
  KEY `is_subscribe_news` (`is_subscribe_news`),
  FULLTEXT KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Table structure for table `funders` */

CREATE TABLE `funders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `campaign_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Table structure for table `giveback_amazon_invoices` */

CREATE TABLE `giveback_amazon_invoices` (
  `unique_id` int(20) NOT NULL AUTO_INCREMENT,
  `tracking_id` mediumtext NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `shop_url_id` int(4) DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `destination` mediumtext,
  `np_user_id` int(11) DEFAULT NULL,
  `sales` double NOT NULL,
  `commision` double NOT NULL,
  PRIMARY KEY (`unique_id`)
) ENGINE=MyISAM AUTO_INCREMENT=428 DEFAULT CHARSET=utf8;

/*Table structure for table `giveback_conversion` */

CREATE TABLE `giveback_conversion` (
  `rate` int(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `giveback_invoices` */

CREATE TABLE `giveback_invoices` (
  `giveback_id` int(11) NOT NULL AUTO_INCREMENT,
  `np_userid` int(11) NOT NULL,
  `np_name` varchar(255) NOT NULL,
  `points` double(16,2) NOT NULL,
  `invoice_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `invoice_id` int(11) NOT NULL COMMENT 'what probid_invoice does this relate to',
  `buyerorseller` tinyint(4) NOT NULL,
  `transtype` varchar(255) NOT NULL,
  `tracking_id` mediumtext NOT NULL,
  `Sales` double(16,2) NOT NULL,
  `Commission` double(16,2) NOT NULL,
  `paid` tinyint(4) NOT NULL,
  `date_paid` date NOT NULL,
  `check no` mediumint(11) NOT NULL,
  PRIMARY KEY (`giveback_id`)
) ENGINE=MyISAM AUTO_INCREMENT=502 DEFAULT CHARSET=latin1;

/*Table structure for table `np_iphistory` */

CREATE TABLE `np_iphistory` (
  `memberid` int(11) NOT NULL,
  `time1` int(11) NOT NULL,
  `time2` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  KEY `memberid` (`memberid`),
  KEY `member_order` (`memberid`,`time1`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `np_news` */

CREATE TABLE `np_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_name` varchar(255) NOT NULL DEFAULT '',
  `news_content` text NOT NULL,
  `news_lang` varchar(255) NOT NULL DEFAULT '',
  `news_order` int(11) NOT NULL DEFAULT '0',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `news_handle` varchar(50) NOT NULL DEFAULT '',
  `show_link` tinyint(4) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`),
  KEY `news_order` (`news_order`,`reg_date`),
  FULLTEXT KEY `news_lang` (`news_lang`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

/*Table structure for table `np_orgtype` */

CREATE TABLE `np_orgtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category_group` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Table structure for table `np_reports_last_updates` */

CREATE TABLE `np_reports_last_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `np_users` */

CREATE TABLE `np_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `birthdate` date NOT NULL DEFAULT '0000-00-00',
  `address` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `state` varchar(100) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `zip_code` varchar(30) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT 'Project Url',
  `password` varchar(32) NOT NULL DEFAULT '',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `probid_user_id` int(11) NOT NULL DEFAULT '0',
  `user_link_active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `items_sold` int(11) NOT NULL DEFAULT '0',
  `items_bought` int(11) NOT NULL DEFAULT '0',
  `enable_aboutme_page` tinyint(4) NOT NULL DEFAULT '0',
  `aboutme_page_content` text NOT NULL,
  `shop_mainpage` mediumblob NOT NULL,
  `shop_mainpage_preview` mediumblob NOT NULL,
  `shop_logo_path` varchar(255) NOT NULL DEFAULT '',
  `aboutme_page_type` tinyint(4) DEFAULT NULL,
  `newsletter` tinyint(4) NOT NULL DEFAULT '0',
  `balance` double(16,2) NOT NULL DEFAULT '0.00',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `mail_activated` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `lang` varchar(255) NOT NULL DEFAULT 'english',
  `is_seller` tinyint(4) NOT NULL DEFAULT '0',
  `preferred_seller` tinyint(4) NOT NULL DEFAULT '0',
  `tax_apply_exempt` tinyint(4) NOT NULL DEFAULT '0',
  `tax_reg_number` varchar(100) NOT NULL DEFAULT '',
  `tax_exempted` tinyint(4) NOT NULL DEFAULT '0',
  `shop_active` tinyint(4) NOT NULL DEFAULT '0',
  `shop_last_payment` int(11) NOT NULL DEFAULT '0',
  `birthdate_year` int(11) NOT NULL DEFAULT '0',
  `default_duration` smallint(6) NOT NULL DEFAULT '0',
  `default_hidden_bidding` tinyint(4) NOT NULL DEFAULT '0',
  `default_enable_swap` tinyint(4) NOT NULL DEFAULT '0',
  `default_shipping_method` tinyint(4) NOT NULL DEFAULT '0',
  `default_shipping_int` tinyint(4) NOT NULL DEFAULT '0',
  `default_payment_methods` text NOT NULL,
  `default_postage_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `default_insurance_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `default_type_service` varchar(50) NOT NULL DEFAULT '',
  `default_shipping_details` mediumtext NOT NULL,
  `referred_by` varchar(200) NOT NULL DEFAULT '',
  `shop_account_id` int(11) NOT NULL DEFAULT '0',
  `shop_categories` text NOT NULL,
  `shop_next_payment` int(11) NOT NULL DEFAULT '0',
  `shop_name` varchar(255) NOT NULL DEFAULT '',
  `payment_mode` tinyint(1) DEFAULT '0',
  `max_credit` double(16,2) DEFAULT '0.00',
  `default_public_questions` tinyint(4) NOT NULL DEFAULT '0',
  `default_bid_placed_email` tinyint(4) NOT NULL DEFAULT '0',
  `mail_account_suspended` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_sold` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_won` tinyint(4) NOT NULL DEFAULT '1',
  `mail_buyer_details` tinyint(4) NOT NULL DEFAULT '1',
  `mail_seller_details` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_watch` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_closed` tinyint(4) NOT NULL DEFAULT '1',
  `mail_wanted_offer` tinyint(4) NOT NULL DEFAULT '1',
  `mail_outbid` tinyint(4) NOT NULL DEFAULT '1',
  `mail_keyword_match` tinyint(4) NOT NULL DEFAULT '1',
  `mail_confirm_to_seller` tinyint(4) NOT NULL DEFAULT '1',
  `shop_nb_items` int(11) NOT NULL DEFAULT '0',
  `shop_template_id` smallint(6) NOT NULL DEFAULT '0',
  `tax_company_name` varchar(100) DEFAULT NULL,
  `pg_paypal_email` varchar(255) DEFAULT NULL,
  `pg_paypal_first_name` varchar(50) NOT NULL,
  `pg_paypal_last_name` varchar(50) NOT NULL,
  `pg_worldpay_id` varchar(100) DEFAULT NULL,
  `pg_ikobo_username` varchar(100) DEFAULT NULL,
  `pg_ikobo_password` varchar(100) DEFAULT NULL,
  `pg_checkout_id` varchar(100) DEFAULT NULL,
  `pg_protx_username` varchar(100) DEFAULT NULL,
  `pg_protx_password` varchar(100) DEFAULT NULL,
  `pg_authnet_username` varchar(100) DEFAULT NULL,
  `pg_authnet_password` varchar(100) DEFAULT NULL,
  `pg_nochex_email` varchar(100) DEFAULT NULL,
  `shop_about` text NOT NULL,
  `shop_specials` text NOT NULL,
  `shop_shipping_info` text NOT NULL,
  `shop_company_policies` text NOT NULL,
  `shop_nb_feat_items` int(11) NOT NULL DEFAULT '0',
  `shop_nb_ending_items` int(11) NOT NULL DEFAULT '0',
  `shop_nb_recent_items` int(11) NOT NULL DEFAULT '0',
  `shop_metatags` text NOT NULL,
  `default_name` varchar(255) NOT NULL DEFAULT '',
  `default_description` text NOT NULL,
  `user_admin_notes` text NOT NULL,
  `auction_approval` tinyint(4) NOT NULL DEFAULT '0',
  `tax_account_type` tinyint(4) NOT NULL DEFAULT '0',
  `salt` char(3) NOT NULL DEFAULT '',
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `mail_messaging_received` tinyint(4) NOT NULL DEFAULT '1',
  `mail_messaging_sent` tinyint(4) NOT NULL DEFAULT '0',
  `pg_mb_email` varchar(255) NOT NULL,
  `seller_verified` tinyint(4) NOT NULL,
  `seller_verif_last_payment` int(11) NOT NULL,
  `seller_verif_next_payment` int(11) NOT NULL,
  `enable_profile_page` tinyint(4) NOT NULL,
  `profile_www` varchar(255) NOT NULL,
  `profile_msn` varchar(255) NOT NULL,
  `profile_icq` varchar(255) NOT NULL,
  `profile_aim` varchar(255) NOT NULL,
  `profile_yim` varchar(255) NOT NULL,
  `profile_skype` varchar(255) NOT NULL,
  `profile_show_birthdate` tinyint(4) NOT NULL,
  `paypal_address_override` tinyint(4) NOT NULL,
  `paypal_first_name` varchar(32) NOT NULL,
  `paypal_last_name` varchar(64) NOT NULL,
  `paypal_address1` varchar(100) NOT NULL,
  `paypal_address2` varchar(100) NOT NULL,
  `paypal_city` varchar(100) NOT NULL,
  `paypal_state` varchar(100) NOT NULL,
  `paypal_zip` varchar(32) NOT NULL,
  `paypal_country` varchar(100) NOT NULL,
  `paypal_email` varchar(255) NOT NULL,
  `paypal_night_phone_a` varchar(3) NOT NULL,
  `paypal_night_phone_b` varchar(16) NOT NULL,
  `paypal_night_phone_c` varchar(4) NOT NULL,
  `default_currency` varchar(100) NOT NULL,
  `default_direct_payment` text NOT NULL,
  `pg_paymate_merchant_id` varchar(255) NOT NULL,
  `preferred_seller_exp_date` int(11) NOT NULL,
  `pg_gc_merchant_id` varchar(255) NOT NULL,
  `pg_gc_merchant_key` varchar(255) NOT NULL,
  `pg_amazon_access_key` varchar(255) NOT NULL,
  `pg_amazon_secret_key` varchar(255) NOT NULL,
  `enable_private_reputation` tinyint(4) NOT NULL,
  `enable_force_payment` tinyint(4) NOT NULL,
  `pc_free_postage` tinyint(4) NOT NULL,
  `pc_free_postage_amount` double(16,2) NOT NULL,
  `pc_postage_type` enum('item','weight','amount','flat') NOT NULL,
  `pc_weight_unit` varchar(50) NOT NULL,
  `pc_postage_calc_type` enum('default','custom') NOT NULL,
  `pc_shipping_locations` enum('global','local') NOT NULL DEFAULT 'global',
  `pc_flat_first` double(16,2) NOT NULL,
  `pc_flat_additional` double(16,2) NOT NULL,
  `shop_nb_feat_items_row` int(11) NOT NULL,
  `provider_profile` text NOT NULL,
  `reverse_earnings` double(16,2) NOT NULL,
  `notif_a` tinyint(4) NOT NULL,
  `pg_alertpay_id` varchar(255) NOT NULL,
  `pg_alertpay_securitycode` varchar(255) NOT NULL,
  `exceeded_balance_email` tinyint(4) NOT NULL,
  `default_bank_details` text NOT NULL,
  `default_auto_relist` tinyint(4) NOT NULL,
  `default_auto_relist_bids` tinyint(4) NOT NULL,
  `default_auto_relist_nb` tinyint(4) NOT NULL,
  `orgtype` varchar(255) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `user_submitted` tinyint(4) NOT NULL DEFAULT '0',
  `npverified` tinyint(4) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `facebook_url` varchar(255) NOT NULL,
  `twitter_url` varchar(255) NOT NULL,
  `affiliate` varchar(255) NOT NULL,
  `wkey` varchar(45) NOT NULL,
  `report_email` varchar(255) NOT NULL,
  `report_daily` tinyint(1) NOT NULL DEFAULT '0',
  `report_weekly` tinyint(1) NOT NULL DEFAULT '0',
  `report_monthly` tinyint(1) NOT NULL DEFAULT '0',
  `pitch_text` text NOT NULL,
  `price` int(255) NOT NULL,
  `project_category` int(255) NOT NULL,
  `campaign_basic` text NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `founddrasing_goal` int(11) NOT NULL,
  `funding_type` varchar(255) NOT NULL,
  `deadline_type_value` varchar(255) NOT NULL,
  `time_period` varchar(255) NOT NULL,
  `certain_date` varchar(255) NOT NULL,
  `end_date` int(11) NOT NULL,
  `payment` double NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `shop_active` (`active`,`shop_active`,`user_id`),
  KEY `stores_list` (`active`,`shop_active`,`shop_nb_items`),
  KEY `acc_overdue_users` (`payment_mode`,`reg_date`),
  KEY `active_users` (`active`,`reg_date`,`approved`),
  KEY `users_tax_acc_type` (`tax_account_type`,`reg_date`),
  KEY `users_tax_exempt` (`tax_apply_exempt`,`tax_exempted`,`reg_date`),
  KEY `active` (`active`),
  FULLTEXT KEY `shop_name` (`shop_name`)
) ENGINE=MyISAM AUTO_INCREMENT=10351 DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Table with probid users';

/*Table structure for table `payment_option_details` */

CREATE TABLE `payment_option_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(20) NOT NULL,
  `access_data` varchar(255) NOT NULL,
  `payment_account` varchar(128) NOT NULL,
  `payment_environment` varchar(20) NOT NULL,
  `rate_of_pay` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `payment_otion_details` */

CREATE TABLE `payment_otion_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(20) DEFAULT NULL,
  `access_data` varchar(255) DEFAULT NULL,
  `payment_account` varchar(128) DEFAULT NULL,
  `payment_environment` varchar(20) DEFAULT NULL,
  `rate_of_pay` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `pps_integration` */

CREATE TABLE `pps_integration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ppa_url` varchar(255) NOT NULL,
  `ppb_path` varchar(255) NOT NULL,
  `ppa_db_prefix` varchar(100) NOT NULL,
  `ppb_db_prefix` varchar(100) NOT NULL,
  `ppa_session_prefix` varchar(100) NOT NULL,
  `ppb_session_prefix` varchar(100) NOT NULL,
  `enable_integration` tinyint(4) NOT NULL,
  `main_page_unified` tinyint(4) NOT NULL,
  `default_skin` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `pps_users` */

CREATE TABLE `pps_users` (
  `pps_id` int(11) NOT NULL AUTO_INCREMENT,
  `ppb_user_id` int(11) NOT NULL,
  `ppb_reg_incomplete` tinyint(4) NOT NULL,
  `ppa_user_id` int(11) NOT NULL,
  `ppa_reg_incomplete` tinyint(4) NOT NULL,
  `ppb_deleted` tinyint(4) NOT NULL,
  `ppa_deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`pps_id`),
  KEY `ppb_user_id` (`ppb_user_id`),
  KEY `ppa_user_id` (`ppa_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=358 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_abuses` */

CREATE TABLE `proads_abuses` (
  `abuse_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `abuser_username` varchar(100) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abuse_id`),
  KEY `reg_date` (`reg_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_admins` */

CREATE TABLE `proads_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `date_created` int(11) NOT NULL DEFAULT '0',
  `date_lastlogin` int(11) NOT NULL DEFAULT '0',
  `level` enum('1','2','3','4','5') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_adverts` */

CREATE TABLE `proads_adverts` (
  `advert_id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_url` varchar(255) NOT NULL DEFAULT '',
  `views` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `advert_img_path` varchar(255) NOT NULL DEFAULT '',
  `advert_alt_text` varchar(255) NOT NULL DEFAULT '',
  `advert_text_under` varchar(255) NOT NULL DEFAULT '',
  `views_purchased` int(11) NOT NULL DEFAULT '0',
  `clicks_purchased` int(11) NOT NULL DEFAULT '0',
  `advert_categories` text NOT NULL,
  `advert_keywords` text NOT NULL,
  `advert_code` longtext NOT NULL,
  `advert_type` tinyint(4) NOT NULL DEFAULT '0',
  `section` tinyint(4) NOT NULL,
  PRIMARY KEY (`advert_id`),
  KEY `views` (`views`,`clicks`,`views_purchased`,`clicks_purchased`,`advert_type`),
  KEY `section` (`section`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Table with banners data';

/*Table structure for table `proads_banned` */

CREATE TABLE `proads_banned` (
  `banned_id` int(11) NOT NULL AUTO_INCREMENT,
  `banned_address` varchar(255) NOT NULL DEFAULT '',
  `address_type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banned_id`),
  KEY `address_type` (`address_type`),
  FULLTEXT KEY `banned_address` (`banned_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_blocked_domains` */

CREATE TABLE `proads_blocked_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_blocked_users` */

CREATE TABLE `proads_blocked_users` (
  `block_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `block_reason` text NOT NULL,
  `show_reason` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`block_id`),
  KEY `block_src` (`user_id`,`owner_id`),
  KEY `reg_src` (`owner_id`,`reg_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_categories` */

CREATE TABLE `proads_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `hidden` tinyint(4) NOT NULL DEFAULT '0',
  `items_counter` int(11) NOT NULL DEFAULT '0',
  `hover_title` varchar(255) NOT NULL DEFAULT '',
  `meta_description` mediumtext NOT NULL,
  `meta_keywords` mediumtext NOT NULL,
  `image_path` varchar(255) NOT NULL DEFAULT '',
  `wanted_counter` int(11) NOT NULL DEFAULT '0',
  `is_subcat` varchar(5) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `custom_fees` tinyint(4) NOT NULL DEFAULT '0',
  `minimum_age` tinyint(4) NOT NULL,
  `custom_skin` varchar(100) NOT NULL,
  `counter_sa` int(11) NOT NULL,
  `counter_ta` int(11) NOT NULL,
  `counter_wa` int(11) NOT NULL,
  `enable_sa` tinyint(4) NOT NULL DEFAULT '1',
  `enable_ta` tinyint(4) NOT NULL DEFAULT '1',
  `enable_wa` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`),
  KEY `parent_id` (`parent_id`),
  KEY `cat_list` (`parent_id`,`hidden`,`order_id`,`user_id`),
  KEY `cat_sa` (`parent_id`,`enable_sa`),
  KEY `cat_ta` (`parent_id`,`enable_ta`),
  KEY `cat_wa` (`parent_id`,`enable_wa`)
) ENGINE=MyISAM AUTO_INCREMENT=1866 DEFAULT CHARSET=latin1 COMMENT='Table with categories';

/*Table structure for table `proads_content_pages` */

CREATE TABLE `proads_content_pages` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_name` varchar(255) NOT NULL DEFAULT '',
  `topic_content` text NOT NULL,
  `topic_lang` varchar(255) NOT NULL DEFAULT '',
  `topic_order` int(11) NOT NULL DEFAULT '0',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `page_id` varchar(255) NOT NULL DEFAULT '',
  `page_handle` varchar(50) NOT NULL DEFAULT '',
  `show_link` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`topic_id`),
  KEY `topic_order` (`topic_order`,`reg_date`),
  FULLTEXT KEY `topic_lang` (`topic_lang`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_countries` */

CREATE TABLE `proads_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `country_order` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `country_iso_code` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_order` (`country_order`),
  KEY `parent_id` (`parent_id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2286 DEFAULT CHARSET=latin1 COMMENT='Table with countries';

/*Table structure for table `proads_currencies` */

CREATE TABLE `proads_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(10) NOT NULL DEFAULT '',
  `caption` varchar(100) NOT NULL DEFAULT '',
  `convert_rate` double(16,6) NOT NULL DEFAULT '1.000000',
  `convert_date` int(11) NOT NULL DEFAULT '0',
  `currency_symbol` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 COMMENT='Table with currency listings';

/*Table structure for table `proads_custom_fields` */

CREATE TABLE `proads_custom_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(255) NOT NULL DEFAULT '',
  `field_order` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `page_handle` varchar(25) NOT NULL DEFAULT '',
  `section_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `field_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`field_id`),
  KEY `field_order` (`field_order`),
  KEY `active` (`active`),
  KEY `page_handle` (`page_handle`),
  KEY `section_id` (`section_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_custom_fields_boxes` */

CREATE TABLE `proads_custom_fields_boxes` (
  `box_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL DEFAULT '0',
  `box_name` text NOT NULL,
  `box_value` text NOT NULL,
  `box_order` int(11) NOT NULL DEFAULT '0',
  `box_type` int(11) NOT NULL DEFAULT '0',
  `mandatory` enum('0','1') NOT NULL DEFAULT '0',
  `box_type_special` int(11) NOT NULL DEFAULT '0',
  `formchecker_functions` text NOT NULL,
  `box_searchable` tinyint(4) NOT NULL,
  `is_contact_info` tinyint(4) NOT NULL,
  PRIMARY KEY (`box_id`),
  KEY `field_id` (`field_id`),
  KEY `box_order` (`box_order`),
  KEY `box_type` (`box_type`),
  KEY `box_searchable` (`box_searchable`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_custom_fields_data` */

CREATE TABLE `proads_custom_fields_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_id` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `box_value` text NOT NULL,
  `page_handle` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`data_id`),
  KEY `box_id` (`box_id`),
  KEY `user_custom_fields` (`owner_id`,`page_handle`),
  KEY `owner_id` (`owner_id`),
  FULLTEXT KEY `box_value` (`box_value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_custom_fields_sections` */

CREATE TABLE `proads_custom_fields_sections` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) NOT NULL DEFAULT '',
  `page_handle` varchar(25) NOT NULL DEFAULT '',
  `order_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`section_id`),
  KEY `page_handle` (`page_handle`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_custom_fields_special` */

CREATE TABLE `proads_custom_fields_special` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_name` varchar(255) NOT NULL DEFAULT '',
  `box_type` int(11) NOT NULL DEFAULT '0',
  `table_name_raw` varchar(255) NOT NULL DEFAULT '',
  `box_value_code` text NOT NULL,
  PRIMARY KEY (`type_id`),
  KEY `box_type` (`box_type`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_custom_fields_types` */

CREATE TABLE `proads_custom_fields_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_type` varchar(100) NOT NULL DEFAULT '',
  `maxfields` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_dateformat` */

CREATE TABLE `proads_dateformat` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT '',
  `value` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT '',
  `active` varchar(20) NOT NULL DEFAULT '0',
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table with date format';

/*Table structure for table `proads_favourite_stores` */

CREATE TABLE `proads_favourite_stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `user_id` (`user_id`),
  KEY `store_user` (`store_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_fees` */

CREATE TABLE `proads_fees` (
  `fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `signup` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `verification_fee` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `verification_recurring` int(11) NOT NULL,
  `pa_pic_upl` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `pa_send_msg` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `sa_pic_upl` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `sa_hpfeat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `sa_catfeat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `sa_hl` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `sa_bold` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `sa_addlcat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `sa_instant_purchase` double(16,2) NOT NULL,
  `sa_makeoffer` double(16,2) NOT NULL,
  `ta_setup` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `ta_pic_upl` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `ta_hpfeat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `ta_catfeat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `ta_hl` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `ta_bold` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `ta_addlcat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `wa_setup` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `wa_pic_upl` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `wa_hpfeat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `wa_catfeat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `wa_hl` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `wa_bold` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `wa_addlcat` double(16,2) unsigned NOT NULL DEFAULT '0.00',
  `account_id` int(11) NOT NULL DEFAULT '0',
  `sa_relist_fee_reduction` double(16,2) NOT NULL,
  `pa_video` double(16,2) NOT NULL,
  `sa_video` double(16,2) NOT NULL,
  `ta_video` double(16,2) NOT NULL,
  `wa_video` double(16,2) NOT NULL,
  `sa_sale_fee_applies` enum('s','b') NOT NULL DEFAULT 's',
  `pa_free_images` int(11) NOT NULL,
  `pa_free_media` int(11) NOT NULL,
  `sa_free_images` int(11) NOT NULL,
  `sa_free_media` int(11) NOT NULL,
  `ta_free_images` int(11) NOT NULL,
  `ta_free_media` int(11) NOT NULL,
  `wa_free_images` int(11) NOT NULL,
  `wa_free_media` int(11) NOT NULL,
  `sa_dd` double(16,2) NOT NULL,
  `ta_dd` double(16,2) NOT NULL,
  `wa_dd` double(16,2) NOT NULL,
  PRIMARY KEY (`fee_id`),
  KEY `account_id` (`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Table with fees';

/*Table structure for table `proads_fees_tiers` */

CREATE TABLE `proads_fees_tiers` (
  `tier_id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_from` double(16,2) NOT NULL DEFAULT '0.00',
  `fee_to` double(16,2) NOT NULL DEFAULT '0.00',
  `fee_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `calc_type` enum('flat','percent') NOT NULL DEFAULT 'flat',
  `fee_type` varchar(50) NOT NULL DEFAULT '',
  `account_id` int(11) NOT NULL DEFAULT '0',
  `store_nb_items` int(11) NOT NULL DEFAULT '0',
  `store_recurring` int(11) NOT NULL DEFAULT '0',
  `store_name` varchar(255) NOT NULL DEFAULT '',
  `store_sale_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `store_featured` tinyint(4) NOT NULL,
  PRIMARY KEY (`tier_id`),
  KEY `fee_type` (`fee_type`),
  KEY `account_id` (`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_gc_transactions` */

CREATE TABLE `proads_gc_transactions` (
  `trx_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `google_order_number` varchar(255) NOT NULL,
  `gc_custom` varchar(50) NOT NULL,
  `gc_table` varchar(50) NOT NULL,
  `gc_price` double(16,2) NOT NULL,
  `gc_currency` varchar(10) NOT NULL,
  `gc_payment_description` varchar(255) NOT NULL,
  `reg_date` int(11) NOT NULL,
  PRIMARY KEY (`trx_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_gen_setts` */

CREATE TABLE `proads_gen_setts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_lang` varchar(40) NOT NULL DEFAULT 'english',
  `admin_lang` varchar(40) NOT NULL DEFAULT 'english',
  `sitename` varchar(255) NOT NULL DEFAULT '',
  `default_theme` varchar(25) NOT NULL DEFAULT 'default',
  `is_mod_rewrite` enum('N','Y') NOT NULL DEFAULT 'N',
  `is_ssl` enum('0','1') NOT NULL DEFAULT '0',
  `site_path` varchar(255) NOT NULL DEFAULT '',
  `site_path_ssl` varchar(255) NOT NULL DEFAULT 'default',
  `account_mode` tinyint(4) NOT NULL DEFAULT '1',
  `account_mode_personal` tinyint(4) NOT NULL DEFAULT '0',
  `init_acc_type` tinyint(4) NOT NULL DEFAULT '1',
  `init_credit` double(16,2) NOT NULL DEFAULT '0.00',
  `max_credit` double(16,2) NOT NULL DEFAULT '0.00',
  `min_invoice_value` double(16,2) NOT NULL DEFAULT '0.00',
  `suspend_over_bal_users` tinyint(4) NOT NULL DEFAULT '0',
  `currency` varchar(20) NOT NULL DEFAULT 'GBP',
  `debug_load_time` tinyint(4) NOT NULL DEFAULT '1',
  `debug_load_memory` tinyint(4) NOT NULL DEFAULT '0',
  `pg_paypal_email` varchar(255) NOT NULL DEFAULT '',
  `pg_nochex_email` varchar(255) NOT NULL DEFAULT '',
  `pg_worldpay_id` varchar(100) NOT NULL DEFAULT '',
  `pg_checkout_id` varchar(100) NOT NULL DEFAULT '',
  `pg_ikobo_username` varchar(100) NOT NULL DEFAULT '',
  `pg_ikobo_password` varchar(100) NOT NULL DEFAULT '',
  `pg_protx_username` varchar(100) NOT NULL DEFAULT '',
  `pg_protx_password` varchar(100) NOT NULL DEFAULT '',
  `pg_authnet_username` varchar(100) NOT NULL DEFAULT '',
  `pg_authnet_password` varchar(100) NOT NULL DEFAULT '',
  `pg_mb_email` varchar(255) NOT NULL DEFAULT '',
  `enable_profile_ad` tinyint(4) NOT NULL DEFAULT '0',
  `enable_standard_ad` tinyint(4) NOT NULL DEFAULT '0',
  `enable_trade_ad` tinyint(4) NOT NULL DEFAULT '0',
  `enable_wanted_ad` tinyint(4) NOT NULL DEFAULT '0',
  `enable_registration` tinyint(4) NOT NULL DEFAULT '1',
  `registered_posting` tinyint(4) NOT NULL DEFAULT '1',
  `signup_settings` tinyint(4) NOT NULL DEFAULT '0',
  `enable_tax` tinyint(4) NOT NULL DEFAULT '0',
  `enable_cat_counters` tinyint(4) NOT NULL DEFAULT '1',
  `time_offset` int(11) NOT NULL DEFAULT '0',
  `enable_addl_category` tinyint(4) NOT NULL DEFAULT '0',
  `max_images` tinyint(4) NOT NULL DEFAULT '0',
  `max_media` tinyint(4) NOT NULL DEFAULT '0',
  `closed_ads_deletion_days` smallint(6) NOT NULL,
  `admin_email` varchar(255) NOT NULL DEFAULT '',
  `language` tinyint(4) NOT NULL DEFAULT '0',
  `amount_format` tinyint(4) NOT NULL DEFAULT '0',
  `amount_digits` tinyint(4) NOT NULL DEFAULT '0',
  `currency_position` tinyint(4) NOT NULL DEFAULT '0',
  `images_max_size` int(11) NOT NULL DEFAULT '0',
  `enable_hpfeat` tinyint(4) NOT NULL DEFAULT '0',
  `enable_catfeat` tinyint(4) NOT NULL DEFAULT '0',
  `enable_bold` tinyint(4) NOT NULL DEFAULT '0',
  `enable_hl` tinyint(4) NOT NULL DEFAULT '0',
  `enable_swaps` tinyint(4) NOT NULL DEFAULT '0',
  `cron_job_type` tinyint(4) NOT NULL DEFAULT '1',
  `enable_header_counter` tinyint(4) NOT NULL DEFAULT '0',
  `enable_shipping_costs` tinyint(4) NOT NULL DEFAULT '0',
  `metatags` text NOT NULL,
  `mailer` varchar(20) NOT NULL DEFAULT '',
  `sendmail_path` varchar(200) NOT NULL DEFAULT '',
  `user_lang` tinyint(4) NOT NULL DEFAULT '0',
  `enable_private_site` tinyint(4) NOT NULL DEFAULT '0',
  `enable_pref_sellers` tinyint(4) NOT NULL DEFAULT '0',
  `pref_sellers_reduction` double(16,2) NOT NULL DEFAULT '0.00',
  `enable_bcc` tinyint(4) NOT NULL DEFAULT '0',
  `enable_asq` tinyint(4) NOT NULL DEFAULT '0',
  `auto_vat_exempt` tinyint(4) NOT NULL DEFAULT '0',
  `invoice_header` text,
  `invoice_footer` text,
  `vat_number` varchar(100) NOT NULL DEFAULT '',
  `invoice_comments` mediumtext NOT NULL,
  `nb_other_items_adp` tinyint(4) NOT NULL DEFAULT '0',
  `maintenance_mode` tinyint(4) NOT NULL DEFAULT '0',
  `enable_stores` tinyint(4) NOT NULL DEFAULT '0',
  `enable_bulk_lister` tinyint(4) NOT NULL DEFAULT '1',
  `activation_key` varchar(255) NOT NULL DEFAULT '',
  `enable_display_phone` tinyint(4) NOT NULL DEFAULT '0',
  `media_max_size` int(11) NOT NULL DEFAULT '0',
  `enable_ads_approval` tinyint(4) NOT NULL DEFAULT '0',
  `approval_categories` text NOT NULL,
  `buyout_process` tinyint(4) NOT NULL DEFAULT '0',
  `sell_nav_position` tinyint(4) NOT NULL DEFAULT '1',
  `nb_autorelist_max` int(11) NOT NULL DEFAULT '0',
  `site_logo_path` varchar(255) NOT NULL DEFAULT 'images/proadslogo.gif',
  `enable_other_items_adp` tinyint(4) NOT NULL DEFAULT '0',
  `mcrypt_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `mcrypt_key` varchar(255) NOT NULL DEFAULT '',
  `makeoffer_process` tinyint(4) NOT NULL,
  `enable_seller_verification` tinyint(4) NOT NULL,
  `makeoffer_private` tinyint(4) NOT NULL,
  `seller_verification_mandatory` tinyint(4) NOT NULL,
  `enable_profile_page` tinyint(4) NOT NULL,
  `enable_store_only_mode` tinyint(4) NOT NULL,
  `enable_enhanced_ssl` tinyint(4) NOT NULL,
  `watermark_text` text NOT NULL,
  `watermark_size` int(20) NOT NULL DEFAULT '500',
  `watermark_pos` tinyint(4) NOT NULL,
  `enable_unlimited_duration` tinyint(4) NOT NULL,
  `enable_reputation` tinyint(4) NOT NULL,
  `main_page_hpfeat` varchar(255) NOT NULL,
  `main_page_popular` varchar(255) NOT NULL,
  `main_page_recent` varchar(255) NOT NULL,
  `header_show_standard` tinyint(4) NOT NULL,
  `header_show_trade` tinyint(4) NOT NULL,
  `header_show_wanted` tinyint(4) NOT NULL,
  `setting_quantity_number` tinyint(4) NOT NULL,
  `setting_quantity_desc` tinyint(4) NOT NULL,
  `setting_quantity_close` tinyint(4) NOT NULL,
  `setting_quantity_purchase` tinyint(4) NOT NULL,
  `setting_start_time` tinyint(4) NOT NULL,
  `setting_duration_unlimited` tinyint(4) NOT NULL,
  `setting_show_status` tinyint(4) NOT NULL,
  `enable_unlimited_quantity` tinyint(4) NOT NULL,
  `setting_display_winner` tinyint(4) NOT NULL,
  `pa_main_page_code` text NOT NULL,
  `pa_main_page_register` tinyint(4) NOT NULL,
  `enable_auto_relist` tinyint(4) NOT NULL DEFAULT '1',
  `pg_paymate_merchant_id` varchar(255) NOT NULL,
  `enable_skin_change` tinyint(4) NOT NULL,
  `preferred_days` int(11) NOT NULL,
  `pg_gc_merchant_id` varchar(255) NOT NULL,
  `pg_gc_merchant_key` varchar(255) NOT NULL,
  `remove_marked_deleted` tinyint(4) NOT NULL,
  `dd_enabled` tinyint(4) NOT NULL,
  `dd_max_size` int(11) NOT NULL,
  `dd_expiration` int(11) NOT NULL,
  `dd_terms` text NOT NULL,
  `max_dd` int(11) NOT NULL DEFAULT '1',
  `dd_folder` varchar(255) NOT NULL,
  `enable_embedded_media` tinyint(4) NOT NULL,
  `enable_addthis` tinyint(4) NOT NULL DEFAULT '1',
  `pg_amazon_access_key` varchar(255) NOT NULL,
  `pg_amazon_secret_key` varchar(255) NOT NULL,
  `enable_private_reputation` tinyint(4) NOT NULL,
  `smtp_host` varchar(255) NOT NULL,
  `smtp_username` varchar(255) NOT NULL,
  `smtp_password` varchar(255) NOT NULL,
  `smtp_port` varchar(20) NOT NULL,
  `ga_code` text NOT NULL,
  `enable_refunds` tinyint(4) NOT NULL,
  `refund_min_days` int(11) NOT NULL,
  `refund_max_days` int(11) NOT NULL,
  `refund_start_date` int(11) NOT NULL,
  `max_additional_files` int(11) NOT NULL DEFAULT '5',
  `enable_swdefeat` tinyint(4) NOT NULL,
  `enable_custom_end_time` tinyint(4) NOT NULL DEFAULT '1',
  `carrier_setts` text NOT NULL,
  `currency_converter_last_update` int(11) NOT NULL,
  `pg_alertpay_id` varchar(255) NOT NULL,
  `pg_alertpay_securitycode` varchar(255) NOT NULL,
  `enable_buyer_create_invoice` tinyint(4) NOT NULL DEFAULT '1',
  `fulltext_search_method` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Table with General Settings';

/*Table structure for table `proads_invoices` */

CREATE TABLE `proads_invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `amount` double(16,6) NOT NULL DEFAULT '0.000000',
  `invoice_date` int(11) NOT NULL DEFAULT '0',
  `current_balance` double(16,6) NOT NULL DEFAULT '0.000000',
  `live_fee` tinyint(4) NOT NULL DEFAULT '0',
  `processor` varchar(50) NOT NULL DEFAULT '',
  `can_rollback` tinyint(4) NOT NULL DEFAULT '0',
  `credit_adjustment` tinyint(4) NOT NULL,
  `tax_amount` double(16,4) NOT NULL,
  `tax_rate` double(16,2) NOT NULL,
  `tax_calculated` tinyint(4) NOT NULL,
  `refund_request` tinyint(4) NOT NULL,
  `refund_request_date` int(11) NOT NULL,
  PRIMARY KEY (`invoice_id`),
  KEY `user_id` (`user_id`,`item_id`,`invoice_date`),
  KEY `can_rollback` (`can_rollback`),
  KEY `account_history_item` (`user_id`,`item_id`,`invoice_date`,`live_fee`,`invoice_id`),
  KEY `account_history_live` (`user_id`,`invoice_date`,`live_fee`,`invoice_id`),
  KEY `tax_calculated` (`tax_calculated`),
  KEY `refund_requests` (`refund_request`,`refund_request_date`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_iphistory` */

CREATE TABLE `proads_iphistory` (
  `memberid` int(11) NOT NULL,
  `time1` int(11) NOT NULL,
  `time2` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  KEY `memberid` (`memberid`),
  KEY `member_order` (`memberid`,`time1`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_item_durations` */

CREATE TABLE `proads_item_durations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `days` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `enable_sa` tinyint(4) NOT NULL DEFAULT '1',
  `enable_ta` tinyint(4) NOT NULL DEFAULT '1',
  `enable_wa` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `days` (`days`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_item_media` */

CREATE TABLE `proads_item_media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `media_type` tinyint(4) NOT NULL DEFAULT '0',
  `media_url` varchar(255) NOT NULL DEFAULT '',
  `upload_in_progress` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `main_photo` tinyint(4) NOT NULL,
  `embedded_code` text NOT NULL,
  PRIMARY KEY (`media_id`),
  KEY `select_item_media` (`item_id`,`upload_in_progress`),
  KEY `select_profile_media` (`user_id`),
  KEY `media_url` (`media_url`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_item_offers` */

CREATE TABLE `proads_item_offers` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `seller_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `accepted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`offer_id`),
  KEY `item_id` (`item_id`),
  KEY `item_id_2` (`item_id`,`buyer_id`,`seller_id`,`accepted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_item_rollbacks` */

CREATE TABLE `proads_item_rollbacks` (
  `rollback_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `price` double(16,2) NOT NULL DEFAULT '0.00',
  `is_instant_purchase` tinyint(4) NOT NULL,
  `is_offer` tinyint(4) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(50) NOT NULL DEFAULT '',
  `hpfeat` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat` tinyint(4) NOT NULL DEFAULT '0',
  `bold` tinyint(4) NOT NULL DEFAULT '0',
  `hl` tinyint(4) NOT NULL DEFAULT '0',
  `addl_category_id` int(11) NOT NULL DEFAULT '0',
  `balance` double(16,2) NOT NULL DEFAULT '0.00',
  `nb_images` int(11) NOT NULL DEFAULT '0',
  `nb_videos` int(11) NOT NULL DEFAULT '0',
  `nb_dd` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rollback_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_item_watch` */

CREATE TABLE `proads_item_watch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_items` */

CREATE TABLE `proads_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_type` tinyint(4) NOT NULL DEFAULT '1',
  `list_in` tinyint(4) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `addl_category_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `quantity_unlimited` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `nb_clicks` int(11) NOT NULL DEFAULT '0',
  `apply_tax` tinyint(4) NOT NULL DEFAULT '0',
  `close_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `currency` varchar(15) NOT NULL DEFAULT '',
  `price` double(16,2) NOT NULL DEFAULT '0.00',
  `is_instant_purchase` tinyint(4) NOT NULL DEFAULT '0',
  `is_offer` tinyint(4) NOT NULL DEFAULT '0',
  `offer_min` double(16,2) NOT NULL DEFAULT '0.00',
  `offer_max` double(16,2) NOT NULL DEFAULT '0.00',
  `hpfeat` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat` tinyint(4) NOT NULL DEFAULT '0',
  `hl` tinyint(4) NOT NULL DEFAULT '0',
  `bold` tinyint(4) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0',
  `end_time_type` enum('duration','custom') NOT NULL DEFAULT 'duration',
  `auto_relist_nb` int(11) NOT NULL DEFAULT '0',
  `auto_relist_sold` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `poster_name` varchar(255) NOT NULL DEFAULT '',
  `poster_address` varchar(255) NOT NULL DEFAULT '',
  `poster_phone` varchar(100) NOT NULL DEFAULT '',
  `poster_email` varchar(255) NOT NULL DEFAULT '',
  `direct_payment` text NOT NULL,
  `payment_methods` text NOT NULL,
  `shipping_method` tinyint(4) NOT NULL DEFAULT '0',
  `shipping_int` tinyint(4) NOT NULL DEFAULT '0',
  `postage_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `insurance_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `item_weight` double(16,2) NOT NULL DEFAULT '0.00',
  `type_service` varchar(100) NOT NULL DEFAULT '',
  `creation_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `creation_date` int(11) NOT NULL DEFAULT '0',
  `shipping_details` mediumtext NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `item_swapped` tinyint(4) NOT NULL DEFAULT '0',
  `enable_swap` tinyint(4) NOT NULL DEFAULT '0',
  `direct_payment_paid` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `purchase_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `bank_details` text,
  `count_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `is_relisted_item` tinyint(4) NOT NULL DEFAULT '0',
  `start_time_type` enum('now','custom') NOT NULL DEFAULT 'now',
  `notif_item_relisted` tinyint(4) NOT NULL,
  `is_draft` tinyint(4) NOT NULL,
  `nb_offers` int(11) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `ad_type` (`ad_type`),
  KEY `list_in` (`list_in`,`ad_type`),
  KEY `stats_drafts` (`ad_type`,`is_draft`,`owner_id`),
  KEY `hp_featured` (`ad_type`,`hpfeat`,`active`,`approved`,`closed`,`creation_in_progress`,`deleted`),
  KEY `other_items` (`owner_id`,`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`item_id`),
  KEY `main_page_popular` (`ad_type`,`active`,`closed`,`approved`,`deleted`,`nb_clicks`),
  KEY `main_page_recent` (`ad_type`,`active`,`closed`,`approved`,`deleted`,`start_time`),
  KEY `browse_items` (`active`,`approved`,`closed`,`creation_in_progress`,`item_id`,`ad_type`,`list_in`,`deleted`),
  KEY `user_items` (`ad_type`,`owner_id`,`closed`,`is_draft`,`creation_in_progress`),
  KEY `category_items` (`active`,`approved`,`closed`,`deleted`,`list_in`,`creation_in_progress`,`category_id`),
  KEY `cat_featured` (`catfeat`,`category_id`,`active`,`closed`,`deleted`),
  KEY `owner_id` (`owner_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `search_pattern` (`name`,`description`)
) ENGINE=MyISAM AUTO_INCREMENT=100009 DEFAULT CHARSET=latin1 PACK_KEYS=0;

/*Table structure for table `proads_keywords_watch` */

CREATE TABLE `proads_keywords_watch` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`keyword_id`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_layout_setts` */

CREATE TABLE `proads_layout_setts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_login_box` enum('Y','N') NOT NULL DEFAULT 'Y',
  `hpfeat_nb` tinyint(4) NOT NULL DEFAULT '0',
  `hpfeat_width` smallint(6) NOT NULL DEFAULT '0',
  `hpfeat_max` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat_nb` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat_width` smallint(6) NOT NULL DEFAULT '0',
  `catfeat_max` tinyint(4) NOT NULL DEFAULT '0',
  `nb_recent_ads` smallint(6) NOT NULL DEFAULT '0',
  `nb_popular_ads` smallint(6) NOT NULL DEFAULT '0',
  `d_login_box` tinyint(4) NOT NULL DEFAULT '0',
  `d_news_box` tinyint(4) NOT NULL DEFAULT '0',
  `d_news_nb` tinyint(4) NOT NULL DEFAULT '0',
  `enable_reg_terms` tinyint(4) NOT NULL DEFAULT '0',
  `reg_terms_content` blob NOT NULL,
  `enable_ad_terms` tinyint(4) NOT NULL DEFAULT '0',
  `ad_terms_content` blob NOT NULL,
  `is_about` tinyint(4) NOT NULL DEFAULT '0',
  `is_terms` tinyint(4) NOT NULL DEFAULT '0',
  `is_contact` tinyint(4) NOT NULL DEFAULT '0',
  `is_pp` tinyint(4) NOT NULL DEFAULT '0',
  `name_pa_s` varchar(255) NOT NULL DEFAULT 'Profile Ad',
  `name_pa_p` varchar(255) NOT NULL DEFAULT 'Profile Ads',
  `name_sa_s` varchar(255) NOT NULL DEFAULT 'Standard Ad',
  `name_sa_p` varchar(255) NOT NULL DEFAULT 'Standard Ads',
  `name_ta_s` varchar(255) NOT NULL DEFAULT 'Trade Ad',
  `name_ta_p` varchar(255) NOT NULL DEFAULT 'Trade Ads',
  `name_wa_s` varchar(255) NOT NULL DEFAULT 'Wanted Ad',
  `name_wa_p` varchar(255) NOT NULL DEFAULT 'Wanted Ads',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_messaging` */

CREATE TABLE `proads_messaging` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `topic_id` int(11) NOT NULL DEFAULT '0',
  `sender_id` int(11) NOT NULL DEFAULT '0',
  `receiver_id` int(11) NOT NULL DEFAULT '0',
  `is_question` int(11) NOT NULL DEFAULT '0',
  `message_title` varchar(255) NOT NULL DEFAULT '',
  `message_content` text NOT NULL,
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `is_read` tinyint(4) NOT NULL DEFAULT '0',
  `message_handle` tinyint(4) NOT NULL DEFAULT '1',
  `sender_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `receiver_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `winner_id` int(11) NOT NULL DEFAULT '0',
  `sc_id` int(11) NOT NULL,
  `admin_message` tinyint(4) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `topic_id` (`topic_id`,`is_question`),
  KEY `public_questions` (`item_id`,`message_handle`,`is_question`,`reg_date`),
  KEY `sent_messages` (`sender_id`,`reg_date`,`sender_deleted`),
  KEY `received_messages` (`receiver_id`,`receiver_deleted`,`reg_date`),
  KEY `is_read` (`is_read`),
  KEY `topic_read` (`topic_id`,`is_read`),
  KEY `item_read` (`item_id`,`is_read`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

/*Table structure for table `proads_newsletter_recipients` */

CREATE TABLE `proads_newsletter_recipients` (
  `recipient_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `newsletter_id` int(11) NOT NULL,
  PRIMARY KEY (`recipient_id`),
  KEY `newsletter_id` (`newsletter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_newsletters` */

CREATE TABLE `proads_newsletters` (
  `newsletter_id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter_subject` varchar(255) NOT NULL,
  `newsletter_content` text NOT NULL,
  PRIMARY KEY (`newsletter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_pa_friends` */

CREATE TABLE `proads_pa_friends` (
  `friend_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `friend_user_id` int(11) NOT NULL,
  `reg_date` int(11) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT '50000',
  PRIMARY KEY (`friend_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `friend_user_id` (`friend_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_pa_friends_requests` */

CREATE TABLE `proads_pa_friends_requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `reg_date` int(11) NOT NULL,
  PRIMARY KEY (`request_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_pa_photo_comments` */

CREATE TABLE `proads_pa_photo_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `reg_date` int(11) NOT NULL,
  `comment` text NOT NULL,
  `accepted` tinyint(4) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `media_id` (`media_id`),
  KEY `media_ordered` (`media_id`,`reg_date`,`accepted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_pa_profile_comments` */

CREATE TABLE `proads_pa_profile_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `reg_date` int(11) NOT NULL,
  `comment` text NOT NULL,
  `accepted` tinyint(4) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `user_id` (`user_id`),
  KEY `user_ordered` (`user_id`,`reg_date`,`accepted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_payment_gateways` */

CREATE TABLE `proads_payment_gateways` (
  `pg_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `dp_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `checked` tinyint(4) NOT NULL DEFAULT '0',
  `logo_url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pg_id`),
  KEY `dp_enabled` (`dp_enabled`),
  KEY `checked` (`checked`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Table with payment gateways';

/*Table structure for table `proads_payment_options` */

CREATE TABLE `proads_payment_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `logo_url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Table with payment methods';

/*Table structure for table `proads_postage_calc_tiers` */

CREATE TABLE `proads_postage_calc_tiers` (
  `tier_id` int(11) NOT NULL AUTO_INCREMENT,
  `tier_from` double(16,2) NOT NULL,
  `tier_to` double(16,2) NOT NULL,
  `postage_amount` double(16,2) NOT NULL,
  `tier_type` enum('weight','amount') NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`tier_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_reputation` */

CREATE TABLE `proads_reputation` (
  `reputation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `reputation_content` text NOT NULL,
  `reputation_rate` int(11) NOT NULL DEFAULT '0',
  `reg_date` int(11) DEFAULT NULL,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `submitted` tinyint(4) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `reputation_type` enum('sale','purchase') NOT NULL DEFAULT 'sale',
  `winner_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reputation_id`),
  KEY `rep_sent` (`from_id`,`submitted`,`reg_date`),
  KEY `rep_received` (`submitted`,`user_id`,`reg_date`),
  KEY `rep_calculation` (`user_id`,`reputation_rate`,`submitted`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_sessions` */

CREATE TABLE `proads_sessions` (
  `session_id` varchar(100) NOT NULL DEFAULT '',
  `session_data` text NOT NULL,
  `expires` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_shipping_carriers` */

CREATE TABLE `proads_shipping_carriers` (
  `carrier_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `weight_unit` varchar(100) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  PRIMARY KEY (`carrier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_shipping_locations` */

CREATE TABLE `proads_shipping_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `locations_id` text NOT NULL,
  `amount` double(16,2) NOT NULL,
  `amount_type` enum('flat','percent') NOT NULL DEFAULT 'flat',
  `pc_default` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_shipping_options` */

CREATE TABLE `proads_shipping_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_shopping_carts` */

CREATE TABLE `proads_shopping_carts` (
  `sc_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `buyer_session_id` varchar(255) NOT NULL,
  `sc_date` int(11) NOT NULL,
  `sc_postage` double(16,2) NOT NULL,
  `sc_insurance` double(16,2) NOT NULL,
  `sc_purchased` tinyint(4) NOT NULL,
  `shipping_calc_auto` tinyint(4) NOT NULL,
  `s_deleted` tinyint(4) NOT NULL,
  `b_deleted` tinyint(4) NOT NULL,
  `flag_paid` tinyint(4) NOT NULL,
  `flag_status` tinyint(4) NOT NULL,
  `direct_payment_paid` tinyint(4) NOT NULL,
  `messaging_topic_id` int(11) NOT NULL,
  `carrier_id` int(11) NOT NULL,
  `shipping_method` varchar(255) NOT NULL,
  `invoice_final` tinyint(4) NOT NULL,
  `tax_amount` double(16,4) NOT NULL,
  `tax_rate` double(16,2) NOT NULL,
  `tax_calculated` tinyint(4) NOT NULL,
  `invoice_comments` text NOT NULL,
  PRIMARY KEY (`sc_id`),
  KEY `seller_id` (`seller_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `seller_date` (`seller_id`,`sc_date`),
  KEY `buyer_date` (`buyer_id`,`sc_date`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_shopping_carts_items` */

CREATE TABLE `proads_shopping_carts_items` (
  `sc_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `sc_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`sc_item_id`),
  KEY `sc_id` (`sc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_suggested_categories` */

CREATE TABLE `proads_suggested_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `regdate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_swaps` */

CREATE TABLE `proads_swaps` (
  `swap_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `seller_id` int(11) NOT NULL DEFAULT '0',
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `accepted` tinyint(4) NOT NULL DEFAULT '0',
  `winner_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`swap_id`),
  KEY `item_seller` (`item_id`,`seller_id`),
  KEY `item_buyer` (`item_id`,`buyer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_tax_settings` */

CREATE TABLE `proads_tax_settings` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(100) NOT NULL DEFAULT '',
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `countries_id` text NOT NULL,
  `tax_user_types` varchar(20) NOT NULL DEFAULT 'a',
  `site_tax` tinyint(4) NOT NULL DEFAULT '0',
  `seller_countries_id` text NOT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_timesettings` */

CREATE TABLE `proads_timesettings` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `value` tinyint(4) NOT NULL DEFAULT '0',
  `caption` varchar(20) NOT NULL DEFAULT '',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1 COMMENT='Table with time settings';

/*Table structure for table `proads_user_accounts` */

CREATE TABLE `proads_user_accounts` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `pa_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `pa_upl_pic` tinyint(4) NOT NULL DEFAULT '0',
  `pa_send_msg` tinyint(4) NOT NULL DEFAULT '0',
  `sa_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `sa_upl_pic` tinyint(4) NOT NULL DEFAULT '0',
  `sa_html` tinyint(4) NOT NULL DEFAULT '0',
  `sa_free_ads` smallint(6) NOT NULL DEFAULT '0',
  `ta_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `ta_upl_pic` tinyint(4) NOT NULL DEFAULT '0',
  `ta_html` tinyint(4) NOT NULL DEFAULT '0',
  `ta_free_ads` smallint(6) NOT NULL DEFAULT '0',
  `wa_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `wa_upl_pic` tinyint(4) NOT NULL DEFAULT '0',
  `wa_html` tinyint(4) NOT NULL DEFAULT '0',
  `wa_free_ads` smallint(6) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `recurring_days` smallint(6) NOT NULL DEFAULT '0',
  `price` double(16,2) NOT NULL DEFAULT '0.00',
  `fees_custom` tinyint(4) NOT NULL DEFAULT '0',
  `fees_reduction` double unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_id`),
  KEY `active` (`active`),
  KEY `price` (`price`),
  KEY `recurring_days` (`recurring_days`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_users` */

CREATE TABLE `proads_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `pg_paypal_email` varchar(255) NOT NULL DEFAULT '',
  `pg_nochex_email` varchar(255) NOT NULL DEFAULT '',
  `pg_worldpay_id` varchar(100) NOT NULL DEFAULT '',
  `pg_checkout_id` varchar(100) NOT NULL DEFAULT '',
  `pg_ikobo_username` varchar(100) NOT NULL DEFAULT '',
  `pg_ikobo_password` varchar(100) NOT NULL DEFAULT '',
  `pg_protx_username` varchar(100) NOT NULL DEFAULT '',
  `pg_protx_password` varchar(100) NOT NULL DEFAULT '',
  `pg_authnet_username` varchar(100) NOT NULL DEFAULT '',
  `pg_authnet_password` varchar(100) NOT NULL DEFAULT '',
  `pg_mb_email` varchar(255) NOT NULL DEFAULT '',
  `account_id` int(11) NOT NULL DEFAULT '0',
  `account_last_payment` int(11) NOT NULL DEFAULT '0',
  `account_next_payment` int(11) NOT NULL DEFAULT '0',
  `payment_mode` tinyint(4) NOT NULL DEFAULT '0',
  `balance` double(16,2) NOT NULL DEFAULT '0.00',
  `max_credit` double(16,2) NOT NULL DEFAULT '0.00',
  `salt` char(3) NOT NULL DEFAULT '',
  `tax_account_type` tinyint(4) NOT NULL DEFAULT '0',
  `tax_reg_number` varchar(100) NOT NULL DEFAULT '',
  `tax_apply_exempt` tinyint(4) NOT NULL DEFAULT '0',
  `tax_exempted` tinyint(4) NOT NULL DEFAULT '0',
  `country` int(11) NOT NULL DEFAULT '0',
  `tax_company_name` varchar(255) NOT NULL DEFAULT '',
  `shop_account_id` int(11) NOT NULL DEFAULT '0',
  `shop_categories` text NOT NULL,
  `shop_active` tinyint(4) NOT NULL DEFAULT '0',
  `state` varchar(100) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `is_seller` tinyint(4) NOT NULL,
  `enable_aboutme_page` tinyint(4) NOT NULL,
  `seller_verified` tinyint(4) NOT NULL,
  `enable_profile_page` tinyint(4) NOT NULL,
  `preferred_seller` tinyint(4) NOT NULL,
  `aboutme_page_content` text NOT NULL,
  `shop_mainpage` mediumblob NOT NULL,
  `shop_logo_path` varchar(255) NOT NULL DEFAULT '',
  `shop_last_payment` int(11) NOT NULL DEFAULT '0',
  `shop_next_payment` int(11) NOT NULL DEFAULT '0',
  `shop_name` varchar(255) NOT NULL DEFAULT '',
  `shop_nb_items` int(11) NOT NULL DEFAULT '0',
  `shop_template_id` smallint(6) NOT NULL DEFAULT '0',
  `shop_about` text NOT NULL,
  `shop_specials` text NOT NULL,
  `shop_shipping_info` text NOT NULL,
  `shop_company_policies` text NOT NULL,
  `shop_nb_feat_items` int(11) NOT NULL DEFAULT '0',
  `shop_metatags` text NOT NULL,
  `newsletter` tinyint(4) NOT NULL DEFAULT '0',
  `mail_activated` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `default_name` varchar(255) NOT NULL DEFAULT '',
  `default_description` text NOT NULL,
  `default_duration` smallint(6) NOT NULL DEFAULT '0',
  `default_enable_swap` tinyint(4) NOT NULL DEFAULT '0',
  `default_shipping_method` tinyint(4) NOT NULL DEFAULT '0',
  `default_shipping_int` tinyint(4) NOT NULL DEFAULT '0',
  `default_payment_methods` text NOT NULL,
  `default_postage_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `default_insurance_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `default_type_service` varchar(50) NOT NULL DEFAULT '',
  `default_shipping_details` mediumtext NOT NULL,
  `default_public_questions` tinyint(4) NOT NULL DEFAULT '0',
  `mail_account_suspended` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_sold` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_won` tinyint(4) NOT NULL DEFAULT '1',
  `mail_buyer_details` tinyint(4) NOT NULL DEFAULT '1',
  `mail_seller_details` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_watch` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_closed` tinyint(4) NOT NULL DEFAULT '1',
  `mail_keyword_match` tinyint(4) NOT NULL DEFAULT '1',
  `mail_confirm_to_seller` tinyint(4) NOT NULL DEFAULT '1',
  `mail_messaging_received` tinyint(4) NOT NULL DEFAULT '1',
  `mail_messaging_sent` tinyint(4) NOT NULL DEFAULT '0',
  `user_admin_notes` text NOT NULL,
  `ad_approval` tinyint(4) NOT NULL DEFAULT '0',
  `seller_verif_last_payment` int(11) NOT NULL,
  `seller_verif_next_payment` int(11) NOT NULL,
  `profile_www` varchar(255) NOT NULL,
  `profile_msn` varchar(255) NOT NULL,
  `profile_icq` varchar(255) NOT NULL,
  `profile_aim` varchar(255) NOT NULL,
  `profile_yim` varchar(255) NOT NULL,
  `profile_skype` varchar(255) NOT NULL,
  `paypal_address_override` tinyint(4) NOT NULL,
  `paypal_first_name` varchar(32) NOT NULL,
  `paypal_last_name` varchar(64) NOT NULL,
  `paypal_address1` varchar(100) NOT NULL,
  `paypal_address2` varchar(100) NOT NULL,
  `paypal_city` varchar(100) NOT NULL,
  `paypal_state` varchar(100) NOT NULL,
  `paypal_zip` varchar(32) NOT NULL,
  `paypal_country` varchar(100) NOT NULL,
  `paypal_email` varchar(255) NOT NULL,
  `paypal_night_phone_a` varchar(3) NOT NULL,
  `paypal_night_phone_b` varchar(16) NOT NULL,
  `paypal_night_phone_c` varchar(4) NOT NULL,
  `items_sold` int(11) NOT NULL,
  `items_bought` int(11) NOT NULL,
  `setting_quantity_number` tinyint(4) NOT NULL DEFAULT '1',
  `setting_quantity_desc` tinyint(4) NOT NULL,
  `setting_quantity_close` tinyint(4) NOT NULL DEFAULT '1',
  `setting_quantity_purchase` tinyint(4) NOT NULL,
  `setting_start_time` tinyint(4) NOT NULL DEFAULT '1',
  `setting_duration_unlimited` tinyint(4) NOT NULL DEFAULT '1',
  `setting_show_status` tinyint(4) NOT NULL,
  `setting_display_winner` tinyint(4) NOT NULL DEFAULT '1',
  `shop_international_shipping` tinyint(4) NOT NULL,
  `shop_add_tax` tinyint(4) NOT NULL,
  `shop_direct_payment` varchar(255) NOT NULL,
  `pa_firstname` varchar(255) NOT NULL,
  `pa_lastname` varchar(255) NOT NULL,
  `pa_gender` enum('m','f') NOT NULL DEFAULT 'm',
  `pa_birthdate` date NOT NULL,
  `pa_hometown` varchar(255) NOT NULL,
  `pa_country` int(11) NOT NULL,
  `profile_template_id` int(11) NOT NULL DEFAULT '0',
  `pa_accept_comments` tinyint(4) NOT NULL,
  `default_currency` varchar(100) NOT NULL,
  `default_direct_payment` text NOT NULL,
  `pg_gc_merchant_id` varchar(255) NOT NULL,
  `pg_gc_merchant_key` varchar(255) NOT NULL,
  `pg_paymate_merchant_id` varchar(255) NOT NULL,
  `preferred_seller_exp_date` int(11) NOT NULL,
  `admin_suspended` tinyint(4) NOT NULL,
  `pg_amazon_access_key` varchar(255) NOT NULL,
  `pg_amazon_secret_key` varchar(255) NOT NULL,
  `enable_private_reputation` tinyint(4) NOT NULL,
  `shop_nb_feat_items_row` int(11) NOT NULL,
  `pc_free_postage` tinyint(4) NOT NULL,
  `pc_free_postage_amount` double(16,2) NOT NULL,
  `pc_postage_type` enum('item','weight','amount','flat') NOT NULL,
  `pc_weight_unit` varchar(50) NOT NULL,
  `pc_postage_calc_type` enum('default','custom','carriers') NOT NULL,
  `pc_shipping_locations` enum('global','local') NOT NULL DEFAULT 'global',
  `pc_flat_first` double(16,2) NOT NULL,
  `pc_flat_additional` double(16,2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `shipping_carriers` varchar(255) NOT NULL,
  `pg_alertpay_id` varchar(255) NOT NULL,
  `pg_alertpay_securitycode` varchar(255) NOT NULL,
  `exceeded_balance_email` tinyint(4) NOT NULL,
  `default_bank_details` text NOT NULL,
  `default_auto_relist` tinyint(4) NOT NULL,
  `default_auto_relist_sold` int(11) NOT NULL,
  `default_auto_relist_nb` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `shop_active` (`active`,`shop_active`,`user_id`),
  KEY `stores_list` (`active`,`shop_active`,`shop_nb_items`),
  KEY `acc_overdue_users` (`payment_mode`,`reg_date`),
  KEY `active_users` (`active`,`reg_date`,`approved`),
  KEY `users_tax_acc_type` (`tax_account_type`,`reg_date`),
  KEY `users_tax_exempt` (`tax_apply_exempt`,`tax_exempted`,`reg_date`),
  KEY `active` (`active`),
  KEY `pa_gender` (`pa_gender`),
  KEY `pa_birthdate` (`pa_birthdate`),
  KEY `pa_country` (`pa_country`),
  FULLTEXT KEY `shop_name` (`shop_name`),
  FULLTEXT KEY `username` (`username`),
  FULLTEXT KEY `email` (`email`),
  FULLTEXT KEY `pa_firstname` (`pa_firstname`),
  FULLTEXT KEY `pa_hometown` (`pa_hometown`)
) ENGINE=MyISAM AUTO_INCREMENT=100009 DEFAULT CHARSET=latin1 PACK_KEYS=0;

/*Table structure for table `proads_vouchers` */

CREATE TABLE `proads_vouchers` (
  `voucher_id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_code` varchar(255) NOT NULL DEFAULT '',
  `voucher_type` varchar(20) NOT NULL DEFAULT '',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `exp_date` int(11) NOT NULL DEFAULT '0',
  `nb_uses` int(11) NOT NULL DEFAULT '0',
  `uses_left` int(11) NOT NULL DEFAULT '0',
  `voucher_reduction` double(16,2) NOT NULL DEFAULT '0.00',
  `assigned_users` text NOT NULL,
  `assigned_fees` varchar(255) NOT NULL DEFAULT '',
  `voucher_name` varchar(100) NOT NULL DEFAULT '',
  `voucher_duration` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`voucher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `proads_winners` */

CREATE TABLE `proads_winners` (
  `winner_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL DEFAULT '0',
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `email_sent` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `s_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `b_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `flag_paid` tinyint(4) NOT NULL DEFAULT '0',
  `flag_status` tinyint(4) NOT NULL DEFAULT '0',
  `direct_payment_paid` tinyint(4) NOT NULL DEFAULT '0',
  `invoice_sent` tinyint(4) NOT NULL DEFAULT '0',
  `vat_included` tinyint(4) NOT NULL DEFAULT '0',
  `postage_included` tinyint(4) NOT NULL DEFAULT '0',
  `insurance_included` tinyint(4) NOT NULL DEFAULT '0',
  `insurance_amount` double(10,2) DEFAULT '0.00',
  `purchase_date` int(11) NOT NULL DEFAULT '0',
  `messaging_topic_id` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) NOT NULL,
  `postage_amount` double(16,2) NOT NULL,
  `sc_id` int(11) NOT NULL,
  `tax_amount` double(16,4) NOT NULL,
  `tax_rate` double(16,2) NOT NULL,
  `tax_calculated` tinyint(4) NOT NULL,
  `is_dd` tinyint(4) NOT NULL,
  `dd_active` tinyint(4) NOT NULL,
  `dd_active_date` int(11) NOT NULL,
  `dd_nb_downloads` int(11) NOT NULL,
  `refund_invoice_id` int(11) NOT NULL,
  `invoice_comments` text NOT NULL,
  `pc_postage_type` enum('item','weight','amount','flat') NOT NULL,
  `carrier_id` int(11) NOT NULL,
  `shipping_method` varchar(255) NOT NULL,
  `invoice_final` tinyint(4) NOT NULL,
  PRIMARY KEY (`winner_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `item_id` (`item_id`),
  KEY `sc_id` (`sc_id`),
  KEY `sold_items_date` (`seller_id`,`invoice_id`,`sc_id`,`s_deleted`,`purchase_date`),
  KEY `sold_items_item` (`seller_id`,`invoice_id`,`sc_id`,`s_deleted`,`item_id`),
  KEY `sold_items_quantity` (`seller_id`,`invoice_id`,`sc_id`,`s_deleted`,`quantity`),
  KEY `sold_items_price` (`seller_id`,`invoice_id`,`sc_id`,`s_deleted`,`amount`),
  KEY `won_items_date` (`buyer_id`,`invoice_id`,`sc_id`,`b_deleted`,`purchase_date`),
  KEY `won_items_item` (`buyer_id`,`invoice_id`,`sc_id`,`b_deleted`,`item_id`),
  KEY `won_items_quantity` (`buyer_id`,`invoice_id`,`sc_id`,`b_deleted`,`quantity`),
  KEY `won_items_price` (`buyer_id`,`invoice_id`,`sc_id`,`b_deleted`,`amount`),
  KEY `calculate_tax` (`invoice_sent`,`tax_calculated`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `proads_wordfilter` */

CREATE TABLE `proads_wordfilter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `word` (`word`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Table with words that are filtered';

/*Table structure for table `probid_abuses` */

CREATE TABLE `probid_abuses` (
  `abuse_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `abuser_username` varchar(100) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `auction_id` int(11) NOT NULL,
  PRIMARY KEY (`abuse_id`),
  KEY `reg_date` (`reg_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table with abuse reports';

/*Table structure for table `probid_admin_notes` */

CREATE TABLE `probid_admin_notes` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `reg_date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_admins` */

CREATE TABLE `probid_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `date_created` int(11) NOT NULL DEFAULT '0',
  `date_lastlogin` int(11) NOT NULL DEFAULT '0',
  `level` enum('1','2','3','4','5') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Table with admin info';

/*Table structure for table `probid_adverts` */

CREATE TABLE `probid_adverts` (
  `advert_id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_url` varchar(255) NOT NULL DEFAULT '',
  `views` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `advert_img_path` varchar(255) NOT NULL DEFAULT '',
  `advert_alt_text` varchar(255) NOT NULL DEFAULT '',
  `advert_text_under` varchar(255) NOT NULL DEFAULT '',
  `views_purchased` int(11) NOT NULL DEFAULT '0',
  `clicks_purchased` int(11) NOT NULL DEFAULT '0',
  `advert_categories` text NOT NULL,
  `advert_keywords` text NOT NULL,
  `advert_code` longtext NOT NULL,
  `advert_type` tinyint(4) NOT NULL DEFAULT '0',
  `section_id` int(11) NOT NULL,
  PRIMARY KEY (`advert_id`),
  KEY `views` (`views`,`clicks`,`views_purchased`,`clicks_purchased`,`advert_type`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=latin1 COMMENT='Table with banners data';

/*Table structure for table `probid_auction_durations` */

CREATE TABLE `probid_auction_durations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `days` int(11) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Table with auction durations';

/*Table structure for table `probid_auction_media` */

CREATE TABLE `probid_auction_media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `media_url` varchar(255) NOT NULL DEFAULT '',
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `media_type` tinyint(4) NOT NULL DEFAULT '0',
  `upload_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `wanted_ad_id` int(11) NOT NULL DEFAULT '0',
  `embedded_code` text NOT NULL,
  `reverse_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  PRIMARY KEY (`media_id`),
  KEY `select_media_simple` (`auction_id`,`upload_in_progress`),
  KEY `select_wa_media_simple` (`wanted_ad_id`,`upload_in_progress`),
  KEY `select_media_advanced` (`auction_id`,`media_type`,`upload_in_progress`),
  KEY `auction_id` (`auction_id`),
  KEY `media_url` (`media_url`),
  KEY `select_reverse_media_simple` (`reverse_id`,`upload_in_progress`),
  KEY `select_profile_media_simple` (`profile_id`,`upload_in_progress`)
) ENGINE=MyISAM AUTO_INCREMENT=3474 DEFAULT CHARSET=latin1 COMMENT='Table with auction media details';

/*Table structure for table `probid_auction_offers` */

CREATE TABLE `probid_auction_offers` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `seller_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `accepted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`offer_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_auction_rollbacks` */

CREATE TABLE `probid_auction_rollbacks` (
  `rollback_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `start_price` double(16,2) NOT NULL DEFAULT '0.00',
  `reserve_price` double(16,2) NOT NULL DEFAULT '0.00',
  `buyout_price` double(16,2) NOT NULL DEFAULT '0.00',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(50) NOT NULL DEFAULT '',
  `hpfeat` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat` tinyint(4) NOT NULL DEFAULT '0',
  `bold` tinyint(4) NOT NULL DEFAULT '0',
  `hl` tinyint(4) NOT NULL DEFAULT '0',
  `addl_category_id` int(11) NOT NULL DEFAULT '0',
  `balance` double(16,2) NOT NULL DEFAULT '0.00',
  `nb_images` int(11) NOT NULL DEFAULT '0',
  `nb_videos` int(11) NOT NULL DEFAULT '0',
  `is_offer` tinyint(4) NOT NULL,
  `nb_dd` int(11) NOT NULL,
  `reverse_id` int(11) NOT NULL,
  PRIMARY KEY (`rollback_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=191 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_auction_watch` */

CREATE TABLE `probid_auction_watch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `auction_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `auction_id` (`auction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COMMENT='Table for Items Watching';

/*Table structure for table `probid_auctions` */

CREATE TABLE `probid_auctions` (
  `auction_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `picpath` varchar(255) NOT NULL DEFAULT '',
  `quantity` smallint(6) NOT NULL DEFAULT '0',
  `auction_type` varchar(30) NOT NULL DEFAULT '',
  `start_price` double(16,2) NOT NULL DEFAULT '0.00',
  `reserve_price` double(16,2) NOT NULL DEFAULT '0.00',
  `buyout_price` double(16,2) NOT NULL DEFAULT '0.00',
  `bid_increment_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `duration` smallint(6) NOT NULL DEFAULT '0',
  `country` varchar(100) NOT NULL DEFAULT '',
  `zip_code` varchar(50) NOT NULL DEFAULT '',
  `shipping_method` tinyint(4) NOT NULL DEFAULT '0',
  `shipping_int` tinyint(4) NOT NULL DEFAULT '0',
  `payment_methods` text NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `start_time_old` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_old` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `nb_bids` int(11) NOT NULL DEFAULT '0',
  `max_bid` double(16,2) NOT NULL DEFAULT '0.00',
  `nb_clicks` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `hpfeat` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat` tinyint(4) NOT NULL DEFAULT '0',
  `bold` tinyint(4) NOT NULL DEFAULT '0',
  `hl` tinyint(4) NOT NULL DEFAULT '0',
  `hidden_bidding` tinyint(4) NOT NULL DEFAULT '0',
  `currency` varchar(100) NOT NULL DEFAULT '',
  `auction_swapped` tinyint(4) NOT NULL DEFAULT '0',
  `postage_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `insurance_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `type_service` varchar(50) NOT NULL DEFAULT '',
  `enable_swap` tinyint(4) NOT NULL DEFAULT '0',
  `direct_payment_paid` tinyint(4) NOT NULL DEFAULT '0',
  `addl_category_id` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `shipping_details` mediumtext NOT NULL,
  `hpfeat_desc` text NOT NULL,
  `reserve_offer` double(16,2) NOT NULL DEFAULT '0.00',
  `reserve_offer_winner_id` int(11) NOT NULL DEFAULT '0',
  `list_in` varchar(50) NOT NULL DEFAULT 'auction',
  `close_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `bid_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `bank_details` text,
  `direct_payment` text,
  `apply_tax` tinyint(4) NOT NULL DEFAULT '0',
  `auto_relist_bids` tinyint(4) NOT NULL DEFAULT '0',
  `end_time_type` enum('duration','custom') NOT NULL DEFAULT 'duration',
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `count_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `listing_type` enum('full','quick','buy_out') NOT NULL DEFAULT 'full',
  `is_offer` tinyint(4) NOT NULL DEFAULT '0',
  `offer_min` double(16,2) NOT NULL DEFAULT '0.00',
  `offer_max` double(16,2) NOT NULL DEFAULT '0.00',
  `auto_relist_nb` tinyint(4) NOT NULL DEFAULT '0',
  `is_relisted_item` tinyint(4) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `creation_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `creation_date` int(11) NOT NULL DEFAULT '0',
  `state` varchar(100) NOT NULL DEFAULT '',
  `start_time_type` enum('now','custom') NOT NULL DEFAULT 'now',
  `retract_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `notif_item_relisted` tinyint(4) NOT NULL,
  `is_draft` tinyint(4) NOT NULL,
  `nb_offers` int(11) NOT NULL,
  `end_time_cron` int(11) NOT NULL,
  `item_weight` int(11) NOT NULL,
  `fb_decrement_amount` double(16,2) NOT NULL,
  `fb_decrement_interval` int(11) NOT NULL,
  `fb_next_decrement` int(11) NOT NULL,
  `fb_current_bid` double(16,2) NOT NULL,
  `npuser_id` int(11) NOT NULL,
  PRIMARY KEY (`auction_id`),
  KEY `stats_drafts` (`is_draft`,`owner_id`),
  KEY `user_auctions` (`owner_id`,`closed`,`deleted`,`creation_in_progress`,`is_draft`),
  KEY `mb_auctions_id` (`deleted`,`owner_id`,`closed`,`creation_in_progress`,`auction_id`),
  KEY `user_auctions_end_time` (`closed`,`owner_id`,`end_time`,`deleted`,`creation_in_progress`,`is_draft`),
  KEY `auctions_start_time` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`start_time`),
  KEY `auctions_nb_bids` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`nb_bids`),
  KEY `auctions_max_bid` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`max_bid`),
  KEY `auctions_start_price` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`start_price`),
  KEY `auctions_name` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`name`),
  KEY `auctions_end_time` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`end_time`),
  KEY `hp_featured` (`hpfeat`,`active`,`approved`,`closed`,`creation_in_progress`,`deleted`),
  KEY `cat_featured` (`catfeat`,`active`,`approved`,`closed`,`creation_in_progress`,`deleted`),
  KEY `owner_id` (`owner_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `zip_code` (`zip_code`)
) ENGINE=MyISAM AUTO_INCREMENT=101537 DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Table with auctions details';

/*Table structure for table `probid_banned` */

CREATE TABLE `probid_banned` (
  `banned_id` int(11) NOT NULL AUTO_INCREMENT,
  `banned_address` varchar(255) NOT NULL DEFAULT '',
  `address_type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banned_id`),
  KEY `address_type` (`address_type`),
  FULLTEXT KEY `banned_address` (`banned_address`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_bid_increments` */

CREATE TABLE `probid_bid_increments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value_from` double(16,2) NOT NULL DEFAULT '0.00',
  `value_to` double(16,2) NOT NULL DEFAULT '0.00',
  `increment` double(16,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `value_from` (`value_from`,`value_to`)
) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=latin1 COMMENT='Table with bid increments';

/*Table structure for table `probid_bids` */

CREATE TABLE `probid_bids` (
  `bid_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `bidder_id` int(11) NOT NULL DEFAULT '0',
  `bid_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `bid_date` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `bid_proxy` double(16,2) NOT NULL DEFAULT '0.00',
  `bid_out` tinyint(4) NOT NULL DEFAULT '0',
  `bid_invalid` tinyint(4) NOT NULL DEFAULT '0',
  `email_sent` tinyint(4) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `rp_winner` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid_id`),
  KEY `auction_id` (`auction_id`),
  KEY `high_bids` (`auction_id`,`bid_amount`),
  KEY `bid_types` (`auction_id`,`bid_out`,`bid_invalid`),
  KEY `auction_bids` (`auction_id`,`bidder_id`)
) ENGINE=MyISAM AUTO_INCREMENT=171 DEFAULT CHARSET=latin1 COMMENT='Table with bids';

/*Table structure for table `probid_blocked_domains` */

CREATE TABLE `probid_blocked_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table with blocked email domains';

/*Table structure for table `probid_blocked_users` */

CREATE TABLE `probid_blocked_users` (
  `block_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `block_reason` text NOT NULL,
  `show_reason` tinyint(4) NOT NULL DEFAULT '0',
  `block_bid` tinyint(4) NOT NULL,
  `block_message` tinyint(4) NOT NULL,
  `block_reputation` tinyint(4) NOT NULL,
  PRIMARY KEY (`block_id`),
  KEY `block_src` (`user_id`,`owner_id`),
  KEY `reg_src` (`owner_id`,`reg_date`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_bulktmp` */

CREATE TABLE `probid_bulktmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `desc` longtext NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  `auctiontype` varchar(20) NOT NULL DEFAULT '',
  `price` varchar(40) NOT NULL DEFAULT '',
  `usereserve` char(1) NOT NULL DEFAULT '',
  `reserve` varchar(40) NOT NULL DEFAULT '',
  `usebuynow` char(1) NOT NULL DEFAULT '',
  `buynow` varchar(40) NOT NULL DEFAULT '',
  `useinc` char(1) NOT NULL DEFAULT '',
  `inc` varchar(40) NOT NULL DEFAULT '',
  `duration` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(250) NOT NULL DEFAULT '',
  `zip` varchar(40) NOT NULL DEFAULT '',
  `whopays` varchar(40) NOT NULL DEFAULT '',
  `shipinter` char(1) NOT NULL DEFAULT '',
  `paymethods` varchar(250) NOT NULL DEFAULT '',
  `category` varchar(40) NOT NULL DEFAULT '',
  `homefeat` char(1) NOT NULL DEFAULT '',
  `catfeat` char(1) NOT NULL DEFAULT '',
  `bold` char(1) NOT NULL DEFAULT '',
  `highlight` char(1) NOT NULL DEFAULT '',
  `private` char(1) NOT NULL DEFAULT '',
  `currency` varchar(40) NOT NULL DEFAULT '',
  `postcosts` varchar(40) NOT NULL DEFAULT '',
  `insurance` varchar(40) NOT NULL DEFAULT '',
  `service` varchar(250) NOT NULL DEFAULT '',
  `allowswap` char(1) NOT NULL DEFAULT '',
  `accdirect` char(1) NOT NULL DEFAULT '',
  `directemail` varchar(250) NOT NULL DEFAULT '',
  `hasimage` varchar(10) NOT NULL DEFAULT '',
  `pictures` longtext NOT NULL,
  `haswebimage` varchar(10) NOT NULL DEFAULT '',
  `webimages` longtext NOT NULL,
  `homefeatdesc` longtext NOT NULL,
  `postnote` varchar(255) NOT NULL DEFAULT '',
  `areaid` varchar(40) NOT NULL DEFAULT '',
  `voucher` varchar(250) NOT NULL DEFAULT '',
  `auctionstore` varchar(40) NOT NULL DEFAULT 'auction',
  `paypalbuyers` char(1) NOT NULL DEFAULT '',
  `catsubfieldid1` int(11) NOT NULL DEFAULT '0',
  `catsubfieldval1` mediumtext NOT NULL,
  `catsubfieldid2` int(11) NOT NULL DEFAULT '0',
  `catsubfieldval2` mediumtext NOT NULL,
  `catsubfieldid3` int(11) NOT NULL DEFAULT '0',
  `catsubfieldval3` mediumtext NOT NULL,
  `catsubfieldid4` int(11) NOT NULL DEFAULT '0',
  `catsubfieldval4` mediumtext NOT NULL,
  `catsubfieldid5` int(11) NOT NULL DEFAULT '0',
  `catsubfieldval5` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_categories` */

CREATE TABLE `probid_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `hidden` tinyint(4) NOT NULL DEFAULT '0',
  `items_counter` int(11) NOT NULL DEFAULT '0',
  `hover_title` varchar(255) NOT NULL DEFAULT '',
  `meta_description` mediumtext NOT NULL,
  `meta_keywords` mediumtext NOT NULL,
  `image_path` varchar(255) NOT NULL DEFAULT '',
  `wanted_counter` int(11) NOT NULL DEFAULT '0',
  `is_subcat` varchar(5) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `custom_fees` tinyint(4) NOT NULL DEFAULT '0',
  `minimum_age` tinyint(4) NOT NULL,
  `custom_skin` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `parent_id` (`parent_id`),
  KEY `parent_id_2` (`parent_id`,`order_id`,`name`),
  KEY `parent_id_3` (`parent_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1903 DEFAULT CHARSET=latin1 COMMENT='Table with categories';

/*Table structure for table `probid_content_pages` */

CREATE TABLE `probid_content_pages` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_name` varchar(255) NOT NULL DEFAULT '',
  `topic_content` text NOT NULL,
  `topic_lang` varchar(255) NOT NULL DEFAULT '',
  `topic_order` int(11) NOT NULL DEFAULT '0',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `page_id` varchar(255) NOT NULL DEFAULT '',
  `page_handle` varchar(50) NOT NULL DEFAULT '',
  `show_link` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`topic_id`),
  KEY `topic_order` (`topic_order`,`reg_date`),
  FULLTEXT KEY `topic_lang` (`topic_lang`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_countries` */

CREATE TABLE `probid_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `country_order` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `country_order` (`country_order`),
  KEY `parent_id` (`parent_id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2287 DEFAULT CHARSET=latin1 COMMENT='Table with countries';

/*Table structure for table `probid_currencies` */

CREATE TABLE `probid_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(10) NOT NULL DEFAULT '',
  `caption` varchar(100) NOT NULL DEFAULT '',
  `convert_rate` double(16,6) NOT NULL DEFAULT '1.000000',
  `convert_date` int(11) NOT NULL DEFAULT '0',
  `currency_symbol` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 COMMENT='Table with currency listings';

/*Table structure for table `probid_custom_fields` */

CREATE TABLE `probid_custom_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(255) NOT NULL DEFAULT '',
  `field_order` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `page_handle` varchar(25) NOT NULL DEFAULT '',
  `section_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `field_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`field_id`),
  KEY `field_order` (`field_order`),
  KEY `active` (`active`),
  KEY `page_handle` (`page_handle`),
  KEY `section_id` (`section_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_custom_fields_boxes` */

CREATE TABLE `probid_custom_fields_boxes` (
  `box_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL DEFAULT '0',
  `box_name` text NOT NULL,
  `box_value` text NOT NULL,
  `box_order` int(11) NOT NULL DEFAULT '0',
  `box_type` int(11) NOT NULL DEFAULT '0',
  `mandatory` enum('0','1') NOT NULL DEFAULT '0',
  `box_type_special` int(11) NOT NULL DEFAULT '0',
  `formchecker_functions` text NOT NULL,
  `box_searchable` tinyint(4) NOT NULL,
  PRIMARY KEY (`box_id`),
  KEY `field_id` (`field_id`),
  KEY `box_order` (`box_order`),
  KEY `box_type` (`box_type`),
  KEY `box_searchable` (`box_searchable`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_custom_fields_data` */

CREATE TABLE `probid_custom_fields_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_id` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `box_value` text NOT NULL,
  `page_handle` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`data_id`),
  KEY `box_id` (`box_id`),
  KEY `user_custom_fields` (`owner_id`,`page_handle`),
  KEY `owner_id` (`owner_id`),
  FULLTEXT KEY `box_value` (`box_value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_custom_fields_sections` */

CREATE TABLE `probid_custom_fields_sections` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) NOT NULL DEFAULT '',
  `page_handle` varchar(25) NOT NULL DEFAULT '',
  `order_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`section_id`),
  KEY `page_handle` (`page_handle`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_custom_fields_special` */

CREATE TABLE `probid_custom_fields_special` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_name` varchar(255) NOT NULL DEFAULT '',
  `box_type` int(11) NOT NULL DEFAULT '0',
  `table_name_raw` varchar(255) NOT NULL DEFAULT '',
  `box_value_code` text NOT NULL,
  PRIMARY KEY (`type_id`),
  KEY `box_type` (`box_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_custom_fields_types` */

CREATE TABLE `probid_custom_fields_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_type` varchar(100) NOT NULL DEFAULT '',
  `maxfields` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_dateformat` */

CREATE TABLE `probid_dateformat` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT '',
  `value` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT '',
  `active` varchar(20) NOT NULL DEFAULT '0',
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table with date format';

/*Table structure for table `probid_favourite_stores` */

CREATE TABLE `probid_favourite_stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `user_id` (`user_id`),
  KEY `store_id_2` (`store_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_fees` */

CREATE TABLE `probid_fees` (
  `fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `signup_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `picture_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `hlitem_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `bolditem_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `hpfeat_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `catfeat_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `rp_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `swap_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `second_cat_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `buyout_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `wanted_ad_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `endauction_fee_applies` enum('s','b') NOT NULL DEFAULT 's',
  `custom_start_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `relist_fee_reduction` double(16,2) NOT NULL DEFAULT '0.00',
  `video_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `makeoffer_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `verification_fee` double(16,2) NOT NULL,
  `verification_recurring` int(11) NOT NULL,
  `free_images` int(11) NOT NULL,
  `free_media` int(11) NOT NULL,
  `dd_fee` double(16,2) NOT NULL,
  `reverse_fees` text NOT NULL,
  `reverse_category_id` int(11) NOT NULL,
  PRIMARY KEY (`fee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Table with fees';

/*Table structure for table `probid_fees_tiers` */

CREATE TABLE `probid_fees_tiers` (
  `tier_id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_from` double(16,2) NOT NULL DEFAULT '0.00',
  `fee_to` double(16,2) NOT NULL DEFAULT '0.00',
  `fee_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `calc_type` varchar(20) NOT NULL DEFAULT '',
  `fee_type` varchar(20) NOT NULL DEFAULT '',
  `store_nb_items` int(11) NOT NULL DEFAULT '0',
  `store_recurring` int(11) NOT NULL DEFAULT '0',
  `store_name` varchar(255) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `store_endauction_fee` double(16,2) NOT NULL DEFAULT '0.00',
  `store_featured` tinyint(4) NOT NULL,
  PRIMARY KEY (`tier_id`),
  KEY `category_id` (`category_id`),
  KEY `fee_type` (`fee_type`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_gc_transactions` */

CREATE TABLE `probid_gc_transactions` (
  `trx_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `google_order_number` varchar(255) NOT NULL,
  `gc_custom` varchar(50) NOT NULL,
  `gc_table` varchar(50) NOT NULL,
  `gc_price` double(16,2) NOT NULL,
  `gc_currency` varchar(10) NOT NULL,
  `gc_payment_description` varchar(255) NOT NULL,
  `reg_date` int(11) NOT NULL,
  PRIMARY KEY (`trx_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_gen_setts` */

CREATE TABLE `probid_gen_setts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(255) NOT NULL DEFAULT '',
  `site_path` varchar(255) NOT NULL DEFAULT '',
  `admin_email` varchar(255) NOT NULL DEFAULT '',
  `pg_paypal_email` varchar(255) NOT NULL DEFAULT '',
  `pg_worldpay_id` varchar(50) NOT NULL DEFAULT '',
  `pg_checkout_id` varchar(50) NOT NULL DEFAULT '',
  `pg_ikobo_username` varchar(100) NOT NULL DEFAULT '',
  `pg_ikobo_password` varchar(100) NOT NULL DEFAULT '',
  `pg_protx_username` varchar(100) NOT NULL DEFAULT '',
  `pg_protx_password` varchar(100) NOT NULL DEFAULT '',
  `pg_authnet_username` varchar(100) NOT NULL DEFAULT '',
  `pg_authnet_password` varchar(100) NOT NULL DEFAULT '',
  `language` tinyint(4) NOT NULL DEFAULT '0',
  `currency` varchar(10) NOT NULL DEFAULT '',
  `amount_format` tinyint(4) NOT NULL DEFAULT '0',
  `amount_digits` tinyint(4) NOT NULL DEFAULT '0',
  `currency_position` tinyint(4) NOT NULL DEFAULT '0',
  `max_images` tinyint(4) NOT NULL DEFAULT '0',
  `images_max_size` int(11) NOT NULL DEFAULT '0',
  `enable_hpfeat` tinyint(4) NOT NULL DEFAULT '0',
  `enable_catfeat` tinyint(4) NOT NULL DEFAULT '0',
  `enable_bold` tinyint(4) NOT NULL DEFAULT '0',
  `enable_hl` tinyint(4) NOT NULL DEFAULT '0',
  `enable_swaps` tinyint(4) NOT NULL DEFAULT '0',
  `cron_job_type` tinyint(4) NOT NULL DEFAULT '1',
  `enable_header_counter` tinyint(4) NOT NULL DEFAULT '0',
  `is_ssl` tinyint(4) NOT NULL DEFAULT '0',
  `site_path_ssl` varchar(255) NOT NULL DEFAULT '',
  `account_mode` tinyint(4) NOT NULL DEFAULT '0',
  `max_credit` double(16,2) NOT NULL DEFAULT '0.00',
  `init_credit` double(16,2) NOT NULL DEFAULT '0.00',
  `closed_auction_deletion_days` smallint(6) NOT NULL DEFAULT '0',
  `enable_shipping_costs` tinyint(4) NOT NULL DEFAULT '0',
  `default_theme` varchar(30) NOT NULL DEFAULT '',
  `metatags` text NOT NULL,
  `site_lang` varchar(30) NOT NULL DEFAULT '',
  `admin_lang` varchar(30) NOT NULL DEFAULT '',
  `always_show_buyout` tinyint(4) NOT NULL DEFAULT '0',
  `enable_addl_category` tinyint(4) NOT NULL DEFAULT '0',
  `mailer` varchar(20) NOT NULL DEFAULT '',
  `sendmail_path` varchar(200) NOT NULL DEFAULT '',
  `user_lang` tinyint(4) NOT NULL DEFAULT '0',
  `enable_sniping_feature` tinyint(4) NOT NULL DEFAULT '0',
  `sniping_duration` int(11) NOT NULL DEFAULT '20',
  `enable_private_site` tinyint(4) NOT NULL DEFAULT '0',
  `enable_pref_sellers` tinyint(4) NOT NULL DEFAULT '0',
  `pref_sellers_reduction` double(16,2) NOT NULL DEFAULT '0.00',
  `enable_bcc` tinyint(4) NOT NULL DEFAULT '0',
  `enable_asq` tinyint(4) NOT NULL DEFAULT '0',
  `enable_reg_approval` tinyint(4) NOT NULL DEFAULT '0',
  `enable_wanted_ads` tinyint(4) NOT NULL DEFAULT '0',
  `enable_hpfeat_desc` tinyint(4) NOT NULL DEFAULT '0',
  `auto_vat_exempt` tinyint(4) NOT NULL DEFAULT '0',
  `invoice_header` text,
  `invoice_footer` text,
  `vat_number` varchar(100) NOT NULL DEFAULT '',
  `invoice_comments` mediumtext NOT NULL,
  `enable_bid_retraction` tinyint(4) NOT NULL DEFAULT '0',
  `pg_mb_email` varchar(255) NOT NULL DEFAULT '',
  `min_reg_age` smallint(6) NOT NULL DEFAULT '0',
  `birthdate_type` tinyint(4) NOT NULL DEFAULT '0',
  `nb_other_items_adp` tinyint(4) NOT NULL DEFAULT '0',
  `maintenance_mode` tinyint(4) NOT NULL DEFAULT '0',
  `enable_stores` tinyint(4) NOT NULL DEFAULT '0',
  `account_mode_personal` tinyint(4) NOT NULL DEFAULT '0',
  `enable_bulk_lister` tinyint(4) NOT NULL DEFAULT '1',
  `suspend_over_bal_users` tinyint(4) NOT NULL DEFAULT '1',
  `activation_key` varchar(255) NOT NULL DEFAULT '',
  `min_invoice_value` double(16,2) NOT NULL DEFAULT '0.00',
  `init_acc_type` tinyint(4) NOT NULL DEFAULT '0',
  `enable_tax` tinyint(4) DEFAULT '0',
  `enable_cat_counters` tinyint(4) NOT NULL DEFAULT '0',
  `enable_display_phone` tinyint(4) NOT NULL DEFAULT '0',
  `media_max_size` int(11) NOT NULL DEFAULT '0',
  `enable_auctions_approval` tinyint(4) NOT NULL DEFAULT '0',
  `approval_categories` text NOT NULL,
  `is_mod_rewrite` tinyint(4) NOT NULL DEFAULT '0',
  `buyout_process` tinyint(4) NOT NULL DEFAULT '0',
  `sell_nav_position` tinyint(4) NOT NULL DEFAULT '1',
  `nb_autorelist_max` int(11) NOT NULL DEFAULT '0',
  `site_logo_path` varchar(255) NOT NULL DEFAULT 'images/probidlogo.gif',
  `time_offset` tinyint(4) NOT NULL DEFAULT '0',
  `max_media` tinyint(4) NOT NULL DEFAULT '1',
  `enable_other_items_adp` tinyint(4) NOT NULL DEFAULT '0',
  `debug_load_time` tinyint(4) NOT NULL DEFAULT '1',
  `debug_load_memory` tinyint(4) NOT NULL DEFAULT '0',
  `pg_nochex_email` varchar(255) NOT NULL DEFAULT '',
  `signup_settings` tinyint(4) NOT NULL DEFAULT '0',
  `mcrypt_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `mcrypt_key` varchar(255) NOT NULL DEFAULT '',
  `makeoffer_process` tinyint(4) NOT NULL,
  `enable_duration_change` tinyint(4) NOT NULL,
  `duration_change_days` int(11) NOT NULL,
  `enable_seller_verification` tinyint(4) NOT NULL,
  `makeoffer_private` tinyint(4) NOT NULL,
  `seller_verification_mandatory` tinyint(4) NOT NULL,
  `enable_profile_page` tinyint(4) NOT NULL,
  `enable_store_only_mode` tinyint(4) NOT NULL,
  `enable_enhanced_ssl` tinyint(4) NOT NULL,
  `watermark_text` text NOT NULL,
  `watermark_size` int(11) NOT NULL DEFAULT '500',
  `watermark_pos` tinyint(4) NOT NULL,
  `enable_auto_relist` tinyint(4) NOT NULL DEFAULT '1',
  `pg_paymate_merchant_id` varchar(255) NOT NULL,
  `enable_skin_change` tinyint(4) NOT NULL,
  `preferred_days` int(11) NOT NULL,
  `pg_gc_merchant_id` varchar(255) NOT NULL,
  `pg_gc_merchant_key` varchar(255) NOT NULL,
  `enable_second_chance` tinyint(4) NOT NULL,
  `second_chance_days` int(11) NOT NULL,
  `remove_marked_deleted` tinyint(4) NOT NULL,
  `dd_enabled` tinyint(4) NOT NULL,
  `dd_max_size` int(11) NOT NULL,
  `dd_expiration` int(11) NOT NULL,
  `dd_terms` text NOT NULL,
  `max_dd` int(11) NOT NULL DEFAULT '1',
  `dd_folder` varchar(255) NOT NULL DEFAULT 'dd_folder/',
  `enable_embedded_media` tinyint(4) NOT NULL,
  `enable_addthis` tinyint(4) NOT NULL DEFAULT '1',
  `pg_amazon_access_key` varchar(255) NOT NULL,
  `pg_amazon_secret_key` varchar(255) NOT NULL,
  `enable_private_reputation` tinyint(4) NOT NULL,
  `smtp_host` varchar(255) NOT NULL,
  `smtp_username` varchar(255) NOT NULL,
  `smtp_password` varchar(255) NOT NULL,
  `smtp_port` varchar(20) NOT NULL,
  `enable_force_payment` tinyint(4) NOT NULL,
  `force_payment_time` int(11) NOT NULL,
  `enable_refunds` tinyint(4) NOT NULL,
  `refund_min_days` int(11) NOT NULL,
  `refund_max_days` int(11) NOT NULL,
  `refund_start_date` int(11) NOT NULL,
  `end_auction_early` tinyint(4) NOT NULL,
  `enable_reverse_auctions` tinyint(4) NOT NULL,
  `enable_fb_auctions` tinyint(4) NOT NULL,
  `max_portfolio_files` int(11) NOT NULL DEFAULT '10',
  `max_additional_files` int(11) NOT NULL DEFAULT '5',
  `enable_swdefeat` tinyint(4) NOT NULL,
  `enable_custom_end_time` tinyint(4) NOT NULL DEFAULT '1',
  `ga_code` text NOT NULL,
  `pg_alertpay_id` varchar(255) NOT NULL,
  `pg_alertpay_securitycode` varchar(255) NOT NULL,
  `enable_buyer_create_invoice` tinyint(4) NOT NULL DEFAULT '1',
  `fulltext_search_method` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Table with General Settings';

/*Table structure for table `probid_invoices` */

CREATE TABLE `probid_invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `invoice_date` int(11) NOT NULL DEFAULT '0',
  `current_balance` double(16,2) NOT NULL DEFAULT '0.00',
  `live_fee` tinyint(4) NOT NULL DEFAULT '0',
  `processor` varchar(50) NOT NULL DEFAULT '',
  `can_rollback` tinyint(4) NOT NULL DEFAULT '0',
  `wanted_ad_id` int(11) NOT NULL DEFAULT '0',
  `credit_adjustment` tinyint(4) NOT NULL,
  `refund_request` tinyint(4) NOT NULL,
  `refund_request_date` int(11) NOT NULL,
  `reverse_id` int(11) NOT NULL,
  `tax_amount` double(16,4) NOT NULL,
  `tax_rate` double(16,2) NOT NULL,
  `tax_calculated` tinyint(4) NOT NULL,
  `sellernp_id` int(11) NOT NULL,
  `buyernp_id` int(11) NOT NULL,
  `points` double(16,2) NOT NULL,
  `sellernp_points` double(16,2) NOT NULL,
  `buyernp_points` double(16,2) NOT NULL,
  PRIMARY KEY (`invoice_id`),
  KEY `can_rollback` (`can_rollback`),
  KEY `account_history_item` (`user_id`,`item_id`,`invoice_date`,`live_fee`,`invoice_id`),
  KEY `account_history_wa` (`user_id`,`wanted_ad_id`,`invoice_date`,`live_fee`,`invoice_id`),
  KEY `account_history_live` (`user_id`,`invoice_date`,`live_fee`,`invoice_id`),
  KEY `refund_requests` (`refund_request`,`refund_request_date`),
  KEY `tax_calculated` (`tax_calculated`)
) ENGINE=MyISAM AUTO_INCREMENT=232 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_iphistory` */

CREATE TABLE `probid_iphistory` (
  `memberid` int(11) NOT NULL,
  `time1` int(11) NOT NULL,
  `time2` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  KEY `memberid` (`memberid`),
  KEY `member_order` (`memberid`,`time1`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_keywords_watch` */

CREATE TABLE `probid_keywords_watch` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`keyword_id`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table with keywords for auction watch option';

/*Table structure for table `probid_layout_setts` */

CREATE TABLE `probid_layout_setts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hpfeat_nb` tinyint(4) NOT NULL DEFAULT '0',
  `hpfeat_width` smallint(6) NOT NULL DEFAULT '0',
  `hpfeat_max` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat_nb` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat_width` smallint(6) NOT NULL DEFAULT '0',
  `catfeat_max` tinyint(4) NOT NULL DEFAULT '0',
  `nb_recent_auct` smallint(6) NOT NULL DEFAULT '0',
  `nb_popular_auct` smallint(6) NOT NULL DEFAULT '0',
  `nb_ending_auct` smallint(6) NOT NULL DEFAULT '0',
  `d_login_box` tinyint(4) NOT NULL DEFAULT '0',
  `d_news_box` tinyint(4) NOT NULL DEFAULT '0',
  `d_news_nb` tinyint(4) NOT NULL DEFAULT '0',
  `enable_buyout` tinyint(4) NOT NULL DEFAULT '0',
  `enable_reg_terms` tinyint(4) NOT NULL DEFAULT '0',
  `reg_terms_content` blob NOT NULL,
  `enable_auct_terms` tinyint(4) NOT NULL DEFAULT '0',
  `auct_terms_content` blob NOT NULL,
  `is_about` tinyint(4) NOT NULL DEFAULT '0',
  `is_terms` tinyint(4) NOT NULL DEFAULT '0',
  `is_contact` tinyint(4) NOT NULL DEFAULT '0',
  `is_pp` tinyint(4) NOT NULL DEFAULT '0',
  `nb_want_ads` tinyint(4) NOT NULL DEFAULT '0',
  `r_hpfeat_nb` tinyint(4) NOT NULL,
  `r_hpfeat_max` tinyint(4) NOT NULL,
  `r_catfeat_nb` tinyint(4) NOT NULL,
  `r_catfeat_max` tinyint(4) NOT NULL,
  `r_recent_nb` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Table with the site''s general layout settings';

/*Table structure for table `probid_messaging` */

CREATE TABLE `probid_messaging` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `topic_id` int(11) NOT NULL DEFAULT '0',
  `sender_id` int(11) NOT NULL DEFAULT '0',
  `receiver_id` int(11) NOT NULL DEFAULT '0',
  `is_question` int(11) NOT NULL DEFAULT '0',
  `message_title` varchar(255) NOT NULL DEFAULT '',
  `message_content` text NOT NULL,
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `is_read` tinyint(4) NOT NULL DEFAULT '0',
  `message_handle` tinyint(4) NOT NULL DEFAULT '1',
  `sender_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `receiver_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `winner_id` int(11) NOT NULL DEFAULT '0',
  `wanted_ad_id` int(11) NOT NULL,
  `admin_message` tinyint(4) NOT NULL,
  `reverse_id` int(11) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `sc_id` int(11) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `topic_id` (`topic_id`,`is_question`),
  KEY `public_questions` (`auction_id`,`message_handle`,`is_question`,`reg_date`),
  KEY `sent_messages` (`sender_id`,`reg_date`,`sender_deleted`),
  KEY `received_messages` (`receiver_id`,`receiver_deleted`,`reg_date`),
  KEY `is_read` (`is_read`),
  KEY `auction_read` (`auction_id`,`is_read`),
  KEY `topic_read` (`topic_id`,`is_read`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 PACK_KEYS=0;

/*Table structure for table `probid_newsletter_recipients` */

CREATE TABLE `probid_newsletter_recipients` (
  `recipient_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `newsletter_id` int(11) NOT NULL,
  PRIMARY KEY (`recipient_id`),
  KEY `newsletter_id` (`newsletter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_newsletters` */

CREATE TABLE `probid_newsletters` (
  `newsletter_id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter_subject` varchar(255) NOT NULL,
  `newsletter_content` text NOT NULL,
  PRIMARY KEY (`newsletter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_partners` */

CREATE TABLE `probid_partners` (
  `advert_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `picpath` varchar(255) NOT NULL DEFAULT '',
  `quantity` smallint(6) NOT NULL DEFAULT '0',
  `auction_type` varchar(30) NOT NULL DEFAULT '',
  `start_price` double(16,2) NOT NULL DEFAULT '0.00',
  `reserve_price` double(16,2) NOT NULL DEFAULT '0.00',
  `buyout_price` double(16,2) NOT NULL DEFAULT '0.00',
  `bid_increment_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `duration` smallint(6) NOT NULL DEFAULT '0',
  `country` varchar(100) NOT NULL DEFAULT '',
  `zip_code` varchar(50) NOT NULL DEFAULT '',
  `shipping_method` tinyint(4) NOT NULL DEFAULT '0',
  `shipping_int` tinyint(4) NOT NULL DEFAULT '0',
  `payment_methods` text NOT NULL,
  `category_id` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `start_time_old` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_old` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `nb_bids` int(11) NOT NULL DEFAULT '0',
  `max_bid` double(16,2) NOT NULL DEFAULT '0.00',
  `nb_clicks` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `hpfeat` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat` tinyint(4) NOT NULL DEFAULT '0',
  `bold` tinyint(4) NOT NULL DEFAULT '0',
  `hl` tinyint(4) NOT NULL DEFAULT '0',
  `hidden_bidding` tinyint(4) NOT NULL DEFAULT '0',
  `currency` varchar(100) NOT NULL DEFAULT '',
  `auction_swapped` tinyint(4) NOT NULL DEFAULT '0',
  `postage_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `insurance_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `type_service` varchar(50) NOT NULL DEFAULT '',
  `enable_swap` tinyint(4) NOT NULL DEFAULT '0',
  `direct_payment_paid` tinyint(4) NOT NULL DEFAULT '0',
  `addl_category_id` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `shipping_details` mediumtext NOT NULL,
  `hpfeat_desc` text NOT NULL,
  `reserve_offer` double(16,2) NOT NULL DEFAULT '0.00',
  `reserve_offer_winner_id` int(11) NOT NULL DEFAULT '0',
  `list_in` varchar(50) NOT NULL DEFAULT 'auction',
  `close_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `bid_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `bank_details` text,
  `direct_payment` text,
  `apply_tax` tinyint(4) NOT NULL DEFAULT '0',
  `auto_relist_bids` tinyint(4) NOT NULL DEFAULT '0',
  `end_time_type` enum('duration','custom') NOT NULL DEFAULT 'duration',
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `count_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `listing_type` enum('full','quick','buy_out') NOT NULL DEFAULT 'full',
  `is_offer` tinyint(4) NOT NULL DEFAULT '0',
  `offer_min` double(16,2) NOT NULL DEFAULT '0.00',
  `offer_max` double(16,2) NOT NULL DEFAULT '0.00',
  `auto_relist_nb` tinyint(4) NOT NULL DEFAULT '0',
  `is_relisted_item` tinyint(4) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `creation_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `creation_date` int(11) NOT NULL DEFAULT '0',
  `state` varchar(100) NOT NULL DEFAULT '',
  `start_time_type` enum('now','custom') NOT NULL DEFAULT 'now',
  `retract_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `notif_item_relisted` tinyint(4) NOT NULL,
  `is_draft` tinyint(4) NOT NULL,
  `nb_offers` int(11) NOT NULL,
  `end_time_cron` int(11) NOT NULL,
  `item_weight` int(11) NOT NULL,
  `fb_decrement_amount` double(16,2) NOT NULL,
  `fb_decrement_interval` int(11) NOT NULL,
  `fb_next_decrement` int(11) NOT NULL,
  `fb_current_bid` double(16,2) NOT NULL,
  `npuser_id` int(11) NOT NULL,
  `advert_url` varchar(255) NOT NULL,
  `views` int(11) NOT NULL,
  `clicks` int(11) NOT NULL,
  `advert_img_path` varchar(255) NOT NULL,
  `advert_alt_text` varchar(255) NOT NULL,
  `advert_text_under` varchar(255) NOT NULL,
  `views_purchased` int(11) NOT NULL,
  `clicks_purchased` int(11) NOT NULL,
  `advert_categories` text NOT NULL,
  `advert_keywords` text NOT NULL,
  `advert_code` longtext NOT NULL,
  `advert_type` tinyint(4) NOT NULL,
  `section_id` int(11) NOT NULL,
  `advert_pct` double(4,2) NOT NULL,
  `big_banner_code` longtext NOT NULL COMMENT 'url to 125x125 banner',
  PRIMARY KEY (`advert_id`),
  KEY `stats_drafts` (`is_draft`,`owner_id`),
  KEY `user_auctions` (`owner_id`,`closed`,`deleted`,`creation_in_progress`,`is_draft`),
  KEY `mb_auctions_id` (`deleted`,`owner_id`,`closed`,`creation_in_progress`),
  KEY `user_auctions_end_time` (`closed`,`owner_id`,`end_time`,`deleted`,`creation_in_progress`,`is_draft`),
  KEY `auctions_start_time` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`start_time`),
  KEY `auctions_nb_bids` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`nb_bids`),
  KEY `auctions_max_bid` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`max_bid`),
  KEY `auctions_start_price` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`start_price`),
  KEY `auctions_name` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`name`),
  KEY `auctions_end_time` (`active`,`approved`,`deleted`,`closed`,`creation_in_progress`,`end_time`),
  KEY `hp_featured` (`hpfeat`,`active`,`approved`,`closed`,`creation_in_progress`,`deleted`),
  KEY `cat_featured` (`catfeat`,`active`,`approved`,`closed`,`creation_in_progress`,`deleted`),
  KEY `owner_id` (`owner_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `zip_code` (`zip_code`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Table with auctions details';

/*Table structure for table `probid_payment_gateways` */

CREATE TABLE `probid_payment_gateways` (
  `pg_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `checked` tinyint(4) NOT NULL DEFAULT '0',
  `dp_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `logo_url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pg_id`),
  KEY `checked` (`checked`),
  KEY `dp_enabled` (`dp_enabled`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Table with payment gateways';

/*Table structure for table `probid_payment_options` */

CREATE TABLE `probid_payment_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `logo_url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COMMENT='Table with payment methods';

/*Table structure for table `probid_postage_calc_tiers` */

CREATE TABLE `probid_postage_calc_tiers` (
  `tier_id` int(11) NOT NULL AUTO_INCREMENT,
  `tier_from` double(16,2) NOT NULL,
  `tier_to` double(16,2) NOT NULL,
  `postage_amount` double(16,2) NOT NULL,
  `tier_type` enum('weight','amount') NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`tier_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_proxybid` */

CREATE TABLE `probid_proxybid` (
  `proxy_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `bidder_id` int(11) NOT NULL DEFAULT '0',
  `bid_amount` double(16,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`proxy_id`),
  KEY `auction_id` (`auction_id`),
  KEY `bidder_id` (`bidder_id`),
  KEY `select_bids` (`auction_id`,`bidder_id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=latin1 COMMENT='Table with proxy bids';

/*Table structure for table `probid_referrals` */

CREATE TABLE `probid_referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_reputation` */

CREATE TABLE `probid_reputation` (
  `reputation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `reputation_content` text NOT NULL,
  `reputation_rate` int(11) NOT NULL DEFAULT '0',
  `reg_date` int(11) DEFAULT NULL,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `submitted` tinyint(4) NOT NULL DEFAULT '0',
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `reputation_type` enum('sale','purchase') NOT NULL DEFAULT 'sale',
  `winner_id` int(11) NOT NULL DEFAULT '0',
  `reverse_id` int(11) NOT NULL,
  `reverse_winner_id` int(11) NOT NULL,
  PRIMARY KEY (`reputation_id`),
  KEY `rep_sent` (`from_id`,`submitted`,`reg_date`),
  KEY `rep_received` (`submitted`,`user_id`,`reg_date`),
  KEY `rep_calculation` (`user_id`,`reputation_rate`,`submitted`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=latin1 COMMENT='Table with feedbacks';

/*Table structure for table `probid_reserve_offers` */

CREATE TABLE `probid_reserve_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auctionid` int(11) NOT NULL DEFAULT '0',
  `bidderid` int(11) NOT NULL DEFAULT '0',
  `bidamount` double(16,2) NOT NULL DEFAULT '0.00',
  `accepted` tinyint(4) NOT NULL DEFAULT '0',
  `regdate` int(11) NOT NULL DEFAULT '0',
  `bidid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_reverse_auctions` */

CREATE TABLE `probid_reverse_auctions` (
  `reverse_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `currency` varchar(100) NOT NULL DEFAULT '',
  `budget_id` double(16,2) NOT NULL DEFAULT '0.00',
  `duration` smallint(6) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `addl_category_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `nb_bids` int(11) NOT NULL DEFAULT '0',
  `nb_clicks` int(11) NOT NULL DEFAULT '0',
  `hpfeat` tinyint(4) NOT NULL DEFAULT '0',
  `catfeat` tinyint(4) NOT NULL DEFAULT '0',
  `bold` tinyint(4) NOT NULL DEFAULT '0',
  `hl` tinyint(4) NOT NULL DEFAULT '0',
  `hidden_bidding` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `bid_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `close_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `count_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `start_time_type` enum('now','custom') NOT NULL DEFAULT 'now',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `end_time_type` enum('duration','custom') NOT NULL DEFAULT 'duration',
  `creation_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `creation_date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reverse_id`),
  KEY `user_auctions` (`owner_id`,`closed`,`deleted`,`creation_in_progress`),
  KEY `mb_auctions_id` (`deleted`,`owner_id`,`closed`,`creation_in_progress`,`reverse_id`),
  KEY `user_auctions_end_time` (`closed`,`owner_id`,`end_time`,`deleted`,`creation_in_progress`),
  KEY `auctions_start_time` (`active`,`deleted`,`closed`,`creation_in_progress`,`start_time`),
  KEY `auctions_nb_bids` (`active`,`deleted`,`closed`,`creation_in_progress`,`nb_bids`),
  KEY `auctions_name` (`active`,`deleted`,`closed`,`creation_in_progress`,`name`),
  KEY `auctions_end_time` (`active`,`deleted`,`closed`,`creation_in_progress`,`end_time`),
  KEY `hp_featured` (`hpfeat`,`active`,`closed`,`creation_in_progress`,`deleted`),
  KEY `cat_featured` (`catfeat`,`active`,`closed`,`creation_in_progress`,`deleted`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_reverse_bids` */

CREATE TABLE `probid_reverse_bids` (
  `bid_id` int(11) NOT NULL AUTO_INCREMENT,
  `reverse_id` int(11) NOT NULL DEFAULT '0',
  `bidder_id` int(11) NOT NULL DEFAULT '0',
  `bid_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `bid_date` int(11) NOT NULL DEFAULT '0',
  `bid_description` text NOT NULL,
  `delivery_days` int(11) NOT NULL DEFAULT '0',
  `winner_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `email_sent` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `messaging_topic_id` int(11) NOT NULL DEFAULT '0',
  `bid_status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
  `apply_tax` tinyint(4) NOT NULL,
  PRIMARY KEY (`bid_id`),
  KEY `reverse_id` (`reverse_id`),
  KEY `auction_bids` (`reverse_id`,`bidder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_reverse_budgets` */

CREATE TABLE `probid_reverse_budgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value_from` double(16,2) NOT NULL,
  `value_to` double(16,2) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_reverse_categories` */

CREATE TABLE `probid_reverse_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `items_counter` int(11) NOT NULL DEFAULT '0',
  `hover_title` varchar(255) NOT NULL DEFAULT '',
  `meta_description` mediumtext NOT NULL,
  `meta_keywords` mediumtext NOT NULL,
  `image_path` varchar(255) NOT NULL DEFAULT '',
  `is_subcat` varchar(5) NOT NULL DEFAULT '',
  `custom_fees` tinyint(4) NOT NULL DEFAULT '0',
  `custom_skin` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `parent_id` (`parent_id`),
  KEY `parent_id_2` (`parent_id`,`order_id`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_reverse_winners` */

CREATE TABLE `probid_reverse_winners` (
  `winner_id` int(11) NOT NULL AUTO_INCREMENT,
  `poster_id` int(11) NOT NULL DEFAULT '0',
  `provider_id` int(11) NOT NULL DEFAULT '0',
  `bid_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `reverse_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `email_sent` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `s_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `b_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `flag_paid` tinyint(4) NOT NULL DEFAULT '0',
  `flag_status` tinyint(4) NOT NULL DEFAULT '0',
  `direct_payment_paid` tinyint(4) NOT NULL DEFAULT '0',
  `purchase_date` int(11) NOT NULL DEFAULT '0',
  `invoice_sent` tinyint(4) NOT NULL DEFAULT '0',
  `invoice_id` int(11) NOT NULL,
  `tax_amount` double(16,4) NOT NULL,
  `tax_rate` double(16,2) NOT NULL,
  `tax_calculated` tinyint(4) NOT NULL,
  PRIMARY KEY (`winner_id`),
  KEY `reverse_id` (`reverse_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `won_items_auction` (`provider_id`,`b_deleted`,`invoice_id`,`reverse_id`),
  KEY `won_items_bid` (`provider_id`,`b_deleted`,`invoice_id`,`bid_amount`),
  KEY `won_items_purchase_date` (`provider_id`,`b_deleted`,`invoice_id`,`purchase_date`),
  KEY `sold_items_auction` (`poster_id`,`s_deleted`,`invoice_id`,`reverse_id`),
  KEY `sold_items_bid` (`poster_id`,`s_deleted`,`invoice_id`,`bid_amount`),
  KEY `sold_items_purchase_date` (`poster_id`,`s_deleted`,`invoice_id`,`purchase_date`),
  KEY `calculate_tax` (`invoice_sent`,`tax_calculated`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_shipping_locations` */

CREATE TABLE `probid_shipping_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locations_id` text NOT NULL,
  `amount` double(16,2) NOT NULL,
  `amount_type` enum('flat','percent') NOT NULL DEFAULT 'flat',
  `pc_default` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_shipping_options` */

CREATE TABLE `probid_shipping_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_shopping_carts` */

CREATE TABLE `probid_shopping_carts` (
  `sc_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `buyer_session_id` varchar(255) NOT NULL,
  `sc_date` int(11) NOT NULL,
  `sc_postage` double(16,2) NOT NULL,
  `sc_insurance` double(16,2) NOT NULL,
  `sc_purchased` tinyint(4) NOT NULL,
  `shipping_calc_auto` tinyint(4) NOT NULL,
  `s_deleted` tinyint(4) NOT NULL,
  `b_deleted` tinyint(4) NOT NULL,
  `flag_paid` tinyint(4) NOT NULL,
  `flag_status` tinyint(4) NOT NULL,
  `direct_payment_paid` tinyint(4) NOT NULL,
  `messaging_topic_id` int(11) NOT NULL,
  `carrier_id` int(11) NOT NULL,
  `shipping_method` varchar(255) NOT NULL,
  `invoice_final` tinyint(4) NOT NULL,
  `tax_amount` double(16,4) NOT NULL,
  `tax_rate` double(16,2) NOT NULL,
  `tax_calculated` tinyint(4) NOT NULL,
  `invoice_comments` text NOT NULL,
  PRIMARY KEY (`sc_id`),
  KEY `seller_id` (`seller_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `seller_date` (`seller_id`,`sc_date`),
  KEY `buyer_date` (`buyer_id`,`sc_date`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_shopping_carts_items` */

CREATE TABLE `probid_shopping_carts_items` (
  `sc_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `sc_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`sc_item_id`),
  KEY `sc_id` (`sc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_stores_accounting` */

CREATE TABLE `probid_stores_accounting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `amountpaid` double(16,2) NOT NULL DEFAULT '0.00',
  `paymentdate` int(11) NOT NULL DEFAULT '0',
  `processor` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_suggested_categories` */

CREATE TABLE `probid_suggested_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `regdate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_swaps` */

CREATE TABLE `probid_swaps` (
  `swap_id` int(11) NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `seller_id` int(11) NOT NULL DEFAULT '0',
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `accepted` tinyint(4) NOT NULL DEFAULT '0',
  `winner_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`swap_id`),
  KEY `auction_id` (`auction_id`,`seller_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_tax_settings` */

CREATE TABLE `probid_tax_settings` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(100) NOT NULL DEFAULT '',
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `countries_id` text NOT NULL,
  `tax_user_types` varchar(20) NOT NULL DEFAULT 'a',
  `site_tax` tinyint(4) NOT NULL DEFAULT '0',
  `seller_countries_id` text NOT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_timesettings` */

CREATE TABLE `probid_timesettings` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `value` tinyint(4) NOT NULL DEFAULT '0',
  `caption` varchar(20) NOT NULL DEFAULT '',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1 COMMENT='Table with time settings';

/*Table structure for table `probid_user_activities` */

CREATE TABLE `probid_user_activities` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_name` varchar(255) NOT NULL,
  `points_awarded` int(11) NOT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_user_points` */

CREATE TABLE `probid_user_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `points_awarded` int(11) NOT NULL,
  `awarded_date` date NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1124 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_users` */

CREATE TABLE `probid_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `birthdate` date NOT NULL DEFAULT '0000-00-00',
  `address` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `zip_code` varchar(30) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `items_sold` int(11) NOT NULL DEFAULT '0',
  `items_bought` int(11) NOT NULL DEFAULT '0',
  `enable_aboutme_page` tinyint(4) NOT NULL DEFAULT '0',
  `aboutme_page_content` text NOT NULL,
  `shop_mainpage` mediumblob NOT NULL,
  `shop_mainpage_preview` mediumblob NOT NULL,
  `shop_logo_path` varchar(255) NOT NULL DEFAULT '',
  `aboutme_page_type` tinyint(4) DEFAULT NULL,
  `newsletter` tinyint(4) NOT NULL DEFAULT '0',
  `balance` double(16,2) NOT NULL DEFAULT '0.00',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `mail_activated` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `lang` varchar(255) NOT NULL DEFAULT 'english',
  `is_seller` tinyint(4) NOT NULL DEFAULT '0',
  `preferred_seller` tinyint(4) NOT NULL DEFAULT '0',
  `tax_apply_exempt` tinyint(4) NOT NULL DEFAULT '0',
  `tax_reg_number` varchar(100) NOT NULL DEFAULT '',
  `tax_exempted` tinyint(4) NOT NULL DEFAULT '0',
  `shop_active` tinyint(4) NOT NULL DEFAULT '0',
  `shop_last_payment` int(11) NOT NULL DEFAULT '0',
  `birthdate_year` int(11) NOT NULL DEFAULT '0',
  `default_duration` smallint(6) NOT NULL DEFAULT '0',
  `default_hidden_bidding` tinyint(4) NOT NULL DEFAULT '0',
  `default_enable_swap` tinyint(4) NOT NULL DEFAULT '0',
  `default_shipping_method` tinyint(4) NOT NULL DEFAULT '0',
  `default_shipping_int` tinyint(4) NOT NULL DEFAULT '0',
  `default_payment_methods` text NOT NULL,
  `default_postage_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `default_insurance_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `default_type_service` varchar(50) NOT NULL DEFAULT '',
  `default_shipping_details` mediumtext NOT NULL,
  `referred_by` varchar(200) NOT NULL DEFAULT '',
  `shop_account_id` int(11) NOT NULL DEFAULT '0',
  `shop_categories` text NOT NULL,
  `shop_next_payment` int(11) NOT NULL DEFAULT '0',
  `shop_name` varchar(255) NOT NULL DEFAULT '',
  `payment_mode` tinyint(1) DEFAULT '0',
  `max_credit` double(16,2) DEFAULT '0.00',
  `default_public_questions` tinyint(4) NOT NULL DEFAULT '0',
  `default_bid_placed_email` tinyint(4) NOT NULL DEFAULT '0',
  `mail_account_suspended` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_sold` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_won` tinyint(4) NOT NULL DEFAULT '1',
  `mail_buyer_details` tinyint(4) NOT NULL DEFAULT '1',
  `mail_seller_details` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_watch` tinyint(4) NOT NULL DEFAULT '1',
  `mail_item_closed` tinyint(4) NOT NULL DEFAULT '1',
  `mail_wanted_offer` tinyint(4) NOT NULL DEFAULT '1',
  `mail_outbid` tinyint(4) NOT NULL DEFAULT '1',
  `mail_keyword_match` tinyint(4) NOT NULL DEFAULT '1',
  `mail_confirm_to_seller` tinyint(4) NOT NULL DEFAULT '1',
  `shop_nb_items` int(11) NOT NULL DEFAULT '0',
  `shop_template_id` smallint(6) NOT NULL DEFAULT '0',
  `tax_company_name` varchar(100) DEFAULT NULL,
  `pg_paypal_email` varchar(255) DEFAULT NULL,
  `pg_worldpay_id` varchar(100) DEFAULT NULL,
  `pg_ikobo_username` varchar(100) DEFAULT NULL,
  `pg_ikobo_password` varchar(100) DEFAULT NULL,
  `pg_checkout_id` varchar(100) DEFAULT NULL,
  `pg_protx_username` varchar(100) DEFAULT NULL,
  `pg_protx_password` varchar(100) DEFAULT NULL,
  `pg_authnet_username` varchar(100) DEFAULT NULL,
  `pg_authnet_password` varchar(100) DEFAULT NULL,
  `pg_nochex_email` varchar(100) DEFAULT NULL,
  `shop_about` text NOT NULL,
  `shop_specials` text NOT NULL,
  `shop_shipping_info` text NOT NULL,
  `shop_company_policies` text NOT NULL,
  `shop_nb_feat_items` int(11) NOT NULL DEFAULT '0',
  `shop_nb_ending_items` int(11) NOT NULL DEFAULT '0',
  `shop_nb_recent_items` int(11) NOT NULL DEFAULT '0',
  `shop_metatags` text NOT NULL,
  `default_name` varchar(255) NOT NULL DEFAULT '',
  `default_description` text NOT NULL,
  `user_admin_notes` text NOT NULL,
  `auction_approval` tinyint(4) NOT NULL DEFAULT '0',
  `tax_account_type` tinyint(4) NOT NULL DEFAULT '0',
  `salt` char(3) NOT NULL DEFAULT '',
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `mail_messaging_received` tinyint(4) NOT NULL DEFAULT '1',
  `mail_messaging_sent` tinyint(4) NOT NULL DEFAULT '0',
  `pg_mb_email` varchar(255) NOT NULL,
  `seller_verified` tinyint(4) NOT NULL,
  `seller_verif_last_payment` int(11) NOT NULL,
  `seller_verif_next_payment` int(11) NOT NULL,
  `enable_profile_page` tinyint(4) NOT NULL,
  `profile_www` varchar(255) NOT NULL,
  `profile_msn` varchar(255) NOT NULL,
  `profile_icq` varchar(255) NOT NULL,
  `profile_aim` varchar(255) NOT NULL,
  `profile_yim` varchar(255) NOT NULL,
  `profile_skype` varchar(255) NOT NULL,
  `profile_show_birthdate` tinyint(4) NOT NULL,
  `paypal_address_override` tinyint(4) NOT NULL,
  `paypal_first_name` varchar(32) NOT NULL,
  `paypal_last_name` varchar(64) NOT NULL,
  `paypal_address1` varchar(100) NOT NULL,
  `paypal_address2` varchar(100) NOT NULL,
  `paypal_city` varchar(100) NOT NULL,
  `paypal_state` varchar(100) NOT NULL,
  `paypal_zip` varchar(32) NOT NULL,
  `paypal_country` varchar(100) NOT NULL,
  `paypal_email` varchar(255) NOT NULL,
  `paypal_night_phone_a` varchar(3) NOT NULL,
  `paypal_night_phone_b` varchar(16) NOT NULL,
  `paypal_night_phone_c` varchar(4) NOT NULL,
  `default_currency` varchar(100) NOT NULL,
  `default_direct_payment` text NOT NULL,
  `pg_paymate_merchant_id` varchar(255) NOT NULL,
  `preferred_seller_exp_date` int(11) NOT NULL,
  `pg_gc_merchant_id` varchar(255) NOT NULL,
  `pg_gc_merchant_key` varchar(255) NOT NULL,
  `pg_amazon_access_key` varchar(255) NOT NULL,
  `pg_amazon_secret_key` varchar(255) NOT NULL,
  `enable_private_reputation` tinyint(4) NOT NULL,
  `enable_force_payment` tinyint(4) NOT NULL,
  `pc_free_postage` tinyint(4) NOT NULL,
  `pc_free_postage_amount` double(16,2) NOT NULL,
  `pc_postage_type` enum('item','weight','amount','flat') NOT NULL,
  `pc_weight_unit` varchar(50) NOT NULL,
  `pc_postage_calc_type` enum('default','custom') NOT NULL,
  `pc_shipping_locations` enum('global','local') NOT NULL DEFAULT 'global',
  `pc_flat_first` double(16,2) NOT NULL,
  `pc_flat_additional` double(16,2) NOT NULL,
  `shop_nb_feat_items_row` int(11) NOT NULL,
  `provider_profile` text NOT NULL,
  `reverse_earnings` double(16,2) NOT NULL,
  `notif_a` tinyint(4) NOT NULL,
  `pg_alertpay_id` varchar(255) NOT NULL,
  `pg_alertpay_securitycode` varchar(255) NOT NULL,
  `exceeded_balance_email` tinyint(4) NOT NULL,
  `default_bank_details` text NOT NULL,
  `default_auto_relist` tinyint(4) NOT NULL,
  `default_auto_relist_bids` tinyint(4) NOT NULL,
  `default_auto_relist_nb` tinyint(4) NOT NULL,
  `npuser_id` int(11) NOT NULL,
  `npname` varchar(255) NOT NULL,
  `cart_enable` tinyint(4) NOT NULL,
  `shop_add_tax` tinyint(4) NOT NULL,
  `shop_direct_payment` varchar(255) NOT NULL,
  `seller_verified_pending` tinyint(4) DEFAULT NULL,
  `globalpartner_email` tinyint(4) DEFAULT NULL,
  `affiliate` varchar(255) NOT NULL,
  `clickreport` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `shop_active` (`active`,`shop_active`,`user_id`),
  KEY `stores_list` (`active`,`shop_active`,`shop_nb_items`),
  KEY `acc_overdue_users` (`payment_mode`,`reg_date`),
  KEY `active_users` (`active`,`reg_date`,`approved`),
  KEY `users_tax_acc_type` (`tax_account_type`,`reg_date`),
  KEY `users_tax_exempt` (`tax_apply_exempt`,`tax_exempted`,`reg_date`),
  KEY `active` (`active`),
  FULLTEXT KEY `shop_name` (`shop_name`)
) ENGINE=MyISAM AUTO_INCREMENT=100446 DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Table with probid users';

/*Table structure for table `probid_vat_rates` */

CREATE TABLE `probid_vat_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vat_id` int(11) NOT NULL DEFAULT '0',
  `country` int(11) NOT NULL DEFAULT '0',
  `rate` double(9,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_vat_setts` */

CREATE TABLE `probid_vat_setts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(10) NOT NULL DEFAULT '',
  `amount` float(9,2) DEFAULT '0.00',
  `countries` text,
  `users_sale_vat` set('a','b','c','d') DEFAULT NULL,
  `users_no_vat` set('a','b','c','d') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `probid_vouchers` */

CREATE TABLE `probid_vouchers` (
  `voucher_id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_code` varchar(255) NOT NULL DEFAULT '',
  `voucher_type` varchar(20) NOT NULL DEFAULT '',
  `reg_date` int(11) NOT NULL DEFAULT '0',
  `exp_date` int(11) NOT NULL DEFAULT '0',
  `nb_uses` int(11) NOT NULL DEFAULT '0',
  `uses_left` int(11) NOT NULL DEFAULT '0',
  `voucher_reduction` double(16,2) NOT NULL DEFAULT '0.00',
  `assigned_users` text NOT NULL,
  `assigned_fees` varchar(255) NOT NULL DEFAULT '',
  `voucher_name` varchar(100) NOT NULL DEFAULT '',
  `voucher_duration` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`voucher_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_wanted_ads` */

CREATE TABLE `probid_wanted_ads` (
  `wanted_ad_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `duration` smallint(6) NOT NULL DEFAULT '0',
  `country` varchar(100) NOT NULL DEFAULT '',
  `zip_code` varchar(50) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `start_time_old` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_old` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `keywords` text NOT NULL,
  `nb_bids` int(11) NOT NULL DEFAULT '0',
  `nb_clicks` int(11) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `addl_category_id` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `picpath` varchar(255) NOT NULL DEFAULT '',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `creation_in_progress` tinyint(4) NOT NULL DEFAULT '0',
  `creation_date` int(11) NOT NULL DEFAULT '0',
  `state` varchar(100) NOT NULL DEFAULT '',
  `npuser_id` int(11) NOT NULL,
  PRIMARY KEY (`wanted_ad_id`),
  KEY `wa_start_time` (`owner_id`,`closed`,`deleted`,`creation_in_progress`,`start_time`),
  KEY `wa_end_time` (`owner_id`,`closed`,`deleted`,`creation_in_progress`,`end_time`),
  KEY `wa_nb_bids` (`owner_id`,`closed`,`deleted`,`creation_in_progress`,`nb_bids`),
  KEY `wa_id` (`owner_id`,`closed`,`deleted`,`creation_in_progress`,`wanted_ad_id`),
  KEY `wa_admin_id` (`creation_in_progress`,`active`,`closed`,`wanted_ad_id`),
  KEY `wa_browse_end_time` (`active`,`closed`,`deleted`,`wanted_ad_id`,`end_time`),
  KEY `wa_browse_nb_bids` (`active`,`closed`,`deleted`,`wanted_ad_id`,`nb_bids`),
  KEY `wa_mainpage` (`closed`,`active`,`deleted`,`creation_in_progress`,`start_time`),
  KEY `owner_id` (`owner_id`),
  FULLTEXT KEY `wa_keywords` (`name`,`description`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Table with wanted ads details';

/*Table structure for table `probid_wanted_offers` */

CREATE TABLE `probid_wanted_offers` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `wanted_ad_id` int(11) NOT NULL DEFAULT '0',
  `auction_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`offer_id`),
  KEY `wanted_ad_id` (`wanted_ad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `probid_winners` */

CREATE TABLE `probid_winners` (
  `winner_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL DEFAULT '0',
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `bid_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `quantity_requested` int(11) NOT NULL DEFAULT '0',
  `quantity_offered` int(11) NOT NULL DEFAULT '0',
  `auction_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `payment_status` varchar(20) NOT NULL DEFAULT '',
  `live_pm_amount` double(16,2) NOT NULL DEFAULT '0.00',
  `email_sent` tinyint(4) NOT NULL DEFAULT '0',
  `live_pm_date` int(11) NOT NULL DEFAULT '0',
  `live_pm_processor` varchar(50) NOT NULL DEFAULT '',
  `s_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `b_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `flag_paid` tinyint(4) NOT NULL DEFAULT '0',
  `flag_status` tinyint(4) NOT NULL DEFAULT '0',
  `direct_payment_paid` tinyint(4) NOT NULL DEFAULT '0',
  `buyout_purchase` tinyint(4) NOT NULL DEFAULT '0',
  `invoice_sent` tinyint(4) NOT NULL DEFAULT '0',
  `vat_included` tinyint(4) NOT NULL DEFAULT '0',
  `postage_included` tinyint(4) NOT NULL DEFAULT '0',
  `insurance_included` tinyint(4) NOT NULL DEFAULT '0',
  `insurance_amount` double(10,2) DEFAULT '0.00',
  `purchase_date` int(11) NOT NULL DEFAULT '0',
  `messaging_topic_id` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) NOT NULL,
  `combined_postage` double(16,2) NOT NULL,
  `combined_insurance` double(16,2) NOT NULL,
  `postage_amount` double(16,2) NOT NULL,
  `is_dd` tinyint(4) NOT NULL,
  `dd_active` tinyint(4) NOT NULL,
  `dd_active_date` int(11) NOT NULL,
  `dd_nb_downloads` int(11) NOT NULL,
  `temp_purchase` tinyint(4) NOT NULL,
  `sale_fee_amount` double(16,2) NOT NULL,
  `sale_fee_invoice_id` int(11) NOT NULL,
  `sale_fee_payer_id` int(11) NOT NULL,
  `refund_invoice_id` int(11) NOT NULL,
  `pc_postage_type` enum('item','weight','amount','flat') NOT NULL,
  `invoice_comments` text NOT NULL,
  `tax_amount` double(16,4) NOT NULL,
  `tax_rate` double(16,2) NOT NULL,
  `tax_calculated` tinyint(4) NOT NULL,
  `sc_id` int(11) NOT NULL,
  PRIMARY KEY (`winner_id`),
  KEY `auction_id` (`auction_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `won_items_auction` (`buyer_id`,`b_deleted`,`invoice_id`,`auction_id`),
  KEY `won_items_bid` (`buyer_id`,`b_deleted`,`invoice_id`,`bid_amount`),
  KEY `won_items_quantity` (`buyer_id`,`b_deleted`,`invoice_id`,`quantity_offered`),
  KEY `won_items_purchase_date` (`buyer_id`,`b_deleted`,`invoice_id`,`purchase_date`),
  KEY `sold_items_auction` (`seller_id`,`s_deleted`,`invoice_id`,`auction_id`),
  KEY `sold_items_bid` (`seller_id`,`s_deleted`,`invoice_id`,`bid_amount`),
  KEY `sold_items_quantity` (`seller_id`,`s_deleted`,`invoice_id`,`quantity_offered`),
  KEY `sold_items_purchase_date` (`seller_id`,`s_deleted`,`invoice_id`,`purchase_date`),
  KEY `temp_purchase` (`temp_purchase`),
  KEY `calculate_tax` (`invoice_sent`,`tax_calculated`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=latin1 COMMENT='Table with the auction''s declared winners';

/*Table structure for table `probid_wordfilter` */

CREATE TABLE `probid_wordfilter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `word` (`word`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Table with words that are filtered';

/*Table structure for table `project_comment` */

CREATE TABLE `project_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `project_id` int(11) NOT NULL,
  `parrent_id` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`project_id`,`parrent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

/*Table structure for table `project_pitch` */

CREATE TABLE `project_pitch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `amoun` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

/*Table structure for table `project_rewards` */

CREATE TABLE `project_rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `parrent_id` int(11) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `estimated_delivery_date` date DEFAULT NULL,
  `shipping_address_required` tinyint(1) NOT NULL DEFAULT '0',
  `available_number` int(11) DEFAULT NULL,
  `given_number` int(11) NOT NULL DEFAULT '0',
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

/*Table structure for table `project_updates` */

CREATE TABLE `project_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `project_id` int(11) NOT NULL,
  `parrent_id` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`project_id`,`parrent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;

/*Table structure for table `shop_tracking_links` */

CREATE TABLE `shop_tracking_links` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tracking_id` mediumtext NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `shop_url_id` int(4) DEFAULT NULL,
  `click_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `target_url` mediumtext,
  `np_userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2117 DEFAULT CHARSET=utf8;

/*Table structure for table `test_click_reports` */

CREATE TABLE `test_click_reports` (
  `unique id` varchar(4) DEFAULT NULL,
  `tracking link` varchar(16) DEFAULT NULL,
  `site user` varchar(32) DEFAULT NULL,
  `user name` varchar(10) DEFAULT NULL,
  `click date` date DEFAULT NULL,
  `vendor` varchar(117) DEFAULT NULL,
  `np-id` varchar(5) DEFAULT NULL,
  `np-name` varchar(40) DEFAULT NULL,
  `Sales` double(16,2) DEFAULT NULL,
  `Commission` double(16,2) DEFAULT NULL,
  `pct` varchar(7) DEFAULT NULL,
  `np-share` double(16,2) DEFAULT NULL,
  `bil-share` double(16,2) DEFAULT NULL,
  `pct_giveback` varchar(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `user_submit` */

CREATE TABLE `user_submit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(50) NOT NULL,
  `captcha` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `vendor_click_reports` */

CREATE TABLE `vendor_click_reports` (
  `unique id` varchar(4) DEFAULT NULL,
  `tracking link` varchar(16) DEFAULT NULL,
  `site user` varchar(32) DEFAULT NULL,
  `user name` varchar(25) DEFAULT NULL,
  `click date` date DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `vendor` varchar(117) DEFAULT NULL,
  `np-id` varchar(5) DEFAULT NULL,
  `np-name` varchar(40) DEFAULT NULL,
  `Sales` double(16,2) DEFAULT NULL,
  `Commission` double(16,2) DEFAULT NULL,
  `pct` varchar(7) DEFAULT NULL,
  `pct_giveback` varchar(7) DEFAULT NULL,
  `np-share` double(16,2) DEFAULT NULL,
  `bil-share` double(16,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*task In user 'signup' make last name not required*/
ALTER TABLE `bl2_users` ADD COLUMN `organization` VARCHAR(128) NULL DEFAULT NULL  AFTER `google_link` ;

/*task Renew campaigns: add setting in member pages>campaign edit>status0%*/
ALTER TABLE `np_users` ADD COLUMN `keep_alive` INT NULL DEFAULT 0  AFTER `payment` ;
ALTER TABLE `np_users` ADD COLUMN `keep_alive_days` INT NULL DEFAULT 0  AFTER `keep_alive` ;

--
-- np_iphistory
-- 

ALTER TABLE np_iphistory
ADD id INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL;

--
-- probid_iphistory
-- 

ALTER TABLE probid_iphistory
ADD id INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL;


/*task create admin script for deleting campaign*/
ALTER TABLE `np_users` ADD COLUMN `disabled` INT NULL DEFAULT 0  AFTER `keep_alive_days` ;

ALTER TABLE `project_rewards`  CHANGE `amount` `amount` DECIMAL(11,2) NOT NULL;

ALTER TABLE `devbr0_auction`.`np_users` ADD COLUMN `homepage_featured` INT NULL  AFTER `disabled` ;

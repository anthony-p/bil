<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<?=(isset($sell_item_header))?$sell_item_header:'';?>
<?=(isset($sell_item_header_menu))?$sell_item_header_menu:'';?>
<br>
<?=(isset($check_voucher_message))?$check_voucher_message:'';?>
<?=(isset($display_formcheck_errors))?$display_formcheck_errors:'';?>

<? if ($current_step!='finish') { ?>
<form action="sell_item.php" method="post" enctype="multipart/form-data" name="ad_create_form">
   <input type="hidden" name="current_step" value="<?=$current_step;?>" >
   <input type="hidden" name="item_id" value="<?=$item_details['item_id'];?>" >
   <input type="hidden" name="box_submit" value="0" >
   <input type="hidden" name="file_upload_type" value="" >
   <input type="hidden" name="file_upload_id" value="" >
   <input type="hidden" name="ad_type" value="<?=(isset($item_details['ad_type']))?$item_details['ad_type']:'';?>" >
   <input type="hidden" name="list_in" value="<?=isset($item_details['list_in'])?$item_details['list_in']:'';?>" >
   <input type="hidden" name="category_id" value="<?=isset($item_details['category_id'])?$item_details['category_id']:'';?>" >
   <input type="hidden" name="addl_category_id" value="<?=isset($item_details['addl_category_id'])?$item_details['addl_category_id']:'';?>" >
   <input type="hidden" name="listing_type" value="<?=isset($item_details['listing_type'])?$item_details['listing_type']:'';?>" >
   <input type="hidden" name="auction_type" value="<?=isset($item_details['auction_type'])?$item_details['auction_type']:'';?>" >
   <input type="hidden" name="voucher_value" value="<?=isset($item_details['voucher_value'])?$item_details['voucher_value']:'';?>" >
   <input type="hidden" name="quantity" value="<?=isset($item_details['quantity'])?$item_details['quantity']:'';?>" >
   <input type="hidden" name="name" value="<?=isset($item_details['name'])?$item_details['name']:'';?>" >
   <input type="hidden" name="description" value="<?=isset($item_details['description'])?$item_details['description']:'';?>" >
   <input type="hidden" name="start_time" value="<?=isset($item_details['start_time'])?$item_details['start_time']:'';?>" >
   <input type="hidden" name="end_time" value="<?=isset($item_details['end_time'])?$item_details['end_time']:'';?>" >
   <input type="hidden" name="currency" value="<?=isset($item_details['currency'])?$item_details['currency']:'';?>" >
   <?php $startPrice = (isset($item_details['start_price']))?$item_details['start_price']:'' ?>
   <input type="hidden" name="start_price" value="<? echo (isset($item_details['listing_type']) && $item_details['listing_type'] == 'buy_out') ? $item_details['buyout_price'] : $startPrice;?>" >
   <input type="hidden" name="buyout_price" value="<?=(isset($item_details['buyout_price']))?$item_details['buyout_price']:'';?>" >
   <input type="hidden" name="reserve_price" value="<?=isset($item_details['reserve_price'])?$item_details['reserve_price']:'';?>" >
   <input type="hidden" name="bid_increment_amount" value="<?=isset($item_details['bid_increment_amount'])?$item_details['bid_increment_amount']:'';?>" >
   <input type="hidden" name="offer_min" value="<?=isset($item_details['offer_min'])?$item_details['offer_min']:'';?>" >
   <input type="hidden" name="offer_max" value="<?=isset($item_details['offer_max'])?$item_details['offer_max']:'';?>" >
   
   <input type="hidden" name="fb_decrement_amount" value="<?=isset($item_details['fb_decrement_amount'])?$item_details['fb_decrement_amount']:'';?>" >
   <input type="hidden" name="fb_decrement_interval" value="<?=isset($item_details['fb_decrement_interval'])?$item_details['fb_decrement_interval']:'';?>" >
   
	<? if ($current_step != 'settings') { ?>
   <input type="hidden" name="apply_tax" value="<?=isset($item_details['apply_tax'])?$item_details['apply_tax']:'';?>" >
   <input type="hidden" name="is_bid_increment" value="<?=isset($item_details['is_bid_increment'])?$item_details['is_bid_increment']:'';?>" >
   <input type="hidden" name="is_reserve" value="<?=isset($item_details['is_reserve'])?$item_details['is_reserve']:'';?>" >
   <?php
        $is_buy_out = (isset($item_details['is_buy_out']))?$item_details['is_buy_out']:'';
   ?>
   <input type="hidden" name="is_buy_out" value="<? echo (isset($item_details['listing_type']) && $item_details['listing_type'] == 'buy_out') ? 1 : $is_buy_out;?>" >
   <input type="hidden" name="is_offer" value="<?=isset($item_details['is_offer'])?$item_details['is_offer']:'';?>" >
   <input type="hidden" name="hpfeat" value="<?=isset($item_details['hpfeat'])?$item_details['hpfeat']:'';?>" >
   <input type="hidden" name="catfeat" value="<?=isset($item_details['catfeat'])?$item_details['catfeat']:'';?>" >
   <input type="hidden" name="bold" value="<?=isset($item_details['bold'])?$item_details['bold']:'';?>" >
   <input type="hidden" name="hl" value="<?=isset($item_details['hl'])?$item_details['hl']:'';?>" >
   <input type="hidden" name="hidden_bidding" value="<?=isset($item_details['hidden_bidding'])?$item_details['hidden_bidding']:'';?>" >
   <input type="hidden" name="enable_swap" value="<?=isset($item_details['enable_swap'])?$item_details['enable_swap']:'';?>" >
   <input type="hidden" name="is_auto_relist" value="<?=isset($item_details['is_auto_relist'])?$item_details['is_auto_relist']:'';?>" >
   <input type="hidden" name="auto_relist_bids" value="<?=isset($item_details['auto_relist_bids'])?$item_details['auto_relist_bids']:'';?>" >
   <?=isset($hidden_custom_fields)?$hidden_custom_fields:'';?>
   <? } ?>
   <input type="hidden" name="country" value="<?=isset($item_details['country'])?$item_details['country']:'';?>" >
   <input type="hidden" name="state" value="<?=isset($item_details['state'])?$item_details['state']:'';?>" >
   <input type="hidden" name="zip_code" value="<?=isset($item_details['zip_code'])?$item_details['zip_code']:'';?>" >

   <?=$media_upload_fields;?>

   <? if ($current_step != 'shipping') { ?>
   <input type="hidden" name="shipping_int" value="<?=isset($item_details['shipping_int'])?$item_details['shipping_int']:'';?>" >
   <input type="hidden" name="direct_payment" value="<?=isset($item_details['direct_payment'])?$item_details['direct_payment']:'';?>" >
   <input type="hidden" name="payment_methods" value="<?=isset($item_details['payment_methods'])?$item_details['payment_methods']:'';?>" >
   <? } ?>
   <input type="hidden" name="shipping_method" value="<?=isset($item_details['shipping_method'])?$item_details['shipping_method']:'';?>" >
   <input type="hidden" name="postage_amount" value="<?=isset($item_details['postage_amount'])?$item_details['postage_amount']:'';?>" >
   <input type="hidden" name="insurance_amount" value="<?=isset($item_details['insurance_amount'])?$item_details['insurance_amount']:'';?>" >
   <input type="hidden" name="shipping_details" value="<?=isset($item_details['shipping_details'])?$item_details['shipping_details']:'';?>" >
   <input type="hidden" name="type_service" value="<?=isset($item_details['type_service'])?$item_details['type_service']:'';?>" >
   <input type="hidden" name="item_weight" value="<?=isset($item_details['item_weight'])?$item_details['item_weight']:'';?>" >

   <input type="hidden" name="start_time_type" value="<?=isset($item_details['start_time_type'])?$item_details['start_time_type']:'';?>" >
   <input type="hidden" name="end_time_type" value="<?=isset($item_details['end_time_type'])?$item_details['end_time_type']:'';?>" >
   <input type="hidden" name="duration" value="<?=isset($item_details['duration'])?$item_details['duration']:'';?>" >
   <input type="hidden" name="poster_email" value="<?=isset($item_details['poster_email'])?$item_details['poster_email']:'';?>" >
   <input type="hidden" name="poster_name" value="<?=isset($item_details['poster_name'])?$item_details['poster_name']:'';?>" >
   <input type="hidden" name="poster_address" value="<?=isset($item_details['poster_address'])?$item_details['poster_address']:'';?>" >
   <input type="hidden" name="poster_phone" value="<?=isset($item_details['poster_phone'])?$item_details['poster_phone']:'';?>" >
   <input type="hidden" name="auto_relist_nb" value="<?=isset($item_details['auto_relist_nb'])?$item_details['auto_relist_nb']:'';?>" >
		<input type="hidden" name="npuser_id" value="<?=isset($item_details['npuser_id'])?$item_details['npuser_id']:'';?>" >
		
<? } ?>
	<?=(isset($sell_item_page_content))?$sell_item_page_content:'';?>
<? if ($current_step!='finish') { ?>
</form>
<? } ?>

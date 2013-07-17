<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%"><img src="images/logo_paxbulk.gif" align="absmiddle" border="0"></td>
    <td class="errormessage contentfont" nowrap><img src="images/zip.gif" align="absmiddle" border="0"> <a href="<?=$path;?>bulk.zip"><b>Download bulk.zip</b></a></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
  <tr>
    <td class="c7" colspan="2"><b>PaxBulk HELP</b></td>
  </tr>
        <tr class="contentfont">
          <td class="c1" width="11"><?=$imgarrow;?></td>
          <td class="c2"><b>Using PaxBulk for the first time:</b></td>
        </tr>
        <tr class="contentfont">
          <td></td>
          <td><p align="justify"> Prior to using the listing you will require your username, password and the email address you used to register with at the auction.<br>
              When the program first starts up you will be presented with a screen to enter your login details for the auction site.<br>
              Once you have filled in the necessary details you can click the Check Login button to verify you have the correct details set or if you are sure there correct click the Apply Button.<br>
              In either case you must click the Apply button once finished.<br>
              If you change your details online in the future you can always go back to the logon screen via the Options menu to set the new details for the BulkLister login.<br>
              Once you have successfully entered your details and clicked close in the login details screen you will if starting the program for the first time be presented with the site automatic configuration screen.<br>
              You must use this screen to get the correct details for the auction site and to allow you to submit auctions to the site, when presented with this screen simply click the update button, and as long as your login details are correct the program will automatically be configured to use with the auction site. This action only needs to be done again if your auction master informs you to update your configurations to take advantage of additional options or if you loose the program configuration files. </p></td>
        </tr>
        <tr class="contentfont">
          <td class="c1" width="11"><?=$imgarrow;?></td>
          <td class="c2"><b>Using sessions:</b></td>
        </tr>
        <tr class="contentfont">
          <td></td>
          <td><p align="justify"> From the man screen of the bulklister you either start a new session, open a previous saved session or import a comma separated listing into a new session.<br>
              Using the new session - When you select new session you are prompted to enter a name to describe the session if you are selling different types of item categories then this name could be something that reminds you of what type of items are in the listing, such as Computing, Electrical or Wholesale etc.<br>
              Naming a session something like its content will allow you to easily use the open previously saved session option to load a listing into the program for submitting to the auction or to allow you to update or add to the listing.<br>
              These listings contains in the session can also be 'Saved As' a different name later on if you change your mind, they can also be exported into a different format of CSV file so that you can if needed call the exported CSV file into a program you may use to keep track of your sales.<br>
              Exporting the current listing/session is a simple and easy to use way of saving only part or all of the information in your listings to a CSV file that can be called into such programs as Excel or other editing software.<br>
              From the export screen you will see the available records on the left hand side of the screen that you can select and transfer to the right hand side of the screen for exporting. You are not limited to exporting to a CSV file as you can set below the delimiter to use (for a CSV use a comma). Your custom editing program may require specific format for each record and these can be set prior to clicking Export. <br>
              Once you click export you can enter the name of the file to save the exported data as. </p></td>
        </tr>
        <tr class="contentfont">
          <td class="c1" width="11"><?=$imgarrow;?></td>
          <td class="c2"><b>Using the auction items listing:</b></td>
        </tr>
        <tr class="contentfont">
          <td></td>
          <td><p align="justify"> When you have either imported a session or added new items to your listing you will see in the listings screen all the details of each items that are available for submitting to the auction, you will see also there are images in the first column and these specify whether or not a photo has been assigned to the auction item.<br>
              Each item in the listing can be edited , previewed or be removed from the listing using the buttons above the listing.<br>
              If you delete an item from the listing the item will be remove from the listing and the saved session so be careful you don't delete item you want to keep.<br>
              You can view the details on any of the items in the listing by either selecting an item and clicking the view button or simply by double clicking on an item in the list.<br>
              If you wish to edit an item to amend or add to the features etc you can select the item and click edit, this will transfer the details of that auction item to the item entry page where you can make the adjustments you want and then simply click the Update/Save button to return to the listing screen. </p></td>
        </tr>
        <tr class="contentfont">
          <td class="c1" width="11"><?=$imgarrow;?></td>
          <td class="c2"><b>Adding new or editing an item:</b></td>
        </tr>
        <tr class="contentfont">
          <td></td>
          <td><p align="justify"> Clicking Add or edit from the listing screen will take you to the item entry page (if you have no session started then you will be prompted to open one). <br>
              In the item entry page you can enter the details of the item you wish to add to the listing for submitting to the auction, these include all the title, description any features you wish to set and any images you need to supply to promote you auction.<br>
              Please follow the on screen tips to help you enter the correct details required for the auction item to be added to the list, once a successful item has been added to the listing you can preview the item from the listing screen.<br>
              Once you have entered your items into the listing and feel your ready to submit your items to the auction site for listing as live auctions review the the listing and use either the submit single or submit all items button to continue. </p></td>
        </tr>
        <tr class="contentfont">
          <td class="c1" width="11"><?=$imgarrow;?></td>
          <td class="c2"><b>Submitting your items to the auction:</b></td>
        </tr>
        <tr class="contentfont">
          <td></td>
          <td><p align="justify"> In the listing screen you will see two buttons, submit and submit all. Submit will submit the item in the listing you have highlighted and will only be enabled if you have selected an item, and then there the Submit all button that will submit all items in the listing that have not already been submitted. <br>
              You can tell by the legend at the bottom that items in pink are items that have not been submitted and items in yellow have previously been submitted in maybe a partially submission.<br>
              The reason for this is that you may not wish you submit all items at once or you may have cancelled a previous submission half way though the list etc.<br>
              Either way the entire listing can be marked as not submitted simply by clicking the button to the right of the find keyword box and has a red cross on it.<br>
              By the way the find keyword box is simply there to help you find a keyword in your listing if your listing grows to a long listing.<br>
              When you click the submit or submit all button you will be presented with the submit screen, this screen simply allows you to starting sending your items to the auction and listing them as live auctions. </p></td>
        </tr>
        <tr class="contentfont">
          <td class="c1" width="11"><?=$imgarrow;?></td>
          <td class="c2"><b>The actual submission process:</b></td>
        </tr>
        <tr class="contentfont">
          <td></td>
          <td><p align="justify"> When presented with the submission screen you will see three buttons, submit cancel and exit.<br>
              Depending on which button you pressed in the listing screen the submission will start submitting the items or item to the auction site when you click submit and you will see the status of the submission for each item in the form of a progress bar that will display the upload progress and show the current item being submitted..<br>
              If you decide that you do not wish to submit the entire listing once you have already started then you can click the cancel button to end the submission.<br>
              On successful submission or partially submission once you close the submit screen you will see that the items that have been submitted to the auction have changed to a yellow color, you can as mention earlier return to that listing at another time and submit all and it will only submit the items still colored in pink, or you can reset them all again using the mark all as not submitted button.<br>
              <br>
              Once you have submitted partially or all of your items you can either use you browser to view the items you have submitted or go to the main screen by clicking the internet button and view them from the bulklister application. <br>
              <br>
              Depending on your auction site configuration you will either see your auctions live or be able to go to your account and make the auctions live. </p></td>
        </tr>
</table>

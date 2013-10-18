<script src="../../scripts/jquery/jquery-1.9.1.js"></script>
<script src="../../scripts/jquery/jquery-ui.js"></script>
<?

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<script>
    $(document).ready(function(){
        $('#disable_campaigns').submit(function() {
            return false;
        });

        $("#users").change(function(){
            var user_id = $("#users").val();
            if (user_id) {
                $.ajax({
                    url: "campaigns.php",
                    data: {user_id: user_id},
                    dataType: "json",
                    success: function(result){
                        $("#npList tbody").html(result.np_users_content);
                        $("#campaigns_list").css({display: "block"});
                        initialize_campaigns();
                        initialize_campaign_types();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
    });

    function initialize_campaign_types()
    {
        $("#campaign_type").change(function(){
            var user_id = $("#users").val();
            var campaign_type = $("#campaign_type").val();
            if (user_id) {
                $.ajax({
                    url: "campaigns.php",
                    data: {user_id: user_id, campaign_type: campaign_type},
                    dataType: "json",
                    success: function(result){
                        console.log(result);
                        $("#npList tbody").html(result.np_users_content);
                        $("#campaigns_list").css({display: "block"});
                        initialize_campaigns();
                        initialize_campaign_types();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
    }

    function initialize_campaigns()
    {
        $(".disable_campaign").click(function(){
            var user_id = $("#users").val();
            var campaign_id = this.id;
            var element_id = 'element_' + campaign_id;
            var disabled_value = $("#campaign_" + this.id).val();
            $.ajax({
                url: "campaigns.php",
                data: {
                    user_id: user_id,
                    campaign_id: campaign_id,
                    disabled: disabled_value,
                    action: 'disable'
                },
                dataType: "json",
                success: function(result){
                    var disabled = '';
                    if (result.np_user_content.disabled == 1) {
                        disabled = 'Enable';
                    } else {
                        disabled = 'Disable';
                    }
                    $("#" + campaign_id).html(disabled);
                    $("#campaign_" + campaign_id).val(result.np_user_content.disabled);
                    initialize_campaigns();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $(".delete_campaign").click(function(){
            var user_id = $("#users").val();
            var campaign_id = this.id;
            var campaign_id = campaign_id.replace('del_', '');
            $( "#dialog-confirm" ).dialog({
                resizable: false,
                position: 'top',
                height:140,
                modal: true,
                buttons: {
                    "Delete": function() {
                        $( this ).dialog( "close" );
                        $.ajax({
                            url: "campaigns.php",
                            data: {
                                user_id: user_id,
                                campaign_id: campaign_id,
                                delete: 1,
                                action: 'delete'
                            },
                            dataType: "json",
                            success: function(result){
                                var campaign_type = $("#campaign_type").val();
                                if (user_id && result.deleted) {
                                    $.ajax({
                                        url: "campaigns.php",
                                        data: {user_id: user_id, campaign_type: campaign_type},
                                        dataType: "json",
                                        success: function(result){
                                            console.log(result);
                                            $("#npList tbody").html(result.np_users_content);
                                            $("#campaigns_list").css({display: "block"});
                                            initialize_campaigns();
                                            initialize_campaign_types();
                                        },
                                        error: function(error) {
                                            console.log(error);
                                        }
                                    });
                                }
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
    }

</script>
<!--<script>-->
<!--    $(function() {-->
<!--        $( "#dialog-confirm" ).dialog({-->
<!--            resizable: false,-->
<!--            height:140,-->
<!--            modal: true,-->
<!--            buttons: {-->
<!--                "Delete all items": function() {-->
<!--                    $( this ).dialog( "close" );-->
<!--                },-->
<!--                Cancel: function() {-->
<!--                    $( this ).dialog( "close" );-->
<!--                }-->
<!--            }-->
<!--        });-->
<!--    });-->
<!--</script>-->
<div id="dialog-confirm" title="Delete campaign?">
    <p>
        <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
        These campaign will be permanently deleted and cannot be recovered. Are you sure?
    </p>
</div>
<div class="mainhead"><img src="images/user.gif" align="absmiddle">
   <?=$header_section;?>
</div>
<!--<pre>-->
<!--    --><?php //var_dump($users)?>
<!--</pre>-->
<?//=isset($msg_changes_saved) ? $msg_changes_saved : '';?>
<?//=isset($display_formcheck_errors) ? $display_formcheck_errors : '';?>
<?//=isset($management_box) ? $management_box : '';?>
<div id="select_user">
    <form id="disable_campaigns">
        <label>Select user: </label><br />
        <select id="users" name="users">
            <option></option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo isset($user['id']) ? $user['id'] : '0' ?>">
                    <?php echo $user['first_name'] . ' ' . $user['last_name'] .$user['organization']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
<div id="campaigns_list" style="display: none">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="4"><img src="images/c1.gif" width="4" height="4"></td>
            <td width="100%" class="ftop"><img src="images/pixel.gif" width="1" height="1"></td>
            <td width="4"><img src="images/c2.gif" width="4" height="4"></td>
        </tr>
    </table>
    <table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
        <tr class="c3">
            <td colspan="2"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b>
                    <?=strtoupper($subpage_title);?>
                </b></td>
        </tr>
    </table>
    <table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside tablesorter" id="npList">
        <thead>
        <tr class="c4">
            <th class="header" width="50" align="center">Campaign ID</th>
            <th width="200" >Title</th>
            <th width="150" align="center">Registration Date</th>
            <th width="150" align="center">End Date</th>
            <th width="150" align="center">Status</th>
            <th filter="false" width="150" align="center"><?=AMSG_OPTIONS;?></th>
        </tr>
        </thead>
        <tbody>
        <?=isset($np_users_content) ? $np_users_content : '';?>
        </tbody>
    </table>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="4"><img src="images/c3.gif" width="4" height="4"></td>
            <td width="100%" class="fbottom"><img src="images/pixel.gif" width="1" height="1"></td>
            <td width="4"><img src="images/c4.gif" width="4" height="4"></td>
        </tr>
    </table>
</div>

<script type="text/javascript">
//		$(document).ready(function() {
//			$('#npList').tableFilter();
//            $("#npList").tablesorter(
//                {
//                    headers: {
//                                5: {
//                                    sorter: false
//                                }
//                            }
//                }
//            );
//		});
</script>
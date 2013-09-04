<script src="../../scripts/jquery/jquery-1.9.1.js"></script>

<script>
    $(document).ready(function(){
        $('#disable_campaigns').submit(function() {
            return false;
        });

        $("#user_id").change(function(){
            var user_id = $("#user_id").val();
            if (user_id) {
                $.ajax({
                    url: "disable_campaigns.php",
                    data: {user_id: user_id},
                    dataType: "json",
                    success: function(result){
                        $("#campaigns_types_block").html('');
                        $("#campaigns_list_block").html('');
                        $("#submit_button_block").html('');
                        var campaign_types_content = '<label>Choose campaign type: </label>';
                        campaign_types_content += '<select name="campaign_type" id="campaign_type"><option></option>';
                        $.each(result.campaign_types, function(index, value) {
                            campaign_types_content += '<option value="' + value + '">' + value + '</option>';
                        });
                        campaign_types_content += '</select>';
                        $("#campaigns_types_block").html(campaign_types_content);
                        initialize_campaigns_type();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
    });

    function initialize_campaigns_type()
    {
        $("#campaign_type").change(function(){
            var user_id = $("#user_id").val();
            var campaign_type = $("#campaign_type").val();
            if (campaign_type) {
                $.ajax({
                    url: "disable_campaigns.php",
                    data: {user_id: user_id, campaign_type: campaign_type},
                    dataType: "json",
                    success: function(result){
                        console.log(result);
                        $("#campaigns_list_block").html('');
                        var campaigns_content = '<ul class="campaigns_list">';
                        $.each(result.campaigns, function(index, value) {
                            var disabled = '';
                            if (value.disabled == 1) {
                                disabled = 'Enable';
                            } else {
                                disabled = 'Disable';
                            }
                            campaigns_content += '<li id="element_' + value.user_id +
                                '"><input type="hidden" id="campaign_' + value.user_id +
                                '" value="' + value.disabled + '" /><div class="campaign_id_cell">' + value.user_id +
                                '</div><div class="campaign_title_cell">' + value.project_title +
                                '</div><div class="campaign_disable_cell"><button id="' + value.user_id +
                                '" class="disable_button">' + disabled + '</button></div></li><br />';
                        });
                        campaigns_content += '</ul>';
                        var pagination_content = '';
                        for (var i = 1; i <= result.pages; i++) {
                            pagination_content += ' <a href="javascript: void(0)" class="campaigns_pagination" id="' +
                                i + '"';
                            if (i == result.current_page) {
                                pagination_content += ' style="color: red" ';
                            }
                            pagination_content += '> ' + i + ' </a>'
                        }
                        $("#campaigns_list_block").html(campaigns_content);
                        $("#pagination").html(pagination_content);
                        initialize_campaigns();
                        initialize_pagination();
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
        $(".disable_button").click(function(){
            var user_id = $("#user_id").val();
            var campaign_type = $("#campaign_type").val();
            var campaign_id = this.id;
            var element_id = 'element_' + campaign_id;
            var disabled_value = $("#campaign_" + this.id).val();
            if (campaign_type) {
                $.ajax({
                    url: "disable_campaigns.php",
                    data: {
                        user_id: user_id,
                        campaign_type: campaign_type,
                        campaign_id: campaign_id,
                        disabled: disabled_value
                    },
                    dataType: "json",
                    success: function(result){
                        $("#" + element_id).html('');
                        console.log(result)
                        var campaign_content = '<input type="hidden" id="campaign_' + result.campaign.user_id +
                            '" value="' + result.campaign.disabled + '" />';
                        var disabled = '';
                        if (result.campaign.disabled == 1) {
                            disabled = 'Enable';
                        } else {
                            disabled = 'Disable';
                        }
                        campaign_content += '<div class="campaign_id_cell">' + result.campaign.user_id +
                            '</div><div class="campaign_title_cell">' + result.campaign.project_title +
                            '</div><div class="campaign_disable_cell"><button id="' + result.campaign.user_id +
                            '" class="disable_button">' + disabled + '</button></div>';
                        $("#" + element_id).html(campaign_content);
                        initialize_campaigns();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
    }

    function initialize_pagination()
    {
        $(".campaigns_pagination").click(function(){
            var page = this.id;
            var user_id = $("#user_id").val();
            var campaign_type = $("#campaign_type").val();
            if (campaign_type) {
                $.ajax({
                    url: "disable_campaigns.php",
                    data: {user_id: user_id, campaign_type: campaign_type, page: page},
                    dataType: "json",
                    success: function(result){
                        console.log(result);
                        $("#campaigns_list_block").html('');
                        var campaigns_content = '<ul class="campaigns_list">';
                        $.each(result.campaigns, function(index, value) {
                            var disabled = '';
                            if (value.disabled == 1) {
                                disabled = 'Enable';
                            } else {
                                disabled = 'Disable';
                            }
                            campaigns_content += '<li id="element_' + value.user_id +
                                '"><input type="hidden" id="campaign_' + value.user_id +
                                '" value="' + value.disabled + '" /><div class="campaign_id_cell">' + value.user_id +
                                '</div><div class="campaign_title_cell">' + value.project_title +
                                '</div><div class="campaign_disable_cell"><button id="' + value.user_id +
                                '" class="disable_button">' + disabled + '</button></div></li><br />';
                        });
                        campaigns_content += '</ul>';
                        var pagination_content = '';
                        for (var i = 1; i <= result.pages; i++) {
                            pagination_content += ' <a href="javascript: void(0)" class="campaigns_pagination" id="' +
                                i + '"';
                            if (i == result.current_page) {
                                pagination_content += ' style="color: red" ';
                            }
                            pagination_content += '> ' + i + ' </a>'
                        }
                        $("#campaigns_list_block").html(campaigns_content);
                        $("#pagination").html(pagination_content);
                        initialize_campaigns();
                        initialize_pagination();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
    }
</script>

<style>
    .campaign_id_cell {/*float: left; margin: 10px; padding: 10px;*/}
    .campaign_title_cell {/*float: left; margin: 10px; padding: 10px;*/}
    .campaign_disable_cell {/*float: left; margin: 10px; padding: 10px;*/}
</style>

<?php if (!isset($user_id) || !$user_id): ?>
    <form name="disable_campaigns" id="disable_campaigns" action="disable_campaigns.php">
        <div id="users_list_block">
            <label>Choose user: </label>
            <select name="user_id" id="user_id">
                <option></option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo (isset($user['id']) && $user['id']) ? $user['id'] : '0'; ?>">
                        <?php
                        echo (isset($user['organization']) && $user['organization']) ? $user['organization'] :
                            (isset($user['first_name']) && isset($user['last_name'])) ? $user['first_name'] . ' ' . $user['last_name'] : '';
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="campaigns_types_block"></div>
        <div id="campaigns_list_block"></div>
        <div id="pagination"></div>
        <div id="submit_button_block"></div>
    </form>
<?php endif; ?>
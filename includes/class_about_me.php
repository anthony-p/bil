<?

include_once ('global.php');
include_once('generate_image_thumbnail.php');
global $db;

if (isset($_POST['form_aboutme_save'])) {

    if (isset ($_FILES["avatar"]) ) {

        $allowed_image_mime_types = array(
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/tiff',
            'image/svg+xml'
        );

        if (in_array($_FILES["avatar"]["type"], $allowed_image_mime_types)) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $logo_file_name = 'images/partner_logos/' . md5($_POST["first_name"] . 'logo') . '.' . $ext;
            $upload_logo = generate_image_thumbnail(
                $_FILES["avatar"]["tmp_name"], $logo_file_name, 240, 240
            );
            exec('chmod 0777 images/partner_logos/temp/' . md5($_POST["first_name"] . 'logo') . '.*');
            exec('rm images/partner_logos/temp/' . md5($_POST["first_name"] . 'logo') . '.*');
            $_POST["avatar"] = $logo_file_name;

            $post_about_details = $db->rem_special_chars_array($_POST);
            $db->query("UPDATE bl2_users SET
					    avatar='" . $post_about_details['avatar'] . "',
                        about_me='" . $post_about_details['about_me'] . "',
                        facebook_link='" . $post_about_details['facebook_link'] . "',
                        twitter_link='" . $post_about_details['twitter_link'] . "',
                        google_link='" . $post_about_details['google_link'] . "' WHERE
                        id='" . $session->value('user_id') . "'");

            $template->set('msg_changes_saved', $msg_changes_saved);
            $user_details = $db->get_sql_row("SELECT * FROM bl2_users WHERE id=" . $session->value('user_id'));
            $template->set('user_details', $user_details);
        } else {
            $user_details= $post_about_details;
            $template->set('user_details', $user_details);
        }
    }
    /**
     * if form is not submit
     * show from DB
     */
} else {
    $user_details = $db->get_sql_row("SELECT * FROM bl2_users WHERE id=" . $session->value('user_id'));
    $template->set('user_details', $user_details);
}

$members_area_page_content = $template->process('members_area_aboutme_edit.tpl.php');
$template->set('members_area_page_content', $members_area_page_content);

?>
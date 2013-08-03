<?

session_start();

include_once ('global.php');
include_once('generate_image_thumbnail.php');
global $db;

define ('PATHINFO_EXTENSION', 4);
define ('AVATAR_HEIGHT', 200);
define ('AVATAR_WIDTH', 240);

$user_id = $session->value('user_id');
if (empty($user_id)) {
    header_redirect('login.php');
}

if (isset($_POST['form_aboutme_save'])) {
    var_dump(1);
    if (!empty ($_FILES["avatar"]) ) {
        $logo_file_name = validateAvatar();
        if (!empty($logo_file_name)) {
            $_POST["avatar"] = $logo_file_name;
        } else {
            $_POST["avatart"] = $_POST["curr_avatart"];
        }
        insertAboutUserDetails($_POST, $db, $user_id);
        $template->set('msg_changes_saved', $msg_changes_saved);
        $user_details = $db->get_sql_row("SELECT * FROM bl2_users WHERE id=" . $user_id);
        $template->set('user_details', $user_details);
    } else {
        var_dump(2);
        $_POST["avatar"] = $_POST["curr_avatar"];
        insertAboutUserDetails($_POST, $db, $user_id);
        $template->set('msg_changes_saved', $msg_changes_saved);
        $user_details = $db->get_sql_row("SELECT * FROM bl2_users WHERE id=" . $user_id);
        $template->set('user_details', $user_details);
    }
} else {
    $user_details = $db->get_sql_row("SELECT * FROM bl2_users WHERE id=" . $user_id);
    $template->set('user_details', $user_details);
}

$members_area_page_content = $template->process('members_area_aboutme_edit.tpl.php');
$template->set('members_area_page_content', $members_area_page_content);

/**
 * @param $data
 * @param $db
 * @param $user_id
 */
function insertAboutUserDetails($data, $db, $user_id)
{
    $post_about_details = $db->rem_special_chars_array($data);

    $db->query("UPDATE bl2_users SET
                    avatar='" . $post_about_details['avatar'] . "',
                    about_me='" . $post_about_details['about_me'] . "',
                    facebook_link='" . $post_about_details['facebook_link'] . "',
                    twitter_link='" . $post_about_details['twitter_link'] . "',
                    google_link='" . $post_about_details['google_link'] . "' WHERE
                    id='" . $user_id . "'");
}

/**
 * @return null|string
 */
function validateAvatar()
{
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
            $_FILES["avatar"]["tmp_name"], $logo_file_name, AVATAR_WIDTH, AVATAR_HEIGHT
        );
        exec('chmod 0777 images/partner_logos/temp/' . md5($_POST["first_name"] . 'logo') . '.*');
        exec('rm images/partner_logos/temp/' . md5($_POST["first_name"] . 'logo') . '.*');
        return $logo_file_name;
    } else {
        return null;
    }
}

?>
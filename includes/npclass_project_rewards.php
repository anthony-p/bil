<?php

/**

 * Created by JetBrains PhpStorm.

 * User: Igor

 * Date: 7/26/13

 * Time: 6:57 PM

 * To change this template use File | Settings | File Templates.

 */

include_once("npclass_custom_field.php");



class npProjectRewards extends npcustom_field

{

    var $item;



    /**

     * @param $project_rewards

     */

    function insert ($project_rewards)

    {

        $currentTime = time();

        if (!empty($project_rewards)) {

            $project_rewards = $this->rem_special_chars_array($project_rewards);

            $insert_query = "INSERT INTO project_rewards(user_id, project_id, parrent_id, comment, create_at) VALUES";



            $insert_query .= "(" .

                    $project_rewards["user_id"] . ", " .

                    $project_rewards["project_id"] . ", '" .

                    $project_rewards["parrent_id"] . "', '" .

                    $project_rewards["comment"] . "', '" .

                    $currentTime .

                    "')";

        $sql_insert_pitch = $this->query($insert_query);

        }

    }



    /**

     * @param null $id

     */

    function delete ($id = null)

    {

        if (!empty($id)) {

            $this->query("DELETE * FROM project_rewards pr WHERE pr.id=" . $id);

        }

    }



    /**

     * @return null|resource

     */

    function getLastComment()

    {

        $result = $this->query(" SELECT project_rewards.id FROM project_updates ORDER BY id DESC LIMIT 1");

        return $result;

    }

}
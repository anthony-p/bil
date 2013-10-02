<?php

/**

 * Created by JetBrains PhpStorm.

 * User: Igor

 * Date: 7/26/13

 * Time: 6:57 PM

 * To change this template use File | Settings | File Templates.

 */

include_once("npclass_custom_field.php");



class npProjectUpdates extends npcustom_field

{

    var $item;



    /**

     * @param $project_updates

     */

    function insert ($project_updates)

    {

        $currentTime = time();

        if (!empty($project_updates)) {

            $project_updates = $this->rem_special_chars_array($project_updates);

            $insert_query = "INSERT INTO project_updates(user_id, project_id, parrent_id, comment, create_at) VALUES";



            $insert_query .= "(" .

                    $project_updates["user_id"] . ", " .

                    $project_updates["project_id"] . ", '" .

                    $project_updates["parrent_id"] . "', '" .

                    $project_updates["comment"] . "', '" .

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

            $this->query("DELETE * FROM project_updates pu WHERE pu.id=" . $id);

        }

    }



    /**

     * @return null|resource

     */

    function getLastComment()

    {

        $result = $this->query(" SELECT project_updates.id FROM project_updates ORDER BY id DESC LIMIT 1");

        return $result;

    }

}
<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * An interface with the underlying model. Dependant modules should use this
 * class.
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode;

defined('MOODLE_INTERNAL') || die();

class manager {

    /**
     * Grace time in seconds.
     */
    const GRACE_TIME = 10 * 60;

    /**
     * Tenemos la configuración.
     *
     * @var config
     */
    private $config;

    /**
     * A singleton instance.
     *
     * @var manager
     */
    private static $instance = null;

    /**
     * Returns a singleton instance for manager.
     * @return manager
     */
    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new manager();
        }
        return self::$instance;
    }

    /**
     * Instantiate the class with get_instance() --> singleton.
     */
    private function __construct() {
        $this->config = config::get_instance();
    }

    /**
     * Adds a new exammode record.
     *
     * @global \moodle_database $DB
     * @param \local_exammode\objects\exammode $em
     * @return boolean true on success, false on failure.
     */
    public function add_exam (objects\exammode $em) {
        global $DB;
        if ($em->get_id()) {
            $em->set_id(null);
        }
        $id = $DB->insert_record('local_exammode', $em->to_db_record());
        if (!$id) {
            return false;
        }
        $em->set_id($id);
        return true;
    }

    /**
     * Gets the exams scheduled for course. Excludes STATE_TODELETE courses.
     *
     * @global \moodle_database $DB
     * @param int $courseid
     */
    public function get_exams_for_course ($courseid, $sort = 'timefrom DESC') {
        global $DB;

        $sql = "SELECT em.* "
                . "FROM {local_exammode} em "
                . "WHERE courseid = :courseid "
                . "      AND state != :deleted "
                . "ORDER BY $sort";

        $recs = $DB->get_records_sql(
            $sql,
            array('courseid' => $courseid,
                  'deleted' => objects\exammode::STATE_TODELETE
            )
        );

        return array_map(function($r) {
            return objects\exammode::to_exammode($r);
        }, $recs);
    }

    /**
     * Gets exammodes marked to be deleted.
     *
     * @global \moodle_database $DB
     *
     */
    public function get_exammodes_to_delete() {
        global $DB;

        $recs = $DB->get_records(
                'local_exammode',
                array('state' => objects\exammode::STATE_TODELETE)
        );
        return array_map(function($r) {
            return objects\exammode::to_exammode($r);
        }, $recs);
    }

    /**
     * Deletes an exam.
     *
     * @global \moodle_database $DB
     * @param int $id
     * @throws \dml_exception
     */
    public function delete_exam ($examid) {
        global $DB;
        $DB->delete_records('local_exammode', array('id' => $examid));
    }

    /**
     * Returns an exam with id examid.
     *
     * @global \moodle_database $DB
     * @param int $examid
     * @return objects\exammode
     */
    public function get_exam($examid) {
        global $DB;
        $dbrec = $DB->get_record('local_exammode', array('id' => $examid), '*', MUST_EXIST);
        return objects\exammode::to_exammode($dbrec);
    }

    /**
     * Actualiza un examen programado.
     *
     * @global \moodle_database $DB
     * @param \local_exammode\objects\exammode $exam
     * @return type
     */
    public function update_exam(objects\exammode $exam) {
        global $DB;
        return $DB->update_record('local_exammode', $exam->to_db_record());
    }

    /**
     * Gets the courses that should be in exammode now.
     *
     * @global \moodle_database $DB
     * @return objects\exammode[]
     */
    public function get_courses_in_exammode() {

        global $DB;

        $now = time();

        $sql = "SELECT id, courseid, timefrom, timeto, state "
                . "FROM {local_exammode} em "
                . "WHERE :from >= em.timefrom "
                . "      AND :to <= em.timeto "
                . "      AND em.state != :state_todelete";

        $dbrecs = $DB->get_records_sql(
                $sql,
                array(
                    'from' => $now + self::GRACE_TIME,
                    'to' => $now - self::GRACE_TIME,
                    'state_todelete' => objects\exammode::STATE_TODELETE
                )
        );

        return array_map(function($dbrec) {
            return objects\exammode::to_exammode($dbrec);
        }, $dbrecs);
    }

    /**
     * Gets the exammodes of courses that should be finished. DO NOT FILTER
     * STATE_TODELETE exammodes because it's necessary to remove users from the
     * update_exammode_users task.
     *
     * @global \moodle_database $DB
     * @return objects\exammode[]
     */
    public function get_finished_exammodes() {
        global $DB;

        $sql = "SELECT id, courseid, timefrom, timeto, state "
                . "FROM {local_exammode} em "
                . "WHERE em.timeto < :to";

        $dbrecs = $DB->get_records_sql(
                $sql,
                array(
                    'to' => time() - self::GRACE_TIME
                )
        );

        return array_map(function($dbrec) {
            return objects\exammode::to_exammode($dbrec);
        }, $dbrecs);
    }

    /**
     * Returns potential users in exammode for the give course.
     *
     * @param objects\exammode $em the exammode
     * @return exammode_user[]
     */
    public function get_potential_users_for_exammode($em) {
        $context = \context_course::instance($em->get_courseid());
        $users = \get_users_by_capability(
                $context,
                'local/exammode:enterexammode',
                'u.id'
        );
        return array_map(function($u) use ($em) {
            return new objects\exammode_user(null, $em->get_id(), $u->id);
        }, $users);
    }

    /**
     * Comprobar si el curso están en exammode.
     *
     * @param string $courseid
     * @global \moodle_database $DB
     * @return objects\exammode|null
     */
    public function is_course_in_exammode ($courseId) {

        global $DB;

        $time = time();

        $sql = "SELECT em.* "
                . "FROM {local_exammode} em "
                . "WHERE courseid = :courseid "
                . "      AND em.timefrom <= :from "
                . "      AND em.timeto >= :to";

        $dbrec = $DB->get_record_sql(
                $sql,
                array(
                    'courseid' => $courseId,
                    'from' => $time + self::GRACE_TIME,
                    'to' => $time - self::GRACE_TIME
                )
        );

        if (!$dbrec) {
            return null;
        }

        return objects\exammode::to_exammode($dbrec);
    }

    /**
     * Gets an array of userid that should be in exammode now (ie. potential
     * users in exammode).
     *
     * @global \moodle_database $DB
     * @return objects\exammode_user[]
     */
    public function get_all_users_in_exammode() {
        global $DB;

        // TODO: esta función debería llamarse get_potential_users_in_exammode()
        $users = array();
        $exammode = $this->get_courses_in_exammode();
        foreach ($exammode as $em) {
            $context = \context_course::instance($em->get_courseid());
            $aux = \get_users_by_capability(
                    $context,
                    'local/exammode:enterexammode',
                    'u.id'
            );
            $aux = array_map(function($u) use ($em) {
                return new objects\exammode_user(null, $em->get_id(), $u->id);
            }, $aux);
            $users = array_merge($users, $aux);
        }

        return $users;
    }

    /**
     * Gets an array of exammode_user of users that are currently in exammode.
     *
     * @global \moodle_database $DB
     * @param int $courseid the courseid.
     * @return objects\exammode_user[]
     */
    public function get_users_in_exammode($courseid = null) {
        global $DB;
        $params = array();

        $sql = "SELECT emu.* "
                . "FROM {local_exammode} em"
                . "     JOIN {local_exammode_user} emu ON emu.exammodeid = em.id ";

        if ($courseid !== null) {
            $params['courseid'] = $courseid;
            $sql .= "WHERE em.courseid = :courseid";
        }

        $dbrecs = $DB->get_records_sql($sql, $params);
        return objects\exammode_user::get_from_db($dbrecs);
    }

    /**
     * Determines if a user is in exammode now in any course.
     *
     * @global \moodle_database $DB
     * @param int $userid
     */
    public function is_user_in_exammode ($userid) {
       global $DB;
       return $DB->record_exists('local_exammode_user', array('userid' => $userid));
    }

    /**
     * Return true if user is in exammode
     *
     * @param string $courseId
     * @param string $userId
     * @global \moodle_database $DB
     * @return boolean
     */
    public function is_user_in_exammode_at_course($courseid, $userid) {

        global $DB;

        $sql = "SELECT count(em.id) as nids "
             . "FROM {local_exammode} em "
             . "  INNER JOIN {local_exammode_user} eu ON em.id = eu.exammodeid "
             . "WHERE em.courseid = :courseid "
             . "      AND eu.userid = :userid";

        $nids = $DB->count_records_sql($sql, array(
                'userid'    => $userid,
                'courseid'  => $courseid)
        );

        return intval($nids) > 0;
    }

    /**
     * Puts a user in exammode.
     *
     * @global \moodle_database $DB
     * @param objects\exammode_user
     */
    public function put_user_in_exammode(objects\exammode_user $emu) {
        global $DB;

        $trx = $DB->start_delegated_transaction();

        $this->configure_moodle_exammode($emu);
        $id = $DB->insert_record('local_exammode_user', $emu->to_db());
        $emu->set_id($id);

        $trx->allow_commit();
    }

    /**
     * Removes a user from exammode.
     *
     * @param objects\exammode_user $emu
     */
    public function remove_user_from_exammode(objects\exammode_user $emu) {
        global $DB;

        $count = $DB->count_records('local_exammode_user', array('userid' => $emu->get_userid()));
        if ($count == 1) {
            $this->unconfigure_moodle_exammode($emu);
        }
        $DB->delete_records('local_exammode_user', array('id' => $emu->get_id()));
    }

    /**
     * Returns an array of blockinstance ids for blocks prohibited at dashboard.
     *
     * @global \moodle_database $DB
     * @param \local_exammode\objects\exammode_user $emu
     * @return int[]
     */
    private function get_prohibited_dashboard_blocks(objects\exammode_user $emu) {
        global $DB, $CFG;

        $sql = "SELECT id "
                . "FROM {block_instances} bi "
                . "WHERE parentcontextid = :contextid "
                . "AND blockname = :privatefiles";

        $blockinstances = $DB->get_records_sql(
                $sql,
                array(
                    'contextid' => \context_user::instance($emu->get_userid())->id,
                    'privatefiles' => 'private_files'
                )
        );

        return array_map(function($bi) {
            return $bi->id;
        }, $blockinstances);
    }

    /**
     * Performs the actions to put moodle into exammode for the user specified.
     *
     * @param \local_exammode\objects\exammode_user $emu
     */
    private function configure_moodle_exammode (objects\exammode_user $emu) {

        // Assign the blocks role.
        $blockinstances = $this->get_prohibited_dashboard_blocks($emu);

        foreach ($blockinstances as $biid) {
            $context = \context_block::instance($biid);
            \role_assign(
                    $this->config->get_roletohideblock(),
                    $emu->get_userid(),
                    $context->id,
                    'local_exammode'
            );
        }

        // Assign the system role.
        \role_assign(
                $this->config->get_roletosystem(),
                $emu->get_userid(),
                \context_system::instance()->id,
                'local_exammode'
        );
    }

    /**
     * Removes the actions performed bu configure_moodle_exammode in order to
     * return a user to a non-exammode state.
     *
     * @param objects\exammode_user $user
     */
    private function unconfigure_moodle_exammode (objects\exammode_user $emu) {

        // Assign the role to show
        $blockinstances = $this->get_prohibited_dashboard_blocks($emu);
        foreach ($blockinstances as $biid) {
            $context = \context_block::instance($biid);
            \role_unassign(
                    $this->config->get_roletohideblock(),
                    $emu->get_userid(),
                    $context->id,
                    'local_exammode'
            );
        }
        \role_unassign(
                $this->config->get_roletosystem(),
                $emu->get_userid(),
                \context_system::instance()->id,
                'local_exammode'
        );
    }


}

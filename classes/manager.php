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
 * @package    local/exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode;

defined('MOODLE_INTERNAL') || die();

class manager {

    const GRACE_TIME = 10 * 60;

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
     * Gets the exmmode courses for the user.
     *
     * @global \moodle_database $DB
     * @param int $userid
     * @return objects\exammode[] An array of courseids the user is enroled into.
     */
    public function get_exammode_courses ($userid) {
        global $DB;

        $courses = \enrol_get_users_courses($userid, true, 'id', '');
        if (!$courses) {
            return array();
        }
        $courseids = array_map(function($course) {
            return $course->id;
        }, $courses);

        list($sql, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);

        $params['from'] = time() - self::GRACE_TIME;
        $params['to'] = time();

        $courses = $DB->get_records_select(
             'local_exammode',
             'courseid $sql AND from <= :from AND to >= :to',
             $params,
             '',
             '*'
        );
        return objects\exammode::to_exammode($courses);
    }

    /**
     * Is the user in exammode
     *
     * @global \moodle_database $DB
     * @param int $userid
     * @param int $courseid
     */
    public function is_in_exammode ($userid, $courseid) {
        global $DB;

        $from = time() - self::GRACE_TIME;
        $to = time();

        $sql = "SELECT * "
                . "FROM {local_exammode} e "
                . "     JOIN {local_exammode_user} eu ON eu.exammodeid = e.id "
                . "WHERE eu.userid = :userid "
                . "      AND e.courseid = :courseid "
                . "      AND e.from <= :from "
                . "      AND e.to >= :to";

        return $DB->record_exists_sql(
            $sql,
            array(
                'userid' => $userid,
                'courseid' => $courseid,
                'from' => $from,
                'to' => $to
            )
        );
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
     * Gets the exams scheduled for course.
     *
     * @global \moodle_database $DB
     * @param int $courseid
     */
    public function get_exams_for_course ($courseid, $sort = 'timefrom DESC') {
        global $DB;

        $recs = $DB->get_records(
            'local_exammode',
            array('courseid' => $courseid),
            $sort
        );

        return array_map(function($r) {
            return objects\exammode::to_exammode($r);
        }, $recs);
    }

    /**
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
     * Returns.
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
}

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
 * Manage exam mode for a course.
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$action = optional_param('action', 'view', PARAM_ALPHA);

require_login($courseid);

$context = context_course::instance($courseid);
require_capability('local/exammode:manage', $context);

$course = $DB->get_record('course', array('id' => $courseid));

$PAGE = new moodle_page();
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->set_url(new \moodle_url('/local/exammode/manage.php', array('courseid' => $courseid)));

$PAGE->set_heading("Manage Exam Mode");
$PAGE->set_title("Manage Exam Modes");

$manager = local_exammode\manager::get_instance();

$output = $PAGE->get_renderer('local_exammode');

if ($action === 'new' || $action === 'edit') {

    if ($action === 'edit') {
        $examid = required_param('examid', PARAM_INT);
    } else {
        $examid = null;
    }

    $newpage = new \local_exammode\output\newpage($examid, $courseid);
    if ($newpage->is_cancelled()) {
        echo $output->header();
    } else if ($data = $newpage->get_data()) {
        // Data is already validated here, so timefrom and duration are within
        // the same day.
        $exam = new local_exammode\objects\exammode(
                $examid,
                $courseid,
                $data->timefrom,
                $data->timefrom + $data->duration
        );

        if ($action === 'new') {
            $success = $manager->add_exam($exam);
        } else {
            $success = $manager->update_exam($exam);
        }

        if (!$success) {
            redirect(
                new \moodle_url('/local/exammode/manage.php', array('courseid' => $courseid)),
                get_string('newexamerror', 'local_exammode'),
                5,
                \core\output\notification::NOTIFY_ERROR
            );
        } else {

            $a = new \stdClass();
            $a->day = userdate($data->timefrom, get_string('strftimedate', 'langconfig'));
            $a->from = userdate($data->timefrom, get_string('strftimetime', 'langconfig'));
            $a->to = userdate($data->timefrom + $data->duration, get_string('strftimetime', 'langconfig'));

            redirect(
                    new \moodle_url('/local/exammode/manage.php', array('courseid' => $courseid)),
                    get_string('newexamsuccess', 'local_exammode', $a),
                    5,
                    \core\output\notification::NOTIFY_SUCCESS
            );

        }

    } else {
        echo $output->header();

        if ($action === 'edit') {
            $examid = required_param('examid', PARAM_INT);

            $exam = $manager->get_exam($examid);
            $newpage->set_exam($exam);
        }

        echo $output->render($newpage);
        echo $output->footer();
        die;
    }

} else if ($action === 'delete') {
    echo $output->header();
    $examid = required_param('examid', PARAM_INT);
    $sesskey = optional_param('sesskey', null, PARAM_RAW);
    if (!$sesskey) {
        echo $output->confirm(
            get_string('confirmdelete', 'local_exammode'),
            new \moodle_url(
                '/local/exammode/manage.php',
                array('action' => 'delete', 'courseid' => $courseid, 'examid' => $examid, 'sesskey' => sesskey())
            ),
            new \moodle_url(
                '/local/exammode/manage.php',
                array('action' => 'view', 'courseid' => $courseid)
            )
        );
        echo $output->footer();
        die;
    }

    require_sesskey();
    // TODO: delete_exam has to unassign the system role defined.
    $manager->delete_exam($examid);
} else {
    echo $output->header();
}

$exams = $manager->get_exams_for_course($courseid);

$manage_page = new local_exammode\output\manage_page(
    $courseid,
    new \local_exammode\output\examstable("examstable", $courseid, $exams),
    new \moodle_url("/local/exammode/manage.php")
);
echo $output->render($manage_page);

echo $output->footer();

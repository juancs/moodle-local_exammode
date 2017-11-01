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
 * @package    local_exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);

$context = context_course::instance($courseid);
require_capability('local/exammode:manage', $context);

$course = $DB->get_record('course', array('id' => $courseid));

$PAGE = new moodle_page();
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->set_url($CFG->wwwroot . '/local/exammode/manage.php', array('courseid' => $courseid));

$PAGE->set_heading("Manage Exam Mode");
$PAGE->set_title("Manage Exam Modes");

$output = $PAGE->get_renderer('local_exammode');
echo $output->header();



echo $output->footer();

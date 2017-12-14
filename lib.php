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
 * Extends the navigation node.
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function local_exammode_extend_navigation_course(navigation_node $navigation, $course, $context) {
    global $CFG;

    if (has_capability('local/exammode:manage', $context)) {
        $cat = $navigation->create(
                get_string('exammode', 'local_exammode'),
                null,
                navigation_node::TYPE_CATEGORY
        );
        $navigation->add_node($cat);

        $node = $cat->create(
            get_string('manageexammode', 'local_exammode'),
            new moodle_url($CFG->wwwroot . '/local/exammode/manage.php', array('courseid' => $course->id)),
            global_navigation::TYPE_SETTING,
            null,
            "manageexammode",
            new pix_icon('e/question', get_string('manageexammode', 'local_exammode'))
        );
        $cat->add_node($node);
    }
}


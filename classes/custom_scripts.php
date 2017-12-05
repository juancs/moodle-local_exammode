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
 * A class to add functionality to the custom scripts defined in this script.
 *
 * @package    local/exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode;

defined('MOODLE_INTERNAL') || die();

class custom_scripts {
    /**
     * Allows or not to continue if the user is or not in exammode.
     *
     * @global string $SCRIPT
     */
    public static function exammode_check() {
        global $SCRIPT, $USER, $CFG;
        if (isset($USER->id)) {
            $manager = \local_exammode\manager::get_instance();
            if ($manager->is_user_in_exammode($USER->id)) {
                redirect(
                        $CFG->wwwroot,
                        get_string('nauthz_exammode', 'local_exammode', $SCRIPT),
                        5,
                        \core\output\notification::NOTIFY_INFO
                );
            }
        }
    }

    /**
     * Returns a json to inform that an ajax script cannot be invoked because
     * user is in exammode.
     * 
     * @global string $SCRIPT
     * @global \stdClass $USER
     * @global \stdClass $CFG
     */
    public static function exammode_check_for_ajax_script() {
        global $SCRIPT, $USER, $CFG;
        if (isset($USER->id)) {
            $manager = \local_exammode\manager::get_instance();
            if ($manager->is_user_in_exammode($USER->id)) {
                $ret = array();
                $ret['error'] = false;
                $ret['data'] = array();
                die(json_encode(array($ret)));
            }
        }
    }
}

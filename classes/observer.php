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
 * Event observer for exammode.
 *
 * @package    local_exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Emili Gonzalez <egonzale@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class local_exammode_observer {
    public static function local_exammode_user_enrolment_created(\core\event\user_enrolment_created $event) {
        /*
        $manager = local_exammode\manager::get_instance();

        $em = $manager->is_course_in_exammode($event->courseid);
        if ( $em != null ) {
            $emu = new \local_exammode\objects\exammode_user(
                    null,
                    $em->get_id(),
                    $event->relateduserid
            );
            $manager->put_user_in_exammode($emu);
        }
         * 
         */
    }
}

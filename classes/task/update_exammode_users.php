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
 * Task to put students in exam mode.
 *
 * @package    local
 * @copyright  2017 Universitat Jaume I (http://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later2
 */
namespace local_exammode\task;

class update_exammode_users extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('update_exammode_users', 'local_exammode');
    }

    public function execute() {
        $manager = \local_exammode\manager::get_instance();

        // Users that should be in exammode.
        $allusers = $manager->get_all_users_in_exammode();

        // Users that are right now in exammode.
        $currentusers = $manager->get_users_in_exammode();

        // Remove from exammode.
        $toremove = array();
        foreach ($currentusers as $cu) {
            $found = false;
            foreach ($allusers as $au) {
                if ($cu->get_exammodeid() == $au->get_exammodeid()) {
                    if ($cu->get_userid() == $au->get_userid()) {
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
                $manager->remove_user_from_exammode($cu);
            }
        }

        // Put in exammode.
        $toadd = array();
        foreach ($allusers as $au) {
            $found = false;
            foreach ($currentusers as $cu) {
                if ($cu->get_exammodeid() == $au->get_exammodeid()) {
                    if ($cu->get_userid() == $au->get_userid()) {
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
                $manager->put_user_in_exammode($au);
            }
        }

    }
}

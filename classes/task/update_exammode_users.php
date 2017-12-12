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

        // Courses that should be in exammode.
        $courses = $manager->get_courses_in_exammode();
        foreach ($courses as $exammode) {
            try {
                if ($exammode->get_state() === \local_exammode\objects\exammode::STATE_PENDING) {
                    $exammode->set_state(\local_exammode\objects\exammode::STATE_CONFIGURING);
                    $manager->update_exam($exammode);
                }

                $potentialusers = $manager->get_potential_users_for_exammode($exammode);
                $currentusers = $manager->get_users_in_exammode($exammode->get_courseid());

                $this->remove_users($exammode, $potentialusers, $currentusers);
                $this->add_users($exammode, $potentialusers, $currentusers);

                $exammode->set_state(\local_exammode\objects\exammode::STATE_WORKING);
                $manager->update_exam($exammode);
            } catch (\Exception $e) {
                mtrace("        ERROR: " . $e->getMessage() . ": " . $e->getTraceAsString());
            }
        }

        // Courses that shouldn't be in exammode.
        $exammodes = $manager->get_finished_exammodes();
        foreach ($exammodes as $exammode) {
            try {
                if ($exammode->get_state() !== \local_exammode\objects\exammode::STATE_UNCONFIGURING) {
                    $exammode->set_state(\local_exammode\objects\exammode::STATE_UNCONFIGURING);
                    $manager->update_exam($exammode);
                }

                $users = $manager->get_users_in_exammode($exammode->get_courseid());
                foreach ($users as $emu) {
                    $manager->remove_user_from_exammode($emu);
                }

                $exammode->set_state(\local_exammode\objects\exammode::STATE_FINISHED);
                $manager->update_exam($exammode);
            } catch (\Exception $e) {
                mtrace("        ERROR: " . $e->getMessage() . ": " . $e->getTraceAsString());
            }
        }

        // Exammodes to be deleted.
        $exammodes = $manager->get_exammodes_to_delete();
        foreach ($exammodes as $em) {
            try {
                $users = $manager->get_users_in_exammode($em->get_courseid());
                foreach ($users as $emu) {
                    $manager->remove_user_from_exammode($emu);
                }

                $manager->delete_exam($em->get_id());
            } catch (\Exception $e) {
                mtrace("        ERROR: " . $e->getMessage() . ": " . $e->getTraceAsString());
            }
        }
    }

    /**
     * Remove users from exammode.
     *
     * @param \local_exammode\objects\exammode_user[] $potential
     * @param \local_exammode\objects\exammode_user[] $current
     */
    private function remove_users($exammode, $potential, $current) {

        $manager = \local_exammode\manager::get_instance();

        // Remove from exammode.
        $toremove = array();
        foreach ($current as $cu) {
            $found = false;
            foreach ($potential as $au) {
                if ($cu->get_exammodeid() == $au->get_exammodeid()) {
                    if ($cu->get_userid() == $au->get_userid()) {
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
                $manager->remove_user_from_exammode($cu);

                $event = \local_exammode\event\user_abandoned::create_from_exammode($exammode, $cu);
                $event->trigger();
            }
        }
    }

    /**
     * Add users.
     *
     * @param \local_exammode\objects\exammode_user[] $potential
     * @param \local_exammode\objects\exammode_user[] $current
     */
    private function add_users($exammode, $potential, $current) {

        global $CFG;

        $manager = \local_exammode\manager::get_instance();

        // Put in exammode.
        $toadd = array();
        foreach ($potential as $au) {
            $found = false;
            foreach ($current as $cu) {
                if ($cu->get_exammodeid() == $au->get_exammodeid()) {
                    if ($cu->get_userid() == $au->get_userid()) {
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
               // OJO. Es necesario ejecutar my_copy_page para asegurar que existen las
               // instancias de bloque en la tabl block_instances para el dashboard.
                require_once($CFG->dirroot . '/my/lib.php');
                if ( \my_copy_page($au->get_userid()) !== false) {
                    $manager->put_user_in_exammode($au);

                    $event = \local_exammode\event\user_started::create_from_exammode($exammode, $au);
                    $event->trigger();
                }
            }
        }

    }
}

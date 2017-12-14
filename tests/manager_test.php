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
 * Manager test.
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use local_exammode\manager;
use local_exammode\objects\exammode;
use local_exammode\objects\exammode_user;

class manager_test extends advanced_testcase {

    private $course1;
    private $course2;

    private $teacher1_course1;
    private $user1_course1;
    private $user2_course1;

    private $systemroleid;
    private $blocksroleid;

    public function setUp() {

        // Role configuration must go first.
        $this->systemroleid = $this->getDataGenerator()->create_role();
        $this->blocksroleid = $this->getDataGenerator()->create_role();

        set_config('roletosystem', $this->systemroleid, 'local_exammode');
        set_config('roletohideblock', $this->blocksroleid, 'local_exammode');

        $this->course1 = $this->getDataGenerator()->create_course();
        $this->course2 = $this->getDataGenerator()->create_course();

        $this->teacher1_course1 = $this->getDataGenerator()->create_user();
        $this->neteacher_course1 = $this->getDataGenerator()->create_user();
        $this->user1_course1 = $this->getDataGenerator()->create_user();
        $this->user2_course1 = $this->getDataGenerator()->create_user();

        $this->getDataGenerator()->enrol_user($this->teacher1_course1->id, $this->course1->id, 'editingteacher');
        $this->getDataGenerator()->enrol_user($this->neteacher_course1->id, $this->course1->id, 'teacher');
        $this->getDataGenerator()->enrol_user($this->user1_course1->id, $this->course1->id, 'student');
        $this->getDataGenerator()->enrol_user($this->user2_course1->id, $this->course1->id, 'student');

        return parent::setUp();
    }

    public function test_get_instance() {
        $this->resetAfterTest();

        $m1 = local_exammode\manager::get_instance();
        $m2 = local_exammode\manager::get_instance();

        $this->assertEquals($m1, $m2);
    }

    /**
     * Add exam works as expected.
     *
     * @global \moodle_database $DB
     */
    public function test_add_exam() {
        $this->resetAfterTest();

        $timefrom = time();
        $timeto = $timefrom + 2 * 3600;

        $manager = local_exammode\manager::get_instance();
        $exam = new local_exammode\objects\exammode(
                null,
                $this->course1->id,
                $timefrom,
                $timeto
        );

        $manager->add_exam($exam);

        $this->assertNotNull($exam->get_id());

        global $DB;
        $dbrec = $DB->get_record('local_exammode', array('id' => $exam->get_id()));

        $this->assertEquals($dbrec->courseid, $this->course1->id);
        $this->assertEquals($dbrec->timefrom, $timefrom);
        $this->assertEquals($dbrec->timeto, $timeto);
    }

    public function test_get_exams_for_course() {
        $this->resetAfterTest();

        $manager = manager::get_instance();

        // Add some exams.
        $timefrom = time();
        $timeto = time() + 4*3600;

        $exam = new exammode(null, $this->course1->id, $timefrom, $timeto);
        $exam2 = new exammode(null, $this->course1->id, $timefrom + 4*3600, $timeto + 6*3600);

        $manager->add_exam($exam);
        $manager->add_exam($exam2);

        $exams = $manager->get_exams_for_course($this->course1->id);
        $exams2 = $manager->get_exams_for_course($this->course2->id);

        $this->assertCount(2, $exams);
        $this->assertCount(0, $exams2);

        $this->assertContainsOnlyInstancesOf(exammode::class, $exams);
    }

    public function test_delete_exam() {
        $this->resetAfterTest();

        $manager = manager::get_instance();
        $exam = new exammode(null, $this->course1->id, time(), time() + 3600);

        $manager->add_exam($exam);

        $exams = $manager->get_exams_for_course($this->course1->id);
        $this->assertCount(1, $exams);

        $manager->delete_exam($exam->get_id());
        $exams = $manager->get_exams_for_course($this->course1->id);
        $this->assertInternalType('array', $exams);
        $this->assertCount(0, $exams);
    }

    public function test_get_exam() {
        $this->resetAfterTest();

        $manager = manager::get_instance();

        $exam = new exammode(
               null,
               $this->course1->id,
               time(),
               time()+3600
        );
        $manager->add_exam($exam);

        // Check existing.
        $dbexam = $manager->get_exam($exam->get_id());

        $this->assertEquals($dbexam->get_courseid(), $exam->get_courseid());
        $this->assertEquals($dbexam->get_from(), $exam->get_from());
        $this->assertEquals($dbexam->get_to(), $exam->get_to());
        $this->assertEquals($dbexam->get_id(), $exam->get_id());
    }

    public function test_get_exam_exception() {
        $this->resetAfterTest();
        $this->expectException(dml_exception::class);

        $manager = manager::get_instance();
        $manager->get_exam(1);
    }

    public function test_get_courses_in_exammode() {
        $this->resetAfterTest();

        $manager = manager::get_instance();

        // Remember that the method doesn't returns courseid. It returns
        // instances of exammode. So, the same course can return more than one
        // exammode.

        // No course in exammode.
        $exammodes = $manager->get_courses_in_exammode();

        $this->assertInternalType('array', $exammodes);
        $this->assertCount(0, $exammodes);

        // One course in exammode.
        $exam = new exammode(null, $this->course1->id, time() - 3600, time()+3600);
        $manager->add_exam($exam);

        $exam = new exammode(null, $this->course1->id, time() + 3600, time()+2*3600);
        $manager->add_exam($exam);

        $exam = new exammode(null, $this->course2->id, time() + 3600, time()+2*3600);
        $manager->add_exam($exam);

        $exammodes = $manager->get_courses_in_exammode();
        $this->assertCount(1, $exammodes);

        $exam = array_pop($exammodes);
        $this->assertEquals($this->course1->id, $exam->get_courseid());

        // Same course, two exammodes.
        $exam = new exammode(null, $this->course1->id, time() - 1000, time() + 1000);
        $manager->add_exam($exam);

        $exammodes = $manager->get_courses_in_exammode();
        $this->assertCount(2, $exammodes);
        $this->assertContainsOnlyInstancesOf(exammode::class, $exammodes);

        $exam = array_pop($exammodes);
        $this->assertEquals($this->course1->id, $exam->get_courseid());
        $exam = array_pop($exammodes);
        $this->assertEquals($this->course1->id, $exam->get_courseid());
    }

    public function test_is_course_in_exammode () {
         $this->resetAfterTest();

         $manager = manager::get_instance();

         // Test grace time. Assume GRACE_TIME > 30.
         $timefrom = time() + manager::GRACE_TIME - 30;
         $timeto = time() + 3600;
         $exam = new exammode(
                null,
                $this->course1->id,
                $timefrom,
                $timeto
         );
         $manager->add_exam($exam);

         $exam = $manager->is_course_in_exammode($this->course1->id);
         $this->assertNotNull($exam);

         // More than GRACE_TIME should return null.
         $timefrom = time() + manager::GRACE_TIME + 30;
         $timeto = time() + manager::GRACE_TIME + 30 + 3600;
         $exam = new exammode(
                 null,
                 $this->course2->id,
                 $timefrom,
                 $timeto
         );
         $manager->add_exam($exam);

         $exam = $manager->is_course_in_exammode($this->course2->id);
         $this->assertNull($exam);
    }

    public function test_get_all_users_in_exammode() {
        $this->resetAfterTest();

        $manager = manager::get_instance();

        // No hay usuarios potenciales en modo examen.
        $users = $manager->get_all_users_in_exammode();
        $this->assertInternalType('array', $users);
        $this->assertCount(0, $users);

        // Put an exammode in course1 not in the range so nobody should be in exammode.
        $exam = new exammode(null, $this->course1->id, time() + 3600, time() + 2*3600);
        $manager->add_exam($exam);

        $users = $manager->get_all_users_in_exammode();
        $this->assertInternalType('array', $users);
        $this->assertCount(0, $users);

        // Put an exammode in course1 in the range so, two people (students) should be in exammode.
        $exam = new exammode(null, $this->course1->id, time()-3600, time() + 4*3600);
        $manager->add_exam($exam);

        $users = $manager->get_all_users_in_exammode();
        $this->assertInternalType('array', $users);
        $this->assertCount(2, $users);

        $userids = array($this->user1_course1->id, $this->user2_course1->id);
        foreach ($users as $user) {
            $this->assertContains($user->get_userid(), $userids);
            $this->assertEquals($exam->get_id(), $user->get_exammodeid());
        }
    }

    public function test_put_user_in_exammode() {

        $this->resetAfterTest();

        // Cannot add block_private_file block because it doesn't have
        // generators yet.

        // Now test, test, test.
        $manager = manager::get_instance();

        $exam = new exammode(null, $this->course1->id, time() - 30, time() + 3600);
        $manager->add_exam($exam);

        $emu = new exammode_user(null, $exam->get_id(), $this->user1_course1->id);
        $manager->put_user_in_exammode($emu);

        // Test that has the system role assigned.
        $roles = get_user_roles(context_system::instance(), $this->user1_course1->id);
        $roleids = array_map(function($role) {
            return $role->roleid;
        }, $roles);

        $this->assertContains($this->systemroleid, $roleids);

    }

}

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

class manager_test extends advanced_testcase {

    private $course;

    public function setUp() {
        $this->course = $this->getDataGenerator()->create_course();
        return parent::setUp();
    }

    /**
     * Add exam works as expected.
     * 
     * @global \moodle_database $DB
     */
    public function test_add_exammode() {        
        $this->resetAfterTest();

        $from = time() - 3600;
        $to = time() + 3600;
        $exammode = new \local_exammode\objects\exammode(
            null,
            $this->course->id,
            $from,
            $to
        );

        $manager = local_exammode\manager::get_instance();
        $manager->add_exammode($exammode);

        $this->assertNotNull($exammode->get_id());

        global $DB;
        $dbrec = $DB->get_record('local_exammode', array(
            'id' => $exammode->get_id()
        ));

        $this->assertEquals($from, $dbrec->timefrom);
        $this->assertEquals($to, $dbrec->timeto);
        $this->assertEquals($this->course->id, $dbrec->courseid);
   }

   public function test_get_exammode() {
       
   }

}

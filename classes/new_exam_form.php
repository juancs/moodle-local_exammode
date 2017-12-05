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
 * Descripción 
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class new_exam_form extends \moodleform {
    protected function definition() {
        global $CFG;
        $mform =& $this->_form;

        $mform->addElement('date_time_selector', 'timestart', get_string('date'));
        $mform->addElement('duration', 'timelimit', 'nose');
    }
}

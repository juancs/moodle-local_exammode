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
 * @package    local/exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Renderable for the new_page template.
 */
class newpage extends \moodleform implements \renderable, \templatable {

    const DEFAULT_DURATION = 2*3600;

    private $courseid;
    private $examid;

    public function __construct($examid, $courseid) {
        $this->examid = $examid;
        $this->courseid = $courseid;
        parent::__construct();
    }

    protected function definition() {
        global $CFG;

        $mform = & $this->_form;

        $mform->addElement('date_time_selector', 'timefrom', get_string('timefrom', 'local_exammode'));

        $mform->addElement('duration', 'duration', get_string('duration', 'local_exammode'), array('defaultunit' => 3600, 'optional' => false));
        $mform->setDefault('duration', self::DEFAULT_DURATION);

        $mform->addElement('hidden', 'courseid', $this->courseid);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'examid', $this->examid);
        $mform->setType('examid', PARAM_INT);

        $mform->addElement('hidden', 'action', optional_param('action', 'new', PARAM_ALPHANUMEXT));
        $mform->setType('action', PARAM_ALPHANUMEXT);

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        $timefrom = $data['timefrom'];
        $duration = $data['duration'];
        if ($duration > 3600*24) {
            $errors['duration'] = get_string('errordurationlong', 'local_exammode');
        } else {
            $timeend = $timefrom + $duration;
            $parsedfrom = usergetdate($timefrom);
            $parsedend = usergetdate($timeend);
            if ($parsedfrom['mday'] != $parsedend['mday']) {
                $errors['duration'] = get_string('errordurationlong', 'local_exammode');
            }
        }

        if ($timefrom + $duration < time()) {
            $errors['duration'] = get_string('errorexaminthepast', 'local_exammode');
            $errors['timefrom'] = get_string('errorexaminthepast', 'local_exammode');
        }
        return $errors;
    }

    public function export_for_template(\renderer_base $output) {
        return array(
            'form' => $this->render()
        );
    }

    public function set_exam(\local_exammode\objects\exammode $exam) {

        $default_values = new \stdClass;
        $default_values->timefrom = $exam->get_from();
        $default_values->duration = $exam->get_to() - $exam->get_from();

        parent::set_data($default_values);
    }
}
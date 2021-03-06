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

namespace local_exammode\output;

defined('MOODLE_INTERNAL') || die();

use local_exammode\objects\exammode;

require_once($CFG->libdir . '/tablelib.php');

class examstable extends \flexible_table implements \renderable {

    private $calendar;
    private $courseid;
    private $exams;

    public function __construct($uniqueid, $courseid, $exams) {
        parent::__construct($uniqueid);

        $this->calendar = \core_calendar\type_factory::get_calendar_instance();
        $this->courseid = $courseid;
        $this->exams = $exams;

        $this->define_columns(
            array(
                'state',
                'date',
                'timefrom',
                'timeto',
                'actions'
            )
        );
        $this->define_headers(
            array(
                get_string('state', 'local_exammode'),
                get_string('date'),
                get_string('timefrom', 'local_exammode'),
                get_string('timeto', 'local_exammode'),
                get_string('actions', 'local_exammode')
            )
        );
        $this->define_baseurl(new \moodle_url('/local/exammode/manage.php'));
        $this->pageable(true);
        $this->sortable(false);
        $this->collapsible(false);
    }

    public function get_exams() {
        return $this->exams;
    }

    protected function col_date(\local_exammode\objects\exammode $data) {
        return userdate($data->get_from(), get_string('strftimedate', 'langconfig'));
    }
    protected function col_timefrom($data) {
        return userdate($data->get_from(), get_string('strftimetime', 'langconfig'));
    }
    protected function col_timeto($data) {
        return userdate($data->get_to(), get_string('strftimetime', 'langconfig'));
    }
    /**
     *
     * @global \core_renderer $OUTPUT
     * @param \local_exammode\objects\exammode $data
     */
    protected function col_actions($data) {
        global $OUTPUT;

        $ret = $OUTPUT->action_icon(
            new \moodle_url(
                '/local/exammode/manage.php',
                array(
                    'courseid' => $this->courseid,
                    'action' => 'edit',
                    'examid' => $data->get_id()
                )
            ),
            new \pix_icon('i/edit', get_string('edit'))
        );
        $ret .= $OUTPUT->action_icon(
            new \moodle_url(
                '/local/exammode/manage.php',
                array(
                    'courseid' => $this->courseid,
                    'action' => 'delete',
                    'examid' => $data->get_id()
                )
            ),
            new \pix_icon('i/delete', get_string('delete'))
        );
        return $ret;
    }
    /**
     * The state of the exammode.
     *
     * @param \local_exammode\objects\exammode $data
     */
    protected function col_state($data) {

        switch ($data->get_state()) {
            case exammode::STATE_CONFIGURING:
                $state = get_string('state_configuring', 'local_exammode');
                $state_desc = get_string('state_configuringdesc', 'local_exammode');
                break;
            case exammode::STATE_FINISHED:
                $state = get_string('state_finished', 'local_exammode');
                $state_desc = get_string('state_finisheddesc', 'local_exammode');
                break;
            case exammode::STATE_PENDING:
                $state = get_string('state_pending', 'local_exammode');
                $state_desc = get_string('state_pendingdesc', 'local_exammode');
                break;
            case exammode::STATE_UNCONFIGURING:
                $state = get_string('state_unconfiguring', 'local_exammode');
                $state_desc = get_string('state_unconfiguringdesc', 'local_exammode');
                break;
            case exammode::STATE_WORKING:
                $state = get_string('state_working', 'local_exammode');
                $state_desc = get_string('state_workingdesc', 'local_exammode');
                break;
        }

        return \html_writer::tag('p', $state, array('title' => $state_desc));
    }
}
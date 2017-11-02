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
 * @package    local_exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

class examstable extends \flexible_table implements \renderable {

    private $calendar;

    public function __construct($uniqueid) {
        parent::__construct($uniqueid);

        $this->calendar = \core_calendar\type_factory::get_calendar_instance();

        $this->define_columns(
            array(
                'choose',
                'date',
                'timefrom',
                'timeto',
                'actions'
            )
        );
        $this->define_headers(
            array(
                '',
                get_string('date'),
                get_string('timefrom', 'local_exammode'),
                get_string('timeto', 'local_exammode'),
                get_string('actions', 'local_exammode')
            )
        );
        $this->define_baseurl(new \moodle_url('local/exammode/manage.php'));
        $this->pageable(true);
        $this->sortable(true);
        $this->collapsible(false);
    }

    protected function col_check($data) {
        return \html_writer::checkbox("algo", "valor", false);
    }

    public function col_date(\local_exammode\objects\exammode $data) {
        return userdate($data->get_from(), get_string('strftimedate', 'langconfig'));
        
    }
    public function col_timefrom($data) {
        return userdate($data->get_from(), get_string('strftimetime', 'langconfig'));
    }
    public function col_timeto($data) {
        return userdate($data->get_to(), get_string('strftimetime', 'langconfig'));
    }

}
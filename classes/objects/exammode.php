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
 * A model for local_exammode table.
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode\objects;

defined('MOODLE_INTERNAL') || die();

class exammode {

    const STATE_PENDING = 0;
    const STATE_CONFIGURING = 1;
    const STATE_WORKING = 2;
    const STATE_UNCONFIGURING = 3;
    const STATE_FINISHED = 4;

    const STATE_TODELETE = 5;

    private static $valid_states = array(
        self::STATE_PENDING,
        self::STATE_CONFIGURING,
        self::STATE_WORKING,
        self::STATE_UNCONFIGURING,
        self::STATE_FINISHED,
        self::STATE_TODELETE
    );

    private $id;
    private $courseid;
    private $timefrom;
    private $timeto;
    private $state;

    /**
     * Given a dbrecord from local_exammode gets an instance of exammode.
     *
     * @param \stdClass $dbrec
     * @return exammode
     */
    public static function to_exammode($dbrec) {
        return new self(
            $dbrec->id, $dbrec->courseid, $dbrec->timefrom, $dbrec->timeto, $dbrec->state
        );
    }

    public function __construct($id, $courseid, $timefrom, $timeto, $state = self::STATE_PENDING) {

        if (!$this->state_is_valid($state)) {
            throw new \local_exammode\exceptions\invalid_state_exception($state);
        }

        $this->id = ($id === null) ? null : (int) $id;
        $this->courseid = (int) $courseid;
        $this->timefrom = (int) $timefrom;
        $this->timeto = (int) $timeto;
        $this->state = (int) $state;
    }

    public function to_db_record() {
        $ret = new \stdClass();
        $ret->id = $this->id;
        $ret->courseid = (int) $this->courseid;
        $ret->timefrom = (int) $this->timefrom;
        $ret->timeto = (int) $this->timeto;
        $ret->state = (int) $this->state;
        return $ret;
    }

    private function state_is_valid($state) {
        return in_array($state, self::$valid_states);
    }

    public function get_id() {
        return $this->id;
    }

    public function get_courseid() {
        return $this->courseid;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function set_courseid($courseid) {
        $this->courseid = $courseid;
    }

    function get_from() {
        return $this->timefrom;
    }

    function get_to() {
        return $this->timeto;
    }

    function set_from($from) {
        $this->timefrom = $from;
    }

    function set_to($to) {
        $this->timeto = $to;
    }

    public function get_state() {
        return $this->state;
    }

    public function set_state($state) {
        if (!$this->state_is_valid($state)) {
            throw new \local_exammode\exceptions\invalid_state_exception($state);
        }
        $this->state = $state;
    }
}

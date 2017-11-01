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
 * @package    local/exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode\objects;

defined('MOODLE_INTERNAL') || die();

class exammode {

    private $id;
    private $courseid;
    private $from;
    private $to;

    /**
     * Given a dbrecord from local_exammode gets an instance of exammode.
     *
     * @param \stdClass $dbrec
     * @return exammode
     */
    public static function to_exammode($dbrec) {
        return new self(
            $dbrec->id, $dbrec->courseid, $dbrec->from, $dbrec->to
        );
    }

    public function __construct($id, $courseid, $from, $to) {
        $this->id = ($id === null) ? null : (int) $id;
        $this->courseid = (int) $courseid;
        $this->from = (int) $from;
        $this->to = (int) $to;
    }

    public function to_db_record() {
        $ret = new \stdClass();
        $ret->id = $this->id;
        $ret->courseid = (int) $this->courseid;
        $ret->from = (int) $this->from;
        $ret->to = (int) $this->to;
        return $ret;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_courseid() {
        return $this->courseid;
    }

    public function get_from() {
        return $this->from;
    }

    public function get_to() {
        return $this->to;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function set_courseid($courseid) {
        $this->courseid = $courseid;
    }

    public function set_from($from) {
        $this->from = $from;
    }

    public function set_to($to) {
        $this->to = $to;
    }

}

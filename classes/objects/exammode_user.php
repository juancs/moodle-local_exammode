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
 * A model for local_exammode_user table.
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode\objects;

defined('MOODLE_INTERNAL') || die();

class exammode_user {
    private $id;
    private $exammodeid;
    private $userid;

    /**
     * Given an array of local_exammode_user records gets a list of exammode_user
     * instances.
     *
     * @param \stdClass[] $dbrecs
     * @return exammode_user[]
     */
    public static function get_from_db($dbrecs) {
        $ret = array();
        foreach ($dbrecs as $key => $dbrec) {
            $ret[$key] = new self($dbrec->id, $dbrec->exammodeid, $dbrec->userid);
        }
        return $ret;
    }

    public function __construct($id, $exammodeid, $userid) {
        $this->id = $id;
        $this->exammodeid = $exammodeid;
        $this->userid = $userid;
    }

    public function to_db() {
        $ret = new \stdClass();
        $ret->id = $this->id;
        $ret->exammodeid = $this->exammodeid;
        $ret->userid = $this->userid;
        return $ret;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_exammodeid() {
        return $this->exammodeid;
    }

    public function get_userid() {
        return $this->userid;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function set_exammodeid($exammodeid) {
        $this->exammodeid = $exammodeid;
    }

    public function set_userid($userid) {
        $this->userid = $userid;
    }
}

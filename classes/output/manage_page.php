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

class manage_page implements \renderable, \templatable {

    /**
     * The courseid.
     * 
     * @var int
     */
    private $courseid;

    /**
     * The table to manage exams.
     * 
     * @var \local_exammode\output\examstable
     */
    private $examstable;

    /**
     * The url to the new exam.
     *
     * @var \moodle_url
     */
    private $newexamurl;

    /**
     * Constructor.
     *
     * @param int $courseid
     * @param examstable $examstable
     * @param \moodle_url $newexamurl
     */
    public function __construct($courseid, examstable $examstable, \moodle_url $newexamurl) {
        $this->courseid = $courseid;
        $this->examstable = $examstable;
        $this->newexamurl = $newexamurl;
    }

    public function export_for_template(\renderer_base $output) {
        return array(
            'courseid' => $this->courseid,
            'examstable' => $output->render($this->examstable),
            'newexamurl' => (string) $this->newexamurl
        );
    }

    /**
     * Devuelve los exámenes.
     *
     * @return \local_exammode\objects\exammode[]
     */
    public function get_exams() {
        return $this->exams;
    }
}


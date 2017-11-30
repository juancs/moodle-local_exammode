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
 * Output exammode.
 *
 * @package    local_exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exammode\output;

defined('MOODLE_INTERNAL') || die();

class exammode implements \templatable, \renderable {
    
    /**
     * The exammode to show.
     *
     * @var \local_exammode\objects\exammode 
     */
    private $exammode;

    public function __construct(\local_exammode\objects\exammode $exammode) {
        $this->exammode = $exammode;
    }
    public function export_for_template(\renderer_base $output) {
        return array(
            'timefrom' => usergetdate($this->exammode->get_from()),
            'timeto' => usergetdate($this->exammode->get_to()),
            'actions' => '1 2 3'
        );
    }
}

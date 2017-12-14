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
 * An abstract class to deal with user events.
 *
 * @package    local_lpi
 * @copyright  2017 Universitat Jaume I (http://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later2
 */

namespace local_exammode\event;

use local_exammode\objects\exammode;
use local_exammode\objects\exammode_user;

abstract class user_event extends \core\event\base {
    public static function create_from_exammode (exammode $em, exammode_user $emu) {
        return self::create(array(
            'objectid' => $emu->get_id(),
            'context' => \context_course::instance($em->get_courseid()),
            'relateduserid' => $emu->get_userid()
        ));
    }
}

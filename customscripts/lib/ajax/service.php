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
 * Just returns nothing for message_popup_get_popup_notifications and
 * core_message_data_for_messagearea_conversations.
 *
 * @package    local/exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$info = optional_param('info', null, PARAM_RAW);

$functions_to_check = array(
    'message_popup_get_popup_notifications',
    'core_message_data_for_messagearea_conversations'
);
if (in_array($info, $functions_to_check)) {
    \local_exammode\custom_scripts::exammode_check_for_ajax_script();
}

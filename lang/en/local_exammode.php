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
 * English lang pack.
 *
 * @package    local_exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['confirmdelete'] = 'Are you sure you want to delete this exam?';
$string['exammode'] = 'Exam Mode';
$string['exammode:manage'] = 'Allows to put a course into exam mode';
$string['manageexammode'] = 'Manage Exam Mode';
$string['pluginname'] = 'Exam Mode';

$string['timefrom'] = 'Start';
$string['timeto'] = 'End';
$string['actions'] = 'Actions';

$string['scheduledexammodes'] = 'Scheduled exam modes';
$string['scheduledexammodesdesc'] = 'You can manage the scheduled exam modes for this course. '
        . 'Remember that an exam mode restricts what students can do during the period '
        . 'of time defined.';

$string['newexam'] = 'New exam';
$string['duration'] = 'Duration';

$string['newexamdesc'] = 'Please choose a start date/time and a duration for '
        . 'the exam. The <em>mode will start 15 minutes before the start '
        . 'date/time</em> to ensure all your students are in exam mode before the '
        . 'exam takes plase. Exam modes can be scheduled just within a day.';

$string['errordurationlong'] = 'An exam mode can be set just within the same '
        . 'day';

$string['errorexaminthepast'] = 'An exam mode cannot be set in the past';

$string['newexamsuccess'] = 'Exam mode scheduled on {$a->day} from {$a->from} to '
        . '{$a->to}';

$string['newexamerror'] = 'Failed to schedule exammode';

$string['exammodesettings'] = 'Exam mode global configuration';
$string['roletoadd'] = 'Role to add';
$string['roletoadddesc'] = 'Role to add at system contetx to course students '
        . 'when we are in exam mode.';

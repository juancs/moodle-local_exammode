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
 * Global settings.
 *
 * @package    local_exammode
 * @copyright  2017 Universitat Jaume I (http://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later2
 */

if ($hassiteconfig) {

    $settings = new admin_settingpage('local_exammode', get_string('exammodesettings', 'local_exammode'));
    $ADMIN->add('localplugins', $settings);

    // roletoadd. The role to add to the students when in exam mode.
    $roles = role_get_names(null, ROLENAME_ORIGINAL, true);
    $settings->add(new admin_setting_configselect(
            'roletoadd',
            get_string('roletoadd', 'local_exammode'),
            get_string('roletoadddesc', 'local_exammode'),
            null,
            $roles
    ));

}

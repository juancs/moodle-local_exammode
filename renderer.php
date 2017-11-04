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
 * The renderer.
 *
 * @package    local_exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class local_exammode_renderer extends plugin_renderer_base {

    public function render_manage_page (local_exammode\output\manage_page $managepage) {
        return $this->render_from_template(
                'local_exammode/manage_page',
                $managepage->export_for_template($this)
        );
    }

    public function render_examstable (\local_exammode\output\examstable $examstable) {
        ob_start();

        $examstable->setup();
        $examstable->format_and_add_array_of_rows($examstable->get_exams(), false);
        $examstable->finish_output();

        $table = ob_get_contents();
        ob_end_clean();

        return $table;
    }
}


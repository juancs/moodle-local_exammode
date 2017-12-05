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
 * Spanish language pack.
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['actions'] = 'Acciones';
$string['confirmdelete'] = '¿Seguro que deseas borrar este examen?';
$string['exammode'] = 'Modo examen';
$string['exammode:manage'] = 'Permite poner un curso en modo examen';
$string['exammode:enterexammode'] = 'Permite a un usuario ser puesto en modo examen';
$string['manageexammode'] = 'Gestionar modo examen';
$string['pluginname'] = 'Modo examen';

$string['timefrom'] = 'Hora de inicio';
$string['timeto'] = 'Hora de fin';

$string['scheduledexammodes'] = 'Modos examen planificados';
$string['scheduledexammodesdesc'] = 'Puedes gestionar los modos examen '
        . 'planificados para el curso. Recuerda que el modo examen restringe lo '
        . 'que los estudiantes pueden hacer en el Aula Virtual durante el '
        . 'periodo de tiempo definido.';

$string['newexam'] = 'Nuevo examen';
$string['duration'] = 'Duración';

$string['newexamdesc'] = 'Por favor, elije una fecha de inicio y duración '
        . 'para el examen. El <em>modo empezará 15 minutos antes del examen '
        . '</em> para asegurar que los estudiantes están en modo examen antes '
        . 'que que el mismo tenga lugar. Un modo examen no puede sobrepasar las '
        . '12 de la noche del día en que se planifica.';

$string['errordurationlong'] = 'Un modo examen puede establecerse sólo dentro '
        . 'del mismo día';

$string['errorexaminthepast'] = 'Un examen no se puede planificar en el pasado';

$string['newexamsuccess'] = 'Examen planificado el {$a->day} desde las '
        . '{$a->from} hasta las {$a->to}';

$string['newexamerror'] = 'Error planificando examen';

$string['exammodesettings'] = 'Configuración global de modo examen';

$string['roletosystem'] = 'Rol a añadir a nivel de sistema';
$string['roletosystemdesc'] = 'Rol a asignar a nivel de sistema a los '
        . 'estudiantes del curso cuando están en modo examen.';

$string['roletohideblock'] = 'Rol a añadir para ocultar bloques';
$string['roletohideblockdesc'] = 'Rol a asignar a nivel de bloque en el '
        . 'Área personal para ocultar los bloques que no están permitidos '
        . '(por ejemplo, el bloque Archivos privados)';

$string['update_exammode_users'] = 'Actualiza usuarios en modo examen';

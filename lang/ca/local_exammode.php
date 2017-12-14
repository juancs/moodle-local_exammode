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
 * Catalan language pack.
 *
 * @package    local
 * @subpackage exammode
 * @copyright  2017 Universitat Jaume I (https://www.uji.es/)
 * @author     Juan Segarra Montesinos <juan.segarra@uji.es>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['actions'] = 'Accions';
$string['confirmdelete'] = 'Esteu segurs de que voleu esborrar aquest examen?';
$string['duration'] = 'Duració';
$string['errordurationlong'] = 'Un examen només es pot planificar dintre del mateix dia';
$string['errorexaminthepast'] = 'Un examen no es pot planificar en el pasat';
$string['exammode'] = 'Mode examen';
$string['exammode:enterexammode'] = 'Permeteix a un usuari ser ficat en mode examen';
$string['exammode:manage'] = 'Permeteix ficar un curs en mode examen';
$string['exammodesettings'] = 'Configuració global de Mode examen';
$string['manageexammode'] = 'Gestionar mode examen';
$string['newexam'] = 'Nou examen';
$string['newexamdesc'] = 'Per favor, escolliu una data de començament i una duració per a l\'examen. El <em>mode començarà 15 minuts abans de la data de començament que indiqueu</em> per a assegurar que l\'estudiantat està en mode examen abans de que escomence l\'examen. Només podeu ficar un mode examen dintre d\'un dia.';
$string['newexamerror'] = 'Error planificant el mode de examen.';
$string['newexamsuccess'] = 'Mode examen planificat el {$a->day} des de les {$a->from} fins les {$a->to}';
$string['pluginname'] = 'Mode examen';
$string['roletohideblock'] = 'Rol a afegir per a ocultar els blocks';
$string['roletohideblockdesc'] = 'Rol a assignar a nivell de bloc en el Tauler per a ocultar blocks prohibits (per exemple, el block de fitxers privats)';
$string['roletosystem'] = 'Rol a afegir a nivell de sistema.';
$string['roletosystemdesc'] = 'Rol a afegir en el context de sistema a l\'estudiantat quan estan en mode examen.';
$string['scheduledexammodes'] = 'Examens planificats';
$string['scheduledexammodesdesc'] = 'Pots gestionar els examens planificats per a aquest curs. Recorda que un mode de examen restringeix el que els estudiants porden fer durant el periode de temps definit.';
$string['timefrom'] = 'Inici';
$string['timeto'] = 'Fi';
$string['update_exammode_users'] = 'Actualitza usuaris en mode examen';

$string['error_invalid_state'] = 'Estat per al mode examen invàlid: {$a}';
$string['state'] = 'Estat';
$string['state_configuring'] = 'Configurant';
$string['state_configuringdesc'] = 'El mode d\'examen s\'està configurant actualment.';
$string['state_finished'] = 'Acabat';
$string['state_finisheddesc'] = 'El mode d\'examen ha acabat.';
$string['state_pending'] = 'Pendent';
$string['state_pendingdesc'] = 'El mode d\'examen està pendent de ésser aplicat.';
$string['state_unconfiguring'] = 'Desconfigurant.';
$string['state_unconfiguringdesc'] = 'El mode d\'examen s\'esta configurant ara mateix.';
$string['state_working'] = 'Funcionant';
$string['state_workingdesc'] = 'El mode d\'examen ha sigut configurat completament i està funcionant actualment.';
$string['update_exammode_state_task'] = 'Actualitza l\'estat del modes de examen.';

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

defined('MOODLE_INTERNAL') || die();

$string['backgroundmode'] = 'Skicka uttryck (statements) via schemalagd uppgift?';
$string['backgroundmode_desc'] = 'Detta kommer att tvinga Totara att skicka uttryck (statements) till LRS:et i bakgrunden, via en schemalagd cron-uppgift. Detta för att undvika att Totara-sidor tar för lång tid att ladda. Det innebär mindre av realtids-processning, men kommer hjälpa till att förhindra oförutsägbar prestanda i Totara kopplat till LRS:ets prestanda.';
$string['endpoint'] = 'Endpoint';
$string['filters'] = 'Filtrera loggar';
$string['filters_help'] = 'Aktivera filter som ska INKLUDERA de handlingar som ska loggas';
$string['logguests'] = 'Logga gästers handlingar';
$string['maxbatchsize'] = 'Maxstorlek på batch';
$string['maxbatchsize_desc'] = 'Uttryck (statements) skickas till LRS batchvis. Denna inställning kontrollerar max antal uttryck som skickas i en enskild operation. Om du ställer in noll, så kommer alla tillgängliga uttryck att skickas direkt, detta är dock inte rekommenderat.';
$string['mbox'] = 'Identifiera användare via e-postadress';
$string['mbox_desc'] = 'Uttryck (statements) kommer att identifiera användare via deras e-postadress (mbox), när detta är valt.';
$string['password'] = 'Lösenord';
$string['pluginadministration'] = 'Administration av Logstore xAPI';
$string['pluginname'] = 'Logstore xAPI';
$string['routes'] = 'Inkludera handlingar som innehåller detta';
$string['send_response_choices'] = 'Skicka svarsalternativ';
$string['send_response_choices_desc'] = 'Uttryck (statements) för flervalsfrågor kommer att skickas till LRS, med korrekta svar och tillgängliga svar';
$string['send_username'] = 'Identifiera användare via ID';
$string['send_username_desc'] = 'Uttryck kommer att identifiera användare via deras användarnamn, när detta är valt, men endast om identifiering via e-postadress är bortvalt.';
$string['sendidnumber'] = 'Skicka ID-nummer för kurs och aktivitet';
$string['sendidnumber_desc'] = 'Uttryck kommer att inkludera ID-nummer (definierat av administratör) för kurser och aktiviteter i objektets tillägg (object extensions)';
$string['settings'] = 'Allmänna inställningar';
$string['shortcourseid'] = 'Skicka kursens kortnamn';
$string['shortcourseid_desc'] = 'Uttryck kommer att innehålla kortnamnet för en kurs som ett tillägg för kurs-ID';
$string['submit'] = 'Skicka in';
$string['taskemit'] = 'Sänd poster till LRS';
$string['username'] = 'Användarnamn';
$string['xapi'] = 'xAPI';
$string['xapifieldset'] = 'Exempel på anpassad uppsättning fält';
$string['xapisettingstitle'] = 'Inställningar för Logstore xAPI';

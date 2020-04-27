<?php
/*
 * This file is part of Moodle LMS
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Capability definitions
 *
 * @package    tool_logstorexapi
 * @author     Michael Lynn <michael.lynn@learningpool.com>
 * @copyright  2020 Learning Pool Ltd <http://learningpool.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
        'tool/logstorexapi:viewerrorlog' => array(
            'riskbitmask'  => RISK_CONFIG,
            'captype'      => 'read',
            'contextlevel' => CONTEXT_SYSTEM,
            'archetypes'   => array(
                'manager' => CAP_ALLOW
            )
        ),
        'tool/logstorexapi:manageerrors' => array(
            'riskbitmask'  => RISK_CONFIG,
            'captype'      => 'read',
            'contextlevel' => CONTEXT_SYSTEM,
            'archetypes'   => array(
                'manager' => CAP_ALLOW
            )
        )
);

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

require_once($CFG->libdir.'/formslib.php');

/**
 * The filter form for the xAPI admin reports
 *
 * @copyright 2020 Learning Pool Ltd <https://learningpool.com/>
 * @author Stephen O'Hara <stephen.ohara@learningpool.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package tool_logstore_xapi
 */
class tool_logstore_xapi_reportfilter_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;
        $errortypes = $this->_customdata['errortypes'];
        $eventnames = $this->_customdata['eventnames'];
        $responses = $this->_customdata['responses'];

        $mform->addElement('select', 'errortype', get_string('errortype', 'logstore_xapi'), $errortypes);
        $mform->addElement('select', 'eventname', get_string('eventname', 'logstore_xapi'), $eventnames);
        $mform->addElement('select', 'response', get_string('response', 'logstore_xapi'), $responses);
        $mform->addElement('date_selector', 'datefrom', get_string('from'), ['optional' => true]);
        $mform->addElement('date_selector', 'dateto', get_string('to'), ['optional' => true]);

        $this->add_action_buttons(false, get_string('search'));
        $mform->addElement('submit', 'resendselected', get_string('resendevents', 'logstore_xapi', ['count' => 'loading']));
    }

    /**
     * Form validation
     *
     * @param array $data data from the form.
     * @param array $files files uploaded.
     *
     * @return array of errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if (!empty($data['datefrom']) && !empty($data['dateto'])) {
            if ($data['datefrom'] >= $data['dateto']) {
                $errors['dateto'] = get_string('datetovalidation', 'logstore_xapi');
            }
        }

        return $errors;
    }
}
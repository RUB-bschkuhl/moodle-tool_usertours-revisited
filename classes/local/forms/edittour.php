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
 * Form for editing tours.
 *
 * @package    tool_usertours
 * @copyright  2016 Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usertours\local\forms;

defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');

require_once($CFG->libdir . '/formslib.php');

use tool_usertours\helper;
use tool_usertours\tour;

/**
 * Form for editing tours.
 *
 * @copyright  2016 Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class edittour extends \moodleform {
    /**
     * @var tool_usertours\tour $tour
     */
    protected $tour;

    /**
     * Create the edit tour form.
     *
     * @param   tour        $tour       The tour being editted.
     */
    public function __construct(\tool_usertours\tour $tour) {
        $this->tour = $tour;

        parent::__construct($tour->get_edit_link());
    }

    /**
     * Form definition.
     */
    public function definition() {
        global $PAGE;
        
        $mform = $this->_form;
        
        // Get the current page context to determine available fields
        $context = $PAGE->context;
        $contextlevel = $context->contextlevel;
        
        // Check if we're in a course context vs system/admin context
        $isincoursecontext = ($contextlevel == CONTEXT_COURSE);
        $isinsystemcontext = ($contextlevel == CONTEXT_SYSTEM);
        $isinmodulecontext = ($contextlevel == CONTEXT_MODULE);
        
        //TODO depending on the context (course or site) there should be different fields
        

        // ID of existing tour.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        // Name of the tour.
        $mform->addElement('text', 'name', get_string('name', 'tool_usertours'), ['size' => '80']);
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);
        $mform->addHelpButton('name', 'name', 'tool_usertours');

        // Admin-only descriptions.
        $mform->addElement('textarea', 'description', get_string('description', 'tool_usertours'));
        $mform->setType('description', PARAM_RAW);
        $mform->addHelpButton('description', 'description', 'tool_usertours');

        // Application.
        $mform->addElement('text', 'pathmatch', get_string('pathmatch', 'tool_usertours'), ['size' => '80']);
        $mform->setType('pathmatch', PARAM_RAW);
        $mform->addHelpButton('pathmatch', 'pathmatch', 'tool_usertours');

        $mform->addElement('checkbox', 'ondemand', get_string('ondemand', 'tool_usertours'));
        $mform->addHelpButton('ondemand', 'ondemand', 'tool_usertours');

        $mform->addElement('select', 'trigger_type', get_string('trigger_type', 'tool_usertours'), [
            'button' => get_string('button', 'tool_usertours'),
            'event' => get_string('event', 'tool_usertours'),
        ]);
        $mform->setDefault('trigger_type', 'button');
        $mform->addHelpButton('trigger_type', 'trigger_type', 'tool_usertours');
        $mform->hideIf('trigger_type', 'ondemand', 'notchecked');

         // Add course-specific options if in course context
         if ($isincoursecontext) {
            $test = 0;
            //TODO Add list of event triggers and placements depending on course elements
         }
         
         // Possibly add admin-specific options if in system context
         if ($isinsystemcontext) {
         }
         
         $mform->addElement('select', 'trigger_type_select', get_string('trigger_type_select', 'tool_usertours'), $triggeroptions);
         $mform->setDefault('trigger_type_select', 'event');
         $mform->addHelpButton('trigger_type_select', 'trigger_type_select', 'tool_usertours');
         $mform->hideIf('trigger_type_select', 'ondemand', 'notchecked');
         $mform->hideIf('trigger_type_select', 'trigger_type', 'notchecked');

        //Adds a list of placement options
        $mform->addElement('select', 'trigger_type_event', get_string('trigger_type_event', 'tool_usertours'), [
            //TODO event list placeholder
        ]);
        // TODO $mform->setDefault('trigger_type_event', 'event1');
        $mform->addHelpButton('trigger_type_event', 'trigger_type_event', 'tool_usertours');
        $mform->hideIf('trigger_type_select', 'ondemand', 'notchecked');
        $mform->hideIf('trigger_type_event', 'trigger_type_select', 'notchecked');
        $mform->hideIf('trigger_type_event', 'event', 'neq', 'event');

        //Adds a list of placement options
        $mform->addElement('select', 'trigger_type_button', get_string('trigger_type_button', 'tool_usertours'), [
            //TODO get list of course elements
        ]);
        // TODO $mform->setDefault('trigger_type_button_list', 'placement1');
        $mform->addHelpButton('trigger_type_button', 'trigger_type_button', 'tool_usertours');
        $mform->hideIf('trigger_type_button', 'ondemand', 'notchecked');
        $mform->hideIf('trigger_type_button', 'trigger_type_select', 'notchecked');
        $mform->hideIf('trigger_type_button', 'placement', 'neq', 'placement');

        $mform->addElement('checkbox', 'enabled', get_string('tourisenabled', 'tool_usertours'));

        $mform->addElement('text', 'endtourlabel', get_string('endtourlabel', 'tool_usertours'), ['size' => '80']);
        $mform->setType('endtourlabel', PARAM_TEXT);
        $mform->addHelpButton('endtourlabel', 'endtourlabel', 'tool_usertours');

        $mform->addElement('checkbox', 'displaystepnumbers', get_string('displaystepnumbers', 'tool_usertours'));
        $mform->addHelpButton('displaystepnumbers', 'displaystepnumbers', 'tool_usertours');

        $mform->addElement(
            'select',
            'showtourwhen',
            get_string('showtourwhen', 'tool_usertours'),
            [
                tour::SHOW_TOUR_UNTIL_COMPLETE => get_string('showtouruntilcomplete', 'tool_usertours'),
                tour::SHOW_TOUR_ON_EACH_PAGE_VISIT => get_string('showtoureachtime', 'tool_usertours'),
            ]
        );
        $mform->setDefault('showtourwhen', tour::SHOW_TOUR_UNTIL_COMPLETE);

        // Configuration.
        $this->tour->add_config_to_form($mform);

        // Filters.
        $mform->addElement('header', 'filters', get_string('filter_header', 'tool_usertours'));
        $mform->addElement('static', 'filterhelp', '', get_string('filter_help', 'tool_usertours'));

        foreach (helper::get_all_filters() as $filterclass) {
            $filterclass::add_filter_to_form($mform);
        }

        $this->add_action_buttons();
    }

    #[\Override]
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);

        // Loop through each filter class and merge any validation errors.
        foreach (helper::get_all_filters() as $filterclass) {
            $errors = array_merge($errors, $filterclass::validate_form($data, $files));
        }

        return $errors;
    }
}

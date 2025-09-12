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

namespace tool_usertours;

use core\hook\output\before_footer_html_generation;
use core\hook\navigation\secondary_extend;

/**
 * Hook callbacks for usertours.
 *
 * @package    tool_usertours
 * @copyright  2024 Andrew Lyons <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks
{
    /**
     * Bootstrap the usertours library.
     *
     * @param before_footer_html_generation $hook
     */
    public static function before_footer_html_generation(before_footer_html_generation $hook): void
    {
        \tool_usertours\helper::bootstrap();
    }

    /**
     * Extends secondary navigation
     *
     * @param \core\hook\navigation\secondary_extend $hook
     */
    public static function secondary_extend(\core\hook\navigation\secondary_extend $hook): void
    {
        $secondarynav = $hook->get_secondaryview();
        //TODO depending on context? 
        //TODO edit node to open tour config with params instead
        $node = \navigation_node::create(
            '[i18n-Todo] Teacher Blocks',
            new \moodle_url('/tool/usertours/configure.php'),
            // new \moodle_url('/blocks/teacher_tours/configure.php'),
            \navigation_node::TYPE_CONTAINER,
            null,
            'teachertours-1'
        );
        if (isloggedin()) {
            $secondarynav->add_node($node);
        }
    }
}

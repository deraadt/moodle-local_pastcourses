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
 * Local plugin "Past Courses" - Local Library
 *
 * @package    local_pastcourses
 * @copyright  2019 Michael de Raadt <michaelderaadt@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Add the list of past courses to Moodle's global navigation.
 *
 * @param global_navigation $navigation
 */
function local_pastcourses_extend_navigation(global_navigation $navigation) {
    global $USER, $CFG, $PAGE, $COURSE;

    // Include local library.
    require_once(__DIR__ . '/locallib.php');

    $nodecounter = 1;
    $keepexpanded = 0;

    // Get list of past courses.
    $usercourses = enrol_get_users_courses($USER->id, true, array('enddate'));
    $pastcoures = array_filter($usercourses, function($course) {
        $classify = course_classify_for_timeline($course);
        return $classify == COURSE_TIMELINE_PAST;
    });

    if (!empty($pastcoures)) {

        // Create top custom node.
        $nodetitle = get_string('pastcourses', 'local_pastcourses');
        $pastcouresnode = local_pastcourses_create_custom_node(
            $nodetitle,
            null,
            'pastcourses',
            global_navigation::TYPE_CUSTOM,
            false
        );
        $navigation->add_node($pastcouresnode, null);

        // Add past courses to the navigation.
        foreach ($pastcoures as $key => $pastcourse) {

            // Check if adding the current course.
            if ($pastcourse->id == $COURSE->id) {
                $keepexpanded = 1;
            }

            $courseurl = new moodle_url('/course/view.php', ['id' => $pastcourse->id]);
            $coursenode = local_pastcourses_create_custom_node(
                $CFG->navshowfullcoursenames ? $pastcourse->fullname : $pastcourse->shortname,
                $courseurl,
                'pastcourse'.$nodecounter,
                global_navigation::TYPE_COURSE,
                true
            );
            $nodecounter++;
            $pastcouresnode->add_node($coursenode, null);
        }

        $PAGE->requires->js_call_amd('local_pastcourses/pastcourses', 'init', ['pastcourses', $keepexpanded]);
    }
}

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
 * Add a custom course node to the Boost navigation.
 *
 * @param string $title
 * @param moodle_url $url
 * @param string $key
 * @param int $type
 * @param bool $collapse
 *
 * @return object
 */
function local_pastcourses_create_custom_node($title, $url, $key, $type, $collapse=false) {
    $icon = new pix_icon('i/course', '');
    $customnode = navigation_node::create(
        s(format_string($title)),
        $url,
        $type,
        null,
        $key,
        $icon);
    $customnode->showinflatnavigation = true;
    $customnode->isexpandable = true;
    $customnode->jsenabled = true;
    $customnode->collapse = $collapse;

    return $customnode;
}
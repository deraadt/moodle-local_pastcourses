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
 *             Based on Boost Fumbling (C) 2017 Kathrin Osswald, Ulm University <kathrin.osswald@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {
    "use strict";

    /**
     * Click handler to toggle the given nav node.
     * @param {Object} node The nav node which should be toggled.
     * @param {string} nodename The nav node's nodename.
     */
    function toggleClickHandler(node, nodename) {
        node.click(function(e) {
            // Prevent that the browser opens the node's default action link (if existing).
            e.preventDefault();

            // Toggle expanded/collapsed.
            if (node.attr('data-collapse') == 0) {
                collapseNode(node, nodename);
            } else if (node.attr('data-collapse') == 1) {
                expandNode(node, nodename);

            }
        });
    }

    /**
     * Helper function to collapse the given nav node.
     * @param {Object} node The nav node which should be toggled.
     * @param {string} nodename The nav node's nodename.
     */
    function collapseNode(node, nodename) {
        // Set the parent hidden attribute to true.
        $('.list-group-item[data-parent-key=' + nodename + ']').attr("data-hidden", "1");

        // Change the collapse attribute to true.
        node.attr("data-collapse", "1");

        // Change the aria-expanded attribute to false.
        node.attr("aria-expanded", "0");
    }

    /**
     * Helper function to expand the given nav node.
     * @param {Object} node The nav node which should be toggled.
     * @param {string} nodename The nav node's nodename.
     */
    function expandNode(node, nodename) {
        // Set the parent hidden attribute to false.
        $('.list-group-item[data-parent-key=' + nodename + ']').attr("data-hidden", "0");

        // Change the collapse attribute to false.
        node.attr("data-collapse", "0");

        // Change the aria-expanded attribute to true.
        node.attr("aria-expanded", "1");
    }

    /**
     * Add aria-attributes to a parent node.
     * @param {Object} node The nav node which should get the aria-attributes.
     * @param {string} nodename The nav node's nodename.
     */
    function addAriaToParent(node, nodename) {
        // Add ids to the child nodes for referencing in aria-controls.
        // Initialise string variable to remember the child node ids.
        var ids = '';

        // Get the elements that have the nodename as their data-parent-key attribute.
        $('.list-group-item[data-parent-key=' + nodename + ']').each(function(index, element) {
            var id = $(element).attr('data-key');
            $(element).attr('id', id);
            ids = ids + id + ' ';
        });

        // Add aria-controls attribute if we have ids to reference.
        if (ids !== '') {
            node.attr('aria-controls', ids.trim());
        }

        // Add aria-expanded attribute.
        // If the parent node is currently expanded or collapsed.
        if (node.attr('data-collapse') == 0) {
            node.attr('aria-expanded', '1');
        } else if (node.attr('data-collapse') == 1) {
            node.attr('aria-expanded', '0');
        }
    }

    return {
        init: function(toggleNode, keepExpanded) {

            // Search node to be collapsible.
            var node = $('.list-group-item[data-key="' + toggleNode + '"]');

            // Add a click handler to this node.
            toggleClickHandler(node, toggleNode);

            // Add aria-attributes to this node.
            addAriaToParent(node, toggleNode);

            // Set initial state to collapsed.
            if (!keepExpanded) {
                collapseNode(node, toggleNode);
            }
        }
    };
});
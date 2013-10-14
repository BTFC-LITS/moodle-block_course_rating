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
 * Block for displaying course rating.
 *
 * @package   block_course_rating
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_course_rating extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_course_rating');
    }

    /**
     * Which page types this block may appear on
     * @return array
     */
    public function applicable_formats() {
        return array('course-view' => true);
    }

    public function get_content() {
        global $CFG, $DB, $COURSE, $OUTPUT;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $courseid = $COURSE->id;
        $viewrating = '';
        $link = '';

        // Select rating for course
        $rating = $DB->get_field('block_course_rating', 'rating', array('courseid'=>$courseid));

        // Get context so we can check capabilities.
        $blockcontext = get_context_instance(CONTEXT_COURSE, $courseid);

        if (has_capability('block/course_rating:viewrating', $blockcontext)) {
            $viewrating = true;
        }
        else $viewrating = false;

        if ($viewrating == true) {
            // Reassure teachers that students can't see their course rating
            $note = '<div style="font-size: 0.85em;">' .
                    get_string('notvisible', 'block_course_rating') . '</div>';
        } else {
            $note = '';
        }

        // Display rating for course and link to course ratings explanation
        if ($viewrating == true and $rating == 'Gold' ) {
            $img = '<div style="margin: 6px; text-align:center;"><img src="' .
                    $CFG->wwwroot . '/blocks/course_rating/pix/gold.png"
                    alt="Gold" title="Gold" width="90" height="98" /></div>';
            $link = '<div align="center">' . $OUTPUT->help_icon('ratings_explained',
                    'block_course_rating', get_string('meaning', 'block_course_rating')) .
                    '</div>';
        } else if ($viewrating == true and $rating == 'Silver' ) {
            $img = '<div style="margin: 6px; text-align:center;"><img src="' .
                    $CFG->wwwroot . '/blocks/course_rating/pix/silver.png"
                    alt="Silver" title="Silver" width="90" height="98" /></div>';
            $link = '<div align="center">' . $OUTPUT->help_icon('ratings_explained',
                    'block_course_rating', get_string('improve', 'block_course_rating')) .
                    '</div>';
        } else if ($viewrating == true and $rating == 'Bronze' ) {
            $img = '<div style="margin: 6px; text-align:center;"><img src="' .
                    $CFG->wwwroot . '/blocks/course_rating/pix/bronze.png"
                    alt="Bronze" title="Bronze" width="90" height="98" /></div>';
            $link = '<div align="center">' . $OUTPUT->help_icon('ratings_explained',
                    'block_course_rating', get_string('improve', 'block_course_rating')) .
                    '</div>';
        } else if ($viewrating == true and $rating === '' ) {
            $img = '<div style="margin: 6px; text-align:center;"><img src="' .
                    $CFG->wwwroot . '/blocks/course_rating/pix/in_development.png"
                    alt="In development" title="In development" width="90" height="90" /></div>';
            $link = '<div align="center">' . $OUTPUT->help_icon('ratings_explained',
                    'block_course_rating', get_string('improve', 'block_course_rating')) .
                    '</div>';
        } else {
            $img = '';
            $viewrating = '';
            $link = '';
        }
        $this->content         = new stdClass;
        $this->content->text   = $note . $img . $link;
        return $this->content;
        return $this->content->text;

    }
}
<?php
/**
 * Course navigation functions
 */

function szkolakademia_get_adjacent_lesson($structure, $current_section, $current_chapter, $current_lesson, $direction = 'next') {
    if (!is_array($structure)) {
        return false;
    }

    $sections = array_keys($structure);
    $current_section_index = array_search($current_section, $sections);

    // Get current section data
    $current_section_data = $structure[$current_section];
    $chapters = array_keys($current_section_data['chapters'] ?? []);
    $current_chapter_index = array_search($current_chapter, $chapters);

    // Get current chapter data
    $current_chapter_data = $current_section_data['chapters'][$current_chapter] ?? [];
    $lessons = array_keys($current_chapter_data['lessons'] ?? []);
    $current_lesson_index = array_search($current_lesson, $lessons);

    if ($direction === 'next') {
        // Try next lesson in current chapter
        if ($current_lesson_index !== false && isset($lessons[$current_lesson_index + 1])) {
            return array(
                'section' => $current_section,
                'chapter' => $current_chapter,
                'lesson' => $lessons[$current_lesson_index + 1]
            );
        }

        // Try first lesson in next chapter
        if ($current_chapter_index !== false && isset($chapters[$current_chapter_index + 1])) {
            $next_chapter = $chapters[$current_chapter_index + 1];
            $first_lesson = array_key_first($structure[$current_section]['chapters'][$next_chapter]['lessons'] ?? []);
            if ($first_lesson !== null) {
                return array(
                    'section' => $current_section,
                    'chapter' => $next_chapter,
                    'lesson' => $first_lesson
                );
            }
        }

        // Try first lesson in first chapter of next section
        if ($current_section_index !== false && isset($sections[$current_section_index + 1])) {
            $next_section = $sections[$current_section_index + 1];
            $first_chapter = array_key_first($structure[$next_section]['chapters'] ?? []);
            if ($first_chapter !== null) {
                $first_lesson = array_key_first($structure[$next_section]['chapters'][$first_chapter]['lessons'] ?? []);
                if ($first_lesson !== null) {
                    return array(
                        'section' => $next_section,
                        'chapter' => $first_chapter,
                        'lesson' => $first_lesson
                    );
                }
            }
        }
    } else {
        // Try previous lesson in current chapter
        if ($current_lesson_index !== false && isset($lessons[$current_lesson_index - 1])) {
            return array(
                'section' => $current_section,
                'chapter' => $current_chapter,
                'lesson' => $lessons[$current_lesson_index - 1]
            );
        }

        // Try last lesson in previous chapter
        if ($current_chapter_index !== false && isset($chapters[$current_chapter_index - 1])) {
            $prev_chapter = $chapters[$current_chapter_index - 1];
            $last_lesson = array_key_last($structure[$current_section]['chapters'][$prev_chapter]['lessons'] ?? []);
            if ($last_lesson !== null) {
                return array(
                    'section' => $current_section,
                    'chapter' => $prev_chapter,
                    'lesson' => $last_lesson
                );
            }
        }

        // Try last lesson in last chapter of previous section
        if ($current_section_index !== false && isset($sections[$current_section_index - 1])) {
            $prev_section = $sections[$current_section_index - 1];
            $last_chapter = array_key_last($structure[$prev_section]['chapters'] ?? []);
            if ($last_chapter !== null) {
                $last_lesson = array_key_last($structure[$prev_section]['chapters'][$last_chapter]['lessons'] ?? []);
                if ($last_lesson !== null) {
                    return array(
                        'section' => $prev_section,
                        'chapter' => $last_chapter,
                        'lesson' => $last_lesson
                    );
                }
            }
        }
    }

    return false;
} 
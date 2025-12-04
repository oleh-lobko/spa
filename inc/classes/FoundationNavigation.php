<?php

namespace theme;

/**
 * Class FoundationNavigation.
 */
class FoundationNavigation extends \Walker_Nav_Menu
{
    /**
     * Adds custom class to dropdown menu for foundation dropdown script.
     *
     * @param string $output Used to append additional content (passed by reference)
     * @param int $depth Depth of menu item. Used for padding.
     * @param \stdClass $args An object of wp_nav_menu() arguments
     */
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n{$indent}<ul class=\"menu submenu\">\n";
    }

    /**
     * Adds custom class to parent item with dropdown menu.
     *
     * @param object $element Data object
     * @param array $children_elements List of elements to continue traversing
     * @param int $max_depth Max depth to traverse
     * @param int $depth Depth of current element
     * @param array $args Arguments
     * @param string $output Passed by reference. Used to append additional content.
     *
     * @return null null on failure with no changes to parameters
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        $id_field = $this->db_fields['id'];
        if (!empty($children_elements[$element->{$id_field}])) {
            $element->classes[] = 'has-dropdown';
        }
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}

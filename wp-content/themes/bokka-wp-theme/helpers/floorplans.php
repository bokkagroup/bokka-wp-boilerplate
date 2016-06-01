<?php
/**
 * Takes a title and returns a description
 * @param $title
 * @return string
 */
function get_feature_description($title){
    switch ($title) {
        case 'Main Floor Living':
        case 'Main Floor Laundry':
            return 'No Need for Steps';
            break;
        case 'Loft':
        case 'Open Great Room':
            return 'For Entertaining Guests';
            break;
        case 'Outdoor Living Space':
            return 'Enjoy Colorado\'s Landscapes';
            break;
        case 'Formal Dining Room':
            return 'No Need for Steps';
            break;
        case 'Finished Lower Level':
            return 'Plenty of Room for Toys';
            break;
        case 'Lower Level Storage Space':
            return 'Plenty of Room for Toys';
            break;
        case 'Dual Master Suites':
        case 'Flex Room or Study':
            return 'You Choose How to Use Your Space';
            break;
        case 'Two Main Floor Bedroom Suites':
            return 'No Need for Steps';
            break;
    }
}


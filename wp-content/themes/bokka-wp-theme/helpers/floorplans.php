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
        case 'Outdoor Living Space':
        case 'Formal Dining Room':
        case 'Finished Lower Level':
        case 'Loft':
            return 'For Entertaining Guests';
            break;
        case 'Lower Level Storage Space':
        case '3 Car Garage':
            return 'Plenty of Room for Toys';
            break;
        case 'Dual Master Suites':
        case 'Flex Room or Study':
        case 'Two Main Floor Bedroom Suites':
            return 'Personalize your Space';
            break;
    }
}





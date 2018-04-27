<?php
/**
 * Takes a title and returns a description
 * @param $title
 * @return string
 */
function get_feature_description($title)
{
    switch ($title) {
        case 'Main Floor Living':
            return 'Your knees will thank you.';
            break;
        case 'Main Floor Laundry':
            return 'No Need for Steps.';
            break;
        case 'Open Great Room':
            return 'Perfect for entertaining.';
            break;
        case 'Outdoor Living Space':
            return 'Soak up the Colorado sun.';
            break;
        case 'Formal Dining Room':
            return 'For the holidays, and everydays.';
            break;
        case 'Finished Lower Level':
            return 'Great for guests or grandkids.';
            break;
        case 'Loft':
            return 'A quiet retreat away from it all.';
            break;
        case 'Lower Level Storage Space':
            return 'Extra space for extra stuff.';
            break;
        case '3 Car Garage':
            return 'Room for storage. Or a sports car.';
            break;
        case 'Flex Room or Study':
            return 'Space to use as you choose.';
            break;
        case 'Two Main Floor Bedrooms':
            return 'Sleep, snooze, and snore in comfort.';
            break;
        case 'Simplified Living':
            return 'No more than you need.';
            break;
        case 'Smaller Home, Fuller Life':
            return 'The balance you seek.';
            break;
        case 'Energy Efficient':
            return 'Spend less, so you can do more.';
            break;
        case '2-Car Garage':
            return 'Space for cars, toys, or hobbies.';
            break;
        case 'Attached Garage':
            return 'Convenience at your back door.';
            break;
    }
}

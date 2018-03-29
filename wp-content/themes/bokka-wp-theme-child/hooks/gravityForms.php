<?php

/**
 * Create a combined "plan - neighborhood" tag value that gets added
 * to the additional tags input.
 */
add_action('gform_pre_submission_38', 'modify_tag_values');
function modify_tag_values($form)
{
    // Floorplan - Get Brochure Form
    if (isset($form['id']) && $form['id'] == 38) {
        $plan = $_POST['input_12'];
        $neighborhood = $_POST['input_13'];

        if (isset($_POST['input_19']) && $plan && $neighborhood) {
            $_POST['input_19'] = ($_POST['input_19']) ? $_POST['input_19'] . ', ' . $plan . ' - ' . $neighborhood : $plan . ' - ' . $neighborhood;
        }
    }
}

add_filter('gform_notification_38', 'add_attachment_pdf', 10, 3); //target form id 2, change to your form id
function add_attachment_pdf($notification, $form, $entry)
{
    //There is no concept of user notifications anymore, so we will need to target notifications based on other criteria,
    //such as name or subject
    if ($notification['name'] == 'User Notification') {
        //get upload root for WordPress
        if (isset($entry[7])) {
            $file = get_field('pdf', $entry[7]);

            $path = get_attached_file($file);


            if (file_exists($path)) {
                $notification['attachments']   = rgar($notification, 'attachments', array());
                $notification['attachments'][] = $path;
                GFCommon::log_debug(__METHOD__ . '(): file added to attachments list: ' . print_r($notification['attachments'], 1));
            } else {
                GFCommon::log_debug(__METHOD__ . '(): not attaching; file does not exist.');
            }
        }
    }
    //return altered notification object
    return $notification;
}

// Virtual pageviews for non-ajax forms being directed to pages without 'thank you' in the URL
add_action('gform_after_submission', 'gform_after_submission', 10, 2);
function gform_after_submission($entry, $form)
{
    $path = '';
    $title = '';

    if (isset($form['id']) && $form['id'] == 35) {
        $path = '/thank-you/campaigns/northern-colorado-ppc';
        $title = 'Northern Colorado Patio Homes and Townhomes | Boulder Creek Neighborhoods';
    } elseif (isset($form['id']) && $form['id'] == 36) {
        $path = '/thank-you/campaigns/boulder-ppc';
        $title = 'Boulder Patio Homes and Townhomes | Boulder Creek Neighborhoods';
    } elseif (isset($form['id']) && $form['id'] == 34) {
        $path = '/thank-you/campaigns/denver-ppc';
        $title = 'Denver Patio Homes and Townhomes | Boulder Creek Neighborhoods';
    }

    if ($path && $title) {
        ga_send_event($path, $title);
    }
}

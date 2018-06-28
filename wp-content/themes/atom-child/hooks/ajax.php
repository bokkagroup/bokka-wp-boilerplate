<?php

function ajax_send_hatchbuck()
{
    $data = isset($_POST['data']) ? $_POST['data'] : false;

    if ($data) {
        $hatchbuck = new BokkaWP\Hatchbuck();
        $contact = $hatchbuck->findContact($data);
        $contact = isset($contact[0]) ? $contact[0] : false;

        if ($contact && !empty($contact->contactId)) {
            $tags = array();

            // All values except email and pdfSrc are sent as tags
            unset($data['email']);
            unset($data['pdfSrc']);
            // Create additional "plan - neighborhood" tag
            $data[] = $data['pageName'] . ' - ' . $data['neighborhood'];
            // Get default plan product type
            $data['productType'] = getDefaultType($data['productType']);

            foreach ($data as $key => $value) {
                $tags[] = array(
                    'name' => $value
                );
            }

            $updated_contact = $hatchbuck->addTag($contact->contactId, $tags);
        }
    }

    wp_die();
}

add_action('wp_ajax_send_hatchbuck', 'ajax_send_hatchbuck');
add_action('wp_ajax_nopriv_send_hatchbuck', 'ajax_send_hatchbuck');

/**
 * Ajax action to send a plan brochure email
 */
function ajax_send_brochure_email()
{
    $data = isset($_POST['data']) ? $_POST['data'] : false;

    if ($data && isset($data['email']) && isset($data['pdfSrc'])) {
        $headers = array('From: hello@livebouldercreek.com');
        $email = $data['email'];
        $subject = "Here's your brochure!";
        $attachment = $data['pdfSrc'];
        $message = "Thank you for your interest! Here is a copy of the brochure for easy reference. Please let me know if you have any questions or would like to see more.<br/><br/>
                    Thanks,<br/>
                    The Boulder Creek Online Team<br/>
                    720-636-7088";

        add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
        $email = wp_mail($email, $subject, $message, $headers, $attachment);
        remove_filter('wp_mail_content_type', 'set_html_content_type');

        echo $email;
    } else {
        echo false;
    }

    wp_die();
}

add_action('wp_ajax_send_brochure_email', 'ajax_send_brochure_email');
add_action('wp_ajax_nopriv_send_brochure_email', 'ajax_send_brochure_email');

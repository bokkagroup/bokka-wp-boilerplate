<?php

namespace BokkaWP;

class Hatchbuck
{
    private $api_key;
    private $source_id;
    private $lead;

    public function __construct()
    {
        $api_key = get_field('hatchbuck_api_key', 'option');
        $source_id = get_field('hatchbuck_lead_source_id', 'option');

        if ($api_key && $source_id) {
            $this->api_key = $api_key;
            $this->source_id = $source_id;
        } else {
            return null;
        }
    }

    public function startCampaign($contact_id, $campaign_id)
    {
        //build params to query for lead
        $params = array(
            'url'     => 'https://api.hatchbuck.com/api/v1/contact/' . $contact_id . '/Campaign',
            'request' => 'POST',
            'data'    => array(
                (object) array(
                    'id' => $campaign_id
                )
            )
        );

        //perform query and return
        return $this->execute($params);
    }

    public function addTag($contact_id, $leadTags)
    {
        //build params to query for lead
        $params = array(
            'url'     => 'https://api.hatchbuck.com/api/v1/contact/' . $contact_id . '/Tags',
            'request' => 'POST',
            'data'    => $leadTags
        );

        //perform query and return
        return $this->execute($params);
    }

    public function findContact($lead_data)
    {
        if ($lead_data) {
            $lead = $this->formatLeadData($lead_data);
        } else {
            return null;
        }

        $existing_lead = $this->find($lead);

        if (is_array($existing_lead)) {
            return $existing_lead;
        }

        return null;
    }

    public function save($lead)
    {
        //check for existing lead
        $existing_lead = $this->find($lead);

        //if it exists, update it, if not create it
        if (is_array($existing_lead)) {
            //convert to array
            $existing_lead = (array) $existing_lead[0];

            //remove empty fields
            $existing_lead = array_filter($existing_lead);
            $lead          = array_filter($lead);

            //merge new values with old and convert back to object
            $lead = (object) array_merge($existing_lead, $lead);

            //call update
            return $this->update($lead);
        } else {
            //remove empty fields
            $lead = array_filter($lead);

            //convert lead array to object
            $lead = (object) $lead;

            //call create with lead data from form
            return $this->create($lead);
        }
    }

    private function formatLeadData($lead_data)
    {
        $lead = array(
            'emails' => []
        );

        $lead['emails'][] = array(
            'type' => 'Work',
            'address' => $lead_data['email'],
        );

        $lead['source'] = array(
            'id' => $this->source_id
        );

        return $lead;
    }

    private function create($lead)
    {
        //build params to query for lead
        $params = array(
            'url'     => 'https://api.hatchbuck.com/api/v1/contact',
            'request' => 'POST',
            'data'    => $lead
        );

        //perform query and return
        return $this->execute($params);
    }

    private function update($lead)
    {
        //build params to query for lead
        $params = array(
            'url'     => 'https://api.hatchbuck.com/api/v1/contact',
            'request' => 'PUT',
            'data'    => $lead
        );

        //perform query and return
        return $this->execute($params);
    }

    private function find($lead)
    {
        //build params to query for lead
        $params = array(
            'url'     => 'https://api.hatchbuck.com/api/v1/contact/search',
            'request' => 'POST',
            'data'    => array(
                'emails' => $lead['emails']
            )
        );

        //perform query and return
        return $this->execute($params);
    }

    private function execute($params)
    {
        //format as json
        $data = json_encode($params['data']);

        //retrieve info from options panel
        $url     = $params['url'] . '?api_key=' . $this->api_key;

        //make curl connection
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $params['request']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));

        //execute curl request
        $response = curl_exec($ch);

        //close curl connection
        curl_close($ch);

        //return results
        return json_decode($response);
    }
}

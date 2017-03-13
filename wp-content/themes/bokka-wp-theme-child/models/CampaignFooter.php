<?php

namespace BokkaWP\Theme\models;

class CampaignFooter extends \BokkaWP\MVC\Model
{
    public $campaign_form;

    public function initialize()
    {
        global $post;

        $this->year = date("Y");
        $this->import($post);

        if (isset($this->campaign_form)) {
            $this->setForm($post, $this->campaign_form['id']);
        }
    }

    private function setForm($post, $id)
    {
        $form = gravity_form($id, false, false, false, null, $ajax = true, 0, false);
        $this->campaign_form = $form;
    }

    private function import(\WP_Post $post)
    {
        foreach (get_object_vars($post) as $key => $value) {
            $this->$key = $value;
        }
    }
}

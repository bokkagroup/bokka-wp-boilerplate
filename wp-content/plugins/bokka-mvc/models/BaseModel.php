<?php
namespace BokkaWP\MVC;
Class Model {

    public $data = array();

    public function __construct($post_id = null)
    {
        if (class_exists('acf')) {
            $this->attachACFFields($post_id);
        }
        $this->initialize($post_id);
    }

    /**
     * Checks to see if there are associated ACF fields and creates members for them
     * @param $post_id
     * @return $this
     */
    private function attachACFFields($post_id)
    {

        if (!isset($post_id)) {
            global $post;
            $post_id = $post->ID;
        }

        $fields = get_fields($post_id);

        foreach ($fields as $field_name => $value) {
		    $this->$field_name = $value;
	    }
        return $this;
    }
}
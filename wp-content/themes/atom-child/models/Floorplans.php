<?php
namespace CatalystWP\AtomChild\models;

class Floorplans extends \CatalystWP\Nucleus\Model
{


    public function initialize()
    {
        $this->facets_template = $this->setFacetsTemplate();
        $this->facets = $this->setFacets();
        $this->count = do_shortcode("[facetwp counts='true']");
    }




    private function setFacets()
    {
        $array = [];
        $array[0]['label'] = 'Base Price:';
        $array[0]['facet'] = do_shortcode("[facetwp facet='floorplan_price']");
        $array[1]['facet'] = do_shortcode("[facetwp facet='layout_style']");
        $array[2]['facet'] = do_shortcode("[facetwp facet='home_type']");
        $array[3]['facet'] = $this->generateLocation();

        return $array;
    }

    private function setFacetsTemplate()
    {
        return do_shortcode("[facetwp template='floorplans']");
    }

    private function generateLocation()
    {
        $neighborhoods = get_posts(array('post_type' => 'communities', 'posts_per_page' => 500));
        $cities = array_unique(
            array_map(function ($neighborhood) {
                return $neighborhood->city;
            }, $neighborhoods)
        );

        $string = '<div class="facet-wrapper">';
        $string .= '<select class="dropdown floorplans-location">';
        $string .= '<option value="" selected>Location</option>';
        foreach ($cities as $city) {
            $string .= "<option value='${city}'>${city}</option>";
        }

        $string .= '</select>';
        $string .= '</div>';

        return $string;
    }
}

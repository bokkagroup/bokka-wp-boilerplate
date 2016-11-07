<?php

namespace BokkaWP\Theme\models;

class Breadcrumbs extends \BokkaWP\MVC\Model
{

    public function localizeData($postIds)
    {
        $breadcrumbData = array_map(function ($postId) {
            $post = get_post($postId);
            $crumb["{$post->post_name}"] = array(
                'title' => get_the_title($post),
                'link' => get_permalink($post),
                'class' => 'icon icon-our-neighborhoods'
            );
            return $crumb;
        }, $postIds);

        if (count($breadcrumbData) > 0) {
            return json_encode($breadcrumbData);
        } else {
            return false;
        }
    }

    public function initialize()
    {
        if (is_singular(array('plans'))) {
            $this->data = $this->floorplans();
        } elseif (is_singular(array('model'))) {
            $this->data = $this->modelHomes();
        } elseif (is_singular(array('home'))) {
            $this->data = $this->homes();
        } elseif (is_page('our-neighborhoods') ||
            is_page('quick-move-in-homes') ||
            is_page('model-homes')) {
            $this->data = $this->neighborhoodOverview();
            $this->data['productOverviewJSON'] = $this->localizeData(array(54, 58, 60));
        } elseif (is_singular(array('communities'))) {
            $this->data = $this->neighborhoods();
        } elseif (is_page('ask-a-question') ||
            is_page('our-locations-sales-centers-models') ||
            is_page('homeowner-resources')) {
            $this->data = $this->askAQuestion();
        } else {
            $this->data = $this->singlePage();
        }
    }

    /**
     * Generates an array of links and their title
     * @return array
     */
    public function singlePage()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => get_the_title($post),
                'link' => get_permalink($post)
            )
        );
    }
    
    /* Floorplans */
    public function floorplans()
    {
        global $post;
        $obj = get_post_type_object(get_post_type($post));
        $postfix = $obj->labels->singular_name;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => get_the_title($post->neighborhood),
                'link' => get_permalink($post->neighborhood),
                'class' => 'icon icon-our-neighborhoods'
            ),
            array(
                'title' => 'Floorplans',
                'link' => get_permalink($post->neighborhood) . "#tab-floorplans"
            ),
            array(
                'title' => $post->post_title .' '. $postfix,
                'link' => '#',
                'class' => 'icon icon-our-homes'
            )
        );
    }

    /* Model Homes */
    public function modelHomes()
    {
        global $post;
        $obj = get_post_type_object(get_post_type($post));
        $postfix = $obj->labels->singular_name;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => get_the_title($post->neighborhood),
                'link' => get_permalink($post->neighborhood),
                'class' => 'icon icon-our-neighborhoods'
            ),
            array(
                'title' => 'Model Homes',
                'link' => get_permalink($post->neighborhood) . "#tab-model-homes"
            ),
            array(
                'title' => $post->post_title .' '. $postfix,
                'link' => '#',
                'class' => 'icon icon-our-homes'
            )
        );
    }

    /* Quick Move-in Homes */
    public function homes()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => get_the_title($post->neighborhood),
                'link' => get_permalink($post->neighborhood),
                'class' => 'icon icon-our-neighborhoods'
            ),
            array(
                'title' => 'Quick Move-In Homes',
                'link' => get_permalink($post->neighborhood) . "#tab-qmi-homes"
            ),
            array(
                'title' => $post->post_title,
                'link' => '#',
                'class' => 'icon icon-our-homes'
            )
        );
    }

    public function neighborhoodOverview()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => get_the_title($post->neighborhood),
                'link' => get_permalink($post->neighborhood),
                'class' => 'icon icon-our-neighborhoods'
            )
        );
    }

    public function neighborhoods()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Our Neighborhoods',
                'link' => '/our-neighborhoods',
                'class' => 'icon icon-our-neighborhoods'
            ),
            array(
                'title' => get_the_title($post->neighborhood),
                'link' => get_permalink($post->neighborhood),
                'class' => 'icon icon-our-neighborhoods'
            )
        );
    }

    public function askAQuestion()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Contact Us',
                'link' => get_permalink($post),
                'class' => 'icon icon-contact-us'
            ),
            array(
                'title' => $post->post_title,
                'link' => get_permalink($post)
            )
        );
    }
}

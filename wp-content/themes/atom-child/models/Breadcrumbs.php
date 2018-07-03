<?php

namespace CatalystWP\AtomChild\models;

class Breadcrumbs extends \CatalystWP\Nucleus\Model
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
            is_page('our-locations') ||
            is_page('homeowner-resources')) {
            $this->data = $this->askAQuestion();
        } elseif (is_post_type_archive('blog-post')) {
            $this->data = $this->blogArchive();
        } elseif (is_singular(array('blog-post'))) {
            $this->data = $this->blogSingle();
        } elseif (is_tax('blog-post-category')) {
            $this->data = $this->blogTax();
        } elseif (is_post_type_archive('career')) {
            $this->data = $this->careerArchive();
        } elseif (is_singular(array('career'))) {
            $this->data = $this->careerSingle();
        } elseif (is_tax('career-category')) {
            $this->data = $this->careerTax();
        } elseif (is_post_type_archive('event')) {
            $this->data = $this->eventArchive();
        } elseif (is_singular(array('event'))) {
            $this->data = $this->eventSingle();
        } elseif (is_tax('event-category')) {
            $this->data = $this->eventTax();
        } elseif (is_post_type_archive('testimonial')) {
            $this->data = $this->testimonialArchive();
        } elseif (is_post_type_archive('plans')) {
            $this->data = $this->plansArchive();
        } elseif (is_singular(array('testimonial'))) {
            $this->data = $this->testimonialSingle();
        } elseif (is_tax('testimonial-category')) {
            $this->data = $this->testimonialTax();
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

        if (is_a($post, 'WP_Post')) {
            $post_parent = get_post($post->post_parent);
        }

        if (is_page() && ($post_parent && strlen($post_parent->post_content) > 1)) {
            // check if page has a parent page
            return array(
                array(
                    'title' => 'Home',
                    'link' => '/'
                ),
                array(
                    'title' => get_the_title($post->post_parent),
                    'link' => get_permalink($post->post_parent)
                ),
                array(
                    'title' => get_the_title($post),
                    'link' => get_permalink($post)
                )
            );
        } else {
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
    }

    /* Floorplans */
    public function floorplans()
    {
        global $post;
        $obj = get_post_type_object(get_post_type($post));
        $postfix = $obj->labels->singular_name;
        $post->post_title = (get_field('display_title', $post->ID)) ? get_field('display_title', $post->ID) : $post->post_title;

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
                'class' => ''
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

    public function blogSingle()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Blog',
                'link' => '/blog',
                'class' => 'icon icon-blog'
            ),
            array(
                'title' => $post->post_title,
                'link' => get_permalink($post)
            )
        );
    }

    public function blogArchive()
    {
        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Blog',
                'link' => '/blog',
                'class' => 'icon icon-blog'
            )
        );
    }

    public function blogTax()
    {
        global $wp_query;
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $term_link = get_term_link($term);

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Blog',
                'link' => '/blog',
                'class' => 'icon icon-blog'
            ),
            array(
                'title' => $term->name,
                'link' => $term_link
            )
        );
    }

    public function careerSingle()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Career Opportunities',
                'link' => '/career-opportunities',
                'class' => 'icon icon-careers'
            ),
            array(
                'title' => $post->post_title,
                'link' => get_permalink($post)
            )
        );
    }

    public function careerArchive()
    {
        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Career Opportunities',
                'link' => '/career-opportunities',
                'class' => 'icon icon-careers'
            )
        );
    }

    public function careerTax()
    {
        global $wp_query;
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $term_link = get_term_link($term);

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Career Opportunities',
                'link' => '/career-opportunities',
                'class' => 'icon icon-careers'
            ),
            array(
                'title' => $term->name,
                'link' => $term_link
            )
        );
    }

    public function eventSingle()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Events',
                'link' => '/events',
                'class' => 'icon icon-events'
            ),
            array(
                'title' => $post->post_title,
                'link' => get_permalink($post)
            )
        );
    }

    public function eventArchive()
    {
        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Events',
                'link' => '/events',
                'class' => 'icon icon-events'
            )
        );
    }

    public function eventTax()
    {
        global $wp_query;
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $term_link = get_term_link($term);

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Events',
                'link' => '/events',
                'class' => 'icon icon-events'
            ),
            array(
                'title' => $term->name,
                'link' => $term_link
            )
        );
    }

    public function testimonialSingle()
    {
        global $post;

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Testimonials',
                'link' => '/testimonials',
                'class' => 'icon icon-testimonials'
            ),
            array(
                'title' => $post->post_title,
                'link' => get_permalink($post)
            )
        );
    }

    public function plansArchive()
    {
        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'New Home Search',
                'class' => 'icon icon-our-homes',
                'link' => '/new-home-search'
            )
        );
    }


    public function testimonialArchive()
    {
        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Testimonials',
                'link' => '/testimonials',
                'class' => 'icon icon-testimonials'
            )
        );
    }

    public function testimonialTax()
    {
        global $wp_query;
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $term_link = get_term_link($term);

        return array(
            array(
                'title' => 'Home',
                'link' => '/'
            ),
            array(
                'title' => 'Testimonials',
                'link' => '/testimonials',
                'class' => 'icon icon-testimonials'
            ),
            array(
                'title' => $term->name,
                'link' => $term_link
            )
        );
    }
}

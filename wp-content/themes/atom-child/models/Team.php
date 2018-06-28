<?php
namespace CatalystWP\AtomChild\models;

class Team extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        global $post;
        $this->setTeam($post);
        $this->data = $post;
    }

    private function setTeam($post)
    {
        $departments = get_field('departments');
        $departments = array_map(function ($department) {
            $department['department_slug'] = sanitize_title($department['department']);
            foreach ($department['team_members'] as &$member) {
                if (isset($member['photo']) && $member['photo']) {
                    $member['photo'] = wp_get_attachment_image_src($member['photo'], 'medium-square')[0];
                } else {
                    $member['photo'] = '';
                }
            }
            return $department;
        }, $departments);

        $post->departments = $departments;
    }
}

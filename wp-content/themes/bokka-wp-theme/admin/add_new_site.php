<?php
class NewSiteForm {
    public function __construct()
    {
        $this->setFields();
        add_action('network_site_new_form', array($this, 'add_form_fields'));
        add_action( 'wpmu_new_blog', array($this, 'add_new_blog_field'));
    }

    private function setFields()
    {
        $this->fields = array(
            'homeowner' => array(
                array(
                    'name' => 'homeowner_first_name',
                    'type' => 'text',
                    'label'=> 'First Name'
                ),
                array(
                    'name' => 'homeowner_last_name',
                    'type' => 'text',
                    'label'=> 'Last Name'
                ),
                array(
                    'name' => 'homeowner_address_1',
                    'type' => 'text',
                    'label'=> 'Address 1'
                ),
                array(
                    'name' => 'homeowner_phone',
                    'type' => 'phone',
                    'label'=> 'Phone'
                ),
                array(
                    'name' => 'homeowner_email',
                    'type' => 'email',
                    'label'=> 'Email'
                ),
                array(
                    'name' => 'homeowner_floorplan',
                    'type' => 'text',
                    'label'=> 'Floorplan',
                )
            ),
            'sales_manager' => array(
                array(
                    'name' => 'sales_manager_name',
                    'type' => 'name',
                    'label'=> 'Name'
                ),
                array(
                    'name' => 'sales_manager_email',
                    'type' => 'email',
                    'label'=> 'Email'
                ),
            )
        );
    }

    public function add_form_fields()
    {
        if (isset($this->fields['site_manager'])) {
            echo "<h2>Sales Manager Info</h2>
            <table class='form-table'>";
            foreach ($this->fields['site_manager'] as $field) {
                echo "<tr class='form-field form-required'>
                    <td>
                        <th>
                            <label for='first_name'>${field[label]}</label>
                        </th>
                    </td>
                    <td>
                        <input type='${field[type]}' name='blog[${field[name]}]' placeholder='${field[label]}'/>
                    </td>
                </tr>";
            }
            echo "</table>";
        }

        if (isset($this->fields['homeowner'])) {
            echo "<h2>Homeowner Info</h2>
            <table class='form-table'>";
            foreach ($this->fields['homeowner'] as $field) {
                echo "<tr class='form-field form-required'>
                    <td>
                        <th>
                            <label for='${field[name]}'>${field[label]}</label>
                        </th>
                    </td>
                    <td>
                        <input type='${field[type]}' name='blog[${field[name]}]' placeholder='${field[label]}'/>
                    </td>
                </tr>";
            }
            echo "</table>";
        }
    }

    public function add_new_blog_field($blog_id)
    {
        // Make sure the user can perform this action and the request came from the correct page.
        switch_to_blog($blog_id);

        // Use a default value here if the field was not submitted.
        foreach ($this->fields as $fieldgroup) {
            foreach($fieldgroup as $field) {
                $field_value = '';

                if (!empty($_POST['blog'][$field['name']])) {
                    $field_value = $_POST['blog'][$field['name']];
                }

                // save option into the database
                update_option($field['name'], $field_value);
            }
        }
        restore_current_blog();
    }
}

new NewSiteForm();


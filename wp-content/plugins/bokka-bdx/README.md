# Bokka BDX  Wordpress Plugin

This plugin is intended to send data to the Bokka-BDX-Service. It provides data mapping configuration form within the admin. Allowing us to easily map what data exists in the database to the structure the BDX Service is expecting.

This form is generated via a configuration file called fields.php. This configuration allows for a variety of field types for retrieving data located in different areas, such as a post title or an ACF field. 

You may find that a field type may not fit your needs. Or you want to further process the data that is extracted from the DB. There are a couple types of hooks similar to wordpress's action/filter hooks. 

---
### Table of Contents
 * [Installation][]
 * [Field Types](#field-types)
 * [Hooks](#hooks)
 * [Examples](#examples)
 ---
 
## Installation

## Field Types

#### Text|Phone|Email|Number
A simple plain text input field
```php
array(
    "Label" =>  "Simple Field",
    "name"  =>  "fieldname",
    "type"  =>  "text|phone|email|number"
)
```
#### Password
A plain input text that visually obfuscates the text. note: the text is not encrypted on save
```php
array(
    "Label" =>  "A Password Field",
    "name"  =>  "fieldname",
    "type"  =>  "password"
)
```

#### Post Field
This field allows you choose the content associated with a specific post. This field is best used as a child field to the post_type field. Should not be used on root level of configuration. 

Available options: ID, post_title, post_content, featured_image, attachments, permalink

```php
array(
    "Label" =>  "A Post Field",
    "name"  =>  "fieldname",
    "type"  =>  "post_field"
)
```

#### State
A drop down that allows selection of 50 US state name abbreviations.
```php
array(
    "Label" =>  "Select a State",
    "name"  =>  "fieldname",
    "type"  =>  "state"
)
```

#### ACF
This field allows you to select an ACF field associated with a post type. This field should not be used on the root level of a configuration. Best used as a child of a post_type field.
```php
array(
    "Label" =>  "An ACF Field",
    "name"  =>  "fieldname",
    "type"  =>  "acf"
)
```

#### Post Type
The post type field provides a drop down of all the registered post types. This field is special because it allows for child fields. The post type field is also special in the way it saves it's value.

The name of the post type field is used as a name space for each of it's child fields as well as it's value. Represented in an array as `fields['post_type_field_name']['value'] = "The actual value of which post type is selected"` thus a child field can save it's value as `fields['post_type_field_name']['child_field_name'] = array()` along side the value of the post_type_field. Understanding this structure is fundamental to understanding how this plugin works.

```php
array(
    "Label"     =>  "Post Type Field",
    "name"      =>  "fieldname",
    "type"      =>  "post_type",
    "fields"    =>  array()
)
```

#### Parent
This field allows users to create a namespace where a value is not needed. Ultimately this field will not generate any type of input field. A great example of usecase is a geolocation. Usually you would have two ACF fields for your geolocation `lat` & `long`. If you need an object with the following structure  `{"geolocation" : { "lat":12345, "long":12345}}` you can use the parent field to give you the "geolocation" top level namespace. This field, like the post_type field, accepts an array of child fields. 

```php
array(
    "Label"     =>  "Parent Field",
    "name"      =>  "fieldname",
    "type"      =>  "parent",
    "fields"    =>  array()
)
```

#### ACF Relationship
The ACF relationship fields allows you to establish replationships for posts. This field is ideally used when you have nested post type fields. In general the plugin traverses the fields array one level at a time. When processing a post_type field, it will check for a child field that is an acf-relationship field. If present it will get the value of the field selected and pass it to a filter, allowing you to filter out posts that aren't associated with eachother. This is better demonstrated in the examples. 

```php
array(
    "Label"     =>  "Relationship",
    "name"      =>  "fieldname",
    "type"      =>  "acf-relationship",
)
```

---
## Hooks

#### bdx_add_filter(string _filtername_, callable _callable_)
This function allows us to register our filters, the signature is nearly identical to the way we register wordpress hooks.

#### Filters available

##### bdx-adapter-configuration
This hook is used to modify the final output of the data retrieved from the database. It expects the value to be the return value of the function. This filter is run once for each field. 

```php
//register our function just like a wordpress hook
\BokkaWP\bdx_add_filter('bdx-adapter-configuration','convertData');

//our function
function convertData($configuration, $options)
{
    return $options['value'];
}
```

##### bdx-adapter-relationship-filter
This hook is used to filter out relationships. As the post type field loops through each post it will run this hook. Expects a true or false value to be returned. If you want a post to be filtered out return false.

```php
//register our function just like a wordpress hook
\BokkaWP\bdx_add_filter('bdx-adapter-relationship-filter','filterFloorplans');

//our function
function filterFloorplans($configuration, $options){
    return false;
}
```
#### How filters work
Each filter function gets passed two parameters.. A `$configuration` array and an `$options` array. The configuration is what has been saved in the database via the configuration page in the admin. A typical configuration looks like the following:

```php
array(
    "title"    =>  array(
        "value" =>  "post_title",
        "type"  =>  "post_field"
)
```
We can use the `key()` method to extract the field name, `key($configuration) === 'title'`

Each bdx-adapter-configuration filter is run after the plugin has already done it's own processing. This data is included in the  `$options` array. An options array might look like. 

```php
array(
    "value" => "The Alderfer", 
    "parent_post"   =>  WP_Post obj,
    "grandparent_post"  =>  WP_Post obj
)
```
The value here is the actual value we are trying to extract from the database, rather than the value of the configuration. In this example I'm assuming we are currently processing the "title" property of a floorplan. When dealing with a relationship filter the value of `$options['value']` is always the value of the relationship field itself. So if this is coming from an ACF relationship field, the value would likely be the ID of the post you are relating to. 

These other hierarchial values help us keep track of where we are in the data tree. So that if the pricing of a floorplan is based on community, we can reference the `grandparent_post` which will be set as the current community post. And the `parent_post` would be the floorplan post itself. Keep in mind our configurations are processed in a recusive manner, so as you process each community, you will process it's floorplans before moving onto another community. 

---

## Examples

### Example Fields configuration
The following configuration was used for Jones. It represents the strucute that the BDX service is expecting, and maps those values to the field types we want to map to.
```php
$fields = array(
    array(
        "label"     =>  "settings",
        "name"      =>  "settings",
        "type"      =>  "parent",
        "fields"    =>  array(
            array(
                "label" => "FTP Host",
                "name"  => "ftp-host",
                "type"  => "text"
            ),
            array(
                "label" => "FTP User",
                "name"  => "ftp-user",
                "type"  => "text"
            ),
            array(
                "label" => "FTP Pass",
                "name"  => "ftp-pass",
                "type"  => "password"
            ),
        )
    ),
    array(
        "label" => "ID",
        "name"  => "id",
        "type"  => "number"
    ),
    array(
        "label" => "Corporate Name",
        "name"  => "corporate-name",
        "type"  => "text"
    ),
    array(
        "label" => "Corporate Number",
        "name"  => "corporate-number",
        "type"  => "number"
    ),
    array(
        "label" => "Name",
        "name"  => "name",
        "type"  => "text"
    ),
    array(
        "label" => "Url",
        "name"  => "url",
        "type"  => "text"
    ),
    array(
        "label" => "Reporting Name",
        "name"  => "reporting-name",
        "type"  => "text"
    ),
    array(
        "label" => "Email",
        "name"  => "email",
        "type"  => "email"
    ),
    array(
        "label" => "State",
        "name"  => "state",
        "type"  => "state"
    ),
    array(
        "label"     => "Communities Post Type",
        "name"      => "communities",
        "type"      => "post_type",
        "fields"    => array(
            array(
                "label" => "ID",
                "name"  => "id",
                "type"  => "post_field"
            ),
            array(
                "label" => "Name",
                "name"  => "name",
                "type"  => "post_field"
            ),
            array(
                "label" => "Leads Email",
                "name"  => "leads-email",
                "type"  => "acf"
            ),
            array(
                "label" => "Style",
                "name"  => "style",
                "type"  => "acf"
            ),
            array(
                "label" => "URL",
                "name"  => "url",
                "type"  => "post_field"
            ),
            array(
                "label" => "Images",
                "name"  => "images",
                "type"  => "acf"
            ),
            array(
                "label"     => "Sales Office Post Type",
                "name"      => "sales-office",
                "type"      => "post_type",
                "fields"    => array(
                    array(
                        "label" =>  "Address 1",
                        "name"  =>  "address_1",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "Address 2",
                        "name"  =>  "address_2",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "City",
                        "name"  =>  "city",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "State",
                        "name"  =>  "state",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "ZIP",
                        "name"  =>  "zip",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label" =>  "Phone",
                        "name"  =>  "phone",
                        "type"  =>  "acf"
                    ),
                    array(
                        "label"     =>  "Geocode",
                        "name"      =>  "geocode",
                        "type"      =>  "parent",
                        "fields"    =>  array(
                            array(
                                "label" =>  "Latitude",
                                "name"  =>  "lat",
                                "type"  =>  "acf"
                            ),
                            array(
                                "label" =>  "Longitude",
                                "name"  =>  "long",
                                "type"  =>  "acf"
                            ),

                        )
                    ),
                    array(
                        "label" =>  "Relationship",
                        "name"  =>  "relationship",
                        "type"  =>  "acf-relationship"
                    )
                )
            ),
            array(
                "label" => "Floor Plans Post Type",
                "name" => "floorplans",
                "type" => "post_type",
                "fields"    => array(
                    array(
                        "label" => "ID",
                        "name"  => "id",
                        "type"  => "post_field"
                    ),
                    array(
                        "label" => "URL",
                        "name"  => "url",
                        "type"  => "post_field"
                    ),
                    array(
                        "label" => "Name",
                        "name"  => "name",
                        "type"  => "post_field"
                    ),
                    array(
                        "label" => "Base Price",
                        "name"  => "base-price",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Baths",
                        "name"  => "baths",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Half Baths",
                        "name"  => "half-baths",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Beds",
                        "name"  => "beds",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Dinning Rooms",
                        "name"  => "dining-rooms",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Sqft",
                        "name"  => "sqft",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Stories",
                        "name"  => "stories",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Master Bed Location",
                        "name"  => "master-location",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Garage",
                        "name"  => "garage",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Garage Entry",
                        "name"  => "garage-entry",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Basement",
                        "name"  => "basement",
                        "type"  => "acf"
                    ),
                    array(
                        "label"     =>  "Images",
                        "name"      =>  "images",
                        "type"      =>  "parent",
                        "fields"    =>  array(
                            array(
                                "label" => "Elevation Images",
                                "name"  => "elevation-images",
                                "type"  => "acf"
                            ),
                            array(
                                "label" => "Floorplan Images",
                                "name"  => "floorplan-images",
                                "type"  => "acf"
                            )
                        )
                    ),
                    array(
                        "label" =>  "Relationship",
                        "name"  =>  "relationship",
                        "type"  =>  "acf-relationship"
                    )
                )
            ),
        )
    )
);
```
### bdx-adapter-relationship-filter
In this example we will try to connect the relationship with a floorplan to it's parent community. See comments for explanation/

```php
//register our function just like a wordpress hook
\BokkaWP\bdx_add_filter('bdx-adapter-relationship-filter','filterFloorplans');

//our function
function filterFloorplans($configuration, $options){

    $name = key($configuration);
    
    //first we want to make sure we are processing the correct fields
    //if this isn't a post_type configuration, and it's value isn't 'plan' (our post type slug)
    if($name != 'post_type' || $configuration[$name]['value'] != 'plan') {
        return false;
    }
    
    //when handling relationship filters $options['value'] is always the value of the relationship field
    //even though our configuration has a type of "post_type" 
    //In this example we are making sure the value of our relationsip field has the same ID as the community above.
   if ($options['value'] == $options['parent_post']->ID) {
            return true;
    }

    //conditions weren't met, return a false value
    return false;
}
```
---
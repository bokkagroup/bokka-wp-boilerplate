<?php

return array(
    array(
        "label"     =>  "Settings",
        "name"      =>  "settings",
        "type"      =>  "parent",
        "fields"    =>  array(
            array(
                "label" => "Enable New Home Feed",
                "name"  => "enable-nhf",
                "type"  => "checkbox",
                "value" => true,
            ),
            array(
                "label" => "Enable BDX",
                "name"  => "enable-bdx",
                "type"  => "checkbox",
            ),
            array(
                "label" => "FTP Host (BDX)",
                "name"  => "ftp-host",
                "type"  => "text"
            ),
            array(
                "label" => "FTP User (BDX)",
                "name"  => "ftp-user",
                "type"  => "text"
            ),
            array(
                "label" => "FTP Pass (BDX)",
                "name"  => "ftp-pass",
                "type"  => "password"
            ),
            array(
                "label" => "New Home Feed API Key",
                "name"  => "nhf-api-key",
                "type"  => "text"
            ),
        )
    ),
    array(
        "label" => "Builder number",
        "name"  => "builder-number",
        "type"  => "text"
    ),
    array(
        "label" => "Corporate Name",
        "name"  => "corporate-name",
        "type"  => "text"
    ),
    array(
        "label" => "Corporate Number",
        "name"  => "corporate-number",
        "type"  => "text"
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
                "type"      => "parent-array",
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
            array(
                "label" => "Homes Post Type",
                "name" => "homes",
                "type" => "post_type",
                "fields"    => array(
                    array(
                        "label" => "ID",
                        "name"  => "id",
                        "type"  => "post_field"
                    ),
                    array(
                        "label" => "Floorplan ID",
                        "name"  => "floorplan_id",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Address 1",
                        "name"  => "address_1",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Address 2",
                        "name"  => "address_2",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "City",
                        "name"  => "city",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "State",
                        "name"  => "state",
                        "type"  => "acf"
                    ),
                    array(
                        "label" => "Zip",
                        "name"  => "zip",
                        "type"  => "acf"
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
                        "label" => "URL",
                        "name"  => "url",
                        "type"  => "post_field"
                    ),
                    array(
                        "label" => "Price",
                        "name"  => "price",
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
            )
        )
    )
);

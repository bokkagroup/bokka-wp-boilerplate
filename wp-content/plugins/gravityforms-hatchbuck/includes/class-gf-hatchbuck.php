<?php

GFForms::include_feed_addon_framework();

class GFHatchbuck extends GFFeedAddOn {

	protected $_version = GF_HATCHBUCK_VERSION;
	protected $_min_gravityforms_version = '1.8.17';
	protected $_slug = 'gravityformshatchbuck';
	protected $_path = 'gravityformshatchbuck/gravityformshatchbuck.php';
	protected $_full_path = __FILE__;
	protected $_url = 'http://www.gravityforms.com';
	protected $_title = 'Gravity Forms Hatchbuck Add-On';
	protected $_short_title = 'Hatchbuck';

	// Members plugin integration
	protected $_capabilities = array( 'gravityforms_hatchbuck', 'gravityforms_hatchbuck_uninstall' );

	// Permissions
	protected $_capabilities_settings_page = 'gravityforms_hatchbuck';
	protected $_capabilities_form_settings = 'gravityforms_hatchbuck';
	protected $_capabilities_uninstall = 'gravityforms_hatchbuck_uninstall';
	protected $_enable_rg_autoupgrade = true;

	private static $api;

	private static $_instance = null;

	/**
	 * Return instance of self
	 */
	public static function get_instance() {

		if ( self::$_instance == null ) {
			self::$_instance = new GFHatchbuck();
		}

		return self::$_instance;
	}

	/**
	 * Init the Plugin
	 */
	public function init() {

		parent::init();

	}

	/**
	 * Init Admin
	 */
	public function init_admin() {

		parent::init_admin();

	}


	// -------------- Plugin Settings --------------- //

	public function plugin_settings_fields() {

		$plugin_settings = array(
			array(
				'title'       => __( 'Configure API', $this->_slug ),
				'fields'      => array(
					array(
						'name'              => 'api_key',
						'label'             => __( 'API Key' ),
						'type'              => 'text',
						'class'             => 'medium',
						'tooltip'           => "<h6>" . __( 'API Key', $this->_slug ) . "</h6> Retrieve your API Key in the Account Settings (Web API) in Hatchbuck",
						'required'          => true,
						'feedback_callback' => array( $this, 'is_valid_api_key' )
					),

					/**
					 * we're using text field for source ID here because Hatchbuck API doesn't yet support retrieval of list
					 */
					array(
							'name'     => 'source_id',
							'label'    => __( 'Lead Source ID' ),
							'type'     => 'text',
							'class'    => 'medium',
							'required' => true
					),

					/**
					 * we're hard-coding the list of status IDs here because Hatchbuck API doesn't yet support retrieval
					 */
					array(
						'name'     => 'contact_status',
						'label'    => __( 'Contact Status', $this->_slug ),
						'type'     => 'select',
						'required' => true,
						'tooltip'  => "<h6>" . __( 'Contact Status', $this->_slug ) . "</h6> Hatchbuck Status for new entries",
						'choices'  => array(
							array(
								'label' => '(please select)',
								'value' => ''
							),
							array(
								'label' => 'Handed Off to Sales',
								'value' => 'a1NtS09xODVMLU5kMG1CbzB4X09XdWYzc1MtbmlVTV9ILXl4NXpGZVR5STE1'
							),
							array(
								'label' => 'In Conversation',
								'value' => 'V1pRdFJMck10NGF2alpCMlNqcHhyazdxalF5QVdDbEVnOVN2WS1pQks3WTE1'
							),
							array(
								'label' => 'New Lead',
								'value' => 'NWZxTGtIS05VZGxQekx5WFJXNDdqcktOcmtVb0U0Q1hOc0RfZ2RuOUpFNDE1'
							),
							array(
								'label' => 'Not Gonna Happen',
								'value' => 'ODlnSFo5VjgzYTR2akRCRmpUeFVoOF9aVkVSckU5MTg4blhPdVlhR3RqZzE1'
							),
							array(
								'label' => 'Sold',
								'value' => 'TVE0N0dWVjdrZERYMnBueXJGc2ZmSHBpR0NpVllydXowaTRjRkU0OHROTTE1'
							)
						)
					),
				)
			)
		);

		return apply_filters( 'gravityforms_hatchbuck_plugin_settings_fields', $plugin_settings );

	}

	public function feed_settings_fields() {

		$feed_settings = array(
			array(
				'title'       => __( 'Hatchbuck Fields', $this->_slug ),
				'description' => __( '', $this->_slug ),
				'fields'      => array(
					array(
						'name'      => 'hatchbuck_fields',
						'label'     => __( 'Hatchbuck Settings', $this->_slug ),
						'type'      => 'field_map',
						'required'  => true,
						'field_map' => $this->hatchbuck_fields_map()
					),

					/**
					 * we're using text field for campaign ID here because Hatchbuck API doesn't yet support retrieval of list
					 */
					array(
						'name'    => 'campaign_id',
						'label'   => __( 'Campaign ID', $this->_slug ),
						'type'    => 'text',
						'class'   => 'medium',
						'tooltip' => "<h6>" . __( 'Campaign ID', $this->_slug ) . "</h6> Campaign ID to be triggered upon form submission"
					),

					array(
						'type' => 'save'
					)
				)
			)
		);

		// Return the feed settings fields
		return apply_filters( 'gravityforms_hatchbuck_feed_settings_fields', $feed_settings );
	}


	//-------- Form Settings ---------

	public function feed_edit_page( $form, $feed_id ) {

		$api = true;

		// ensures valid credentials were entered in the settings page
		if ( ! $api ) {
			?>
			<div><?php echo sprintf(
					__( 'We are unable to login to Hatchbuck with the provided credentials. Please make sure they are valid in the %sSettings Page%s', $this->_slug ),
					"<a href='" . $this->get_plugin_settings_url() . "'>", '</a>'
				); ?>
			</div>

			<?php
			return;
		}

		echo '<script type="text/javascript">var form = ' . GFCommon::json_encode( $form ) . ';</script>';

		parent::feed_edit_page( $form, $feed_id );
	}

	public function feed_list_columns() {

		return array(
			'hatchbuck_fields' => __( 'Mapped Fields', $this->_slug )
		);

	}

	public function hatchbuck_fields_map() {

		$hatchbuck_fields = array(
			array(
				'label'    => 'First Name',
				'name'     => 'firstName',
				'required' => true
			),
			array(
				'label'    => 'Last Name',
				'name'     => 'lastName',
				'required' => true
			),
			array(
				'label' => 'Email',
				'name'  => 'emails',
				'required' => true
			),
			array(
				'label' => 'Phone',
				'name'  => 'phones'
			),
				array(
				'label' => 'Street Address',
				'name'  => 'street',
			),
			array(
				'label' => 'City',
				'name'  => 'city',
			),
			array(
				'label' => 'State',
				'name'  => 'state'
			),
			array(
				'label' => 'ZIP',
				'name'  => 'zip'
			),
			array(
				'label' => 'Custom Field: Comments',
				'name' => 'custom_ZXRmdUQzd0RxWUtKZk1rQkdSdElwNEFJb2lnamRULTlOQUEwNEtUdUo4azE1'
			),
            array(
                'label' => 'Custom Field: UTM_Campaign',
                'name' => 'c3dib1hTWmU1S0EyUGlnQ1A1ZHB0NmpYbkVlQVVsYmNBX3RWVVh6OUpKSTE1'
            ),
            array(
                'label' => 'Custom Field: UTM_Medium',
                'name' => '	ZU9tckh0S2RTZ2RVQnFERy1xSmlIRERnZjItcDVPME5fMkl5UUk5QVhGTTE1'
            ),
            array(
                'label' => 'Custom Field: UTM_Source',
                'name' => 'dWY5a2NvVnlzQ1JDYjk1TTVETV8wS1lSNW5aS21JcFdyb1c4NkpMaUR6RTE1'
            ),
			array(
				'label' => 'Tags',
				'name' => 'tags'
			),
			array(
				'label' => 'Additional Tags',
				'name' => 'additional_tags'
			),
			array(
				'label' => 'Product Types',
				'name' => 'product_types'
			),
			array(
				'label' => 'Neighborhood Name',
				'name' => 'neighborhood_name'
			),
			array(
				'label' => 'Page Name',
				'name' => 'page_name'
			),
			array(
				'label' => 'Post Type',
				'name' => 'post_type'
			),
			array(
				'label' => 'BCN Insider',
				'name' => 'bcn_insider'
			)
		);

		return apply_filters( 'gravityforms_hatchbuck_fieldmap', $hatchbuck_fields );

	}


	//------ Core Functionality ------

	public function get_column_value_hatchbuck_fields( $feed ) {

		// Get the mapped fields
		$mappedFields = $this->get_field_map_fields( $feed, 'hatchbuck_fields' );

		// Initialize empty array
		$fields = array();

		// If there are mapped fields
		if ( $mappedFields ) {

			// Loop through the mapped fields
			foreach ( $mappedFields as $name => $field_id ) {

				// Only if the field has actually been set as mapped
				if ( $field_id ) {

					// Add field to the fields array
					$fields[] = $name;

				}

			}

		}

		// Run the fields through a filter
		$fields = apply_filters( 'gravityforms_hatchbuck_column_fields', $fields );

		// Return comma delimited name of fields that have been mapped
		return join( ', ', $fields );

	}

	public function process_feed( $feed, $entry, $form ) {

		// Log when the feed is processing
		$this->log_debug( 'Process feed' );

		// Get Custom Fields
		$feed_meta = $feed['meta'];

		// Get the mapped fields
		$field_map = $this->get_field_map_fields( $feed, 'hatchbuck_fields' );

		// If there are no mapped fields, don't proceed
		if ( empty( $field_map ) ) {
			return;
		}

		// Set default lead_data values
		$lead_data = array();

		//set placeholder for custom fields
		$customFields = array();

		//set placeholder for address fields
		$addressFields = array();

		//set placeholder for tags
		$tags = array();

		// Loop through the form Fields
		foreach ( $field_map as $name => $field_id ) {

			//If there's no name, use the Admin Label as the name
			if ( empty( $name ) ) {
				$field = RGFormsModel::get_field( $form, $field_id );
				$name  = $field['adminLabel'];
			}

			//reset value
			$value = '';

			switch ( strtolower( $field_id ) ) {

				case 'form_title':
					$merge_vars[ $name ] = rgar( $form, 'title' );
					break;

				case 'date_created':
				case 'ip':
				case 'source_url':
					$lead_data[ $name ] = rgar( $entry, strtolower( $field_id ) );
					break;

				default:

					$field       = RGFormsModel::get_field( $form, $field_id );
					$is_integer  = $field_id == intval( $field_id );
					$input_type  = RGFormsModel::get_input_type( $field );
					$field_value = rgar( $entry, $field_id );

					switch ( $input_type ) {

						case 'email':
							//format email for Hatchbuck
							$value = array(
								array(
									'type'    => 'Work',
									'address' => $field_value
								)
							);
							break;

						case 'phone':
							//format phone for Hatchbuck
							$value = array(
								array(
									'type'   => 'Work',
									'number' => $field_value
								)
							);
							break;

						case 'website':
							//format phone for Hatchbuck
							$value = array(
								array(
									'websiteUrl' => $field_value
								)
							);
							break;

						case 'checkbox':
							// handling checkboxes
							if ( $is_integer ) {

								// Instantiate empty selected array
								$selected = array();

								foreach ( $field['inputs'] as $input ) {

									$index = (string) $input['id'];
									if ( ! rgempty( $index, $entry ) ) {
										$selected[] = apply_filters( 'gravityforms_hatchbuck_field_value', rgar( $entry, $index ), $form['id'], $field_id, $entry, $name );
									}

								}

								$value = join( ', ', $selected );

							}

							break;

						default:

							$value = apply_filters( 'gravityforms_hatchbuck_field_value', $field_value, $form['id'], $field_id, $entry, $name );

					}

			}

			//check for custom fields
			if ( substr($name,0,7) == 'custom_') {
				$customFields[] = array(
						'id'    => substr( $name, 7 ),
						'value' => $value,
				);
				continue;
			}

			//check for address fields
			if (in_array($name, array('street','city','state','zip'))) {
				if(!empty($value)){
					$addressFields[ $name ] = $value;
				}
				continue;
			}

			//assign formatted value to field
			$lead_data[ $name ] = $value;

		}

		//check our custom arrays for additional formatting
		if(count($customFields)){

			//set lead data
			$lead_data['customFields'] = $customFields;

		}

		//check our address array for additional formatting
		if(count($addressFields)){

			//set defaults
			$addressFields['type'] = 'Work';
			$addressFields['country'] = 'United States';

			//set lead data
			$lead_data['addresses'] = array($addressFields);

		}

		//check for tags for additional formatting
		if ( isset($lead_data['tags']) || isset($lead_data['additional_tags'])) {
			$field_tags = array();

			//add tags
			if ( ! empty( $lead_data['tags'] ) ) {
				$field_tags = array_merge( $field_tags, explode( ', ', $lead_data['tags'] ) );
			}

			//additional tags
			if ( ! empty( $lead_data['additional_tags'] ) ) {
				$field_tags = array_merge( $field_tags, explode( ', ', $lead_data['additional_tags'] ) );
			}

			if ( ! empty( $lead_data['product_types'] ) ) {
				$field_tags = array_merge( $field_tags, explode( ', ', $lead_data['product_types'] ) );
			}

			if ( ! empty( $lead_data['neighborhood_name'] ) ) {
				$field_tags = array_merge( $field_tags, explode( ', ', $lead_data['neighborhood_name'] ) );
			}

			if ( ! empty( $lead_data['bcn_insider'] ) ) {
				$field_tags = array_merge( $field_tags, explode( ', ', $lead_data['bcn_insider'] ) );
			}

			if ( ! empty( $lead_data['page_name'] ) ) {
				$field_tags = array_merge( $field_tags, explode( ', ', $lead_data['page_name'] ) );
			}

			if ( ! empty( $lead_data['post_type'] ) ) {
				$field_tags = array_merge( $field_tags, explode( ', ', $lead_data['post_type'] ) );
			}

			//format tags array out of unique tags
			$field_tags = array_unique($field_tags);
			foreach ( $field_tags as $field_tag ) {
				$tags[] = array(
						'name' => $field_tag
				);
			}

			if ( count( $tags ) ) {
				//remove lead data fields
				unset( $lead_data['tags'] );
				unset( $lead_data['additional_tags'] );
				unset( $lead_data['product_types'] );
				unset( $lead_data['neighborhood_name'] );
				unset( $lead_data['bcn_insider'] );
				unset( $lead_data['page_name'] );
				unset( $lead_data['post_type'] );
			}
		}

		// If there's no feed settings, don't continue
		if ( ! $feed_meta ) {
			return;
		}

		//add required Status field from Plugin settings (could be moved to Form Settings if it needs to be more specific
		$lead_data['status'] = array(
			'id' => $this->get_plugin_setting( 'contact_status' )
		);

		// Add source ID from Plugin settings (could be moved to Form Settings if it needs to be more specific
		$lead_data['source'] = array(
			'id' => $this->get_plugin_setting('source_id')
		);

		// Run the lead_data through a filter
		$lead_data = apply_filters( 'gravityforms_hatchbuck_lead_data', $lead_data, $feed, $entry, $form );

//		die();

		if ( ! empty( $lead_data ) ) {

			//save our lead data back to Hatchbuck
			$hatchbuck_contact = $this->hatchbuck_save( $lead_data );

			//add the appropriate tags to our lead
			if ( count( $tags ) && ! empty( $hatchbuck_contact->contactId ) ) {
				$this->hatchbuck_add_tag( $hatchbuck_contact->contactId, $tags );
            }

			//start campaign if one is specified for the form
			if ( ! empty( $feed_meta['campaign_id'] ) && ! empty( $hatchbuck_contact->contactId ) ) {
				$this->hatchbuck_start_campaign( $hatchbuck_contact->contactId, $feed_meta['campaign_id'] );
            }
		}

//		die();

	}


	//------- Helpers ----------------

	public function is_enabled( $apiKey ) {

		return true;

	}

	private function get_address( $entry, $field_id ) {

		$street_value  = str_replace( '  ', ' ', trim( $entry[ $field_id . '.1' ] ) );
		$street2_value = str_replace( '  ', ' ', trim( $entry[ $field_id . '.2' ] ) );
		$city_value    = str_replace( '  ', ' ', trim( $entry[ $field_id . '.3' ] ) );
		$state_value   = str_replace( '  ', ' ', trim( $entry[ $field_id . '.4' ] ) );
		$zip_value     = trim( $entry[ $field_id . '.5' ] );
		$country_value = GFCommon::get_country_code( trim( $entry[ $field_id . '.6' ] ) );

		$address = $street_value;
		$address .= ! empty( $address ) && ! empty( $street2_value ) ? '  ' . $street2_value : $street2_value;
		$address .= ! empty( $address ) && ( ! empty( $city_value ) || ! empty( $state_value ) ) ? '  ' . $city_value : $city_value;
		$address .= ! empty( $address ) && ! empty( $city_value ) && ! empty( $state_value ) ? '  ' . $state_value : $state_value;
		$address .= ! empty( $address ) && ! empty( $zip_value ) ? '  ' . $zip_value : $zip_value;
		$address .= ! empty( $address ) && ! empty( $country_value ) ? '  ' . $country_value : $country_value;

		return $address;

	}

	private function get_name( $entry, $field, &$leadData ) {

		switch ( $field['nameFormat'] ) {

			case 'advanced':
				//get first name
				$id                    = $field['inputs'][1]['id'];
				$leadData['firstName'] = $entry["$id"];

				//get last name
				$id                   = $field['inputs'][3]['id'];
				$leadData['lastName'] = $entry["$id"];

				$value = $leadData['firstName'] . ' ' . $leadData['lastName'];
				break;

			case 'normal':
				//get first name
				$id                    = $field['inputs'][0]['id'];
				$leadData['firstName'] = $entry["$id"];

				//get last name
				$id                   = $field['inputs'][1]['id'];
				$leadData['lastName'] = $entry["$id"];

				$value = $leadData['firstName'] . ' ' . $leadData['lastName'];
				break;

			case 'simple':
				//single text field
				$parts                 = explode( ' ', $entry[ $field['id'] ] );
				$leadData['firstName'] = $parts[0];
				$leadData['lastName']  = $parts[1];
				break;

			default:
				$value = $entry[ $field['id'] ];

		}

		return $value;
	}


	//------- Hatchbuck API ----------------

	private function hatchbuck_start_campaign( $contact_id, $campaign_id ) {
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
		return $this->hatchbuck_execute( $params );
	}

	private function hatchbuck_add_tag( $contact_id, $leadTags ) {
		//build params to query for lead
		$params = array(
			'url'     => 'https://api.hatchbuck.com/api/v1/contact/' . $contact_id . '/Tags',
			'request' => 'POST',
			'data'    => $leadTags
		);

		//perform query and return
		return $this->hatchbuck_execute( $params );
	}

	private function hatchbuck_save( $lead ) {
		//check for existing lead
		$existing_lead = $this->hatchbuck_find( $lead );

		//if it exists, update it, if not create it
		if ( is_array( $existing_lead ) ) {

			//convert to array
			$existing_lead = (array) $existing_lead[0];

			//remove empty fields
			$existing_lead = array_filter( $existing_lead );
			$lead          = array_filter( $lead );

			//merge new values with old and convert back to object
			$lead = (object) array_merge( $existing_lead, $lead );

			//call update
			return $this->hatchbuck_update( $lead );

		} else {
			//remove empty fields
			$lead = array_filter( $lead );

			//convert lead array to object
			$lead = (object) $lead;

			//call create with lead data from form
			return $this->hatchbuck_create( $lead );

		}
	}

	private function hatchbuck_create( $lead ) {
		//build params to query for lead
		$params = array(
			'url'     => 'https://api.hatchbuck.com/api/v1/contact',
			'request' => 'POST',
			'data'    => $lead
		);

		//perform query and return
		return $this->hatchbuck_execute( $params );
	}

	private function hatchbuck_update( $lead ) {
		//build params to query for lead
		$params = array(
			'url'     => 'https://api.hatchbuck.com/api/v1/contact',
			'request' => 'PUT',
			'data'    => $lead
		);

		//perform query and return
		return $this->hatchbuck_execute( $params );
	}

	private function hatchbuck_find( $lead ) {
		//build params to query for lead
		$params = array(
			'url'     => 'https://api.hatchbuck.com/api/v1/contact/search',
			'request' => 'POST',
			'data'    => array(
				'emails' => $lead['emails']
			)
		);

		//perform query and return
		return $this->hatchbuck_execute( $params );
	}

	private function hatchbuck_execute( $params ) {
		//format as json
		$data = json_encode( $params['data'] );

		//retrieve info from options panel
		$api_key = $this->get_plugin_setting( 'api_key' );
		$url     = $params['url'] . '?api_key=' . $api_key;

		//make curl connection
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $params['request'] );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen( $data )
		) );

		//execute curl request
		$response = curl_exec( $ch );

		//close curl connection
		curl_close( $ch );

		//return results
		return json_decode( $response );
	}
}
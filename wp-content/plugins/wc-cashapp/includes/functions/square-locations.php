<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

$location_id = array();
try {
    $locations = $this->wc_cash_app_locations_api();
    if ( $locations['status'] === true && count( $locations ) > 0 ) {
        $options = $locations['options'];
        $location_id = array(
            'title'       => count($options) . " Active Square Location(s) associated with your merchant account (Last fetched at {$locations['time']})",
            'label'       => 'Select one of your <a href="https://squareup.com/dashboard/locations" target="_blank">Square Locations</a>',
            'description' => 'Select one of your <a href="https://squareup.com/dashboard/locations" target="_blank">Square Locations</a>',
            'type'        => 'select',
            'options'     => $options,
            'default'     => $this->SQ_Location_Id,
            //   'desc_tip'    => true,
        );
    } else {
        $location_id = array(
            'title'       => 'Square Location ID<br>' . $square,
            'type'        => 'text',
            'description' => $locations['message'] . '. No locations found. Please add a new business location in your <a href="https://squareup.com/dashboard/locations/new" target="_blank">Square Dashboard > Account & Settins Business > Locations</a>',
            'placeholder' => 'LXXXXXXXXXXXX',
        );
    }
} catch (Exception $e) {
    $location_id = array(
        'title'       => 'Square Location ID<br>' . $square,
        'type'        => 'text',
        'description' => $e->getMessage() . '<br>Get one of your <a href="https://squareup.com/dashboard/locations" target="_blank">Square Locations</a>',
        'placeholder' => 'LXXXXXXXXXXXX',
    );
    // $location_id['default'] =  !empty($this->SQ_Location_Id) ? $this->SQ_Location_Id : undefined;
    if ( !empty($this->SQ_Location_Id) ) { $location_id['default'] = $this->SQ_Location_Id; }
}

?>
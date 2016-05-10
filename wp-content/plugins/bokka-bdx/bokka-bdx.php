<?php
/**
 * @package Bokka_bdx
 * @version a-0.0.1
 */
/*
Plugin Name: Bokka BDX
Plugin URI: http://bokka.com
Description: Bokka BDX
Author: Bokka Group
Version: a0.0.1
Author URI: http://bokkagroup.com
*/

namespace BokkaWP;
define("BGBDX_DIR", plugin_dir_path(__FILE__) );

/**
 * BokkaBuilder
 * @version 0.0.1 Singleton
 */
class BDX {

    private static $instance;



    public function __construct(){

        $upload_dir = wp_upload_dir();

        if( is_admin() ){
            require_once( BGBDX_DIR .'/admin/admin.php' );
            $this->admin = new BDX\Admin( );
        }

    }

    /**
     * Singleton instantiation
     * @return [static] instance
     */
    public static function get_instance(){
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

}

$BGMVC = BDX::get_instance();

register_activation_hook( __FILE__, 'bgwpbdx_install' );

$writer = new \XMLWriter();
$writer->openURI('php://output');
$writer->startDocument('1.0','UTF-8');
    $writer->setIndent(4);
    $writer->startElement('Builders');
        $writer->writeAttribute('DateGenerated', date("l") );
        $writer->startElement('Corporation');
            $writer->writeElement('CorporateBuilderNumber', "");
            $writer->writeElement('CorporateState', "");
            $writer->writeElement('CorporateName', "");
            $writer->startElement('Builder');
                $writer->writeElement('BuilderNumber', "");
                $writer->writeElement('BrandName', "");
                $writer->startElement('DefaultLeadsEmail');
                    $writer->writeAttribute('LeadsPerMessage', 'All' );
                    $writer->text('email@email.com');
                $writer->endElement();//default leads
                $writer->writeElement('BuilderWebsite', "http://domain.com");
                $writer->startElement('Subdivision');
                    $writer->writeElement('SubdivisionNumber', "");
                    $writer->writeElement('SubdivisionName', "");
                    $writer->startElement('SubLeadsEmail');
                        $writer->writeAttribute('LeadsPerMessage', 'All' );
                        $writer->text('email@email.com');
                    $writer->endElement();//sub leads
                    $writer->writeElement('CommunityStyle', "");
                    $writer->startElement('SalesOffice');
                        $writer->startElement('Address');
                            $writer->writeAttribute('OutOfCommunity', "false" );
                            $writer->writeElement('street1', "");
                            $writer->writeElement('street2', "");
                            $writer->writeElement('county', "");
                            $writer->writeElement('city', "");
                            $writer->writeElement('state', "");
                            $writer->writeElement('zip', "");
                            $writer->writeElement('country', "");
                            $writer->startElement('Geocode');
                                $writer->writeElement('Latitude', "");
                                $writer->writeElement('Longitude', "");
                            $writer->endElement();//Geocode
                            $writer->startElement('Phone');
                                $writer->writeElement('AreaCode', "");
                                $writer->writeElement('Prefix', "");
                                $writer->writeElement('Suffix', "");
                                $writer->writeElement('Extension', "");
                            $writer->endElement();//Phone
                            $writer->writeElement('Email', "");
                        $writer->endElement();//Address
                    $writer->endElement();//sales office
                    $writer->writeElement('DrivingDirections', "");
                    $writer->writeElement('SubDescription', "");
                    $writer->startElement('SubImage');
                        $writer->writeAttribute('Type', "Standard" );
                        $writer->writeAttribute('SequencePosition', "1" );
                        $writer->writeAttribute('Title', "" );
                        $writer->writeAttribute('ReferenceType', "URL" );
                        $writer->text('email@email.com');
                    $writer->endElement();//SubImage
                    $writer->writeElement('SubWebsite', "");
                    $writer->startElement('Plan');
                        $writer->writeAttribute('Type', "SingleFamily" );
                        $writer->writeElement('PlanNumber', "");
                        $writer->writeElement('PlanName', "");
                        $writer->writeElement('PlanTypeName', "");
                        $writer->startElement('BasePrice');
                            $writer->writeAttribute('ExcludesLand', "False" );
                            $writer->text('email@email.com');
                        $writer->endElement();//BasePrice
                        $writer->writeElement('BaseSqft', "");
                        $writer->writeElement('Stories', "");
                        $writer->writeElement('Baths', "");
                        $writer->writeElement('HalfBaths', "");
                        $writer->startElement('Bedrooms');
                            $writer->writeAttribute('MasterBedLocation', "Down" );
                            $writer->text('4');
                        $writer->endElement();//Bedrooms
                        $writer->writeElement('Garage', "");
                        $writer->writeElement('DiningAreas', "");
                        $writer->writeElement('Basement', "");
                        $writer->writeElement('MarketingHeadline', "");
                        $writer->writeElement('Description', "");
                        $writer->startElement('PlanImages');
                            $writer->startElement('FloorPlanImage');
                                $writer->writeAttribute('SequencePosition', "Down" );
                                $writer->writeAttribute('Title', "Down" );
                                $writer->writeAttribute('ReferenceType', "Down" );
                                $writer->text('4');
                            $writer->endElement();//PlanImage
                        $writer->endElement();//PlanImages
                        $writer->writeElement('PlanWebsite', "");
                    $writer->endElement();//Plan
                    $writer->writeElement('PlanCount', "");
                $writer->endElement();//subdivision
            $writer->endElement();//Builder
        $writer->endElement();//corporation
    $writer->endElement();//builders
$writer->endDocument();
$writer->flush();
?>




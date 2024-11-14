<?php

namespace Jellygnite\Enhance\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Dev\Debug;
use Jellygnite\Enhance\Model\EnhancedImage;


class SiteConfigExtension extends DataExtension 
{

    private static $db = [
		"AdministratorEmail" => "Varchar(254)",
		"ContactResponse" => "HTMLText",
		"Phone" => "Varchar(50)",
		"Fax" => "Varchar(50)",
		"Email" => "Varchar(254)",
		"Company" => "Text",
		"ABN" => "Varchar(50)",
		"Address" => "Text",
		"Postal" => "Text",
		"OpeningHours" => "Text",
		"MapAddress" => "Varchar(254)",		
		"Latitude" => "Decimal(9,5)",
		"Longitude" => "Decimal(9,5)",
		"Zoom" => "Int",
		
		"FacebookURL" 	=> "Varchar(255)",
		"TwitterURL" 	=> "Varchar(255)",
		"LinkedInURL" 	=> "Varchar(255)",
		"InstagramURL" 	=> "Varchar(255)",
		
		"MapURL" 	=> "Varchar(255)",
    ];
    
	/* using File */
	private static $has_one = [
	    "Logo" => File::class,
	    "ExtraLogo" => File::class,
	    "DefaultBanner" => Image::class
    ];

	private static $casting = [
		"MapEmbedCode" => "HTMLFragment"
    ];


    private static $owns = [
        "Logo",
        "ExtraLogo",
        "DefaultBanner",
    ];
	
    public function updateCMSFields(FieldList $fields) {

		$fields->addFieldsToTab("Root.ContactDetails", array(
			TextField::create("AdministratorEmail", "Email to receive Submissions")
				->setRightTitle("All form submissions will be sent to this email address."),
			HTMLEditorField::create("ContactResponse", "Contact Response")
				->setRightTitle("Message to display after submitting contact form.")
				->setRows(5)
		));

	    $fields->addFieldsToTab("Root.ContactDetails", array(
		    TextField::create("Phone", "Phone"),
		    TextField::create("Fax", "Fax"),
		    $tfEmail = TextField::create("Email", "Email"),
		    TextField::create("Company", "Company"),
		    TextField::create("ABN", "ABN"),
		    TextareaField::create("Address", "Address"),
		    TextareaField::create("Postal", "Postal"),
		    TextareaField::create("OpeningHours", "Opening Hours"),
		    TextField::create("MapAddress", "Map Address")
				->setRightTitle('Address used in search query inside embedded Google map.'),
		    TextField::create("Latitude", "Latitude")
				->setRightTitle('LatLong will override Map Address above.'),
		    TextField::create("Longitude", "Longitude"),
		    TextField::create("Zoom", "Zoom")
	  	));
		$tfEmail->setRightTitle("This email will be displayed on the website.");
		
		$fields->addFieldsToTab("Root.Social", array(
		    $tfFacebook = TextField::create("FacebookURL", "Facebook URL"),
		    $tfTwitter = TextField::create("TwitterURL", "Twitter URL"),
		    $tfLinkedIn = TextField::create("LinkedInURL", "LinkedIn URL"),
		    $tfLinkedIn = TextField::create("InstagramURL", "Instagram URL"),
		    $tfMap = TextField::create("MapURL", "Map URL")
	    ));
		$tfFacebook->setRightTitle("Leave blank to omit link from menu.");
		
		$uploadField = UploadField::create("Logo", "Logo");
		$uploadField->setAllowedFileCategories(['image/supported','image/unsupported']);
	    $uploadField->setFolderName('images/logos');
	    $fields->addFieldsToTab("Root.Images", array(
		    $uploadField
	    ));
		$ufExtraLogo = UploadField::create("ExtraLogo", "Extra Logo");
		$ufExtraLogo->setAllowedFileCategories(['image/supported','image/unsupported']);
	    $ufExtraLogo->setFolderName('images/logos');
	    $fields->addFieldsToTab("Root.Images", array(
		    $ufExtraLogo
	    ));
		$ufDefaultBanner = UploadField::create("DefaultBanner", "Default Banner");
	    $ufDefaultBanner->setFolderName('images/banners');
	    $fields->addFieldsToTab("Root.Images", array(
		    $ufDefaultBanner
	    ));

    }
	
	public function onAfterWrite(){
		if ($this->owner->LogoID) {
			$this->owner->Logo()->publishSingle();
		}
		if ($this->owner->ExtraLogoID) {
			$this->owner->ExtraLogo()->publishSingle();
		}
		if ($this->owner->DefaultBannerID) {
			$this->owner->DefaultBanner()->publishSingle();
		}
	}

	public function PhoneLink(){
		$phone = preg_replace('/[^\d\+]/', '', $this->owner->Phone ?? '');
		return $phone;
	}
	

	public function getMapEmbedCode() {
		$siteconfig = $this->owner;
		$zoom = $siteconfig->Zoom ? $siteconfig->Zoom : 14;
		$query = false;
		
		if( ((float) $siteconfig->Latitude) && ( (float) $siteconfig->Longitude ) ) {
			$query = rawurlencode ($siteconfig->Latitude . ',' . $siteconfig->Longitude);
		} elseif($siteconfig->MapAddress) {
			//$query = str_replace(",", "", preg_replace("!\s+!", " ", preg_replace( "/\r|\n/", " ", strip_tags ($siteconfig->Postal))));
			$query = rawurlencode (str_replace(",", "", preg_replace("!\s+!", " ", preg_replace( "/\r|\n/", " ", strip_tags ($siteconfig->MapAddress)))));
			
	
    	}
		if($query){
			return '<iframe width="100%" height="450" id="gmap_canvas" src="https://maps.google.com/maps?q='.$query.'&t=&z='.$zoom.'&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>';
		}
					
	}
}
<?php

namespace Jellygnite\Enhance\Extensions;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use Jellygnite\Enhance\Model\EnhancedImage;
use SilverStripe\Dev\Debug;
/**
 * 
 *	Adds some checking and functions for SVG files
 * 
 */

class FileExtension extends DataExtension {
	private static $class_for_file_extension = array(
        'svg' => EnhancedImage::class,
        'webp' => Image::class
    );

    public function IsWebP(){
        if($this->owner->getExtension()=='webp') {
            return true;
        }
        return false;
    }

    public function IsSVG(){
        if($this->owner->getExtension()=='svg') {
            return true;
        }
        return false;
    }
	
	public function IsUnsupported(){
		if($this->IsSVG()){
			return true;
		}
	}
}
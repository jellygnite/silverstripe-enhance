<?php

namespace Jellygnite\Enhance\Model;

use SilverStripe\Assets\File;
use SilverStripe\Assets\Storage\DBFile;

/*
* SVG Support
*  
* Instructions
* in your Data Object

    private static $has_one = array(
        'Image' => File::class,   // use file class but limit file categories to support SVG
    );
	
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
			
            $image = $fields->dataFieldByName('Image')
                ->setDescription(_t(__CLASS__.'.ImageDescription', 'Optional. Display an image.'))
				->setAllowedFileCategories('image/supported','image/unsupported');
            $fields->addFieldToTab(
                'Root.Images',
                $image
            );
		}
	}
	
* If you are allowing SVG uploads, to avoid potential for script execution in malicious SVG files, it is safer to use in your template. 

	<img src="$Image.ScaleMaxWidth(640).URL" alt="$Image.Title.ATT">

* Best to limit uploads to admin only, i.e. don't enable SVG upload in front end.
*
*/


class EnhancedImage extends File {

    public function forTemplate()
    {
		if($this->owner->IsSVG()) {
			$template = 'DBFile_image';
			return (string)$this->renderWith($template);
		}
		
        return parent::forTemplate();
    }
	

    public function getFileType(){
        if($this->getExtension()=='svg') return "SVG image - good for line drawings";

        return parent::getFileType();
    }

    public function Pad($width, $height, $backgroundColor = 'FFFFFF', $transparencyPercent = 0)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::Pad($width, $height, $backgroundColor, $transparencyPercent);
    }

    public function Resampled()
    {
     	if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::Resampled();
    }
	
    public function ResizedImage($width, $height)
    {
     	if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::ResizedImage($width, $height);
    }
	
    public function Fit($width, $height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::Fit($width, $height);
    }

    public function FitMax($width, $height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::FitMax($width, $height);
    }

    public function ScaleWidth($width)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::ScaleWidth($width);
    }

    public function ScaleMaxWidth($width)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::ScaleMaxWidth($width);
    }

    public function ScaleHeight($height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::ScaleHeight($height);
    }

    public function ScaleMaxHeight($height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::ScaleMaxHeight($height);
    }

    public function CropWidth($width)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::CropWidth($width);
    }
	
    public function CropHeight($height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::CropHeight($height);
    }
	
    public function FillMax($width, $height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::FillMax($width, $height);
    }
	
    public function Fill($width, $height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::Fill($width, $height);
    }

    public function Quality($quality)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::Quality($quality);
    }

    public function CMSThumbnail()
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::CMSThumbnail();
    }

    public function StripThumbnail()
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::StripThumbnail();
    }

    public function PreviewThumbnail()
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::PreviewThumbnail();
    }

    public function Thumbnail($width, $height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::Thumbnail($width, $height);
    }

    public function ThumbnailIcon($width, $height)
    {
		if($this->owner->IsUnsupported()) {
			return $this;
		}
		
        return parent::ThumbnailIcon($width, $height);
    }


}

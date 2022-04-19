<?php

namespace Jellygnite\Enhance\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Dev\Debug;

/**
 * 
 *	This saves a rounded integer value which measures the 'luminance' percentage
 * 
 */

class ImageExtension extends DataExtension {

	// luminance value for image to be considered light or dark
    private static $luminance = 55;

    private static $db = [
        'Luminance' => 'Int'
    ];
	
	public function IsDark() {
		 $luminance = $this->getOwner()->config()->get('luminance');		
		if($this->owner->Luminance > 0 && $this->owner->Luminance <= $luminance){
			return true;
		}
		return false;
	}
	public function IsLight() {
		 $luminance = $this->getOwner()->config()->get('luminance');
		if($this->owner->Luminance > 0 && $this->owner->Luminance > $luminance){
			return true;
		}
		return false;
	}
	
	public function onBeforeWrite() 
    {
		if($this->owner->isInDb()) {
			// lets work with a smaller image
			$image = $this->owner->FitMax(500,500);
			if($image){
				$filename = ASSETS_PATH . '/' . $image->FileName;
				if(file_exists($filename)){				
				//	$brightness = $this->getBrightness($filename);
					$brightness = $this->getAverageLuminance($filename);
					if($brightness){
						$this->owner->Luminance = (int) $brightness;
					}
				}
			}
        }

        parent::onBeforeWrite();
    }
	private function getBrightness($filename) {
		$gdHandle = imagecreatefromjpeg ( $filename );
		
        $width = imagesx($gdHandle);
        $height = imagesy($gdHandle);

        $totalBrightness = 0;

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($gdHandle, $x, $y);

                $red = ($rgb >> 16) & 0xFF;
                $green = ($rgb >> 8) & 0xFF;
                $blue = $rgb & 0xFF;

                $totalBrightness += (max($red, $green, $blue) + min($red, $green, $blue)) / 2;
            }
        }

        imagedestroy($gdHandle);

        return ($totalBrightness / ($width * $height)) / 2.55;
    }
	
	// get average luminance, by sampling $num_samples times in both x,y directions
	private function getAverageLuminance($filename, $num_samples=20) {
        $gdHandle = @$this->imagecreatefromfile($filename);
		if($gdHandle){
			$width = imagesx($gdHandle);
			$height = imagesy($gdHandle);
	
			$x_step = intval($width/$num_samples);
			$y_step = intval($height/$num_samples);
	
			$total_lum = 0;
	
			$sample_no = 1;
	
			for ($x=0; $x<$width; $x+=$x_step) {
				for ($y=0; $y<$height; $y+=$y_step) {
	
					$rgb = imagecolorat($gdHandle, $x, $y);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
	
					// choose a simple luminance formula from here
					// http://stackoverflow.com/questions/596216/formula-to-determine-brightness-of-rgb-color
					$lum = ($r+$r+$b+$g+$g+$g)/6;
	
					$total_lum += $lum;
	
					// debugging code
		 //           echo "$sample_no - XY: $x,$y = $r, $g, $b = $lum<br />";
					$sample_no++;
				}
			}
	
	//        imagedestroy($gdHandle);
			
			// work out the average
			$avg_lum  = $total_lum / $sample_no  / 2.55;
       		return $avg_lum;
		}
		return false;
    }
	
	private function imagecreatefromfile( $filename ) {
		if (!file_exists($filename)) {
			return false;
		}
		switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
			case 'jpeg':
			case 'jpg':
				return imagecreatefromjpeg($filename);
			break;
	
			case 'png':
				return imagecreatefrompng($filename);
			break;
	
			case 'gif':
				return imagecreatefromgif($filename);
			break;
	
			default:
				return false;
			break;
		}
	}

}
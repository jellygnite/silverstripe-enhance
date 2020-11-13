<?php


namespace Jellygnite\Enhance\Extensions;

use SilverStripe\Admin\LeftAndMainExtension;
use SilverStripe\View\Requirements;

use SilverStripe\Dev\Debug;

class CMSExtension extends LeftAndMainExtension
{
    public function init()
    {
        parent::init();
		
        Requirements::javascript('jellygnite/silverstripe-enhance:client/dist/javascript/custom.js');
        Requirements::css('jellygnite/silverstripe-enhance:client/dist/css/custom.css');
		
    }

}
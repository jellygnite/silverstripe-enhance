<?php

namespace Jellygnite\Enhance\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\View\SSViewer;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\Debug;

/**
 * class LinkExtension
 * =============================
 *
 * Extends Sheadawson\Linkable\Models\Link;
 *
 * License: MIT-style license http://opensource.org/licenses/MIT
 * 
 * add this to your mysite.yml
 
Sheadawson\Linkable\Models\Link:
  extensions:
    - Jellygnite\Enhance\Extensions\LinkExtension
 *
 *
**/

class LinkExtension extends DataExtension 
{
 	// checks if URL is an anchor and keeps link on same page due to anchor rewrite disabled.
    public function updateLinkURL(&$LinkURL) 
    {
		$rewrite_hash_links = Config::inst()->get(SSViewer::class, 'rewrite_hash_links');
		$type = $this->owner->Type;
		if($type == 'URL' && substr($LinkURL,0,1) == '#' && !$rewrite_hash_links) {
		
			$LinkURL = Controller::join_links( Controller::curr()->Link() , $LinkURL);

		}
    }
}
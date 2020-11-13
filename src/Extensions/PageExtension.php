<?php

namespace Jellygnite\Enhance\Extensions;

use DNADesign\Elemental\Forms\TextCheckboxGroupField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextAreaField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Security\Permission;
use SilverStripe\Dev\Debug;
/**
 * 
 * adds the content of the entire page
 * 
 */

class PageExtension extends DataExtension {
	
	private static $enable_summary = false;
	
	private static $db = [
        'Summary' => 'Text',
        'ShowTitle' => 'Boolean',
	];
	
	private static $defaults = array (
		'ShowTitle' => '1'
	);

	
    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields) 
    {
        parent::updateCMSFields($fields);
		 // Add a combined field for "Title" and "Displayed" checkbox in a Bootstrap input group
		$fields->removeByName('ShowTitle');
		$fields->replaceField(
			'Title',
			TextCheckboxGroupField::create(_t(__CLASS__ . '.TitleLabel', 'Page Title (displayed if checked)'))

		);
		
        $enable_summary = $this->owner->config()->get('enable_summary');
		if($enable_summary){
			$fields->insertBefore(
				TextAreaField::create("Summary", "Summary")
					->setRightTitle('If no summary is specified the first 50 words will be used from the Content field.')
					->setRows(3), 
				'Content'
			);      
		}
		
    }

	public function getClassBasename(){
        $c = basename(str_replace('\\', '/', get_class($this->owner)));
        return $c;
    }

	public function Summary($wordsToDisplay = 50) {
		
        $enable_summary = $this->owner->config()->get('enable_summary');
		if($enable_summary){
			if($this->owner->Summary){
				return $this->owner->Summary;
			}		
	        return DBField::create_field('HTMLText', $this->owner->Content)->Summary($wordsToDisplay);
		}
    }

    public function IsAdmin()
    {
        return Permission::check('ADMIN');
    }
}
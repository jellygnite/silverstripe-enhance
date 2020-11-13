# Jellygnite Enhance

## Introduction

This contains various improvements to the Silverstripe installation.

## Requirements

* SilverStripe ^4.0

## Installation

```
composer require jellygnite/silverstripe-enhance
```

## Notes

### Get Class Basename
Can be used in templates to get simpified version of PageClass. e.g.

```
<html class="$ClassBasename">
```

### Image Brightness
Occasionally when you use an Image as a slide background or similar and you want to overlay text on it, it is good to know if the Image is light or dark so you can choose the respective text colour.

This extension adds a new column `Luminance` to the Image table that stores the image's brightness.

You can call the $IsDark or $IsLight function in your template. e.g. 

```
<li class="slide<% if $Image.IsDark %> invert<% end_if %>">
```

### SVG Files
Create an object using the File::class not Image::class as SVG files aren't the same as binary data image files.

```
private static $has_one = [
  "Logo" => File::class
];
```

Then when adding the upload field to your FieldsList, only allow normal images and the new category 'image/unsupported'. This will allow the user to upload standard image files and SVG files.

```
$uploadField->setAllowedFileCategories(['image/supported','image/unsupported']);
```

You can then refer to the image in your template using all the image manipulation functions. You don't have to check if it is an SVG first. Any image manipulation functions will be ignored and the orginal SVG will be returned in an <img src="$URL"> tag.

If you are allowing SVG uploads, to avoid potential for script execution in malicious SVG files, it is safer to use in your template. Best to limit uploads to admin only, i.e. don't enable SVG upload in front end.

```
$Logo.ScaleWidth(250)
<img src="$Logo.ScaleWidth(250).URL" alt="$Logo.Title.ATT">
```

Check if an image is an SVG.

```
<% if $Logo.IsSVG() %>do something<% end_if %>
```

### SiteConfig Extension
The following fields are added to Settings.
```
    private static $db = [
		"AdministratorEmail" => "Varchar(254)",
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
    
	private static $has_one = [
	    "Logo" => File::class,
	    "ExtraLogo" => File::class,
	    "DefaultBanner" => Image::class
	]
```

Map embed code method allows you to embed a simple google map iframe without using the Google Maps API. Most of the time this is all that is needed so saves the hassle of getting an account set up for the client.

The map is created based on the data entered into MapAddress. Or a precise location can be added using Latitude and Longitude.

```
<div id="gmap">
$SiteConfig.MapEmbedCode
</div>
```

### WebP Support
Support for webP images is added. This will only work if you have webP functions available in GD or ImageMagick.

Check if an image is webP.

```
<% if $Logo.IsWebP() %>do something<% end_if %>
```


TODO:
Thumbnails in CMS. I think this is in asset-admin/client/dist/js/bundle.js


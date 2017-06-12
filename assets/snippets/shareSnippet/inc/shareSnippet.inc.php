<?php
if (!defined('MODX_BASE_PATH')) { die('What are you doing? Get out of here!'); }

/*

Plattforms
  - Facebook (Image, Link)
  - Twitter (Image, Link)
  - Xing 
  - LinkedIn
  - Google Plus
  - Pinterest
  - Instagram
  - Tumblr

Directories
  - shareSnippet/schemes : Keep configs and schemes for correct sharing of media to different platforms
  - shareSnippet/tpl     : default HTML-templates (can be replaced by customized chunks)
  - shareSnippet/style   : optional style-related files

Param &get
  - "button": Get a single Button
  - "button": Get a single Button
    

[[shareSnippet?
	&get=`button,list`

	&platforms=`all | twitter,facebook,googleplus,linkedin,pinterest,vk,xing,tumblr,reddit`
	&twitter_handle=``
	
	&style=`default`
	&outerTpl=`default.outerTpl`
	&rowTpl=`default.rowTpl`

	&title=`[*pagetitle*]`
	&url=`[~[*id*]~] (yams_doc:[*id*]`
	&summary=`[*introtext*]`
	&image=`[*image_tv*]`
	&description=`[*image_description*]`

	&autoSummary=`0`
	&summaryLength=`100`
]]

*/

require_once(MODX_BASE_PATH.'assets/snippets/shareSnippet/inc/class.shareSnippet.php');

$config = array(
	'get'               => isset($get)  ? $get  : 'list',
	'platforms'         => !isset($platforms) || $platforms == 'all'    ? 'twitter,facebook,googleplus,linkedin,pinterest,vk,xing,tumblr,reddit' : $platforms,
	'twitter_handle'    => isset($twitter_handle)  ? $twitter_handle    : '',
	'lang'              => isset($lang)            ? $lang              : 'english',
	
	'style'             => isset($style)           ? $style             : 'default',
	'outerTpl'          => isset($outerTpl)        ? $outerTpl          : 'default.outerTpl',
	'rowTpl'            => isset($rowTpl)          ? $rowTpl            : 'default.rowTpl',
	
	'title'         => isset($title)        ? $title        : '',
	'url'           => isset($url)          ? $url          : '',
	'summary'       => isset($summary)      ? $summary      : '',
	'image'         => isset($image)        ? $image        : '',
	'description'   => isset($description)  ? $description  : '',
	
	'autoSummary'   => isset($autoSummary)  ? $autoSummary      : false,
	'summaryLength' => isset($summaryLength)? $summaryLength    : 100,
	'target'        => isset($target)       ? trim($target).' ' : 'target="_blank" ',
);

$ss = new shareSnippet( $config );

return $ss->output();
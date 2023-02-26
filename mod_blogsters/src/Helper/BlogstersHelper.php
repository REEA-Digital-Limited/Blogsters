<?php

/**
 * @package     Blogsters
 * @subpackage  mod_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Module\Blogsters\Site\Helper;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

class BlogstersHelper
{
    public static function getBlogsters($params)
    { 
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$latest_blogs_number = $params->get('latest_blogs_number', 3);

        $query = $db->getQuery(true)
            ->select('a.*')
            ->from('#__blogsters AS a')
            ->where('a.state = 1 ')
			->order('id DESC')
            ->setLimit($latest_blogs_number);
        $db->setQuery($query);
		$query = $db->loadObjectList();
		
		return $query;
    }
	public static function getPortfoliocasesCategories($catIDs)
	{
		$catIDs = explode(',', (string)$catIDs);
		$formatIDs = '';
		foreach ($catIDs as $ids) {
			$formatIDs .= "'" . $ids . "',";
		}
		$formatIDs = rtrim($formatIDs, ",");

		$db = Factory::getDbo();
		$objectDisplay = '';
		$queryObj = $db->getQuery(true);
		$queryObj->select('a.title');
		$queryObj->from('#__blogsters_categories AS a');
		$queryObj->where("a.state = 1 AND a.id IN (" . $formatIDs . ")");
		$db->setQuery($queryObj);
		$resultObj = $db->loadObjectList();
		foreach ($resultObj as $obj) {
			$objectDisplay .= BlogstersModel::stringURLSafe(@$obj->title) . ' ';
		}

		$objectDisplay = rtrim($objectDisplay, " ");
		return $objectDisplay;
	}
	public static function getThumbsUpPercentage($post_id)
	{ 
		$db = Factory::getDbo();
		$upvoatePercent = '';

		$queryObjCNT = $db->getQuery(true);
		$queryObjCNT->select('COUNT(up_vote) AS upvote');
		$queryObjCNT->from('#__blogsters_votings');
		$queryObjCNT->where('up_vote = 1');
		$queryObjCNT->where('state = 1');
		$queryObjCNT->where('post_id = '.$post_id);
		$db->setQuery($queryObjCNT);
		$resultObjCNT = $db->loadObject();

		$queryObjSUM = $db->getQuery(true);
		$queryObjSUM->select('COUNT(id) AS total');
		$queryObjSUM->from('#__blogsters_votings');
		$queryObjSUM->where('state = 1');
		$queryObjSUM->where('post_id = '.$post_id);
		$db->setQuery($queryObjSUM);
		$resultObjSUM = $db->loadObject();

		if($resultObjSUM->total){
			$upvoatePercent = ($resultObjCNT->upvote * 100)/$resultObjSUM->total;
		}
		else{
			$upvoatePercent = 0;
		}

		return $upvoatePercent.'%';
	}
	public static function getBlogCategorySpaceSeparated($cat_id){
		$db = Factory::getDbo();
		$objectDisplay = '';

		$queryObj = $db->getQuery(true);
		$queryObj->select('a.*');
		$queryObj->from('#__blogsters_categories AS a');
		$queryObj->where('a.state = 1');
		$queryObj->where('a.id = '.$cat_id);
		$db->setQuery($queryObj);
		$resultObj = $db->loadObject();
		$slug = BlogstersHelper::getTitleSlug($resultObj->title);
		$bloglink = Route::_(BlogstersHelper::getBlogCatRoute($resultObj->id, $slug));
		if(strstr( $bloglink, 'component/blogsters')){
			$bloglink = str_replace("component/blogsters/","blog/",$bloglink);
			$bloglink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $bloglink);
			$bloglink = str_replace("?Itemid","",$bloglink);
		}
		$objectDisplay .= '<a href="'.$bloglink.'" class="cat list">'.$resultObj->title.'</a>';

		return $objectDisplay;
	}
	public static function getBlogTagSpaceSeparated($tags_id){
		$db = Factory::getDbo();
		$objectDisplay = '';
		$tagData = explode(',',$tags_id);

		foreach($tagData as $tagObj){
			$queryObj = $db->getQuery(true);
			$queryObj->select('a.*');
			$queryObj->from('#__blogsters_tags AS a');
			$queryObj->where('a.state = 1');
			$queryObj->where('a.id = '.$tagObj);
			$db->setQuery($queryObj);
			$resultObj = $db->loadObject();
			$slug = BlogstersHelper::getTitleSlug($resultObj->title);
			$ajaxMoreLink = Route::_(BlogstersHelper::getBlogTagRoute($resultObj->id, $slug));
			$ajaxMoreLink = str_replace("component/blogsters/","blog/",$ajaxMoreLink);
			$ajaxMoreLink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $ajaxMoreLink);
			$ajaxMoreLink = str_replace("?Itemid","",$ajaxMoreLink);			
			
			$objectDisplay .= '<a href="'.$ajaxMoreLink.'" class="tag list">'.$resultObj->title.'</a>';
		}
		return $objectDisplay;
	}
	public static function getTitleSlug($title)
	{
		$slug = preg_replace('/[^a-z\d]/i', '-', $title);
		$slug = strtolower(str_replace(' ', '-', $slug));

		return $slug;
	}
	public static function getBlogRoute($id, $slug, $language = 0, $layout = null)
	{
		$link = 'index.php?option=com_blogsters&view=blogster&id=' . $id . '&slug=' . $slug;

		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		if ($layout)
		{
			$link .= '&layout=' . $layout;
		}

		return $link;
	}
	public static function getBlogCatRoute($id, $slug, $language = 0, $layout = null)
	{
		$link = 'index.php?option=com_blogsters&view=blogcats&id=' . $id . '&slug=' . $slug;

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		if ($layout) {
			$link .= '&layout=' . $layout;
		}

		return $link;
	}
	public static function getBlogTagRoute($id, $slug, $language = 0, $layout = null)
	{
		$link = 'index.php?option=com_blogsters&view=blogtags&id=' . $id . '&slug=' . $slug;

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		if ($layout) {
			$link .= '&layout=' . $layout;
		}

		return $link;
	}
}

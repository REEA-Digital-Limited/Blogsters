<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

abstract class RouteHelper
{
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
	public static function getMoreBlogItems($next, $list_limit)
	{
		$next = (int)$next * (int)$list_limit;
		$old = $next - $list_limit;
		if ($old <= 0) {
			$old = 0;
		}

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('a.*')
			->from('#__blogsters as a')
			->where('a.state=1')
			->order('a.is_featured DESC')
			->setLimit($list_limit, $old);

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
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
			$objectDisplay .= RouteHelper::stringURLSafe(@$obj->title) . ' ';
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
		$slug = RouteHelper::getTitleSlug($resultObj->title);
		$bloglink = Route::_(RouteHelper::getBlogCatRoute($resultObj->id, $slug));
		
		if(strstr( $bloglink, 'component/blogsters')){
			$bloglink = str_replace("component/blogsters/","blog/",$bloglink);
			$bloglink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $bloglink);
			$bloglink = str_replace("&amp;Itemid","",$bloglink);
		}
		
		$objectDisplay .= '<a href="'.$bloglink.'" class="cat list">'.$resultObj->title.'</a>';

		return $objectDisplay;
	}
	public static function getBlogCategorySpaceSeparatedByCategory($cat_id){
		$db = Factory::getDbo();
		$objectDisplay = '';

		$queryObj = $db->getQuery(true);
		$queryObj->select('a.*');
		$queryObj->from('#__blogsters_categories AS a');
		$queryObj->where('a.state = 1');
		$queryObj->where('a.id = '.$cat_id);
		$db->setQuery($queryObj);
		$resultObj = $db->loadObject();
		$slug = RouteHelper::getTitleSlug($resultObj->title);
		$bloglink = Route::_(RouteHelper::getBlogCatRoute($resultObj->id, $slug));
		if(strstr( $bloglink, 'component/blogsters')){
			$bloglink = str_replace("component/blogsters/","blog/",$bloglink);
			$bloglink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $bloglink);
			$bloglink = str_replace("&amp;Itemid","",$bloglink);
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
			$slug = RouteHelper::getTitleSlug($resultObj->title);
			$bloglink = Route::_(RouteHelper::getBlogTagRoute($resultObj->id, $slug));

			if(strstr( $bloglink, 'component/blogsters')){
				$bloglink = str_replace("component/blogsters/","blog/",$bloglink);
				$bloglink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $bloglink);
				$bloglink = str_replace("&amp;Itemid","",$bloglink);
			}
			
			$objectDisplay .= '<a href="'.$bloglink.'" class="tag list">'.$resultObj->title.'</a>';
		}
		return $objectDisplay;
	}
	public static function getBlogTagSpaceSeparatedByTags($tags_id){
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
			$slug = RouteHelper::getTitleSlug($resultObj->title);
			$bloglink = Route::_(RouteHelper::getBlogTagRoute($resultObj->id, $slug));
			if(strstr( $bloglink, 'component/blogsters')){
				$bloglink = str_replace("component/blogsters/","blog/",$bloglink);
				$bloglink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $bloglink);
				$bloglink = str_replace("&amp;Itemid","",$bloglink);
			}
			$objectDisplay .= '<a href="'.$bloglink.'" class="tag list">'.$resultObj->title.'</a>';
		}
		return $objectDisplay;
	}
	public static function getTitleSlug($title)
	{
		$slug = preg_replace('/[^a-z\d]/i', '-', $title);
		$slug = strtolower(str_replace(' ', '-', $slug));

		return $slug;
	}
	public static function stringURLSafe($string)
	{
		$str = str_replace('-', ' ', $string);
		$str = str_replace('_', ' ', $string);

		$str = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $str);

		$str = trim(strtolower($str));
		return $str;
	}

	public static function truncateString($text, $length = 0)
	{
		if ($length > 0 && strlen($text) > $length)
		{
				$tmp = substr($text, 0, $length);
				$tmp = substr($tmp, 0, strrpos($tmp, ' '));

				if (strlen($tmp) >= $length - 3) {
						$tmp = substr($tmp, 0, strrpos($tmp, ' '));
				}

				$text = $tmp.'...';
		}

		return $text;
	}
	public static function getAuthorInfo($authorID){
		$db = Factory::getDbo();
		$queryObj = $db->getQuery(true);
		$queryObj->select('a.*');
		$queryObj->from('#__blogsters_authors AS a');
		$queryObj->where('a.state = 1');
		$queryObj->where('a.id = '.$authorID);
		$db->setQuery($queryObj);
		$resultObj = $db->loadObject();

		return $resultObj;
	}
	public static function checkIfVotedAlready ($id){
		$db = Factory::getDbo();
		$queryObj = $db->getQuery(true);
		$queryObj->select('a.*');
		$queryObj->from('#__blogsters_votings AS a');
		$queryObj->where('a.post_id = '.$id);
		$db->setQuery($queryObj);
		$resultObj = $db->loadObject();

		if(@$resultObj->id){
			return @$resultObj->id;
		}
		else{
			return false;
		}
	}
	public static function getUserIpAddress() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
	}
	public static function getMoreBlogItemsByCategory($cat, $next, $list_limit)
	{	
		$next = (int)$next * (int)$list_limit;
		$old = $next - $list_limit;
		if ($old <= 0) {
			$old = 0;
		}

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('a.*')
			->from('#__blogsters as a')
			->where('a.state=1')
			->where('a.cat_id ='.$cat)
			->order('a.id DESC')
			->setLimit($list_limit, $old);

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}

	public static function getMoreBlogItemsByTags($id, $next, $list_limit)
	{	
		$next = (int)$next * (int)$list_limit;
		$old = $next - $list_limit;
		if ($old <= 0) {
			$old = 0;
		}

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('a.*')
			->from('#__blogsters as a')
			->where('a.state=1')
			->where('FIND_IN_SET("'.$id.'",a.tags_id)')
			->order('a.id DESC')
			->setLimit($list_limit, $old);

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}

}
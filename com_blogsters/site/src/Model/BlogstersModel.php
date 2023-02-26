<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Component\Blogsters\Site\Helper\RouteHelper as BlogstersHelperRoute;

class BlogstersModel extends ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		$app = Factory::getApplication();
		$paramsCom = $app->getParams();
		$list_limit = $paramsCom->get('list_limit');
		
		$this->setState('list.limit', $list_limit);
		$this->setState('list.start', 0);

		$params = $app->getParams();
		$this->setState('params', $params);

	}

	protected function getStoreId($id = '')
	{
		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		$user = Factory::getUser();

		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$this->getState(
				'list.select',
				'a.*')
		);
		$query->from('#__blogsters AS a');
		$query->where('a.state=1');
		$query->where('a.primary_is_featured !=1');
		$query->order('a.is_featured DESC');

		return $query;
	}

	public function getItems()
	{
		$items  = parent::getItems();
		return $items;
	}

	public function getStart()
	{
		return $this->getState('list.start');
	}

	public static function getPrimaryFeaturedPost(){
		$db = Factory::getDBO();    
		$query = $db->getQuery(true);

		$query->select('a.*');
		$query->from('#__blogsters AS a');
		$query->where('a.state=1');
		$query->where('a.primary_is_featured=1');
		$query->order('a.primary_is_featured DESC');
		$query->setLimit('1');
		$db->setQuery($query);    
		$rows = $db->loadObject();

		return $rows;	
	}
	public static function getCategoryList()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__blogsters_categories');
		$query->where('state = 1');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	public static function getCommaSeparatedCategories()
	{
		$db = Factory::getDbo();
		$objectDisplay = '';
		$queryObj = $db->getQuery(true);
		$queryObj->select('a.title');
		$queryObj->from('#__blogsters_categories AS a');
		$queryObj->where('a.state = 1');
		$db->setQuery($queryObj);
		$resultObj = $db->loadObjectList();
		foreach ($resultObj as $obj) {
			$objectDisplay .= '.' . BlogstersModel::stringURLSafe(@$obj->title) . ', ';
		}

		$objectDisplay = rtrim($objectDisplay, ", ");

		return $objectDisplay;
	}
	public static function stringURLSafe($string)
	{
		$str = str_replace('-', ' ', $string);
		$str = str_replace('_', ' ', $string);

		$str = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $str);

		$str = trim(strtolower($str));
		return $str;
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

		$slug = BlogstersModel::getTitleSlug($resultObj->title);
		$objectDisplay .= '<a href="'.BlogstersHelperRoute::getBlogCatRoute($resultObj->id, $slug).'" class="cat list">'.$resultObj->title.'</a>';

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
			$slug = BlogstersModel::getTitleSlug($resultObj->title);
			$objectDisplay .= '<a href="'.BlogstersHelperRoute::getBlogTagRoute($resultObj->id, $slug).'" class="tag list">'.$resultObj->title.'</a>';
		}
		return $objectDisplay;
	}
	public static function getTitleSlug($title)
	{
		$slug = preg_replace('/[^a-z\d]/i', '-', $title);
		$slug = strtolower(str_replace(' ', '-', $slug));

		return $slug;
	}
}

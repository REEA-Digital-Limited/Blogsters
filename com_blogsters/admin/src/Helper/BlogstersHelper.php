<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class BlogstersHelper
{
	public static function getBlogsterTitle($id)
	{
		if (empty($id))
		{
			// throw an error or ...
			return false;
		}
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('title');
		$query->from('#__blogsters');
		$query->where('id = ' . $id);
		$db->setQuery($query);
		return $db->loadObject();
	}

	public static function getItemsByCommaSeparated($itemObject, $dbTable)
	{
		if (empty($itemObject)) {
			return false;
		}
		$db = Factory::getDbo();
		$objectID = explode(',', (string)$itemObject);
		$objectDisplay = '';
		if ($objectID[0]) {
			foreach ($objectID as $objid) {
				$queryObj = $db->getQuery(true);
				$queryObj->select('a.*');
				$queryObj->from('' . $dbTable . ' AS a');
				$queryObj->where('(a.id = ' . $objid . ')');
				$db->setQuery($queryObj);
				$resultObj = $db->loadObjectList();
				$objectDisplay .= '<li>' . @$resultObj[0]->title . '</li>';
			}
		}

		return '<ul>' . $objectDisplay . '</ul>';
	}
	public static function getUpVoteCount($id)
	{
		if (empty($id))
		{
			return false;
		}
		
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(id) AS total');
		$query->from('#__blogsters_votings');
		$query->where('up_vote = 1');
		$query->where('post_id = ' . $id);
		$db->setQuery($query);

		return $db->loadObject();
	}
	public static function getDownVoteCount($id)
	{
		if (empty($id))
		{
			return false;
		}

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(id) AS total');
		$query->from('#__blogsters_votings');
		$query->where('down_vote = 1');
		$query->where('post_id = ' . $id);
		$db->setQuery($query);

		return $db->loadObject();
	}
	public static function getTotalVoteCount($id)
	{
		if (empty($id))
		{
			return false;
		}

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(id) AS total');
		$query->from('#__blogsters_votings');
		$query->where('post_id = ' . $id);
		$db->setQuery($query);

		return $db->loadObject();
	}
}
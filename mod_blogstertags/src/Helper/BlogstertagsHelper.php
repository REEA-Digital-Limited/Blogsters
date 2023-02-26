<?php

/**
 * @package     Blogsters
 * @subpackage  mod_blogstertags
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Module\Blogstertags\Site\Helper;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class BlogstertagsHelper
{
    public static function getBlogstertags($params)
    { 
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$max_tags_number = $params->get('max_tags_number', 3);

        $query = $db->getQuery(true)
            ->select('a.*')
            ->from('#__blogsters_tags AS a')
            ->where('a.state = 1 ')
			->order('id DESC')
            ->setLimit($max_tags_number);
        $db->setQuery($query);
		$query = $db->loadObjectList();
		
		return $query;
    }
	public static function getTitleSlug($title)
	{
		$slug = preg_replace('/[^a-z\d]/i', '-', $title);
		$slug = strtolower(str_replace(' ', '-', $slug));

		return $slug;
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

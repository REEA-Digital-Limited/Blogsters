<?php

/**
 * @package     Blogsters
 * @subpackage  mod_blogstertags
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\Blogstertags\Site\Helper\BlogstertagsHelper;

$max_tags_number = $params->get('max_tags_number', 30);

$blogstertags = BlogstertagsHelper::getBlogstertags($params);

require ModuleHelper::getLayoutPath('mod_blogstertags', $params->get('layout', 'default'));

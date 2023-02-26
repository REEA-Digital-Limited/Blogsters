<?php

/**
 * @package     Blogsters
 * @subpackage  mod_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\Blogsters\Site\Helper\BlogstersHelper;

$latest_blogs_number = $params->get('latest_blogs_number', 3);

$blogsters = BlogstersHelper::getBlogsters($params);

require ModuleHelper::getLayoutPath('mod_blogsters', $params->get('layout', 'default'));

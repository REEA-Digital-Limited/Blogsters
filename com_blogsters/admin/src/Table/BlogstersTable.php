<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Administrator\Table;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;

class BlogstersTable extends Table
{
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__blogsters', 'id', $db);
	}
}

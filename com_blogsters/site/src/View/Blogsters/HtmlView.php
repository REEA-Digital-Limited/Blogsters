<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Site\View\Blogsters;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
	protected $state;
	protected $items;
	protected $pagination;
	protected $params = null;

	public function display($tpl = null)
	{
		$app    = Factory::getApplication();
		$params = $app->getParams();

		$state      = $this->get('State');
		$items      = $this->get('Items');
		$pagination = $this->get('Pagination');

		$pagination->hideEmptyLimitstart = true;

		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->state      = &$state;
		$this->items      = &$items;
		$this->params     = &$params;
		$this->pagination = &$pagination;

		return parent::display($tpl);
	}
}

<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Site\View\Blogster;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
	protected $state;
	protected $item;
	protected $reports;

	public function display($tpl = null)
	{ 	
		$state      = $this->get('State');
		$item       = $this->get('Item');
		$reports    = $this->get('Reports');

		$this->state       = &$state;
		$this->item        = &$item;
		$this->reports     = &$reports;

		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		return parent::display($tpl);
	}
}

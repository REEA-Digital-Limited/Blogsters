<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Administrator\View\Blogster;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
	protected $form;
	protected $item;
	protected $state;
	protected $canDo;

	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$isNew      = ($this->item->id == 0);

		$canDo = ContentHelper::getActions('com_blogsters');

		$toolbar = Toolbar::getInstance();

		ToolbarHelper::title(
			Text::_('COM_BLOGSTERS_PAGE_TITLE_' . ($isNew ? 'ADD_BLOGSTER' : 'EDIT_BLOGSTER'))
		);

		if ($canDo->get('core.create'))
		{
			if ($isNew)
			{
				$toolbar->apply('blogster.save');
				ToolbarHelper::save('blogster.save');
			}
			else
			{
				$toolbar->apply('blogster.apply');
				ToolbarHelper::save('blogster.save');
			}
		}
		$toolbar->cancel('blogster.cancel', 'JTOOLBAR_CLOSE');
	}
}

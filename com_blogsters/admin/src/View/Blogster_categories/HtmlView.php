<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Administrator\View\Blogster_categories;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
	public $filterForm;
	public $activeFilters;

	public function display($tpl = null)
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	protected function addToolbar()
	{
		$canDo = ContentHelper::getActions('com_blogsters');
		$user  = Factory::getUser();

		$toolbar = Toolbar::getInstance('toolbar');

		ToolbarHelper::title(Text::_('COM_BLOGSTERS_CATEGORIES_PAGE_TITLE'), 'blogster_categories');

		if ($canDo->get('core.create'))
		{
			$toolbar->addNew('blogster_categorie.add');
		}

		if ($canDo->get('core.edit.state'))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('icon-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();
			$childBar->publish('blogster_categories.publish')->listCheck(true);
			$childBar->unpublish('blogster_categories.unpublish')->listCheck(true);
			$childBar->archive('blogster_categories.archive')->listCheck(true);

			if ($this->state->get('filter.published') != -2)
			{
				$childBar->trash('blogster_categories.trash')->listCheck(true);
			}
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			$toolbar->delete('blogster_categories.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
		}
		ToolbarHelper::link('index.php?option=com_blogsters', Text::_('COM_BLOGSTERS_DASHBOARD'), 'dashboard');
		ToolbarHelper::link('index.php?option=com_blogsters&view=blogster_categories', Text::_('COM_BLOGSTERS_CATEGORIES'), 'list-2');
		ToolbarHelper::link('index.php?option=com_blogsters&view=blogster_tags', Text::_('COM_BLOGSTERS_TAGS'), 'tags');
		ToolbarHelper::link('index.php?option=com_blogsters&view=blogster_authors', Text::_('COM_BLOGSTERS_AUTHORS'), 'users');
	}

	protected function getSortFields()
	{
		return array(
			'a.created'      => Text::_('COM_BLOGSTERS_CREATED_LABEL'),
			'a.id'           => Text::_('JGRID_HEADING_ID'),
		);
	}
}

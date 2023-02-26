<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

class Blogster_tagsController extends AdminController
{
	protected $default_view = 'blogster_tags';

	public function display($cachable = false, $urlparams = array())
	{
		$view   = $this->input->get('view', $this->default_view);
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		if ($view == 'blogster_tag' && $layout == 'edit' && !$this->checkEditId('com_blogsters.edit.blogster_tag', $id))
		{
			$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			$this->setRedirect(Route::_('index.php?option=com_blogsters&view=blogster_tags', false));

			return false;
		}

		return parent::display();
	}

	public function getModel($name = 'Blogster_tag', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}

<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

class BlogsterModel extends AdminModel
{
	protected $text_prefix = 'COM_BLOGSTERS';

	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			return Factory::getUser()->authorise('core.delete', 'com_blogsters.blogsters.' . (int) $record->id);
		}

		return false;
	}

	protected function canEditState($record)
	{
		$user = Factory::getUser();

		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_blogsters.blogsters.' . (int) $record->id);
		}

		return parent::canEditState($record);
	}

	public function getTable($name = '', $prefix = '', $options = array())
	{
		$name = 'blogsters';
		$prefix = 'Table';

		if ($table = $this->_createTable($name, $prefix, $options))
		{
			return $table;
		}

		throw new \Exception(Text::sprintf('JLIB_APPLICATION_ERROR_TABLE_NAME_NOT_SUPPORTED', $name), 0);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_blogsters.blogster', 'blogster', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$app = Factory::getApplication();
		$data = $app->getUserState('com_blogsters.edit.blogster.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_blogsters.blogster', $data);

		if ($data->tags_id) {
			$data->tags_id = explode(',', (string)$data->tags_id);
		}

		return $data;
	}

	public function publish(&$pks, $value = 1) {
		$db = $this->getDbo();

		$query = $db->getQuery(true);

		$query->update('`#__blogsters`');
		$query->set('state = ' . $value);
		$query->where('id IN (' . implode(',', $pks). ')');
		$db->setQuery($query);
		$db->execute();
	}
}

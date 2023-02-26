<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;

class BlogsterModel extends ItemModel
{
	protected $_context = 'com_blogsters.blogster';

	protected function populateState()
	{
		$app = Factory::getApplication();

		$pk = $app->input->getInt('id');
		$this->setState('blogster.id', $pk);

		$offset = $app->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		$params = $app->getParams();
		$this->setState('params', $params);
	}

	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('blogster.id');

			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.*'
						)
					);
				$query->from('#__blogsters AS a')
					->where('a.id = ' . (int) $pk);

				$db->setQuery($query);

				$data = $db->loadObject();

				if (empty($data))
				{
					throw new \Exception(Text::_('COM_BLOGSTERS_ERROR_NOT_FOUND'), 404);
				}
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					throw new \Exception($e->getMessage(), 404);
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}

		return $data;
	}
	
}

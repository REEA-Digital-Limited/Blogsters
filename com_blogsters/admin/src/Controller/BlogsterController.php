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

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Date\Date;

class BlogsterController extends FormController
{
	protected function postSaveHook($model, $validData = array())
	{
		if (isset($validData['tags_id'])) {
			$data['tags_id'] = implode(',', $validData['tags_id']);
		}
		if ($validData['alias']) {
			$data['alias'] = ApplicationHelper::stringURLSafe($validData['alias'], $this->language);
		}
        else{
            $data['alias'] = ApplicationHelper::stringURLSafe($validData['title'], $this->language);
        }

		$model->save($data);
	}
}

<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Site\Service;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\RulesInterface;

class BlogstersNomenuRules implements RulesInterface
{
	protected $router;

	public function __construct(RouterView $router)
	{
		$this->router = $router;
	}

	public function preprocess(&$query)
	{
		$test = 'Test';
	}

	public function parse(&$segments, &$vars)
	{
		$vars['view'] = 'blogster';
		$vars['id'] = substr($segments[0], strpos($segments[0], '-') + 1);
		array_shift($segments);
		array_shift($segments);
		return;
	}

	public function build(&$query, &$segments)
	{
		if (!isset($query['view']) || (isset($query['view']) && $query['view'] !== 'blogster') || isset($query['format']))
		{
			return;
		}
		$segments[] = $query['view'] . '-' . $query['id'];

		if (isset($query['slug'])) {
			$segments[] = $query['slug'];
			unset($query['slug']);
		}
		unset($query['view']);
		unset($query['id']);
	}
}


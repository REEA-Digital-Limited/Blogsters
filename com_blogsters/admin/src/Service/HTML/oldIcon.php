<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Content\Administrator\Service\HTML;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Workflow\Workflow;
use Joomla\Component\Mailto\Site\Helper\MailtoHelper;
use Joomla\Registry\Registry;

class Icon
{

	private $application;

	public function __construct(CMSApplication $application)
	{
		$this->application = $application;
	}

	public function create($category, $params, $attribs = array(), $legacy = false)
	{
		$uri = Uri::getInstance();

		$url = 'index.php?option=com_content&task=article.add&return=' . base64_encode($uri) . '&a_id=0&catid=' . $category->id;

		$text = LayoutHelper::render('joomla.content.icons.create', array('params' => $params, 'legacy' => $legacy));

		// Add the button classes to the attribs array
		if (isset($attribs['class']))
		{
			$attribs['class'] .= ' btn btn-primary';
		}
		else
		{
			$attribs['class'] = 'btn btn-primary';
		}

		$button = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		$output = '<span class="hasTooltip" title="' . HTMLHelper::_('tooltipText', 'COM_CONTENT_CREATE_ARTICLE') . '">' . $button . '</span>';

		return $output;
	}

	public function email($article, $params, $attribs = array(), $legacy = false)
	{
		$uri      = Uri::getInstance();
		$base     = $uri->toString(array('scheme', 'host', 'port'));
		$template = $this->application->getTemplate();
		$link     = $base . Route::_(\ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language), false);
		$url      = 'index.php?option=com_mailto&tmpl=component&template=' . $template . '&link=' . MailtoHelper::addLink($link);

		$height = Factory::getApplication()->get('captcha', '0') === '0' ? 450 : 550;
		$status = 'width=400,height=' . $height . ',menubar=yes,resizable=yes';

		$text = LayoutHelper::render('joomla.content.icons.email', array('params' => $params, 'legacy' => $legacy));

		$attribs['title']   = Text::_('JGLOBAL_EMAIL_TITLE');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';
		$attribs['class']   = 'dropdown-item';

		return HTMLHelper::_('link', Route::_($url), $text, $attribs);
	}

	public function edit($article, $params, $attribs = array(), $legacy = false)
	{
		$user = Factory::getUser();
		$uri  = Uri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return;
		}

		// Ignore if the state is negative (trashed).
		if (!in_array($article->state, [Workflow::CONDITION_UNPUBLISHED, Workflow::CONDITION_PUBLISHED]))
		{
			return;
		}

		// Set the link class
		$attribs['class'] = 'dropdown-item';

		// Show checked_out icon if the article is checked out by a different user
		if (property_exists($article, 'checked_out')
			&& property_exists($article, 'checked_out_time')
			&& $article->checked_out > 0
			&& $article->checked_out != $user->get('id'))
		{
			$checkoutUser = Factory::getUser($article->checked_out);
			$date         = HTMLHelper::_('date', $article->checked_out_time);
			$tooltip      = Text::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . Text::sprintf('COM_CONTENT_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br> ' . $date;

			$text = LayoutHelper::render('joomla.content.icons.edit_lock', array('tooltip' => $tooltip, 'legacy' => $legacy));

			$output = HTMLHelper::_('link', '#', $text, $attribs);

			return $output;
		}

		$contentUrl = \ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language);
		$url        = $contentUrl . '&task=article.edit&a_id=' . $article->id . '&return=' . base64_encode($uri);

		if ($article->state == Workflow::CONDITION_UNPUBLISHED)
		{
			$overlib = Text::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = Text::_('JPUBLISHED');
		}

		$date   = HTMLHelper::_('date', $article->created);
		$author = $article->created_by_alias ?: $article->author;

		$overlib .= '&lt;br&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br&gt;';
		$overlib .= Text::sprintf('COM_CONTENT_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		$text = LayoutHelper::render('joomla.content.icons.edit', array('article' => $article, 'overlib' => $overlib, 'legacy' => $legacy));

		$attribs['title']   = Text::_('JGLOBAL_EDIT_TITLE');
		$output = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}

	public function print_popup($article, $params, $attribs = array(), $legacy = false)
	{
		$url  = \ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language);
		$url .= '&tmpl=component&print=1&layout=default';

		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		$text = LayoutHelper::render('joomla.content.icons.print_popup', array('params' => $params, 'legacy' => $legacy));

		$attribs['title']   = Text::sprintf('JGLOBAL_PRINT_TITLE', htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8'));
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';
		$attribs['class']   = 'dropdown-item';

		return HTMLHelper::_('link', Route::_($url), $text, $attribs);
	}

	public function print_screen($params, $legacy = false)
	{
		$text = LayoutHelper::render('joomla.content.icons.print_screen', array('params' => $params, 'legacy' => $legacy));

		return '<button type="button" onclick="window.print();return false;">' . $text . '</button>';
	}
}

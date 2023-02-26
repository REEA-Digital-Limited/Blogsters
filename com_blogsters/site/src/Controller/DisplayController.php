<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

namespace Joomla\Component\Blogsters\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\Component\Blogsters\Site\Helper\RouteHelper as BlogstersHelperRoute;

class DisplayController extends BaseController
{
	public function display($cachable = false, $urlparams = array())
	{
		return parent::display();
	}

	public function getLoadMoreBlogItems()
	{	
		if (Factory::getSession()->getFormToken() != Factory::getApplication()->input->get('token')) {
			die('Invalid security token; please reload the page and try again.');
		} else {
			$next = Factory::getApplication()->input->get('next', '');
			$list_limit = Factory::getApplication()->input->get('list_limit', '', 'string');
			$result = BlogstersHelperRoute::getMoreBlogItems($next, $list_limit);
			foreach ($result as $id => $item){
				$ajaxMoreLink = Route::_(BlogstersHelperRoute::getBlogRoute($item->id, $item->alias));
				$ajaxMoreLink = str_replace("component/blogsters/","blog/",$ajaxMoreLink);
				$ajaxMoreLink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $ajaxMoreLink);
				$ajaxMoreLink = str_replace("?Itemid","",$ajaxMoreLink);
		?>
			<div class="blog <?php echo Route::_(BlogstersHelperRoute::getPortfoliocasesCategories($item->cat_id)); ?> ajaxList" data-cat="<?php echo Route::_(BlogstersHelperRoute::getPortfoliocasesCategories($item->cat_id)); ?>" style="display: inline-block;">
				<div class="blog-wrapper">
					<div class="image">
						<?php if(isset($item->featured_image)): ?>
							<a href="<?php echo $ajaxMoreLink; ?>">
								<img src="<?php echo URI::root().$item->featured_image; ?>" alt="<?php echo $item->title; ?>" />
							</a>
						<?php endif; ?>
					</div>
					<div class="metabox">
						<div class="mBox">
							<div class="left">
								<i class="icon-thumbs-up icons"></i> <span class="percentBox"><?php echo BlogstersHelperRoute::getThumbsUpPercentage($item->id); ?></span>
							</div>
							<div class="right">
								<?php if($item->read_time): ?><i class="icon-clock icons"></i> <span class="minutesBox"><?php echo $item->read_time.' min'; ?></span><?php endif; ?>
							</div>
						</div>
					</div>
					<div class="titlebox">
						<a href="<?php echo $ajaxMoreLink; ?>"><h3><?php if($item->is_featured): ?><i class="icon-bookmark"></i><?php endif; ?><?php echo BlogstersHelperRoute::truncateString($item->title,70); ?></h3></a>
					</div>
					<div class="cat-tag-box">
						<div class="mBox">
							<?php echo BlogstersHelperRoute::getBlogCategorySpaceSeparated($item->cat_id); ?>
							<?php echo BlogstersHelperRoute::getBlogTagSpaceSeparated($item->tags_id); ?>
						</div>
					</div>
				</div>
			</div>
		<?php
			}
			exit;
		}
	}
	public function getSetUpVoteCount(){
 		if (Factory::getSession()->getFormToken() != Factory::getApplication()->input->get('token')) {
			die('Invalid security token; please reload the page and try again.');
		} else {
			$id = Factory::getApplication()->input->get('id', '');
		} 

		if(BlogstersHelperRoute::checkIfVotedAlready($id)){
			echo 'exist';
		}
		else{
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$up_vote = 1;
			$down_vote = 0;
			$ip=BlogstersHelperRoute::getUserIpAddress();
			$created = date("Y-m-d H:i:s");
			$state = 1;
			$columns = array('post_id','up_vote','down_vote', 'ip', 'created', 'state');
			$values = array($db->quote($id), $db->quote($up_vote), $db->quote($down_vote), $db->quote($ip), $db->quote($created), $db->quote($state));
			$query
			->insert($db->quoteName('#__blogsters_votings'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
			$db->setQuery($query);
			$db->execute();
		}
		exit;
	}
	public function getSetDownVoteCount(){
 		if (Factory::getSession()->getFormToken() != Factory::getApplication()->input->get('token')) {
			die('Invalid security token; please reload the page and try again.');
		} else {
			$id = Factory::getApplication()->input->get('id', '');
		} 

		if(BlogstersHelperRoute::checkIfVotedAlready($id)){
			echo 'exist';
		}
		else{
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$up_vote = 0;
			$down_vote = 1;
			$ip=BlogstersHelperRoute::getUserIpAddress();
			$created = date("Y-m-d H:i:s");
			$state = 1;
			$columns = array('post_id','up_vote','down_vote', 'ip', 'created', 'state');
			$values = array($db->quote($id), $db->quote($up_vote), $db->quote($down_vote), $db->quote($ip), $db->quote($created), $db->quote($state));
			$query
			->insert($db->quoteName('#__blogsters_votings'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
			$db->setQuery($query);
			$db->execute();
		}
		exit;
	}
	public function getLoadMoreBlogItemsByCategory()
	{	
		if (Factory::getSession()->getFormToken() != Factory::getApplication()->input->get('token')) {
			die('Invalid security token; please reload the page and try again.');
		} else {
			$next = Factory::getApplication()->input->get('next', '');
			$id = Factory::getApplication()->input->get('id', '');
			$list_limit = Factory::getApplication()->input->get('list_limit', '', 'string');
			$result = BlogstersHelperRoute::getMoreBlogItemsByCategory($id, $next, $list_limit);
			foreach ($result as $id => $item){
				$ajaxMoreLink = Route::_(BlogstersHelperRoute::getBlogRoute($item->id, $item->alias));
				$ajaxMoreLink = str_replace("component/blogsters/","blog/",$ajaxMoreLink);
				$ajaxMoreLink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $ajaxMoreLink);
				$ajaxMoreLink = str_replace("?Itemid","",$ajaxMoreLink);
		?>
			<div class="blog <?php echo Route::_(BlogstersHelperRoute::getPortfoliocasesCategories($item->cat_id)); ?> ajaxList" data-cat="<?php echo Route::_(BlogstersHelperRoute::getPortfoliocasesCategories($item->cat_id)); ?>" style="display: inline-block;">
				<div class="blog-wrapper">
					<div class="image">
						<?php if(isset($item->featured_image)): ?>
							<a href="<?php echo $ajaxMoreLink; ?>">
								<img src="<?php echo URI::root().$item->featured_image; ?>" alt="<?php echo $item->title; ?>" />
							</a>
						<?php endif; ?>
					</div>
					<div class="metabox">
						<div class="mBox">
							<div class="left">
								<i class="icon-thumbs-up icons"></i> <span class="percentBox"><?php echo BlogstersHelperRoute::getThumbsUpPercentage($item->id); ?></span>
							</div>
							<div class="right">
								<?php if($item->read_time): ?><i class="icon-clock icons"></i> <span class="minutesBox"><?php echo $item->read_time.' min'; ?></span><?php endif; ?>
							</div>
						</div>
					</div>
					<div class="titlebox">
						<a href="<?php echo $ajaxMoreLink; ?>"><h3><?php if($item->is_featured): ?><i class="icon-bookmark"></i><?php endif; ?><?php echo BlogstersHelperRoute::truncateString($item->title,70); ?></h3></a>
					</div>
					<div class="cat-tag-box">
						<div class="mBox">
							<?php echo Route::_(BlogstersHelperRoute::getBlogCategorySpaceSeparatedByCategory($item->cat_id)); ?>
							<?php echo BlogstersHelperRoute::getBlogTagSpaceSeparated($item->tags_id); ?>
						</div>
					</div>
				</div>
			</div>
		<?php
			}
			exit;
		}
	}
	public function getLoadMoreBlogItemsByTags()
	{	
		if (Factory::getSession()->getFormToken() != Factory::getApplication()->input->get('token')) {
			die('Invalid security token; please reload the page and try again.');
		} else {
			$next = Factory::getApplication()->input->get('next', '');
			$id = Factory::getApplication()->input->get('id', '');
			$list_limit = Factory::getApplication()->input->get('list_limit', '', 'string');
			$result = BlogstersHelperRoute::getMoreBlogItemsByTags($id, $next, $list_limit);
			foreach ($result as $id => $item){
				$ajaxMoreLink = Route::_(BlogstersHelperRoute::getBlogRoute($item->id, $item->alias));
				$ajaxMoreLink = str_replace("component/blogsters/","blog/",$ajaxMoreLink);
				$ajaxMoreLink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $ajaxMoreLink);
				$ajaxMoreLink = str_replace("?Itemid","",$ajaxMoreLink);
		?>
			<div class="blog <?php echo Route::_(BlogstersHelperRoute::getPortfoliocasesCategories($item->cat_id)); ?> ajaxList" data-cat="<?php echo Route::_(BlogstersHelperRoute::getPortfoliocasesCategories($item->cat_id)); ?>" style="display: inline-block;">
				<div class="blog-wrapper">
					<div class="image">
						<?php if(isset($item->featured_image)): ?>
							<a href="<?php echo $ajaxMoreLink; ?>">
								<img src="<?php echo URI::root().$item->featured_image; ?>" alt="<?php echo $item->title; ?>" />
							</a>
						<?php endif; ?>
					</div>
					<div class="metabox">
						<div class="mBox">
							<div class="left">
								<i class="icon-thumbs-up icons"></i> <span class="percentBox"><?php echo BlogstersHelperRoute::getThumbsUpPercentage($item->id); ?></span>
							</div>
							<div class="right">
								<?php if($item->read_time): ?><i class="icon-clock icons"></i> <span class="minutesBox"><?php echo $item->read_time.' min'; ?></span><?php endif; ?>
							</div>
						</div>
					</div>
					<div class="titlebox">
						<a href="<?php echo $ajaxMoreLink; ?>"><h3><?php if($item->is_featured): ?><i class="icon-bookmark"></i><?php endif; ?><?php echo BlogstersHelperRoute::truncateString($item->title,70); ?></h3></a>
					</div>
					<div class="cat-tag-box">
						<div class="mBox">
							<?php echo Route::_(BlogstersHelperRoute::getBlogCategorySpaceSeparatedByCategory($item->cat_id)); ?>
							<?php echo Route::_(BlogstersHelperRoute::getBlogTagSpaceSeparatedByTags($item->tags_id)); ?>
						</div>
					</div>
				</div>
			</div>
		<?php
			}
			exit;
		}
	}















}

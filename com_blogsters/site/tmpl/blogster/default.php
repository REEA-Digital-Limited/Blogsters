<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Component\Blogsters\Site\Helper\RouteHelper as BlogstersHelperRoute;

$authorInfo = BlogstersHelperRoute::getAuthorInfo($this->item->author_id);
$votedAlready = BlogstersHelperRoute::checkIfVotedAlready($this->item->id);
?>

<div class="BlogMainWrapper" id="BlogMainWrapper">
	<div class="topFeatureBox">
		<div class="primaryImageBox detailsPage">
			<?php if(isset($this->item->featured_image)): ?>
				<img src="<?php echo JURI::base().$this->item->featured_image; ?>" alt="<?php echo $this->item->title; ?>" class="f-image" />
			<?php endif; ?>
		</div>
		<div class="primaryBlogTitleBox detailsPage">
			<?php if(isset($this->item->title)): ?>
			<div class="title">
				<h1><?php echo $this->item->title; ?></h1>
			</div>
			<?php endif; ?>
			<div class="blogMetaDataBox">
				<div class="firstMeta">
					<?php if($this->item->created): ?><span class="date"><?php echo HtmlHelper::date($this->item->created, 'd. F Y'); ?></span><?php endif; ?>
					<span class="print-page"><i class="icon-print icons"></i><?php echo JText::_('COM_BLOGSTERS_PRINT_PAGE'); ?></span>
					<?php if($this->item->read_time): ?><span class="read-time">
						<?php
							$wordsCount = preg_split('/\s+/', $this->item->description);
							$wordsCount = count($wordsCount);
						?>
						<i class="icon-clock icons"></i><?php echo Text::sprintf('COM_BLOGSTERS_READ_TIME_WORDS_COUNT', $this->item->read_time, $wordsCount); ?>
					</span><?php endif; ?>
				</div>
				<div class="secondMeta">
					<?php if($this->item->is_featured): ?><span class="bookmark"><?php if($this->item->is_featured): ?><i class="icon-bookmark icons"></i><?php endif; ?><?php echo JText::_('COM_BLOGSTERS_FEATURED_BOOKMARK'); ?></span><?php endif; ?>
					<?php if($this->item->cat_id): ?><span class="category"><?php echo BlogstersHelperRoute::getBlogCategorySpaceSeparated($this->item->cat_id); ?></span><?php endif; ?>
					<?php if($this->item->tags_id): ?><span class="tag"><?php echo BlogstersHelperRoute::getBlogTagSpaceSeparated($this->item->tags_id); ?></span><?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="mainBlogBoxItems detailsPage" id="printDivContent">
		<div class="MainContent">
			<?php echo $this->item->description; ?>
		</div>
		<div class="QuotationBox">
			<div class="authorMeta">
				<h3 class="authHeading"><?php echo JText::_('COM_BLOGSTERS_AUTHOR_HEADING'); ?></h3>
				<div class="box">
					<div class="left">
						<?php if($authorInfo->profile_image) : ?>
							<img width="150" src="<?php echo JURI::base().$authorInfo->profile_image; ?>" alt="<?php echo $authorInfo->title; ?>" />
						<?php endif; ?>
					</div>
					<div class="right">
						<div class="name"><?php echo $authorInfo->title; ?></div>
						<div class="designation"><?php echo $authorInfo->designation; ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="footerLikeSocialWrapper"> 
	<div class="uk-container">
		<div class="box">
			<div class="vote">
				<div class="icon">
					<div class="up" data-id="<?php echo $this->item->id; ?>" <?php if($votedAlready){ ?> title="<?php echo JText::_('COM_BLOGSTERS_ALREADY_VOTED'); ?>" style="cursor:not-allowed;" <?php } ?>>
						<div class="fav up"><i class="icon-thumbs-up icons" <?php if($votedAlready){ ?> style="cursor:not-allowed;" <?php } ?>></i></div>
						<div class="text"><?php echo JText::_('COM_BLOGSTERS_BLOG_UP_VOTE_TEXT'); ?></div>
					</div>
					<div class="down" data-id="<?php echo $this->item->id; ?>" <?php if($votedAlready){ ?> title="<?php echo JText::_('COM_BLOGSTERS_ALREADY_VOTED'); ?>" style="cursor:not-allowed;" <?php } ?>>
						<div class="fav down"><i class="icon-thumbs-down icons" <?php if($votedAlready){ ?> style="cursor:not-allowed;" <?php } ?>></i></div>
						<div class="text"><?php echo JText::_('COM_BLOGSTERS_BLOG_DOWN_VOTE_TEXT'); ?></div>
					</div>
				</div>
			</div>
			<div class="social">
				<div class="list">
					<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo JUri::getInstance(); ?>" target="_blank"><img src="<?php echo JURI::base(); ?>components/com_blogsters/assets/images/icon-linkedin.svg" /></a>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo JUri::getInstance(); ?>" target="_blank"><img src="<?php echo JURI::base(); ?>components/com_blogsters/assets/images/icon-facebook.svg" /></a>
					<a href="mailto:you@yourdomain.com?subject=<?php echo $this->item->title; ?>&body=<?php echo JUri::getInstance(); ?>" target="_blank"><img src="<?php echo JURI::base(); ?>components/com_blogsters/assets/images/icon-mail.svg" /></a>
				</div>
				<div class="text"><?php echo JText::_('COM_BLOGSTERS_BLOG_SOCIAL_TEXT'); ?></div>
			</div>
			<input id="token" type="hidden" name="<?php echo JSession::getFormToken(false); ?>" value="1" />
		</div>
	</div>
</div>
<?php
	$document = JFactory::getDocument();
	$componentPath = JURI::base() . 'components/com_blogsters/assets/';
	$document->addStyleSheet($componentPath . 'css/custom-front-end-style.css');
	$document->addStyleSheet($componentPath . 'css/jquery-ui.css');
	$document->addScript($componentPath . 'js/jquery-3.6.3.min.js');
	$document->addScript($componentPath . 'js/custom-front-end-script.js');
	$document->addScript($componentPath . 'js/jquery-ui.js');
?>
<?php if($votedAlready){ ?>
	<script> jQuery( function() { jQuery( document ).tooltip(); } );</script>
<?php } ?>
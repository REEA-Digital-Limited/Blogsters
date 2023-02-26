<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Blogsters\Site\Helper\RouteHelper as BlogstersHelperRoute;
$model = $this->getModel();
$data  = $model->getPrimaryFeaturedPost();
$cat  = $model->getCategoryList();
?>

<div class="BlogMainWrapper">
	<?php if(isset($data->featured_image)): ?>
	<div class="topFeatureBox">
		<div class="primaryImageBox">
			<a href="<?php echo Route::_(BlogstersHelperRoute::getBlogRoute($data->id, $data->alias)); ?>">
				<img src="<?php echo JURI::base().$data->featured_image; ?>" alt="<?php echo $data->title; ?>" class="f-image" />
			</a>
		</div>
		<div class="primaryBlogTitleBox">
			<?php if(isset($data->title)): ?>
			<div class="title">
				<a href="<?php echo Route::_(BlogstersHelperRoute::getBlogRoute($data->id, $data->alias)); ?>"><h1><?php echo $data->title; ?></h1></a>
			</div>
			<?php endif; ?>
			<div class="read-more">
				<a href="<?php echo Route::_(BlogstersHelperRoute::getBlogRoute($data->id, $data->alias)); ?>" class="more-link"><span class="icon-chevron-right"></span><?php echo Text::_('COM_BLOGSTERS_READ_MORE'); ?></a>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<div class="mainBlogBoxItems">
		<div class="BlogTab">
			<ul id="filters" class="clearfix">
				<li class="filter active parent" data-filter="<?php echo $model->getCommaSeparatedCategories(); ?>"><?php echo JText::_('COM_BLOGSTERS_ALL'); ?></li>
				<?php foreach ($cat as $cObj) { ?>
					<li class="filter" data-filter=".<?php echo $model->stringURLSafe(@$cObj->title); ?>"><?php echo $cObj->title; ?></li>
				<?php } ?>
			</ul>
		</div>
		<div class="blogMainGridWrapper">
			<div class="blog-grid" id="blogMainWrapper">
				<?php foreach ($this->items as $id => $item) : ?>
				<div class="blog <?php echo Route::_($model->getPortfoliocasesCategories($item->cat_id)); ?>" data-cat="<?php echo Route::_($model->getPortfoliocasesCategories($item->cat_id)); ?>">
					<div class="blog-wrapper">
						<div class="image">
							<?php if(isset($item->featured_image)): ?>
								<a href="<?php echo Route::_(BlogstersHelperRoute::getBlogRoute($item->id, $item->alias)); ?>">
									<img src="<?php echo JURI::base().$item->featured_image; ?>" alt="<?php echo $item->title; ?>" />
								</a>
							<?php endif; ?>
						</div>
						<div class="metabox">
							<div class="mBox">
								<div class="left">
									<i class="icon-thumbs-up icons"></i> <span class="percentBox"><?php echo $model->getThumbsUpPercentage($item->id); ?></span>
								</div>
								<div class="right">
									<?php if($item->read_time): ?><i class="icon-clock icons"></i> <span class="minutesBox"><?php echo $item->read_time.' min'; ?></span><?php endif; ?>
								</div>
							</div>
						</div>
						<div class="titlebox">
							<a href="<?php echo Route::_(BlogstersHelperRoute::getBlogRoute($item->id, $item->alias)); ?>"><h3><?php if($item->is_featured): ?><i class="icon-bookmark"></i><?php endif; ?><?php echo JHTML::_('string.truncate', ($item->title), 70, true, false); ?></h3></a>
						</div>
						<div class="cat-tag-box">
							<div class="mBox">
								<?php echo $model->getBlogCategorySpaceSeparated($item->cat_id); ?>
								<?php echo $model->getBlogTagSpaceSeparated($item->tags_id); ?>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="loadMoreBoxMainWrapper"></div>
			<div class="loadmoreWrapper">
				<a href="javascript:void(0);" id="seeMore" class="hover-effect-right" data-next="2"><span class="icon-chevron-right"></span><?php echo JText::_('COM_BLOGSTERS_SHOW_MORE'); ?></a>
			</div>

			<input id="token" type="hidden" name="<?php echo JSession::getFormToken(false); ?>" value="1" />
		</div>
	</div>
</div>
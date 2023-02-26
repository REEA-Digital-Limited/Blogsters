<?php

/**
 * @package     Blogsters
 * @subpackage  mod_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Module\Blogsters\Site\Helper\BlogstersHelper as ModHelper;
?>

<div id="ss-latest-blogs-wrapper" class="uk-container">
	<?php 
		foreach ($blogsters as $i => $item) : 
		$ajaxMoreLink = Route::_(ModHelper::getBlogRoute($item->id, $item->alias));
		$ajaxMoreLink = str_replace("component/blogsters/","blog/",$ajaxMoreLink);
		$ajaxMoreLink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $ajaxMoreLink);
		$ajaxMoreLink = str_replace("?Itemid","",$ajaxMoreLink);
	?>
		<div class="blog">
			<div class="blog-wrapper">
				<div class="image">
					<?php if(isset($item->featured_image)): ?>
						<a href="<?php echo $ajaxMoreLink; ?>">
							<img src="<?php echo JURI::base().$item->featured_image; ?>" alt="<?php echo $item->title; ?>" />
						</a>
					<?php endif; ?>
				</div>
				<div class="metabox">
					<div class="mBox">
						<div class="left">
							<i class="icon-thumbs-up icons"></i> <span class="percentBox"><?php echo ModHelper::getThumbsUpPercentage($item->id); ?></span>
						</div>
						<div class="right">
							<?php if($item->read_time): ?><i class="icon-clock icons"></i> <span class="minutesBox"><?php echo $item->read_time.' min'; ?></span><?php endif; ?>
						</div>
					</div>
				</div>
				<div class="titlebox">
					<a href="<?php echo $ajaxMoreLink; ?>"><h3><?php if($item->is_featured): ?><i class="icon-bookmark"></i><?php endif; ?><?php echo JHTML::_('string.truncate', ($item->title), 70, true, false); ?></h3></a>
				</div>
				<div class="cat-tag-box">
					<div class="mBox">
						<?php echo ModHelper::getBlogCategorySpaceSeparated($item->cat_id); ?>
						<?php echo ModHelper::getBlogTagSpaceSeparated($item->tags_id); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<style>
	#ss-latest-blogs-wrapper {
		margin-top: 15px;
		margin-bottom: 15px;
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		padding: 0px;
	}
	#ss-latest-blogs-wrapper .blog {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		-o-box-sizing: border-box;
		display: inline-block;
		margin: 8px;
		border: 1px solid #ccc;
		background: #fff;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper {
		position: relative !important;
		cursor: pointer;
		padding: 8px;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .image {
		overflow: hidden;
		max-height: 215px;
		text-align: center;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .image img {
		width: 100%;
		height: auto;
		transition: 1s;
		transform: scale(1);
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .metabox {
		background: #fff;
		position: absolute;
		width: 55%;
		margin-top: -25px;
		z-index: 999;
		margin-right: 0px;
		right: 0px;
		padding: 2px 15px;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .metabox .mBox {
		clear: both;
		width: 100%;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .metabox .mBox .left {
		float: left;
		width: 50%;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .metabox .mBox .icons {
		font-size: 13px;
		color: #0a268a;
		font-weight: 400;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .metabox .mBox .percentBox, #ss-latest-blogs-wrapper .blog .blog-wrapper .metabox .mBox .minutesBox {
		font-size: 13px;
		color: #0a268a;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .metabox .mBox .right {
		float: left;
		width: 50%;
		text-align: right;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .metabox .mBox .percentBox, #ss-latest-blogs-wrapper .blog .blog-wrapper .metabox .mBox .minutesBox {
		font-size: 13px;
		color: #0a268a;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .titlebox h3 {
		font-size: 24px;
		line-height: 28px;
		margin-top: 10px;
		margin-bottom: 10px;
		font-weight: 400;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .cat-tag-box .mBox {
		display: inline-block;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .cat-tag-box .mBox .list.cat {
		color: #0a268a;
		font-weight: 800 !important;
		margin-right: 5px;
		font-size: 13px;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .cat-tag-box .mBox .list {
		display: inline-block;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .cat-tag-box .mBox .list.tag {
		color: #0a268a;
		font-weight: normal;
		font-weight: 400 !important;
		margin-right: 5px;
		font-size: 13px;
	}
	#ss-latest-blogs-wrapper .blog .blog-wrapper .titlebox .icon-bookmark {
		color: #b02473;
		font-size: 18px;
		margin-right: 6px;
	}
	#ss-latest-blogs-wrapper .blog-wrapper .titlebox a{
		text-decoration: none;
		display: block;
		line-height: 1.1;		
	}
	#ss-latest-blogs-wrapper .blog-wrapper .cat-tag-box .mBox .list{
		text-decoration: none;
		line-height: 1.1;		
	}
	@media only screen and (min-width: 640px) and (max-width: 960px) {
		#ss-latest-blogs-wrapper {
			grid-template-columns: repeat(2, 1fr);
		}
		#ss-latest-blogs-wrapper .blog .blog-wrapper .titlebox h3 {
			font-size: 20px !important;
			line-height: 25px !important;
		}
	}
	@media only screen and (min-width: 100px) and (max-width: 640px) {
		#ss-latest-blogs-wrapper {
			grid-template-columns: repeat(1, 1fr);
		}
		#ss-latest-blogs-wrapper .blog .blog-wrapper .titlebox h3 {
			font-size: 18px !important;
			line-height: 22px !important;
		}
	}
</style>
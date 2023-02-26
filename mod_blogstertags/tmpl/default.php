<?php

/**
 * @package     Blogsters
 * @subpackage  mod_blogstertags
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Module\Blogstertags\Site\Helper\BlogstertagsHelper as ModHelper;
?>

<div id="ss-tag-clouds-wrapper" class="uk-container">
	<div class="tagCloudsMainWrapper">
		<div class="outer-circle">
			<ul class="cloud" role="navigation">
				<?php 
					foreach ($blogstertags as $i => $item) :
						$alias = ModHelper::getTitleSlug($item->title);
						$ajaxMoreLink = Route::_(ModHelper::getBlogTagRoute($item->id, $alias));
						if ( strstr( $ajaxMoreLink, 'component/blogsters/' ) ) {
							$ajaxMoreLink = str_replace("component/blogsters/","blog/",$ajaxMoreLink);
							$ajaxMoreLink = preg_replace('/\W\w+\s*(\W*)$/', '$1', $ajaxMoreLink);
							$ajaxMoreLink = str_replace("&amp;Itemid","",$ajaxMoreLink);
						}
				?>
					<li><a href="<?php echo $ajaxMoreLink; ?>" data-weight="<?php echo rand(1,10); ?>"><?php echo $item->title; ?></a></li>
				<?php 
					endforeach; 
				?>
			</ul>
			<div class="inner-circle"></div>
		</div>
	</div>
</div>

<style>
	#ss-tag-clouds-wrapper {
		padding: 0px;
	}
	.blogsterTagsCloudWrapper .blogstertags-container {
		max-width: 1200px;
		padding-left: 40px;
		padding-right: 40px;
		display: flow-root;
		box-sizing: content-box;
		max-width: 1200px;
		margin-left: auto;
		margin-right: auto;
		padding-left: 15px;
		padding-right: 15px;
	}
	.blogsterTagsCloudWrapper .blogstertags-container h3{
		font-size: 30px;
		font-weight: 600;
		line-height: 1.2em;
		margin: 20px 0px 20px 0px;
		text-transform: none;
	}
	.tagCloudsMainWrapper .outer-circle {
		height: 500px;
		width: 500px;
		background: #f5f6fa;
		border-radius: 50%;
		position: relative;
		display: flex;
		justify-content: center;
		align-items: center;
		margin: auto;
	}
	.tagCloudsMainWrapper .inner-circle {
		height: 250px;
		width: 250px;
		background: #ebedf5;
		border-radius: 50%;
	}
	.tagCloudsMainWrapper ul.cloud {
		list-style: none;
		padding-left: 0;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: center;
		line-height: 2.75rem;
		width: 85%;
		position: absolute;
	}
	.tagCloudsMainWrapper ul.cloud a {
		--size: 4;
		--color: #0a268a;
		color: var(--color);
		font-size: calc(var(--size) * 0.25rem + 0.5rem);
		display: block;
		padding: 0.125rem 0.25rem;
		position: relative;
		text-decoration: none;
		border-radius: 50% 0% 50% 0%;
		width: auto;
		border: 1px solid rgba(10, 38, 138, 0.080);
		padding: 5px 10px;
		margin: 5px;
	}
	.tagCloudsMainWrapper ul.cloud a[data-weight="1"] {
		--size: 1;
	}
	.tagCloudsMainWrapper ul.cloud a[data-weight="2"] {
		--size: 1.5;
	}
	.tagCloudsMainWrapper ul.cloud a[data-weight="3"] {
		--size: 2;
	}
	.tagCloudsMainWrapper ul.cloud a[data-weight="4"] {
		--size: 2.5;
	}
	.tagCloudsMainWrapper ul.cloud a[data-weight="5"] {
		--size: 3;
	}
	.tagCloudsMainWrapper ul.cloud a[data-weight="6"] {
		--size: 4;
	}
	.tagCloudsMainWrapper ul.cloud a[data-weight="7"] {
		--size: 5;
	}
	.tagCloudsMainWrapperul.cloud a[data-weight="8"] {
		--size: 6;
		font-weight: 600;
	}
	.tagCloudsMainWrapper ul.cloud a[data-weight="9"] {
		--size: 7;
		font-weight: 800;
	}
	.tagCloudsMainWrapper ul[data-show-value] a::after {
		content: " (" attr(data-weight) ")";
		font-size: 1rem;
	}
	.tagCloudsMainWrapper ul.cloud li:nth-child(2n+1) a {
		--color: #0a268a;
	}
	.tagCloudsMainWrapper ul.cloud li:nth-child(3n+1) a {
		--color: #0a268a;
	}
	.tagCloudsMainWrapper ul.cloud li:nth-child(4n+1) a {
		--color: #0a268a;
	}
	.tagCloudsMainWrapper ul.cloud a:focus {
		outline: 1px dashed;
	}
	.tagCloudsMainWrapper ul.cloud a::before {
		content: "";
		position: absolute;
		top: 0;
		left: 50%;
		width: 0;
		height: 100%;
		background: var(--color);
		transform: translate(-50%, 0);
		opacity: 0.15;
		transition: width 0.25s;
		border-radius: 50% 0% 50% 0%;
	}
	.tagCloudsMainWrapper ul.cloud a:focus::before,
	.tagCloudsMainWrapper ul.cloud a:hover::before {
		width: 100%;
	}
	@media screen and (max-width: 650px) {
	.tagCloudsMainWrapper .outer-circle {
		height: 450px;
		width: 450px;
	}
	.tagCloudsMainWrapper .inner-circle {
		height: 250px;
		width: 250px;
	}
	}
	@media screen and (max-width: 550px) {
	.tagCloudsMainWrapper .outer-circle {
		height: 400px;
		width: 400px;
	}
	.tagCloudsMainWrapper .inner-circle {
		height: 230px;
		width: 230px;
	}
	}
	@media screen and (max-width: 450px) {
	.tagCloudsMainWrapper .outer-circle {
		height: 350px;
		width: 350px;
	}
	.tagCloudsMainWrapper .inner-circle {
		height: 200px;
		width: 200px;
	}
	.tagCloudsMainWrapper ul.cloud a {
		font-size: calc(var(--size) * 0.15rem + 0.5rem) !important;
	}
	.tagCloudsMainWrapper ul.cloud {
		line-height: 2.2rem !important;
		width: 80% !important;
	}
	}
	@media screen and (max-width: 400px) {
	.tagCloudsMainWrapper .outer-circle {
		height: 320px !important;
		width: 320px !important;
	}
	.tagCloudsMainWrapper .inner-circle {
		height: 180px;
		width: 180px;
	}
	}
	@media screen and (max-width: 767px) {
	.tagCloudsMainWrapper ul.cloud * {
		transition: none !important;
	}
	}
</style>
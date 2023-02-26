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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Blogsters\Administrator\Helper\BlogstersHelper;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$states = array (
		'0' => Text::_('JUNPUBLISHED'),
		'1' => Text::_('JPUBLISHED'),
		'2' => Text::_('JARCHIVED'),
		'-2' => Text::_('JTRASHED')
);
$editIcon = '<span class="fa fa-pen-square me-2" aria-hidden="true"></span>';
$featuredIcon = '<span class="fa fa-solid fa-star" aria-hidden="true" style="font-size: 20px;color: #008000;"></span>';
$db = JFactory::getDbo();

?>
<form action="<?php echo Route::_('index.php?option=com_blogsters'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-md-12">
			<div id="j-main-container" class="j-main-container">
				<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
				<?php if (empty($this->items)) : ?>
					<div class="alert alert-info">
						<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
				<?php else : ?>
					<table class="table" id="blogstersList">
						<caption id="captionTable">
							<?php echo Text::_('COM_BLOGSTERS_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
						</caption>
						<thead>
							<tr>
								<td style="width:1%" class="text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
								</td>
								<th scope="col" style="width:1%; min-width:85px" class="text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:8%" class="d-none d-md-table-cell">
									<?php echo Text::_('COM_BLOGSTERS_FEATURED_HEADING'); ?>
								</th>
								<th scope="col" style="width:25%">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:10%">
									<?php echo Text::_('COM_BLOGSTERS_CATEGORY_HEADING'); ?>
								</th>
								<th scope="col" style="width:10%">
									<?php echo Text::_('COM_BLOGSTERS_AUTHOR_HEADING'); ?>
								</th>
								<th scope="col" style="width:10%">
									<?php echo Text::_('COM_BLOGSTERS_TAGS_HEADING'); ?>
								</th>
								<th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_BLOGSTERS_READTIME_HEADING', 'a.read_time', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_BLOGSTERS_CREATED_HEADING', 'a.created', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo Text::_('COM_BLOGSTERS_IMAGE_HEADING'); ?>
								</th>
								<th scope="col" style="width:5%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$n = count($this->items);
						foreach ($this->items as $i => $item) :
							$categoriesDisplay = BlogstersHelper::getItemsByCommaSeparated($item->cat_id, '#__blogsters_categories');
							$authorsDisplay = BlogstersHelper::getItemsByCommaSeparated($item->author_id, '#__blogsters_authors');
							$tagsDisplay = BlogstersHelper::getItemsByCommaSeparated($item->tags_id, '#__blogsters_tags');
							$upVoteCNT = BlogstersHelper::getUpVoteCount($item->id);
							$downVoteCNT = BlogstersHelper::getDownVoteCount($item->id);
							$totalVoteCNT = BlogstersHelper::getTotalVoteCount($item->id);
							?>
							<tr class="row<?php echo $i % 2; ?>">
								<td class="text-center">
									<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
								</td>
								<td class="article-status">
									<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'blogsters.'); ?>
								</td>
								<td class="d-none d-md-table-cell" style="text-align: center;">
									<?php if($item->primary_is_featured==1){
										echo 'P: '.$featuredIcon;
									} ?>
									<?php if($item->is_featured==1){
										echo 'S: '.$featuredIcon;
									} ?>
								</td>
								<td scope="row" class="has-context">
									<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_blogsters&task=blogster.edit&id=' . $item->id); ?>">
										<?php echo $editIcon; ?><?php echo $this->escape($item->title); ?>
									</a>
								</td>
								<td class="">
									<?php echo $categoriesDisplay; ?>
								</td>
								<td class="">
									<?php echo $authorsDisplay; ?>
								</td>
								<td class="tagsBox">
									<?php echo $tagsDisplay; ?>
								</td>
								<td class="d-none d-md-table-cell">
									<?php echo $item->read_time.' Minutes'; ?>
								</td>
								<td class="d-none d-md-table-cell">
									<?php echo JHTML::_('date', $item->created, 'F j, Y, g:i a'); ?>
								</td>
								<td class="d-none d-md-table-cell">
									<?php
									$caption = $item->featured_image_alt_text ?: '';
									$src = JURI::root() . ($item->featured_image ?: '');
									$html = '<img class="trigger" width="60px" data-src="%s" src="%s" alt="%s">';
									echo sprintf($html, $src, $src, $caption);
									?>
								</td>
								<td class="d-none d-md-table-cell">
									<?php echo $item->id; ?>
								</td>
							</tr>
							<tr>
								<td colspan="4" style="border-top: 2px solid transparent;background: #f7f7f7;font-weight: bold;text-align: center;padding: 3px;color: #000;"><i class="icon-thumbs-up icons"></i> <?php echo Text::_('COM_BLOGSTERS_UP_VOTE_HEADING'); ?><?php echo $upVoteCNT->total; ?></td>
								<td colspan="4" style="border-top: 2px solid transparent;background: #f7f7f7;font-weight: bold;text-align: center;padding: 3px;color: #000;"><i class="icon-thumbs-down icons"></i> <?php echo Text::_('COM_BLOGSTERS_DOWN_VOTE_HEADING'); ?><?php echo $downVoteCNT->total; ?></td>
								<td colspan="3" style="border-top: 2px solid transparent;background: #f7f7f7;font-weight: bold;text-align: center;padding: 3px;color: #000;"><?php echo Text::_('COM_BLOGSTERS_TOTAL_VOTE_HEADING'); ?><?php echo $totalVoteCNT->total; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php // load the pagination. ?>
					<?php echo $this->pagination->getListFooter(); ?>

				<?php endif; ?>
				<input type="hidden" name="task" value="">
				<input type="hidden" name="boxchecked" value="0">
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>
<?php
$document = JFactory::getDocument();
$componentPath = JURI::base() . 'components/com_blogsters/assets/';
$document->addStyleSheet($componentPath . 'css/custom.style.css');
$document->addScript($componentPath . 'js/custom.script.js');
?>
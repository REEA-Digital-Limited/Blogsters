<?php
/**
 * @package     Blogsters
 * @subpackage  com_blogsters
 *
 * @copyright   Copyright (C) 2023 REEA Digital Limited. All rights reserved.
 * @license     GNU General Public License version 3 or later.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Blogsters\Administrator\Helper\BlogstersHelper;

HTMLHelper::_('behavior.multiselect');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$states = array (
		'0' => Text::_('JUNPUBLISHED'),
		'1' => Text::_('JPUBLISHED'),
		'2' => Text::_('JARCHIVED'),
		'-2' => Text::_('JTRASHED')
);
$editIcon = '<span class="fa fa-pen-square me-2" aria-hidden="true"></span>';
?>
<form action="<?php echo Route::_('index.php?option=com_blogsters&view=blogster_authors'); ?>" method="post" name="adminForm" id="adminForm">
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
					<table class="table" id="blogsterList">
						<caption id="captionTable">
							<?php echo Text::_('COM_BLOGSTERS_AUTHORS_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
						</caption>
						<thead>
							<tr>
								<td style="width:1%" class="text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
								</td>
								<th scope="col" style="width:1%; min-width:85px" class="text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:30%;">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_BLOGSTERS_AUTHORS_LABEL_TITLE', 'a.title', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:38%;">
									<?php echo Text::_('COM_BLOGSTERS_AUTHORS_BIO'); ?>
								</th>
								<th scope="col" style="width:15%;">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_BLOGSTERS_AUTHORS_DESIGNATION', 'a.designation', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:10%;">
									<?php echo Text::_('COM_BLOGSTERS_AUTHORS_PICTURE'); ?>
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
							?>
							<tr class="row<?php echo $i % 2; ?>">
								<td class="text-center">
									<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
								</td>
								<td>
									<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'blogster_authors.'); ?>
								</td>
								<td>
									<a href="index.php?option=com_blogsters&task=blogster_author.edit&id=<?php echo $item->id; ?>">
									<?php echo $editIcon; ?><?php echo $item->title; ?></a>
								</td>
								<td>
									<?php echo JHTML::_('string.truncate', ($item->author_bio), 200, true, false); ?>
								</td>
								<td>
									<?php echo $item->designation; ?>
								</td>
								<td>
									<?php
									$src = JURI::root() . ($item->profile_image ?: '');
									$html = '<img class="trigger" width="60px" data-src="%s" src="%s">';
									echo sprintf($html, $src, $src);
									?>
								</td>
								<td>
									<?php echo $item->id; ?>
								</td>
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

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
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\MVC\View\GenericDataException;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$this->ignore_fieldsets = array('details', 'item_associations', 'jmetadata');
$this->useCoreUI = true;
?>

<form action="<?php echo Route::_('index.php?option=com_blogsters&view=blogster_categorie&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="blogster-categorie-form" class="form-validate">

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div>
		<?php echo HTMLHelper::_('uitab.startTabSet', 'BlogTab', array('active' => 'details')); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'BlogTab', 'details', Text::_('COM_BLOGSTERS_DATE_TAB_DETAILS')); ?>
		<div class="row">
			<div class="col-md-9">
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->form->renderField('description'); ?>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card card-light">
					<div class="card-body">
						<?php echo $this->form->renderField('created'); ?>
						<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
						<?php echo $this->form->renderField('id'); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

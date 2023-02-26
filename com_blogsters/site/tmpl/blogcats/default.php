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
use Joomla\CMS\Factory;

HTMLHelper::_('behavior.core');

?>

<div class="com-contact-categories categories-list listBlogByCategory">
	<?php
		echo $this->loadTemplate('items');
	?>
</div>

<?php
	$document = JFactory::getDocument();
	$componentPath = JURI::base() . 'components/com_blogsters/assets/';
	$document->addStyleSheet($componentPath . 'css/custom-front-end-style.css');
	$document->addScript($componentPath . 'js/jquery-3.6.3.min.js');
	$document->addScript($componentPath . 'js/jquery.mixitup.min.js');
	$document->addScript($componentPath . 'js/custom-front-end-script.js');
	$app = Factory::getApplication();
	$paramsCom = $app->getParams();
	$list_limit = $paramsCom->get('list_limit');
?>
<script>
	jQuery(document).ready(function() {
		var nextPage = 0;
		jQuery(".listBlogByCategory .BlogItemsByCategory .mainBlogBoxItems .blogMainGridWrapper .loadmoreWrapper #seeMoreByCat").click(function(e) {

			jQuery('.listBlogByCategory .BlogItemsByCategory .mainBlogBoxItems .blogMainGridWrapper .loadmoreWrapper').hide();
			jQuery('.listBlogByCategory .BlogItemsByCategory .mainBlogBoxItems .blogMainGridWrapper .loadMoreBoxMainWrapper').html('<div class="spinLoading multi"></div>');

			var next = jQuery(this).attr("data-next");
			var id = jQuery(this).attr("data-id");
			var token = jQuery("#token").attr("name");
			var list_limit = <?php echo $list_limit; ?>;

			var getUrl = "index.php?option=com_blogsters&task=getLoadMoreBlogItemsByCategory&tmpl=component";
			jQuery.ajax({
				type: "POST",
				url: getUrl,
				data: {
					token: token,
					next: next,
					id: id,
					list_limit: list_limit
				},
				success: function(result, status, xhr) {
					jQuery('.listBlogByCategory .BlogItemsByCategory .mainBlogBoxItems .blogMainGridWrapper .loadMoreBoxMainWrapper').empty();
					nextPage = (parseInt(next)) + 1;
					jQuery('.listBlogByCategory .BlogItemsByCategory .mainBlogBoxItems .blogMainGridWrapper .loadmoreWrapper #seeMoreByCat').attr('data-next', nextPage);
					jQuery('.listBlogByCategory #blogMainWrapper').append(result);
					var numItems = jQuery(result).filter('.ajaxList').length;
					if (numItems >= <?php echo $list_limit; ?>) {
						jQuery('.listBlogByCategory .BlogItemsByCategory .mainBlogBoxItems .blogMainGridWrapper .loadmoreWrapper').show();
					}
				},
				error: function() {}
			});
		});
	});
</script>
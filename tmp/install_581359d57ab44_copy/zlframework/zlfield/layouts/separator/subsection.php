<?php
/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

defined('_JEXEC') or die();
	
	// prepare title
	if($title = $field->find('specific.title'))
	{
		$vars = explode('||', $title);
		$text = JText::_($vars[0]);
		unset($vars[0]);

		$title = count($vars) ? $this->app->zlfield->replaceVars($vars, $text) : $text;
	}
?>

	<div class="row subsection-title" data-type="separator" data-id="<?php echo $id ?>" >
		<?php echo $title ?>
	</div>
<?php
defined('_JEXEC') or die;

/*
 * none (output raw module content)
 */
function modChrome_jday($module, &$params, &$attribs)
{
	echo '<div class="jday">';
	echo $module->content;
	echo '</div>';
}


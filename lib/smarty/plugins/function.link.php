<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.link.php
 * Type:     function
 * Name:     link
 * Purpose:  make link
 * -------------------------------------------------------------
 */
function smarty_function_link($params, Smarty_Internal_Template $template) {
	$args = [];
	if(isset($params['args']) && is_array($params['args'])) {
		$args = $params['args'];
		unset($params['args']);
	}

	$q = [];
	foreach ($params as $arg => $value) {
		$q[] = [$arg, $value];
	}

	return URI::Make($q, $args);
}
?>
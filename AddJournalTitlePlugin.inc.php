<?php

import('lib.pkp.classes.plugins.GenericPlugin');

class AddJournalTitlePlugin extends GenericPlugin {
	
	/**
	 * @copydoc Plugin::register()
	 */
	function register($category, $path, $mainContextId = null) {
		if (parent::register($category, $path, $mainContextId)) {
			if ($this->getEnabled($mainContextId)) {
				HookRegistry::register('Templates::Issue::Issue::Article', array($this, 'myFn'));
			}
			return true;
		}
		return false;
	}

	
	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.generic.addJournalTitle.displayName');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return __('plugins.generic.addJournalTitle.description');
	}

	
	/**
	 * @param $hookName string
	 * @param $params array
	 * @return boolean
	 */
	function myFn($hookName, $params) {
		
		$smarty =& $params[1];
		$output =& $params[2];
		
		$journal = $smarty->tpl_vars['journal']->value;
		
		$journalPath = $journal->getPath();
		
		$contextDao = Application::getContextDAO();
		$journal = $contextDao->getByPath(	$journalPath);
		$journalTitle = $journal->getLocalizedName();
		$config = Config::getData	()['general'];
		$restful_urls = $config['restful_urls'];
		$base_url = $config['base_url'];
		
		$url = $base_url . '/' . (!$restful_urls?'index.php/':''). $journalPath;
		$output .= '<a href="'.$url.'" target="_blank">'.$journalTitle.'</>';
		return false;
	}
}



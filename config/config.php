<?php

/**
 * News4ward
 * a contentelement driven news/blog-system
 *
 * @author Christoph Wiechert <wio@psitrax.de>
 * @copyright 4ward.media GbR <http://www.4wardmedia.de>
 * @package news4ward_related
 * @filesource
 * @licence LGPL
 */


// FE-Modules
$GLOBALS['FE_MOD']['news4ward']['news4wardRelated'] = '\News4ward\Module\Related';

// Hook for updateDatabase to create the view if news4ward_tags is installed
$GLOBALS['TL_HOOKS']['sqlCompileCommands'][] = array('\News4ward\RelatedHelper','sqlCompileCommands');


if(TL_MODE == 'BE' && (\Input::get('do') == 'composer' && \Input::get('update') == 'database' || \Environment::get('request') == 'contao/install.php'))
{
	$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('\News4ward\RelatedHelper','fixView');
}
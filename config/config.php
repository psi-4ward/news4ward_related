<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
$GLOBALS['FE_MOD']['news4ward']['news4wardRelated'] = 'ModuleNews4wardRelated';

// Hook for updateDatabase to create the view if news4ward_tags is installed
$GLOBALS['TL_HOOKS']['sqlCompileCommands'][] = array('News4wardRelatedHelper','sqlCompileCommands');
?>
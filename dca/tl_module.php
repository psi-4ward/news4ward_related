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


// Fields
$GLOBALS['TL_DCA']['tl_module']['fields']['news4ward_related_count'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_module']['news4ward_related_count'],
	'inputType'	=> 'text',
	'default'	=> 0,
	'eval'		=> array('mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50')
);

// Palette
$GLOBALS['TL_DCA']['tl_module']['palettes']['news4wardRelated']    = '{title_legend},name,headline,type;{config_legend},news4ward_archives,news4ward_related_count,news4ward_template,imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

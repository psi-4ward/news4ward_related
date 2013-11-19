<?php

/**
 * News4ward
 * a contentelement driven news/blog-system
 *
 * @author Christoph Wiechert <wio@psitrax.de>
 * @copyright 4ward.media GbR <http://www.4wardmedia.de>
 * @package news4ward_categories
 * @filesource
 * @licence LGPL
 */


// Register the namespace
ClassLoader::addNamespace('Psi');

// Register the classes
ClassLoader::addClasses(array
(
	'Psi\News4ward\Module\Related'   	=> 'system/modules/news4ward_related/Module/Related.php',
	'Psi\News4ward\RelatedHelper'   	=> 'system/modules/news4ward_related/RelatedHelper.php',
));

// Register the templates
TemplateLoader::addFiles(array
(
	'mod_news4ward_related' 			=> 'system/modules/news4ward_related/templates',
	'news4ward_list_related' 			=> 'system/modules/news4ward_related/templates',
));

<?php if(!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 
class News4wardRelatedHelper extends System
{

	/**
	 * Handle database update
	 * its a callback, executed within install-tool
	 * @param array $arrData SQL-Statements
	 * @return array SQL-Statements
	 */
	public function sqlCompileCommands($arrData)
	{
		$arrAllModules = scan(TL_ROOT . '/system/modules');

		// remove DROP statment for tl_news4ward_articleWithTags if news4ward_tags is installed
		if(is_array($arrData['DROP']))
		{
			foreach($arrData['DROP'] as $k => $v)
			{
				if($v == 'DROP TABLE `tl_news4ward_articleWithTags`;' && in_array('news4ward_tags',$arrAllModules))
				{
					// prevent view from deletion
					unset($arrData['DROP'][$k]);
				}
				elseif($v == 'DROP TABLE `tl_news4ward_articleWithTags`;')
				{
					// tell contao to do a DROP VIEW instead of DROP TABLE
					unset($arrData['DROP'][$k]);
					$arrData['DROP'][] = 'DROP VIEW `tl_news4ward_articleWithTags`;';
				}
			}
			if(!count($arrData['DROP'])) unset($arrData['DROP']);
		}

		// if news4ward_tags is installed tell contao to CREATE VIEW if not already done
		$this->import('Database');
		if(in_array('news4ward_tags',$arrAllModules) && !$this->Database->tableExists('tl_news4ward_articleWithTags'))
		{
			$arrData['CREATE'][] = "CREATE OR REPLACE VIEW `tl_news4ward_articleWithTags` AS
  SELECT tl_news4ward_article.*, GROUP_CONCAT(tag) AS tags
  FROM tl_news4ward_article
  LEFT OUTER JOIN tl_news4ward_tag ON (tl_news4ward_tag.pid = tl_news4ward_article.id)
  GROUP BY tl_news4ward_article.id";
		}

		return $arrData;
	}
}

?>
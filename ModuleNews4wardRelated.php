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

class ModuleNews4wardRelated extends News4ward
{
	/**
   	 * Template
   	 * @var string
   	 */
   	protected $strTemplate = 'mod_news4ward_related';


    /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### News4ward related articles ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news4ward_archives));

		// Return if there are no archives
		if (!is_array($this->news_archives) || count($this->news_archives) < 1)
		{
			return '';
		}

		// Read the alias from the url
		if(!preg_match("~.*".preg_quote($GLOBALS['objPage']->alias)."/([a-z0-9_-]+).*~i",$this->Environment->request,$erg))
		{
			return '';
		}
		$this->alias = $erg[1];

		return parent::generate();
	}



	public function compile()
	{

/*
 * Vieleicht hat ein self-join query bessere performance
 * in diesem fall müsste man aber die keywords ebenfalls in eine 1:N tabelle auslagern
 * title und description würde nicht mehr in die gewichtung einfließen

		select count(t2.id) as score, t2.pid
		from tl_news4ward_tag as t1
		join tl_news4ward_tag as t2 on t2.tag = t1.tag
		where t1.pid = 1
		group by t2.pid
*/

		/* build where */
		$where = array();

		// news archives
		$where[] = 'article.pid IN('. implode(',', array_map('intval', $this->news_archives)) . ')';

		// published
		if(!BE_USER_LOGGED_IN)
		{
			$where[] = "(article.start='' OR article.start<".time().") AND (article.stop='' OR article.stop>".time().") AND article.status='published'";
		}

		// @todo filter protected


		/* get the article */
		$objArticle = $this->Database->prepare("
			SELECT article.id, article.pid, article.keywords,
				(SELECT jumpTo FROM tl_news4ward WHERE tl_news4ward.id=article.pid) AS parentJumpTo
			FROM tl_news4ward_article AS article
			WHERE ".implode(' AND ',$where)
				.' AND article.alias = "'.mysql_real_escape_string($this->alias).'"')->execute();

		if(!$objArticle->numRows)
		{
			$tpl->items = array();
			return;
		}


		$words = $objArticle->keywords;


		if(in_array('news4ward_tags',$this->Config->getActiveModules()))
		{
			// fetch tags
			$objTags = $this->Database->prepare('SELECT tag FROM tl_news4ward_tag WHERE pid=?')->execute($objArticle->id);
			while($objTags->next())
				$words .= ' '.$objTags->tag;

			// use the view if news4ward_tags is installed
			$objErg = $this->Database->prepare('
				SELECT *, MATCH (keywords,tags,title,description) AGAINST (? IN BOOLEAN MODE) AS score
				FROM tl_news4ward_articleWithTags AS article
				WHERE id<>? AND '.implode(' AND ',$where).'
					AND MATCH (keywords,tags,title,description) AGAINST (? IN BOOLEAN MODE) > 0
				ORDER BY score DESC');
		}
		else
		{
			$objErg = $this->Database->prepare('
				SELECT *, MATCH (keywords,title,description) AGAINST (? IN BOOLEAN MODE) AS score
				FROM tl_news4ward_article AS article
				WHERE id<>? AND '.implode(' AND ',$where).'
					AND MATCH (keywords,title,description) AGAINST (? IN BOOLEAN MODE) > 0
				ORDER BY score DESC');
		}


		// limit the result
		if($this->news4ward_related_count > 0)
			$objErg->limit($this->news4ward_related_count);

		$objErg = $objErg->execute($words, $objArticle->id, $words);

		// generate links
		while($objErg->next())
		{
			$objErg->href = $this->generateUrl($objErg);
		}


		$this->Template->items = $objErg->fetchAllAssoc();

	}
}

?>
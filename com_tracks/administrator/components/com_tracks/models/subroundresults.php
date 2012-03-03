<?php
/**
* @version    0.2 $Id$ 
* @package    JoomlaTracks
* @copyright	Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla Tracks is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
require_once (JPATH_COMPONENT.DS.'models'.DS.'list.php');

/**
 * Joomla Tracks Component sub-round results Model
 *
 * @package		Tracks
 * @since 0.2
 */
class TracksModelSubroundResults extends TracksModelList
{
	var $projectround_id = 0;
  
 /**
   * Constructor
   *
   * @since 0.1
   */
  function __construct()
  {
    parent::__construct();
    
    $srid = JRequest::getVar('srid', 0, '', 'int');
    $this->subround_id = $srid;
  }
  
  function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT rr.*,
								pi.id AS piid, pi.team_id, pi.individual_id, pi.number, 
								i.first_name, i.last_name, 
								t.name as team_name, u.name AS editor '
			. ' FROM #__tracks_rounds_results AS rr '
      . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.id = rr.subround_id '
      . ' INNER JOIN #__tracks_projects_rounds AS pr ON pr.id = sr.projectround_id '
			. ' INNER JOIN #__tracks_individuals AS i ON (i.id = rr.individual_id) '
      . ' INNER JOIN #__tracks_projects_individuals AS pi ON (pi.individual_id = rr.individual_id AND pr.project_id = pi.project_id ) '
			. ' LEFT JOIN #__tracks_teams AS t ON (t.id = pi.team_id) '
			. ' LEFT JOIN #__users AS u ON u.id = rr.checked_out '
			. $where
			. $orderby
		;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.viewsubroundresults.filter_order',		'filter_order',		'rr.rank',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.viewsubroundresults.filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		if ($filter_order == 'rr.rank'){
			$orderby 	= ' ORDER BY rr.rank '.$filter_order_Dir.', i.last_name ASC, i.first_name ASC ';
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$where 		= ' WHERE rr.subround_id = '.$this->subround_id;

		return $where;
	}
	
  /**
   * Make sure that all individuals from project have a record.
   *
   */
  function _initRoundResults()
  {
    // get project id 
    $this->_db->setQuery( 
          ' SELECT pr.project_id FROM #__tracks_projects_rounds AS pr '
        . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.projectround_id = pr.id '
        . ' WHERE sr.id=' . $this->subround_id 
        );
    $project_id = $this->_db->loadResult();
    
    if ( $project_id )
    {
      $query = ' INSERT IGNORE INTO #__tracks_rounds_results (individual_id, team_id, subround_id) '
           . ' SELECT pi.individual_id, pi.team_id, ' . $this->subround_id 
           . ' FROM #__tracks_projects_individuals AS pi '
           . ' WHERE pi.project_id = ' .$project_id;
      $this->_db->setQuery($query);
      
      $this->_db->query();
      if ($this->_db->getErrorNum()) {
        echo $this->_db->getErrorMsg();
        $this->setError($this->_db->getErrorMsg());
        return false;
      }
    }
    return true;
  }

  function getData()
  {   
    //$this->_initRoundResults();
    return parent::getData();
  }
  
	function getSubroundInfo()
	{		
		$query = ' SELECT sr.projectround_id, p.id AS project_id, '
		        . ' r.name AS roundname, pr.comment, '
		        . ' srt.name AS subroundname, sr.id AS subround_id, sr.projectround_id, '
            . ' p.name AS projectname '
            . ' ,s.name AS seasonname '
		        . ' FROM #__tracks_projects_rounds AS pr '
		        . ' INNER JOIN #__tracks_rounds AS r ON r.id = pr.round_id '
            . ' INNER JOIN #__tracks_projects_subrounds AS sr ON sr.projectround_id = pr.id '
            . ' INNER JOIN #__tracks_subroundtypes AS srt ON sr.type = srt.id '
            . ' INNER JOIN #__tracks_projects AS p ON p.id = pr.project_id '
            . ' INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id '
            . ' WHERE sr.id=' . $this->subround_id 
            ;
		$this->_db->setQuery( $query );
		$res = $this->_db->loadObject();
		//echo "debug: ";	print_r($res);exit;
		return $res;
	}
	
	/**
	 * creates article and weblink for the subround results
	 * 
	 * @param id $subround_id
	 * @return boolean true on success
	 */
	public function createnews($subround_id)
	{
		$article = JTable::getInstance('content');
		$this->subround_id = $subround_id;
		
		$info = $this->getSubroundInfo();
		$results = $this->getData();
		
		$title = ( $info->seasonname =='event' 
		         ? $info->projectname 
		         : $info->projectname .' '. $info->subroundname . ' (' . $info->roundname .($info->comment ? ' '.$info->comment : '') .')'  ) . " report";
				
		$content = '';
		$podium = array();
		// results should be sorted by rank...
		foreach ($results as $r)
		{
			if ($r->rank && $r->rank < 4) {
				$podium[$r->rank][] = $r;
			}
		}
		$podiumres = array();
		foreach ($podium as $rank => $individuals)
		{
			$ind = array();
			foreach ($individuals as $i) {
				$ind[] = $i->first_name.' '.$i->last_name;
			}
			switch ($rank)
			{
				case 1:
					$podiumres[] = count($ind) > 1 
					             ? JText::sprintf('COM_TRACKS_XJ_ARTICLE_COME_FIRST', implode(JText::_('COM_TRACKS_XJ_ARTICLE_TIED_INDIVIDUALS_SEPARATOR'), $ind))
					             : JText::sprintf('COM_TRACKS_XJ_ARTICLE_COMES_FIRST', implode(JText::_('COM_TRACKS_XJ_ARTICLE_TIED_INDIVIDUALS_SEPARATOR'), $ind)) ;
					break;
				case 2:
					$podiumres[] = count($ind) > 1 
					             ? JText::sprintf('COM_TRACKS_XJ_ARTICLE_COME_SECOND', implode(JText::_('COM_TRACKS_XJ_ARTICLE_TIED_INDIVIDUALS_SEPARATOR'), $ind))
					             : JText::sprintf('COM_TRACKS_XJ_ARTICLE_COMES_SECOND', implode(JText::_('COM_TRACKS_XJ_ARTICLE_TIED_INDIVIDUALS_SEPARATOR'), $ind)) ;
					break;
				case 3:
					$podiumres[] = count($ind) > 1 
					             ? JText::sprintf('COM_TRACKS_XJ_ARTICLE_COME_THIRD', implode(JText::_('COM_TRACKS_XJ_ARTICLE_TIED_INDIVIDUALS_SEPARATOR'), $ind))
					             : JText::sprintf('COM_TRACKS_XJ_ARTICLE_COMES_THIRD', implode(JText::_('COM_TRACKS_XJ_ARTICLE_TIED_INDIVIDUALS_SEPARATOR'), $ind)) ;
					break;
			}
		}
		$content .= '<p>'.implode(", ", $podiumres).'</p>';
		
		// previous leader:		
		require_once (JPATH_SITE.DS.'components'.DS.'com_tracks'.DS.'sports'.DS.'default'.DS.'rankingtool.php');
		$rankingtool =  new TracksRankingTool($info->project_id);
		$previousranking = $rankingtool->getIndividualsRankings(null, null, $info->projectround_id, true);
		$ind = reset($previousranking);
		$previousleader = null;
		
		if ($ind && $ind->rank == 1 && $ind->points) 
		{
			foreach ($results as $r)
			{
				if ($r->individual_id == $ind->id && $r->rank > 3) {
					$content .= '<p>'.JText::sprintf('COM_TRACKS_XJ_ARTICLE_LEADER', $r->first_name.' '.$r->last_name, $r->rank).'</p>';
				}
			}
		}
		
		foreach ($results as $r)
		{
			if (strcasecmp($r->performance, 'DQ') == 0) {
				$content .= '<p>'.$r->first_name.' '.$r->last_name.' DQ-ed</p>';
			}
		}
		$fullres = TracksHelperRoute::getRoundResultRoute($info->projectround_id);
		$content .= '<p>'.JText::sprintf('COM_TRACKS_XJ_ARTICLE_COMPLETE_RESULTS_LINK', $fullres).'</p>';
		
		$article->title = $title;
		$article->alias = JFilterOutput::stringURLSafe($title);
		$article->introtext = $content;
		$article->catid = 78;
		$article->state = 1;
		$article->featured = 0;
		$article->language = "*";
		if (!$article->check()) {
			$this->setError($article->getError());
			return false;
		}
		
		if (!$article->store()) {
			$this->setError($article->getError());
			return false;
		}
		
		// then the weblink
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_weblinks'.DS.'tables'.DS.'weblink.php');
		require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
		
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('MAX(ordering)');
		$query->from('#__weblinks');
		$db->setQuery($query);
		$max = $db->loadResult();
		
		$weblink = JTable::getInstance('Weblink', 'WeblinksTable');
		
		$weblink->title = $title;
		$weblink->url = JRoute::_(JURI::root().ContentHelperRoute::getArticleRoute($article->id, $article->catid));
		$weblink->state = 1;
		$weblink->catid = 13;
		$weblink->language = "*";
		$weblink->ordering = $max+1;
		
		if (!$weblink->check()) {
			$this->setError($weblink->getError());
			return false;
		}
		
		if (!$weblink->store()) {
			$this->setError($weblink->getError());
			return false;
		}
		
		return true;
	}
}
?>

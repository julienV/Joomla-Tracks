<?php
/**
* Tracks Tab Class for handling the CB tab api
* @version $Id$
* @package Joomla tracks
* @subpackage trackstab.php
* @author Julien Vonthron
* @copyright (C) www.jlv-solutions.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

DEFINE('_TRACKS_ADMIN', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_tracks');
DEFINE('_TRACKS_SITE', JPATH_BASE.DS.'components'.DS.'com_tracks');


class getTracksTab extends cbTabHandler {
	
	function getTracksTab() {
		$this->cbTabHandler();
	}
	
	function getDisplayTab($tab,$user,$ui) {
		global $_CB_framework, $_CB_database, $mainframe;
    
		//parameters
		$params = $this->params;
    
		$return="";
		$query = "SELECT i.* "
		. "\n FROM #__tracks_individuals AS i "
		. "\n WHERE i.user_id=". (int) $user->id
		;
		$_CB_database->setQuery( $query );
		//print $_CB_database->getQuery();
		$items = $_CB_database->loadObjectList();
		if(!count($items)>0) {
			$return .= "<br /><br /><div class=\"sectiontableheader\" style=\"text-align:left;width:95%;\">";
			$return .= JText::_('No_associated_individual');
			$return .= "</div>";
			return $return;
		}
    		
		$return .= $this->_writeTabDescription( $tab, $user );
		
		$individual = $items[0];
		// individual
		$return .= '<div class="tracksprofile">';
		$individualURL= JRoute::_( 'index.php?option=com_tracks&amp;view=individual&amp;i=' . $individual->id);
		$return .= '<a href="'.$individualURL.'">'.$individual->first_name.' '.$individual->last_name.'</a>';
    $return .= '</div>';
    
	    
    if ($params->get('show_history')) 
    {
      require_once(_TRACKS_SITE.DS.'models'.DS.'ranking.php');
    
			$query = "SELECT pi.number, pi.project_id, p.name as projectname, t.name as teamname, s.name as seasonname"
	    . "\n FROM #__tracks_projects_individuals AS pi "
	    . "\n INNER JOIN #__tracks_projects AS p ON p.id = pi.project_id "
	    . "\n INNER JOIN #__tracks_competitions AS c ON c.id = p.competition_id "
	    . "\n INNER JOIN #__tracks_seasons AS s ON s.id = p.season_id "
	    . "\n LEFT JOIN #__tracks_teams AS t ON t.id = pi.team_id "
	    . "\n WHERE pi.individual_id=". (int) $individual->id
	    . "\n ORDER BY s.ordering DESC, c.ordering DESC, p.ordering DESC "
	    ;
	    $_CB_database->setQuery( $query );
	    //print $_CB_database->getQuery();
	    $items = $_CB_database->loadObjectList();
	    
	    if(count($items)>0) 
	    {
	      $return .= '<div class="tracksproject">';
	    	$return .= '<div class="">'.JText::_('History').'</div>';
	    	$return .= "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"95%\">";
		    $return .= "<tr class=\"sectiontableheader\">";
		    if ($params->get('show_number')) {
		      $return .= "<th>".JText::_('#')."</th>";
		    }
        if ($params->get('show_team')) {
		      $return .= "<th>".JText::_('Team')."</th>";
        }
	      $return .= "<th>".JText::_('Competition')."</th>";
        if ($params->get('show_season')) {
	        $return .= "<th>".JText::_('Season')."</th>";
        }
        if ($params->get('show_rank')) {
	        $return .= "<th>".JText::_('Rank')."</th>";
        }
        if ($params->get('show_points')) {
	        $return .= "<th>".JText::_('Points')."</th>";
        }
        if ($params->get('show_wins')) {
	        $return .= "<th>".JText::_('Wins')."</th>";
        }
        if ($params->get('show_bestrank')) {
	        $return .= "<th>".JText::_('Best_rank')."</th>";
        }
	      $return .= "</tr>";
	      $i=0;
	    	foreach ($items as $project)
	    	{
	    		$ranking = new TracksModelRanking();
	    		$rank = $ranking->getIndividualRanking($project->project_id, $individual->id);
	    		$link = JRoute::_('index.php?option=com_tracks&view=project&p=' . $project->project_id);
	        $return .= '<tr class="sectiontableentry'.(1+$i).'">';
	        if ($params->get('show_number')) {
	          $return .= '<td style="width:5px;">'.$project->number.'</td>';
	        }
	        if ($params->get('show_team')) {
	          $return .= '<td>'.$project->teamname.'</td>';
	        }
	        $return .= '<td><a href="'.$link.'" title="'.JText::_('Display').'">'.$project->projectname.'</td>';
	        if ($params->get('show_season')) {
	          $return .= '<td>'.$project->seasonname.'</td>'; 
	        }
	        if ($params->get('show_rank')) {
	          $return .= '<td>'.$rank->rank.'</td>';      
	        }
	        if ($params->get('show_points')) {
	          $return .= '<td>'.$rank->points.'</td>';     
	        }
	        if ($params->get('show_wins')) {
	          $return .= '<td>'.$rank->wins.'</td>';   
	        }
	        if ($params->get('show_bestrank')) {
	          $return .= '<td>'.$rank->best_rank.'</td>'; 
	        } 
	        $return .= '</tr>';
	    	}      
	      $return .= '</table>';  
	      $return .= '</div>';    	
	    }
    }
		return $return;
	}
}	// end class getTracksTab.
?>

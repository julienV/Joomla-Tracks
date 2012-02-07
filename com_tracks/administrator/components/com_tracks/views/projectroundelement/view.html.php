<?php
/**
* @version    $Id: view.html.php 94 2008-05-02 10:28:05Z julienv $ 
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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Tracks component
 *
 * @static
 * @package		Tracks
 * @since 0.1
 */
class TracksViewProjectroundElement extends JView
{
  function display()
  {
    $mainframe = &JFactory::getApplication();
$option = JRequest::getCmd('option');
    
    // Initialize variables
    $db     = &JFactory::getDBO();

    $document = & JFactory::getDocument();
    $document->setTitle('Project Round Selection');

    JHTML::_('behavior.modal');

    $template = $mainframe->getTemplate();
    $document->addStyleSheet("templates/$template/css/general.css");

    $limitstart = JRequest::getVar('limitstart', '0', '', 'int');
    
    $lists = $this->_getLists();

    $rows = &$this->get('List');
    $page = &$this->get('Pagination');
    JHTML::_('behavior.tooltip');
    
    $function = JRequest::getCmd('function', 'jSelectBook');
    ?>
    <form action="index.php?option=com_tracks&amp;controller=projectround&amp;task=element&amp;tmpl=component" method="post" name="adminForm" id="adminForm">

      <table>
        <tr>
          <td width="100%">
            <?php echo JText::_('COM_TRACKS_Filter' ); ?>:
            <input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
            <button onclick="this.form.submit();"><?php echo JText::_('COM_TRACKS_Go' ); ?></button>
            <button onclick="getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_TRACKS_Reset' ); ?></button>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap">
            <?php
            echo $lists['projectid'];
            echo $lists['competitionid'];
            echo $lists['seasonid'];
            ?>
          </td>
        </tr>
      </table>

      <table class="adminlist" cellspacing="1">
      <thead>
        <tr>
          <th width="5">
            <?php echo JText::_('COM_TRACKS_Num' ); ?>
          </th>
          <th class="title">
            <?php echo JHTML::_('grid.sort',   'Name', 'pr.name', @$lists['order_Dir'], @$lists['order'] ); ?>
          </th>
          <th class="title" width="15%" nowrap="nowrap">
            <?php echo JHTML::_('grid.sort',   'Project', 'p.name', @$lists['order_Dir'], @$lists['order'] ); ?>
          </th>
          <th class="title" width="15%" nowrap="nowrap">
            <?php echo JHTML::_('grid.sort',   'Competition', 'c.name', @$lists['order_Dir'], @$lists['order'] ); ?>
          </th>
          <th class="title" width="15%" nowrap="nowrap">
            <?php echo JHTML::_('grid.sort',   'Season', 's.name', @$lists['order_Dir'], @$lists['order'] ); ?>
          </th>
        </tr>
      </thead>
      <tfoot>
      <tr>
        <td colspan="5">
          <?php echo $page->getListFooter(); ?>
        </td>
      </tr>
      </tfoot>
      <tbody>
      <?php
      $k = 0;
      for ($i=0, $n=count( $rows ); $i < $n; $i++)
      {
        $row = &$rows[$i];

        $link   = '';
        ?>
        <tr class="<?php echo "row$k"; ?>">
          <td>
            <?php echo $page->getRowOffset( $i ); ?>
          </td>
          <td>
            <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $row->id; ?>', '<?php echo $this->escape(addslashes($row->name)); ?>');"><?php echo $this->escape($row->name); ?></a>
          </td>
          <td>
            <?php echo $row->project; ?>
          </td>
          <td>
            <?php echo $row->competition; ?>
          </td>
          <td>
            <?php echo $row->season; ?>
          </td>
        </tr>
        <?php
        $k = 1 - $k;
      }
      ?>
      </tbody>
      </table>

    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
    <input type="hidden" name="function" value="<?php echo $function; ?>" />
    </form>
    <?php
  }

  function _getLists()
  {
    $mainframe = &JFactory::getApplication();
$option = JRequest::getCmd('option');

    // Initialize variables
    $db   = &JFactory::getDBO();

    // Get some variables from the request
    $projectid      = JRequest::getVar( 'projectid', -1, '', 'int' );
    $redirect     = $projectid;
    $option       = JRequest::getCmd( 'option' );
    $filter_order   = $mainframe->getUserStateFromRequest('projectroundelement.filter_order',    'filter_order',   '', 'cmd');
    $filter_order_Dir = $mainframe->getUserStateFromRequest('projectroundelement.filter_order_Dir',  'filter_order_Dir', '', 'word');
    $filter_state   = $mainframe->getUserStateFromRequest('projectroundelement.filter_state',    'filter_state',   '', 'word');
    $competitionid = $mainframe->getUserStateFromRequest('projectroundelement.competitionid',  'competitionid', -1, 'int');
    $seasonid = $mainframe->getUserStateFromRequest('projectroundelement.seasonid',  'seasonid', -1, 'int');
    $limit        = $mainframe->getUserStateFromRequest('global.list.limit',          'limit', $mainframe->getCfg('list_limit'), 'int');
    $limitstart     = $mainframe->getUserStateFromRequest('projectroundelement.limitstart',      'limitstart',   0,  'int');
    $search       = $mainframe->getUserStateFromRequest('projectroundelement.search',        'search',     '', 'string');
    $search       = JString::strtolower($search);

    // get list of categories for dropdown filter
    $where = array();
    if ($competitionid > 0) {
      $where[] = 'p.competition_id = '.$db->Quote($competitionid);
    }
    if ($seasonid > 0) {
      $where[] = 'p.season_id = '.$db->Quote($seasonid);
    }
    if (count($where)) {
      $filter = ' WHERE ' . implode(' AND ', $where);
    }
    else {
    	$filter = "";
    }
    
    // get list of categories for dropdown filter
    $query = 'SELECT p.id AS value, p.name AS text' .
        ' FROM #__tracks_projects AS p' .
        $filter .
        ' ORDER BY p.ordering';
        
    $lists['projectid'] = TracksHelper::filterProject($query, $projectid);
    $lists['competitionid'] = TracksHelper::filterCompetition('', $competitionid);
    $lists['seasonid'] = TracksHelper::filterSeason('', $seasonid);
    
    // table ordering
    $lists['order_Dir'] = $filter_order_Dir;
    $lists['order']   = $filter_order;

    // search filter
    $lists['search'] = $search;

    return $lists;
  }
}
?>

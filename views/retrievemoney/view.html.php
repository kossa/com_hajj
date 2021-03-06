<?php
/**
 * @version     1.0.0
 * @package     com_weandlife
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Kouceyla Hadji <hadjikouceyla@gmail.com> - http://www.behance.net/kossa
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Weandlife.
 */
class hajjViewRetrieveMoney extends JViewLegacy
{

  /**
   * Display the view
   */
  public function display($tpl = null)
  {   
      $app         = JFactory::getApplication();
      
      $model       = JModelLegacy::getInstance('admin', 'HajjModel');
      $where       = 'register_status = 9 AND money_back = 0';
      $this->hajjs = $model->getHajjs(0, 0, $where);
      
      parent::display($tpl);
  }

}
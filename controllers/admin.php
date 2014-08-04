<?php
/**
 * @version     1.0.0
 * @package     com_hajj
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Kouceyla Hadji <hadjikouceyla@gmail.com> - http://www.behance.net/kossa
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class HajjControllerAdmin extends JControllerLegacy
{

/*
|------------------------------------------------------------------------------------
| Change the construct
|------------------------------------------------------------------------------------
*/
  public function __construct(){
   
    if (!JFactory::getUser()->authorise('core.manage', 'com_hajj'))
    {
      return JError::raiseWarning(404, "JText::_('JERROR_ALERTNOAUTHOR')");
    }
    parent::__construct();
  }

/*
|------------------------------------------------------------------------------------
| Get List of hajjs
|------------------------------------------------------------------------------------
*/
  public function Hajjs(){
    $result = $this->getModel("Admin")->getHajjs();

    $view   = $this->getView('adminhajjs', 'html'); //get the view
    $view->assignRef('data', $result); // assign data from the model
    $view->display(); // display the view
  }

/*
|------------------------------------------------------------------------------------
| Get only one Hajj
|------------------------------------------------------------------------------------
*/
  public function Hajj(){
    $jinput = JFactory::getApplication()->input;

    $id = $jinput->get('id','','STRING');

    $result = $this->getModel("Admin")->getHajj($id);

    $view   = $this->getView('adminhajj', 'html'); //get the view
    $view->assignRef('data', $result); // assign data from the model
    $view->display(); // display the view
  }

/*
|------------------------------------------------------------------------------------
| Get SMS status
|------------------------------------------------------------------------------------
*/
  public function Sms(){
    $result = $this->getModel("Admin")->getSMS();

    $view   = $this->getView('adminsms', 'html'); //get the view
    $view->assignRef('data', $result); // assign data from the model
    $view->display(); // display the view

  }

/*
|------------------------------------------------------------------------------------
| Admin Remove Hajj
|------------------------------------------------------------------------------------
*/
  public function removeHajj(){

    $app = JFactory::getApplication();
    $id = $app->input->get('id','','STRING');
    $hajj = $this->getModel("admin")->getHajj($id);

    $mobile = $hajj->mobile;
    $id_user = $hajj->id_user;

    $result = $this->getModel("hajj")->removeHajj($id_user, TRUE); // True for Admin

    $msgcode = "062A0645002006250644063A06270621002006270644062D062C0632000A";
    require_once JPATH_COMPONENT.'/helpers/' .'hajj.php';
    HajjFrontendHelper::sendTheSMS($mobile, $msgcode);

    $txt = "تم حذف الحجز رقم: " . $id ." بنجاح";
    $app->redirect("index.php?option=com_hajj&task=admin.hajjs", $txt, "success");

  }

/*
|------------------------------------------------------------------------------------
| Admin set Program
|------------------------------------------------------------------------------------
*/
  public function setProgram(){
    $app = JFactory::getApplication();
    $jinput = $app->input;

    $obj = new stdClass();
    $obj->id = $jinput->get('id','','STRING');
    $obj->name = $jinput->get('name','','STRING');
    $obj->price_program = $jinput->get('price_program','','STRING');
    $obj->status = $jinput->get('status','','STRING');

    if ($obj->id != "") { // Edit
      $this->getModel('admin')->setEditProgram($obj);
    }else{ // New Program
      $this->getModel('admin')->setProgram($obj);
    }

    
    $app->redirect('index.php?option=com_hajj&view=adminPrograms', 'تم حفظ البيانات بنجاح', 'success');
  }

/*
|------------------------------------------------------------------------------------
| Admin set Camps
|------------------------------------------------------------------------------------
*/
  public function setCamps(){
    $app = JFactory::getApplication();
    $jinput = $app->input;

    $obj = new stdClass();
    $obj->id = $jinput->get('id','','STRING');
    $obj->group = $jinput->get('group','','STRING');
    $obj->box = $jinput->get('box','','STRING');
    $obj->camp = $jinput->get('camp','','STRING');
    $obj->site = $jinput->get('site','','STRING');
    $obj->coordinates = $jinput->get('coordinates','','STRING');
    $obj->status = $jinput->get('status','','STRING');
    

    if ($obj->id != "") { // Edit
      $this->getModel('admin')->setEditCamps($obj);
    }else{ // New Camps
      $this->getModel('admin')->setCamps($obj);
    }

    
    $app->redirect('index.php?option=com_hajj&view=adminCamps', 'تم حفظ المخيم بنجاح', 'success');
  }


/*
|------------------------------------------------------------------------------------
| Admin set Program
|------------------------------------------------------------------------------------
*/
  public function setBranch(){
    $app = JFactory::getApplication();
    $jinput = $app->input;

    $obj = new stdClass();
    $obj->id = $jinput->get('id','','STRING');
    $obj->name = $jinput->get('name','','STRING');
    $obj->status = $jinput->get('status','','STRING');

    if ($obj->id != "") { // Edit
      $this->getModel('admin')->setEditBranch($obj);
    }else{ // New Branch
      $this->getModel('admin')->setBranch($obj);
    }
    
    $app->redirect('index.php?option=com_hajj&view=adminBranchs', 'تم حفظ البيانات بنجاح', 'success');
  }

/*
|------------------------------------------------------------------------------------
| Admin set Program
|------------------------------------------------------------------------------------
*/
  public function benefits(){
    $result = $this->getModel("Admin")->getBenefits();

    $view   = $this->getView('adminbenefits', 'html'); //get the view
    $view->assignRef('data', $result); // assign data from the model
    $view->display(); // display the view
  }
}
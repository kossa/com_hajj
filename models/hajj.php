<?php
/**
 * @version     1.0.0
 * @package     com_Hajj
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Kouceyla Hadji <hadjikouceyla@gmail.com> - http://www.behance.net/kossa
 */
 
// No direct access
defined('_JEXEC') or die;

class HajjModelHajj extends JModelLegacy {

/*
|------------------------------------------------------------------------------------
| Set new Hajj
|------------------------------------------------------------------------------------
*/
  public function setNewHajj($object) {
    $db = JFactory::getDbo();
    $result = $db->insertObject('#__hajj_users', $object);
    return $db->insertid();
  }

/*
|------------------------------------------------------------------------------------
| Get Id number by id user
|------------------------------------------------------------------------------------
*/
  public function getIdNumber($ID, $where=''){
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);      
    $query
        ->select($db->quoteName(array('id')))
        ->from($db->quoteName('#__hajj_users'))
        ->where($db->quoteName('id_user') . ' = '. $ID);

    if ($where != '') {
      $query->where($where);
    }

    $db->setQuery($query);
    $results = $db->loadObject();
    return (isset($results->id)) ? $results->id : 0;
  }

/*
|------------------------------------------------------------------------------------
| Set New Hajj Addon
|------------------------------------------------------------------------------------
*/
  public function setNewHajjAddon($object){
    $db = JFactory::getDbo();
    $result = $db->insertObject('#__hajj_users', $object);
    return $db->insertid();
  }


/*
|------------------------------------------------------------------------------------
| Get info Hajj
|------------------------------------------------------------------------------------
*/
  public function getHajj($ID){
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);        
    $query
        ->select('*')
        ->from($db->quoteName('#__hajj_users'))
        ->where($db->quoteName('id_user') . ' = '. $ID);
    
    $db->setQuery($query);
    $results = $db->loadObject();
    return $results;
  }


/*
|------------------------------------------------------------------------------------
| Get info Hajj
|------------------------------------------------------------------------------------
*/
  public function getHajjByIdHajj($ID){
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);        
    $query
        ->select('*')
        ->from($db->quoteName('#__hajj_users'))
        ->where($db->quoteName('id') . ' = '. $ID);
    
    $db->setQuery($query);
    $results = $db->loadObject();
    return $results;
  }



/*
|------------------------------------------------------------------------------------
| Edit Hajj
|------------------------------------------------------------------------------------
*/
  public function setEditHajj($object, $withUsersTable = true){

    JFactory::getDbo()->updateObject('#__hajj_users', $object, 'id_user');

    if ($withUsersTable) {
      $userObject           = new stdClass();
      $userObject->id       = $object->id_user;
      $userObject->email    = $object->email;
      $userObject->password = md5($object->mobile);
      JFactory::getDbo()->updateObject('#__users', $userObject, 'id');
    }
  }

/*
|------------------------------------------------------------------------------------
| get Register Status
|------------------------------------------------------------------------------------
*/
  public function getRegisterStatus($ID){

    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
    $query
        ->select($db->quoteName(array('register_status', 'id')))
        ->from($db->quoteName('#__hajj_users'))
        ->where($db->quoteName('id_user') . ' = '. $ID);
    
    $db->setQuery($query);
    $results = $db->loadObject();
    return $results; 
  }

/*
|------------------------------------------------------------------------------------
| get List of addons
|------------------------------------------------------------------------------------
*/ 
public function getAddons($ID){
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
    $query
        ->select("*")
        ->from($db->quoteName('#__hajj_users'))
        ->where($db->quoteName('addon') . ' = '. $ID);
    
    $db->setQuery($query);
    $results = $db->loadObjectList();
    return $results; 
  }

/*
|------------------------------------------------------------------------------------
| Get one Addon
|------------------------------------------------------------------------------------
*/
  public function getAddon($ID, $id_parent){
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);
    $query
        ->select("*")
        ->from($db->quoteName('#__hajj_users'))
        ->where($db->quoteName('addon') . ' = '. $id_parent . ' AND id = ' . $ID);
    
    $db->setQuery($query);
    $result = $db->loadObject();
    return $result;
  }

/*
|------------------------------------------------------------------------------------
| Remove Hajj
|------------------------------------------------------------------------------------
*/
  public function removeHajj($ID, $admin=false){ // If admin remove the hajj so we update SMS5

    $db    = JFactory::getDBO();
    $query = $db->getQuery(TRUE);

    // Fields to update.
    $fields = array($db->quoteName('register_status') . ' = ' . 5 );
    if ($admin) {
      array_push($fields, $db->quoteName('sms5') . ' = ' . "'تم إلغاء الحجز'");
    }

    // Update the status of the HAJJ
    $query
        ->update($db->quoteName('#__hajj_users'))
        ->set($fields) // Set register_status + sms 
        ->where($db->quoteName('id') . ' = ' . $ID . ' OR ' . $db->quoteName('addon') . ' = ' . $ID); // where id or addon 

    $db->setQuery($query)->query(); // Set the Query


    // Deleting users from user table
    // Get user to delete 
    $IDs = $this->getUserToDelete($ID);
    $theIDs = implode(', ', $IDs);

    // Delete the users
    $this->deleteUsers($theIDs);
    return;
  }

/*
|------------------------------------------------------------------------------------
| Get users to delete
|------------------------------------------------------------------------------------
*/
  public function getUserToDelete($ID){
    $db    = JFactory::getDBO();
    $query = $db->getQuery(true);    
    
    // get the IDs to remove
    $query
        ->select($db->quoteName(array('id_user')))
        ->from($db->quoteName('#__hajj_users'))
        ->where($db->quoteName('id') . ' = ' . $ID . ' OR ' . 'addon = ' . $ID);

    $db->setQuery($query);
    $results = $db->loadObjectList();

    $IDs = array($ID);
    foreach ($results as $key => $value) {
      array_push($IDs, $value->id_user);
    }

    return $IDs;
  }

/*
|------------------------------------------------------------------------------------
| Delete Users from USER table of Joomla
|------------------------------------------------------------------------------------
*/
  public function deleteUsers($IDs){
    $db    = JFactory::getDBO();
    $query = $db->getQuery(true);
    
    // Deleting users from user table
    $query
        ->delete($db->quoteName('#__users'))
        ->where($db->quoteName('id') . ' IN (' . $IDs . ')');

    $db->setQuery($query);
    $result = $db->query();
  }

/*
|------------------------------------------------------------------------------------
| get number of addons + price of the program for Update "ToPay" for the Hajj
|------------------------------------------------------------------------------------
*/
  public function getUpdateToPayHajj($ID){
    $db = JFactory::getDBO();
    
    $query = $db->getQuery(true);    
    $query
        ->select(array('COUNT(fils.id) AS nb_addon', 'HP.price_program'))
        ->from($db->quoteName('#__hajj_users', 'HU'))
        ->join('INNER', $db->quoteName('#__hajj_program', 'HP') . 
          ' ON (' . $db->quoteName('HP.id') . ' = ' . $db->quoteName('HU.hajj_program') . ')')
        ->leftJoin('#__hajj_users as fils ON fils.addon = HU.id ')
        ->group($db->quoteName('HU.id'))
        ->where($db->quoteName('HU.addon') . ' = 0 AND HU.register_status = 2 AND HU.id = ' . $ID . ' AND fils.register_status != 5');
    
    $db->setQuery($query);
    $results = $db->loadObject();

    return $results;
  } 

/*
|------------------------------------------------------------------------------------
| Update to pay
|------------------------------------------------------------------------------------
*/ 
  public function setUpdateToPayHajj($obj){
    $result = JFactory::getDbo()->updateObject('#__hajj_users', $obj, 'id');
    return $result;
  }

/*
|------------------------------------------------------------------------------------
| Set Hajjs to TamaDaf3
|------------------------------------------------------------------------------------
*/
  public function setTamaDaf3($obj){

    $result = JFactory::getDbo()->updateObject('#__hajj_users', $obj, id);
    return $result;
  }

/*
|------------------------------------------------------------------------------------
| Get mobiles of Hajjs after Tama Daf3
|------------------------------------------------------------------------------------
*/
  public function getMobile($ID){
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);    
    $query
        ->select($db->quoteName(array('mobile')))
        ->from($db->quoteName('#__hajj_users'))
        ->where($db->quoteName('id') . ' = '. $ID);
    
    $db->setQuery($query);
    $results = $db->loadObject()->mobile;
    return $results;
  }   

/*
|------------------------------------------------------------------------------------
| Set Gama Status to 1
|------------------------------------------------------------------------------------
*/
  public function setGamaStatus($IDsString, $value=1){
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    // Fields to update.
    $fields = array(
        $db->quoteName('gama_status') . ' = ' . $value ,
    );

    // Conditions for which records should be updated.
    $conditions = array(
        $db->quoteName('id') . ' in (' . $IDsString . ')', 
    );

    $query->update($db->quoteName('#__hajj_users'))->set($fields)->where($conditions);

    $db->setQuery($query);

    $result = $db->query();
    return $result;
  }
    
    
}
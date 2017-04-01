<?php

require_once 'CRM/Core/Page.php';

class CRM_Memberperiod_Page_PeriodDetails extends CRM_Core_Page {
 
  function run() {  
    // retrieve contact id
    $contactID  = (int) CRM_Utils_Request::retrieve( 'cid', 'Positive', $this, true, NULL, 'GET');
    
    // retrieve membership ids of this contact
    $membershipIds = self::getMembershipIds($contactID);
    $data = array();
    foreach ($membershipIds as $membershipId) {
      $query = "
        SELECT
        a.id as period_id, a.start_date, a.end_date, a.membership_id, b.id, b.total_amount
        FROM civicrm_membership_period a
        INNER JOIN civicrm_contribution b ON b.id = a.contribution_id
        WHERE a.membership_id = %1";
      $queryParams = array(1=>array($membershipId, 'Int'));
      $dao = CRM_Core_DAO::executeQuery($query, $queryParams);
      $count = 1;
      while ($dao->fetch()) {
        $data[$membershipId][$count++] = array(
          'start_date' => $dao->start_date,
          'end_date' => $dao->end_date,
          'contribution_id' => $dao->id,
          'contribution_amount' => $dao->total_amount,
          'contact_id' => $contactID);
      }
    }
    CRM_Core_Error::debug_var('$data', $data);
    // assign it to this form
    $this->assign('data', $data);
    parent::run();
  }
  
  static function getMembershipIds($contactId) {
    if(empty($contactId)) {
      return;      
    }
    // get memberships of a contact
    $result = civicrm_api3('Membership', 'get', array(
      'sequential' => 1,
      'return' => array("id"),
      'contact_id' => $contactId,
    ));  
    $membershipIds = array();
    foreach ($result['values'] as $key => $value) {
      $membershipIds[] = $value['id'];
    }
    return $membershipIds;
  }
}

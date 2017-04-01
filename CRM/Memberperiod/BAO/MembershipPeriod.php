<?php

class CRM_Memberperiod_BAO_MembershipPeriod extends CRM_Memberperiod_DAO_MembershipPeriod {

  /**
   * Create a new MembershipPeriod based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Memberperiod_DAO_MembershipPeriod|NULL
   *
   * 
   * @param array $params
   * @return \CRM_Memberperiod_DAO_MembershipPeriod|\NULL|\className
   */
  public static function create($params) {
    $className = 'CRM_Memberperiod_DAO_MembershipPeriod';
    $entityName = 'MembershipPeriod';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    
    if (!$instance->find(TRUE)) {
      $instance->save();
    }
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);
    return $instance;
  } 

}

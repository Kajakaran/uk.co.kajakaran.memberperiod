<?php

/**
 * MembershipPeriod.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_membership_period_create_spec(&$spec) {
  $spec['start_date']['api.required'] = 1;
  $spec['membership_id']['api.required'] = 1;
  $spec['end_date']['api.required'] = 0;
  $spec['contribution_id']['api.required'] = 0;
}

/**
 * MembershipPeriod.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_membership_period_create($params) {
  return _civicrm_api3_basic_create('CRM_Memberperiod_BAO_MembershipPeriod', $params);
}

/**
 * MembershipPeriod.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_membership_period_delete($params) {
  return _civicrm_api3_basic_delete('CRM_Memberperiod_BAO_MembershipPeriod', $params);
}

/**
 * MembershipPeriod.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_membership_period_get($params) {
  return _civicrm_api3_basic_get('CRM_Memberperiod_BAO_MembershipPeriod', $params);
}

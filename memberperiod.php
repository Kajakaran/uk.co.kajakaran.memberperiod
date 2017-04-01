<?php

require_once 'memberperiod.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function memberperiod_civicrm_config(&$config) {
  _memberperiod_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function memberperiod_civicrm_xmlMenu(&$files) {
  _memberperiod_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function memberperiod_civicrm_install() {
  _memberperiod_civix_civicrm_install();
  $schema = new CRM_Logging_Schema();
  $schema->fixSchemaDifferences();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function memberperiod_civicrm_postInstall() {
  _memberperiod_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function memberperiod_civicrm_uninstall() {
  _memberperiod_civix_civicrm_uninstall();
  $schema = new CRM_Logging_Schema();
  $schema->fixSchemaDifferences();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function memberperiod_civicrm_enable() {
  _memberperiod_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function memberperiod_civicrm_disable() {
  _memberperiod_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function memberperiod_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _memberperiod_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function memberperiod_civicrm_managed(&$entities) {
  _memberperiod_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function memberperiod_civicrm_caseTypes(&$caseTypes) {
  _memberperiod_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function memberperiod_civicrm_angularModules(&$angularModules) {
  _memberperiod_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function memberperiod_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _memberperiod_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function memberperiod_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function memberperiod_civicrm_navigationMenu(&$menu) {
  _memberperiod_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'uk.co.kajakaran.memberperiod')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _memberperiod_civix_navigationMenu($menu);
} // */

function memberperiod_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  // when new membership is created
  if ($objectName == 'Membership' && ($op == 'create' || $op == 'edit')) {
    // find membership period start date from membership end date if it is a edit
    if ($op == 'create') {
      $membershipPeriodStartDate = $objectRef->start_date;
    } else {
      $membershipPeriodStartDate = _memberperiod_get_period_start_date($objectRef->membership_type_id, $objectRef->end_date);
    }
    // create new membership period entry for this membership
    $result = civicrm_api3('MembershipPeriod', 'create', array(
      'sequential' => 1,
      'start_date' => $membershipPeriodStartDate,
      'end_date' => CRM_Utils_Date::processDate($objectRef->end_date),
      'membership_id' => $objectId,
    ));
  }
  // when membership payment is created
  if ($objectName == 'MembershipPayment' && $op == 'create') {
    // find membership and conribution ids
    $membershipId = $objectRef->membership_id;
    $contributionId = $objectRef->contribution_id;
    // find membership end date
    try {
      $result = civicrm_api3('Membership', 'get', array(
        'sequential' => 1,
        'return' => array("end_date", "membership_type_id"),
        'id' => $membershipId,
      ));
      $membershipEndDate = $result['values'][0]['end_date'];
      $membershipTypeId  = $result['values'][0]['membership_type_id'];
      $membershipPeriodStartDate = _memberperiod_get_period_start_date($membershipTypeId, $membershipEndDate);
       // create new period entry for this membership
      $result = civicrm_api3('MembershipPeriod', 'create', array(
        'sequential' => 1,
        'start_date' => $membershipPeriodStartDate,
        'end_date' => CRM_Utils_Date::processDate($membershipEndDate),
        'membership_id' => $membershipId,
        'contribution_id' => $contributionId,
      ));
    } catch (CiviCRM_API3_Exception $e) {
        CRM_Core_Error::debug_var('api error while getting membership end date', $e->getMessage());
    }
  }
}

/* 
 * Function to get membership period start date from end date and membership type
 */
function _memberperiod_get_period_start_date($membershipTypeId, $membershipEndDate) {
  // return if either membership type id or membership end date is empty
  if (empty($membershipTypeId) || empty($membershipEndDate)) {
    return;
  }
  
  // call membership type api to get duration unit and duration interval
  try {
    $result = civicrm_api3('MembershipType', 'get', array(
      'sequential' => 1,
      'return' => array("duration_unit", "duration_interval"),
      'id' => $membershipTypeId,
    ));
    
    // find membership period start date from membership end date from duration unit and duration interval
    $durationUnit = $result['values'][0]['duration_unit'];
    $durationInterval = $result['values'][0]['duration_interval'];
    $membershipPeriodStartDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($membershipEndDate)) . " -".$durationInterval." $durationUnit"));
    // fix me - start date should be 1 day extra
    $membershipPeriodStartDate = date("Y-m-d", strtotime($membershipPeriodStartDate . ' +1 day'));
  } catch (CiviCRM_API3_Exception $e) {
    CRM_Core_Error::debug_var('api error while calling membership type\s duration', $e->getMessage());
  }
  return CRM_Utils_Date::processDate($membershipPeriodStartDate);
}
/*
 * function to create new tab for shows membership periods for payments.
 */
function memberperiod_civicrm_tabs( &$tabs, $contactID ) {
  require_once 'CRM/Memberperiod/Page/PeriodDetails.php';
  $membershipIds = CRM_Memberperiod_Page_PeriodDetails::getMembershipIds($contactID);
  if (!empty($membershipIds)) {
    $url    = CRM_Utils_System::url( 'civicrm/contact/memberperiodinfo', 'snippet=2&cid='.$contactID);
    $tabs[] = array( 'id'    => 'memberperiod',
                     'url'   => $url,
                     'title' => 'Membership Period(s)',
                     'weight'=> 300
                    );
  }
}
<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2016                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 * Class CRM_Memberperiod_BAO_MembershipPeriodTest
 * @group headless
 */
class CRM_Memberperiod_BAO_MembershipPeriodTest extends CiviUnitTestCase {

  public function setUp() {
    parent::setUp();
    // FIXME: something NULLs $GLOBALS['_HTML_QuickForm_registered_rules'] when the tests are ran all together
    $GLOBALS['_HTML_QuickForm_registered_rules'] = array(
      'required' => array('html_quickform_rule_required', 'HTML/QuickForm/Rule/Required.php'),
      'maxlength' => array('html_quickform_rule_range', 'HTML/QuickForm/Rule/Range.php'),
      'minlength' => array('html_quickform_rule_range', 'HTML/QuickForm/Rule/Range.php'),
      'rangelength' => array('html_quickform_rule_range', 'HTML/QuickForm/Rule/Range.php'),
      'email' => array('html_quickform_rule_email', 'HTML/QuickForm/Rule/Email.php'),
      'regex' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
      'lettersonly' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
      'alphanumeric' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
      'numeric' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
      'nopunctuation' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
      'nonzero' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
      'callback' => array('html_quickform_rule_callback', 'HTML/QuickForm/Rule/Callback.php'),
      'compare' => array('html_quickform_rule_compare', 'HTML/QuickForm/Rule/Compare.php'),
    );

    $this->_contactID = $this->organizationCreate();
    $this->_membershipTypeID = $this->membershipTypeCreate(array('member_of_contact_id' => $this->_contactID));
    // add a random number to avoid silly conflicts with old data
    $this->_membershipStatusID = $this->membershipStatusCreate('test status' . rand(1, 1000));
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  public function tearDown() {
    $this->membershipTypeDelete(array('id' => $this->_membershipTypeID));
    $this->membershipStatusDelete($this->_membershipStatusID);
    $this->contactDelete($this->_contactID);

    $this->_contactID = $this->_membershipStatusID = $this->_membershipTypeID = NULL;
  }

  public function testCreate() {

    $contactId = $this->individualCreate();

    $params = array(
      'contact_id' => $contactId,
      'membership_type_id' => $this->_membershipTypeID,
      'join_date' => date('Ymd', strtotime('2006-01-21')),
      'start_date' => date('Ymd', strtotime('2006-01-21')),
      'end_date' => date('Ymd', strtotime('2006-12-21')),
      'source' => 'Payment',
      'is_override' => 1,
      'status_id' => $this->_membershipStatusID,
    );
    $ids = array();
    CRM_Member_BAO_Membership::create($params, $ids);

    $membershipId = $this->assertDBNotNull('CRM_Member_BAO_Membership', $contactId, 'id',
      'contact_id', 'Database check for created membership.'
    );
    // check for membership period
    $membershipPeriodId = $this->assertDBNotNull('CRM_Memberperiod_BAO_MembershipPeriod', $membershipId, 'id',
      'membership_id', 'Database check for created membership period.'
    );

    $this->membershipDelete($membershipId);
    $this->contactDelete($contactId);
  }

  /**
   * Renew membership with change in membership type.
   */
  public function testRenewMembership() {
    $contactId = $this->individualCreate();
    $joinDate = $startDate = date("Ymd", strtotime(date("Ymd") . " -6 month"));
    $endDate = date("Ymd", strtotime($joinDate . " +1 year -1 day"));
    $params = array(
      'contact_id' => $contactId,
      'membership_type_id' => $this->_membershipTypeID,
      'join_date' => $joinDate,
      'start_date' => $startDate,
      'end_date' => $endDate,
      'source' => 'Payment',
      'is_override' => 1,
      'status_id' => $this->_membershipStatusID,
    );
    $ids = array();
    $membership = CRM_Member_BAO_Membership::create($params, $ids);
    $membershipId = $this->assertDBNotNull('CRM_Member_BAO_Membership', $contactId, 'id',
      'contact_id', 'Database check for created membership.'
    );

    // check for membership period
    $membershipPeriodId = $this->assertDBNotNull('CRM_Memberperiod_BAO_MembershipPeriod', $membershipId, 'id',
      'membership_id', 'Database check for created membership period.'
    );
    
    $this->assertDBNotNull('CRM_Member_BAO_MembershipLog',
      $membership->id,
      'id',
      'membership_id',
      'Database checked on membershiplog record.'
    );

    // this is a test and we dont want qfKey generation / validation
    // easier to suppress it, than change core code
    $config = CRM_Core_Config::singleton();
    $config->keyDisable = TRUE;

    $isTestMembership = 0;
    list($MembershipRenew) = CRM_Member_BAO_Membership::processMembership(
      $contactId,
      $this->_membershipTypeID,
      $isTestMembership,
      NULL,
      NULL,
      NULL,
      1,
      FALSE,
      NULL,
      NULL,
      FALSE,
      NULL,
      NULL
    );
    $endDate = date("Y-m-d", strtotime($membership->end_date . " +1 year"));

    $this->assertDBNotNull('CRM_Member_BAO_MembershipLog',
      $MembershipRenew->id,
      'id',
      'membership_id',
      'Database checked on membershiplog record.'
    );
    // check end date exists in membership period table
    $membershipPeriodId = $this->assertDBNotNull('CRM_Memberperiod_BAO_MembershipPeriod', $endDate, 'id',
      'end_date', 'Database check for created membership period.'
    );
    $this->assertEquals($this->_membershipTypeID, $MembershipRenew->membership_type_id, 'Verify membership type is changed during renewal.');
    $this->assertEquals($endDate, $MembershipRenew->end_date, 'Verify correct end date is calculated after membership renewal');

    $this->membershipDelete($membershipId);
    $this->contactDelete($contactId);
  }

}

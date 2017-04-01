# uk.co.kajakaran.memberperiod

# Membership Period Functionality for CiviCRM Membership#

### Overview ###

Currently, when a membership is renewed in CiviCRM the “end date” field on the membership itself is extended by the length of the membership as defined in CiviCRM membership type configuration
but no record of the actual length of any one period or term is recorded.
As such it is not possible to see how many “terms” or “periods” of membership a contact may have had. 

I.e. If a membership commenced on 1 Jan 2014 and each term was of 12 months in length, by 1 Jan 2016 the member would be renewing for their 3rd term. The terms would be:

Term/Period 1: 1 Jan 2014 - 31 Dec 2014
Term/Period 2: 1 Jan 2015 - 31 Dec 2016
Term/Period 3: 1 Jan 2016 - 31 Dec 2017

### Aim ###

The aim of this extension is to extend the CiviCRM membership component so that when a membership is created or renewed a record for the membership “period” is recorded. 
The membership period should be connected to a contribution record if a payment is taken for this membership or renewal.

### Installation ###

* Install the extension manually in CiviCRM. More details [here](http://wiki.civicrm.org/confluence/display/CRMDOC/Extensions#Extensions-Installinganewextension) about installing extensions in CiviCRM.

### Usage ###

* Create/Renew membership with membership payment and look for new membership period(s) tab for membership periods for that membership in contact summary.

### Support ###

kajakaran (at) yahoo.com
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Creates a pull-down list of countries
 */

function autoCompleteCountryList($name, $selected = '', $parameters = '') {
  global $db;

  $countriesAtTopOfList = array();
  $countries_array = array(
    array(
      'id' => '',
      'text' => PULL_DOWN_DEFAULT
    )
  );
  $countries = $db->Execute("SELECT countries_id, countries_name, countries_iso_code_2
                             FROM " . TABLE_COUNTRIES . "
                             WHERE status != 0
                             ORDER BY countries_name");
  foreach ($countries as $values) {
    $countries_values[$values['countries_id']] = array(
      'countries_id' => $values['countries_id'],
      'countries_iso_code_2' => $values['countries_iso_code_2'],
      'countries_name' => $values['countries_name']);
  }

  // Set some default entries at top of list:
  if (STORE_COUNTRY != SHOW_CREATE_ACCOUNT_DEFAULT_COUNTRY) {
    $countriesAtTopOfList[] = $countries_values[SHOW_CREATE_ACCOUNT_DEFAULT_COUNTRY]['countries_iso_code_2'];
  }
  $countriesAtTopOfList[] = $countries_values[STORE_COUNTRY]['countries_iso_code_2'];
  // IF YOU WANT TO ADD MORE DEFAULTS TO THE TOP OF THIS LIST, SIMPLY ENTER THEIR NUMBERS HERE.
  // Duplicate more lines as needed
  // Example: Canada is 108, so use 108 as shown:
  //$countriesAtTopOfList[] = 108;
  //process array of top-of-list entries:
  foreach ($countriesAtTopOfList as $key => $val) {
    $countries_name = $db->Execute("SELECT countries_name
                                    FROM " . TABLE_COUNTRIES . "
                                    WHERE countries_iso_code_2 = '" . $val . "'");
    $countries_array[] = array(
      'id' => $val,
      'text' => $countries_name->fields['countries_name']);
  }
  // now add anything not in the defaults list:
  foreach ($countries_values as $key => $value) {
    $alreadyInList = FALSE;
    foreach ($countriesAtTopOfList as $key => $val) {
      if ($value['countries_iso_code_2'] == $val) {
        // If you don't want to exclude entries already at the top of the list, comment out this next line:
        $alreadyInList = TRUE;
        continue;
      }
    }
    if (!$alreadyInList) {
      $countries_array[] = array(
        'id' => $value['countries_iso_code_2'],
        'text' => $value['countries_name']);
    }
  }

  if ($selected != '') {
    $query = $db->Execute("SELECT countries_iso_code_2
                           FROM " . TABLE_COUNTRIES . "
                           WHERE countries_id = " . (int)$selected . "
                           ORDER BY countries_id");
    $selected_iso_code = $query->fields['countries_iso_code_2'];
  } else {
    $selected_iso_code = '';
  }
  return zen_draw_pull_down_menu($name, $countries_array, $selected_iso_code, $parameters);
}

function gaa_countries() {
  global $db;

  $countriesQuery = "SELECT countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id
                     FROM " . TABLE_COUNTRIES . "
                     WHERE status != 0";
  $countries = $db->Execute($countriesQuery);

  $countriesArray = array();

  foreach ($countries as $countriesValues) {
    $countriesArray[] = array(
      'countries_id' => $countriesValues['countries_id'],
      'countries_name' => $countriesValues['countries_name'],
      'countries_iso_code_2' => $countriesValues['countries_iso_code_2'],
      'countries_iso_code_3' => $countriesValues['countries_iso_code_3'],
      'address_format_id' => $countriesValues['address_format_id']);
  }
  return $countriesArray;
}

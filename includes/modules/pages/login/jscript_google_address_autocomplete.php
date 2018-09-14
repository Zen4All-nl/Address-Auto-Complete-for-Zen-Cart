<?php

/*
 * change to db values later
 * current values to use are "long" or "short"
 */

?>
<script type="text/javascript">
  $(function ($) {
    $(document).ready(function () {
      //show the hidden fields manually
      $('#billing_address_not_found').click(function (e) {
        e.preventDefault();
        $('#billing_address_not_found').hide();
        $('#billing_hide_detailed_address').show();
        $('.details_billing').slideDown('slow','swing',function() {
          if ($(this).is(':visible'))
            $(this).css('display','block');
}         );
        });

      $('#billing_hide_detailed_address').click(function (e) {
        e.preventDefault();
        $('#billing_address_not_found').show();
        $('#billing_hide_detailed_address').hide();
        $('.details_billing').slideUp();
      });
    }); //document ready
  }); //jQuery
</script>
<?php $countryRestrict = zen_get_countries_with_iso_codes(SHOW_CREATE_ACCOUNT_DEFAULT_COUNTRY); ?>
<script type="text/javascript">

  var placeSearch;
  var autocomplete;
  var countryRestrict = {'country': '<?php echo strtolower($countryRestrict['countries_iso_code_2']); ?>'};
  var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
<?php
if (ACCOUNT_STATE == 'true') {
  if ($flag_show_pulldown_states == false) {
    if (GAA_STATE_NAME_LENGTH == 'long') {
?>
    administrative_area_level_1: 'long_name',
<?php
    } elseif (GAA_STATE_NAME_LENGTH == 'short') {
?>
    administrative_area_level_1: 'short_name',
<?php
    }
  }
}
?>
    country: 'short_name',
    postal_code: 'short_name'
  };

  function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('autocomplete')), {
              types: ['geocode'],
              componentRestrictions: countryRestrict
            }
    );
    // When the user selects an address from the dropdown, populate the address fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
    // Add a DOM event listener to react when the user selects a country.
    document.getElementById('autoCompleteCountry').addEventListener('change', setAutocompleteCountry);
  }
  // Set the country restriction based on user input.
  function setAutocompleteCountry() {
    var country = document.getElementById('autoCompleteCountry').value;
    autocomplete.setComponentRestrictions({'country': country});
    clearResults();
  }

  function clearResults() {
    var results = document.getElementById('autocomplete');
    while (results.childNodes[0]) {
      results.removeChild(results.childNodes[0]);
    }
  }

  function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();
  /*
    for (var component in componentForm) {
      document.getElementById(component).value = '';
      document.getElementById(component).disabled = false;
    }
  */
    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    var countriesArray = <?php echo json_encode(gaa_countries()) ; ?>;
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var addressVal = place.address_components[i][componentForm[addressType]];
        if (addressType == 'locality') {
          $('#city').val(addressVal);
        } else if (addressType == 'postal_code') {
          $('#postal_code').val(addressVal);
        } else if (addressType == 'administrative_area_level_1') {
          $('#state').val = addressVal;
        } else if (addressType == 'country') {
          for (var j = 0; j < countriesArray.length; j++) {
            var countries_iso_code_2 = countriesArray[j].countries_iso_code_2;
            if (countries_iso_code_2 == place.address_components[i][componentForm[addressType]]) {
    //          var counrtyShortName = place.address_components[i][componentForm[addressType]]
              var countryId = countriesArray[j].countries_id;
              $('#country').val(countryId);
            }
          }
        } else if (addressType == 'street_number') {
          $('#street_number').val(addressVal);
        } else if (addressType == 'route') {
            $('#street_address').val(addressVal);
        }
      }
    }
    if (place.address_components != undefined) {
      $('.details_billing').slideDown();
      $('#billing_address_not_found').hide();
      $('#locationField').hide();
    }
  }
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
  function geolocate() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var circle = new google.maps.Circle({
          center: geolocation,
          radius: position.coords.accuracy
        });
        autocomplete.setBounds(circle.getBounds());
      });
    }
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GAA_API_KEY; ?>&libraries=places&callback=initAutocomplete" async defer></script>
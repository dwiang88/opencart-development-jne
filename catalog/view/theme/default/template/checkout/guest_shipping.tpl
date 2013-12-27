<table class="form">
  <tr>
    <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
    <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
    <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><?php echo $entry_company; ?></td>
    <td><input type="text" name="company" value="<?php echo $company; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
    <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><?php echo $entry_address_2; ?></td>
    <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_city; ?></td>
    <td><input type="text" name="city" value="<?php echo $city; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
    <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_country; ?></td>
    <td><select name="country_id" class="large-field">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($countries as $country) { ?>
        <?php if ($country['country_id'] == $country_id) { ?>
        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?>
        <?php } ?>
      </select></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
    <td><select name="zone_id" class="large-field">
      </select></td>
  </tr>
  <!-- ================ JNE ================ -->
  <tr id="cb-shipping-address-city" 
      style="display:<?php echo ($country_id == 100) ? 'table-row' : 'none' ; ?>">
    <td><span class="required">*</span> <?php echo $entry_city; ?></td>
    <td><select name="city_id" class="large-field">
        <option value=""><?php echo $text_select; ?></option>
        </select>
    </td>
  </tr>>
  <!-- ================ / ================ -->
</table>
<br />
<div class="buttons">
  <div class="right"><input type="button" value="<?php echo $button_continue; ?>" id="button-guest-shipping" class="button" /></div>
</div>
<script type="text/javascript"><!--
$('#shipping-address select[name=\'country_id\']').bind('change', function() {
	var value = this.value;
  if ( value == '') return;
  else {
    if( value == 100 )
      $('#cb-shipping-address-city').show();
    else
      $('#cb-shipping-address-city').hide();
  }

	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + value,
		dataType: 'json',
		beforeSend: function() {
			$('#shipping-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#shipping-postcode-required').show();
			} else {
				$('#shipping-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('#shipping-address select[name=\'zone_id\']').html(html);

      if(value == 100) $('#payment-address select[name=\'zone_id\']').trigger('change');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// JNE cb zone
$('#shipping-address select[name=\'zone_id\']').bind('change', function() {
  var country_id = $('select[name=\'country_id\']').val();
  var zone_id = this.value ? this.value : '<?php echo $zone_id; ?>';

  console.log('shipping-address:zone_id',  zone_id);

  // indonesia only (country id = 100)
  if( !zone_id || !country_id || country_id != 100 ) return false;

  $.ajax({
    url: 'index.php?route=checkout/cart/jneTax&act=city&province=' + zone_id,
    dataType: 'json',
    beforeSend: function() {
      $('select[name=\'zone_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
    },
    complete: function() {
      $('.wait').remove();
    },      
    success: function(json) {
      if (json['postcode_required'] == '1') {
        $('#postcode-required').show();
      } else {
        $('#postcode-required').hide();
      }

      console.log('shipping-address:city_id', '<?php echo $city_id; ?>');
      
      var $cb = $('select[name=\'city_id\']');
      $cb.html('<option value=""><?php echo $text_select; ?></option>');

      $.each(json['data'], function(key, cat) {
        // create group
        var group = $('<optgroup>', {
          label: key
        });
        // option combobox kota
        $.each(cat, function(k, v) {
          var option = $("<option/>", { value: k, text : v });

          if( k == '<?php echo $city_id; ?>' ){
            option.prop('selected', true);
          }

          option.appendTo(group);
        });
        // add to group
        group.appendTo($cb);
      });

      $('#shipping-address select[name=\'city_id\']').trigger('change');

    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('#shipping-address select[name=\'city_id\']').change(function() {
  var selected = $("option:selected", this);
  var city = selected.parent()[0].label + ', ' + selected.text();
  if( !selected.val() ){
    $('#shipping-address input[name=\'city\']').val('');
    return;
  }
  $('#shipping-address input[name=\'city\']').val(city);
});

$('#shipping-address select[name=\'country_id\']').trigger('change');
//--></script>
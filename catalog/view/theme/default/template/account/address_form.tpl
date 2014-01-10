<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_edit_address; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_company; ?></td>
          <td><input type="text" name="company" value="<?php echo $company; ?>" /></td>
        </tr>
        <?php if ($company_id_display) { ?>
        <tr>
          <td><?php echo $entry_company_id; ?></td>
          <td><input type="text" name="company_id" value="<?php echo $company_id; ?>" />
            <?php if ($error_company_id) { ?>
            <span class="error"><?php echo $error_company_id; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php if ($tax_id_display) { ?>
        <tr>
          <td><?php echo $entry_tax_id; ?></td>
          <td><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
            <?php if ($error_tax_id) { ?>
            <span class="error"><?php echo $error_tax_id; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" />
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
        </tr>
        <tr id="input-account-city"
            style="display: <?php echo $country_id == 100 ? 'table-row' : 'none' ; ?>">
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><select name="country_id">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
          <td><select name="zone_id">
            </select>
            <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?></td>
        </tr>

        <tr id="cb-account-address-city"
            style="display: <?php echo $country_id == 100 ? 'table-row' : 'none' ; ?>">
          <td><span class="required">*</span> <?php echo $entry_city_id; ?></td>
          <td><select name="city_id">
            </select>
            <?php if ($error_city_id) { ?>
            <span class="error"><?php echo $error_city_id; ?></span>
            <?php } ?></td>
        </tr>

        <tr>
          <td><?php echo $entry_default; ?></td>
          <td><?php if ($default) { ?>
            <input type="radio" name="default" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="default" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="default" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="default" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
var city_id = '<?php echo $city_id ?>';
$('select[name=\'country_id\']').bind('change', function() {
  var value = this.value;
  if( value == 100 ){    
    $('#input-account-city').hide();
    $('#cb-account-address-city').show();
  } else {
    $('#input-account-city').show();
    $('#cb-account-address-city').hide();
  }
	$.ajax({
		url: 'index.php?route=account/address/country&country_id=' + value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
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
			
			$('select[name=\'zone_id\']').html(html);
      
      if(value == 100) $('select[name=\'zone_id\']').trigger('change');

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// JNE cb zone
$('select[name=\'zone_id\']').bind('change', function() {
  var country_id = $('select[name=\'country_id\']').val();
  var textSelected = $(this).find('option:selected').text();
  var zone_id = this.value ? textSelected : '<?php echo $zone_id; ?>';
 
  console.log('zone_id',  zone_id);
  console.log('city_id',  city_id);

  // indonesia only (country id = 100)
  if( !zone_id || !country_id || country_id != 100 ) return false;

  $.ajax({
    url: 'index.php?route=checkout/jne/tax&act=city&province=' + zone_id,
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

          if( k == city_id ){
            option.prop('selected', true);
          }

          option.appendTo(group);
        });
        // add to group
        group.appendTo($cb);
      });

      $cb.trigger('change');

    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// JNE combobox city
$('select[name=\'city_id\']').change(function() {
  var selected = $("option:selected", this);
  var city = selected.parent()[0].label + ', ' + selected.text();
  if( !selected.val() ){
    $('input[name=\'city\']').val('');
    return;
  }
  $('input[name=\'city\']').val(city);
});

$('select[name=\'country_id\']').trigger('change');
//--></script> 
<?php echo $footer; ?>
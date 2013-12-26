<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>

  <?php
  // echo '<pre>';
  // var_dump($jne_zone);
  // echo '</pre>';
  ?>

  <style>
    .jne-setting-provinces { display:inline-table; width: 200px }
    .jne-setting-provinces label{ display:block }
  </style>

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td style="vertical-align:top;"><?php echo $entry_jne_province; ?></td>
            <td>
              <p>
                <label>
                  <input type="checkbox" id="select-all-provinces" />
                  <?php echo $entry_jne_select_all; ?>
                </label>
              </p>
              <div class="jne-setting-provinces">
              <?php 
              $i = 1; 
              foreach( $jne_zone as $index => $prov ) : 
                $checked = ( $jne_zone_allowed ) 
                        ? (in_array( $index, $jne_zone_allowed ) ? 'checked' : '')
                        : 'checked' ;
                ?>
                <label>
                  <input type="checkbox" name="jne_zone_allowed[]" value="<?php echo $index ?>" class="cb-provinsi" <?php echo $checked;  ?> /> <?php echo $prov['name'] ?>
                </label>
                <?php if( ($i % 10) == 0 ) : ?>
                </div>
                <div class="jne-setting-provinces">
                <?php 
                endif; 
                $i++; 
              endforeach; ?>
              </div>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_jne_tolerance; ?></td>
            <td>
              <input type="number" name="jne_tolerance" value="<?php echo $jne_tolerance; ?>" min="0" step="0.1" />
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="jne_status">
                <?php if ($jne_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="jne_sort_order" value="<?php echo $jne_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
<script type="text/javascript">
  jQuery(document).ready(function($){
    $('#select-all-provinces').click(function(){
      var checked = this.checked
      $('input.cb-provinsi').each(function(){
        this.checked = checked;
      })
    });    
    $('#form').submit(function(){
      var form = $(this).serialize(),
          cb = $('input.cb-provinsi'),                      
          cbChecked = cb.filter(':checked').length;
      
      if( cbChecked == 0 )
      {
        alert('Silahkan pilih provinsi. provinsi tidak boleh kosong');
        return false;
      }
    }); 
  });
</script>
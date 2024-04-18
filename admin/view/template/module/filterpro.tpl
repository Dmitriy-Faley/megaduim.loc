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
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons">
	<a class="button" id="apply_button" onclick="apply()"><span><?php echo $button_apply; ?></span></a>
		<a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
<div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-data"><?php echo $tab_seo; ?></a><a href="#tab-additional"><?php echo $tab_additional; ?></a></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<div id="tab-general">
	  <table>
<tr valign="top">
	<td>
		<table id="settings" class="list" style="width: 830px;">
			<thead>
			<tr>
				<td></td>
				<td></td>
				<td><input type="checkbox" onchange="expand_all(this.checked)">&nbsp;<?php echo $text_expanded?></td>
				<td style="width: 330px;"><?php echo $text_tooltip; ?></td>
			</tr>
			</thead>
			<tr>
				<td><?php echo $text_price_slider?></td>
				<td colspan="3"><input id="price_slider_yes" type="radio" name="filterpro_setting[price_slider]"
						   value="1" <?php if(!isset($setting['price_slider']) || $setting['price_slider'] == '1') echo " checked='checked'"?>>
					<label for="price_slider_yes"><?php echo $text_yes?></label>
					<input id="price_slider_no" type="radio" name="filterpro_setting[price_slider]"
						   value="0" <?php if(isset($setting['price_slider']) && $setting['price_slider'] == '0') echo " checked='checked'"?>>
					<label for="price_slider_no"><?php echo $text_no?></label></td>
			</tr>
			<?php if (version_compare(VERSION, "1.5.5")>=0) {?>
			<tr>
				<td><?php echo $text_filters?></td>
				<td><input id="filters_yes" type="radio" name="filterpro_setting[filters]"
						   value="1" <?php if(isset($setting['filters']) && $setting['filters'] == '1') echo " checked='checked'"?>>
					<label for="filters_yes"><?php echo $text_yes?></label>
					<input id="filters_no" type="radio" name="filterpro_setting[filters]"
						   value="0" <?php if(!isset($setting['filters']) || $setting['filters'] == '0') echo " checked='checked'"?>>
					<label for="filters_no"><?php echo $text_no?></label></td>
				<td colspan="2"><input type="checkbox" class="expanded" name="filterpro_setting[expanded_filters]" <?php if(isset($setting['expanded_filters'])) echo ' checked="checked"';?>></td>
			</tr>
			<?php } ?>
			<tr>
				<td><?php echo $text_manufacturer?></td>
				<td>
					<select name="filterpro_setting[display_manufacturer]">
						<?php foreach($display_options as $display_option) { ?>
						<option value="<?php echo $display_option['value']?>"
							<?php if(isset($setting['display_manufacturer']) && $display_option['value'] == $setting['display_manufacturer']) echo ' selected="selected"';?>>
							<?php echo $display_option['name']?>
						</option>
						<?php }?>
					</select>
				</td>
				<td colspan="4"><input type="checkbox" class="expanded" name="filterpro_setting[expanded_manufacturer]" <?php if(isset($setting['expanded_manufacturer'])) echo ' checked="checked"';?>></td>
			</tr>
			<tr>
				<td><?php echo $text_categories?></td>
				<td>
					<select name="filterpro_setting[display_categories]">
						<?php foreach($display_categories as $display) { ?>
						<option value="<?php echo $display['value']?>"
							<?php if(isset($setting['display_categories']) && $display['value'] == $setting['display_categories']) echo ' selected="selected"';?>>
							<?php echo $display['name']?>
						</option>
						<?php }?>
					</select>
				</td>
				<td colspan="4"><input type="checkbox" class="expanded" name="filterpro_setting[expanded_categories]" <?php if(isset($setting['expanded_categories'])) echo ' checked="checked"';?>></td>
			</tr>
			<tr>
				<td><?php echo $text_tags?></td>
				<td>
					<select name="filterpro_setting[display_tags]">
						<?php foreach($display_categories as $display) { ?>
						<option value="<?php echo $display['value']?>"
							<?php if(isset($setting['display_tags']) && $display['value'] == $setting['display_tags']) echo ' selected="selected"';?>>
							<?php echo $display['name']?>
						</option>
						<?php }?>
					</select>
				</td>
				<td colspan="4"><input type="checkbox" class="expanded" name="filterpro_setting[expanded_tags]" <?php if(isset($setting['expanded_tags'])) echo ' checked="checked"';?>></td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<?php $option_row = 0; ?>
			<?php foreach($options as $option){?>
			<?php $option_row++; ?>
			<tr>
				<td><?php echo $option['name']?></td>
				<td>
					<select name="filterpro_setting[display_option_<?php echo $option['option_id']?>]">
						<?php foreach($display_options as $display_option) { ?>
						<option value="<?php echo $display_option['value']?>"
							<?php if(isset($setting['display_option_' . $option['option_id']]) && $display_option['value'] == $setting['display_option_' . $option['option_id']]) echo '  selected="selected"';?>>
							<?php echo $display_option['name']?>
						</option>
						<?php }?>
					</select>
				</td>
				<td><input type="checkbox" class="expanded" name="filterpro_setting[expanded_option_<?php echo $option['option_id']?>]" <?php if(isset($setting['expanded_option_' . $option['option_id']])) echo ' checked="checked"';?>></td>
                <td>
					<textarea name="option[<?php echo $option_row; ?>][tooltip]" style="width: 325px; overflow: auto;" rows="1"><?php echo $option['tooltip']; ?></textarea>
                    <input type="hidden" name="option[<?php echo $option_row; ?>][option_id]" value="<?php echo $option['option_id'] ?>" />
					<script type="text/javascript">
					<!--
					$('textarea[name="option[<?php echo $option_row; ?>][tooltip]"]').dblclick(function()
					{
						$(this).ckeditor
						({
							width: "325px", height: "75px", autoParagraph: false,
							resize_enabled: true, toolbarStartupExpanded: false,
							filebrowserBrowseUrl:      'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserUploadUrl:      'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
						});
					});
					//-->
					</script>
            	</td>
			</tr>
			<?php }?>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<?php $attribute_row = 0; ?>
			<?php $attribute_group_row = 0; ?>
			<?php $attribute_group = false;?>
			<?php foreach($attributes as $attribute){?>
			<?php if ($attribute_group !=$attribute['attribute_group']) { ?>
				<?php $attribute_group = $attribute['attribute_group'];?>
				<?php $attribute_group_row++; ?>
				<tr>
					<td colspan="3" style="text-align: center;"><?php echo $attribute['attribute_group']?></td>
					<td>
						<textarea name="attribute_group[<?php echo $attribute_group_row; ?>][attribute_group_tooltip]" style="width: 325px; overflow: auto;" rows="1"><?php echo $attribute['attribute_group_tooltip']; ?></textarea>
						<input type="hidden" name="attribute_group[<?php echo $attribute_group_row; ?>][attribute_group_id]" value="<?php echo $attribute['attribute_group_id'] ?>" />
						<script type="text/javascript">
						<!--
						$('textarea[name="attribute_group[<?php echo $attribute_group_row; ?>][attribute_group_tooltip]"]').dblclick(function()
						{
							$(this).ckeditor
							({
								width: "325px", height: "75px", autoParagraph: false,
								resize_enabled: true, toolbarStartupExpanded: false,
								filebrowserBrowseUrl:      'index.php?route=common/filemanager&token=<?php echo $token; ?>',
								filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
								filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
								filebrowserUploadUrl:      'index.php?route=common/filemanager&token=<?php echo $token; ?>',
								filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
								filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
							});
						});
						//-->
						</script>
					</td>
				</tr>
			<?php }	?>
			<?php $attribute_row++; ?>
			<tr>
				<td><?php echo $attribute['name']?></td>
				<td>
					<select name="filterpro_setting[display_attribute_<?php echo $attribute['attribute_id']?>]">
						<?php foreach($display_options_attr as $display_option) { ?>
						<option value="<?php echo $display_option['value']?>"
							<?php if(isset($setting['display_attribute_' . $attribute['attribute_id']]) && $display_option['value'] == $setting['display_attribute_' . $attribute['attribute_id']]) echo '  selected="selected"';?>>
							<?php echo $display_option['name']?>
						</option>
						<?php }?>
					</select>
				</td>
				<td><input type="checkbox" class="expanded" name="filterpro_setting[expanded_attribute_<?php echo $attribute['attribute_id']?>]" <?php if(isset($setting['expanded_attribute_' . $attribute['attribute_id']])) echo ' checked="checked"';?>></td>
				<td>
					<textarea name="attribute[<?php echo $attribute_row; ?>][attribute_tooltip]" style="width: 325px; overflow: auto;" rows="1"><?php echo $attribute['attribute_tooltip']; ?></textarea>
					<input type="hidden" name="attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $attribute['attribute_id'] ?>" />
					<script type="text/javascript">
					<!--
					$('textarea[name="attribute[<?php echo $attribute_row; ?>][attribute_tooltip]"]').dblclick(function()
					{
						$(this).ckeditor
						({
							width: "325px", height: "75px", autoParagraph: false,
							resize_enabled: true, toolbarStartupExpanded: false,
							filebrowserBrowseUrl:      'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserUploadUrl:      'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
							filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
						});
					});
					//-->
					</script>
				</td>
			</tr>
			<?php }?>
			<tr>
				<td><?php echo $text_instock; ?></td>
				<td><input id="instock_checked" type="checkbox" name="filterpro_setting[instock_checked]" <?php if (isset($setting['instock_checked'])) echo ' checked="checked"'; ?>>&nbsp;<label for="instock_checked"><?php echo $text_checked; ?></label></td>
				<td colspan="4"><input id="instock_visible" type="checkbox" name="filterpro_setting[instock_visible]" <?php if (isset($setting['instock_visible'])) echo ' checked="checked"'; ?>>&nbsp;<label for="instock_visible"><?php echo $text_visible; ?></label></td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: right;"><span class="help"><?php echo $text_tooltip_help; ?></span></td>
			</tr>
		</table>
	</td>
	<td>
		<table class="list" style="float: left">
		<tr>
			<td><?php echo $text_option_mode?></td>
			<td><input id="option_mode_or" type="radio" name="filterpro_setting[option_mode]"
					   value="or" <?php if(!isset($setting['option_mode']) || $setting['option_mode'] == 'or') echo " checked='checked'"?>>
				<label for="option_mode_or"><?php echo $text_mode_or?></label>
				<input id="option_mode_and" type="radio" name="filterpro_setting[option_mode]"
					   value="and" <?php if(isset($setting['option_mode']) && $setting['option_mode'] == 'and') echo " checked='checked'"?>>
				<label for="option_mode_and"><?php echo $text_mode_and?></label>
			</td>
		</tr>
		<tr>
			<td><?php echo $text_attribute_mode?></td>
			<td><input id="attribute_mode_or" type="radio" name="filterpro_setting[attribute_mode]"
					   value="or" <?php if(!isset($setting['attribute_mode']) || $setting['attribute_mode'] == 'or') echo " checked='checked'"?>>
				<label for="attribute_mode_or"><?php echo $text_mode_or?></label>
				<input id="attribute_mode_and" type="radio" name="filterpro_setting[attribute_mode]"
					   value="and" <?php if(isset($setting['attribute_mode']) && $setting['attribute_mode'] == 'and') echo " checked='checked'"?>>
				<label for="attribute_mode_and"><?php echo $text_mode_and?></label>
			</td>
		</tr>
		</table>
</td>
</tr>

</table>
<?php echo $text_filter_position; ?>
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_position; ?></td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php
        $module_row = 0;
        ?>
        <?php foreach ($modules as $module) { ?>
		  <?php if($module['type'] !=0){continue;}?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left"><input type="hidden" name="filterpro_module[<?php echo $module_row; ?>][type]" value="0">
				<select name="filterpro_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><select name="filterpro_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="filterpro_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="filterpro_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
		  <tfoot>
			<tr>
			  <td colspan="4"></td>
			  <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
			</tr>
		  </tfoot>
	  </table>
</div>
	<div id="tab-data">
		<style type="text/css">
			#seo_filter input{
				width: 100%;
			}
			#seo_filter textarea{
				width: 100%;
			}
		</style>
		      			<table id="seo_filter" class="list">
				<thead>
				<tr>
					<td class="left"><?php echo $entry_filter_seo; ?></td>
					<td class="left"><?php echo $entry_filter_url; ?></td>
					<td class="left"><?php echo $entry_filter_h1; ?></td>
					<td class="left"><?php echo $entry_filter_title; ?></td>
					<td class="left"><?php echo $entry_filter_meta_description; ?></td>
					<td class="left"><?php echo $entry_filter_meta_keywords; ?></td>
					<td class="right"><?php echo $entry_filter_description; ?></td>
					<td class="right" style="width: 150px;"></td>
				</tr>
				</thead>
				<?php
		$seo_filter_row = 0;
				?>
				<?php foreach($filterpro_seo as $seo_filter) { ?>
				<tbody id="seo_filter-row<?php echo $seo_filter_row; ?>">
				<tr>
					<td class="left"><input type="text" name="filterpro_seo[<?php echo $seo_filter_row; ?>][seo]" value="<?php echo $seo_filter['seo']; ?>"/></td>
					<td class="left"><input type="text" name="filterpro_seo[<?php echo $seo_filter_row; ?>][url]" value="<?php echo $seo_filter['url']; ?>"/></td>
					<td class="left">
						<?php foreach($languages as $language) { ?>
						<input type="text" name="filterpro_seo[<?php echo $seo_filter_row; ?>][lang][<?php echo $language['language_id']; ?>][h1]"
							   value="<?php echo $seo_filter['lang'][$language['language_id']]['h1']; ?>"/>
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach($languages as $language) { ?>
						<input type="text" name="filterpro_seo[<?php echo $seo_filter_row; ?>][lang][<?php echo $language['language_id']; ?>][title]"
							   value="<?php echo $seo_filter['lang'][$language['language_id']]['title']; ?>"/>
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach($languages as $language) { ?>
						<input type="text" name="filterpro_seo[<?php echo $seo_filter_row; ?>][lang][<?php echo $language['language_id']; ?>][meta_description]"
							   value="<?php echo $seo_filter['lang'][$language['language_id']]['meta_description']; ?>"/>
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach($languages as $language) { ?>
						<input type="text" name="filterpro_seo[<?php echo $seo_filter_row; ?>][lang][<?php echo $language['language_id']; ?>][meta_keywords]"
							   value="<?php echo $seo_filter['lang'][$language['language_id']]['meta_keywords']; ?>"/>
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>
						<?php } ?>
					</td>
					<td class="left">
						<?php foreach($languages as $language) { ?>
						<textarea class="seo-description" rows="10" cols="10" name="filterpro_seo[<?php echo $seo_filter_row; ?>][lang][<?php echo $language['language_id']; ?>][description]"
								  value=""><?php echo $seo_filter['lang'][$language['language_id']]['description']; ?></textarea>
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>
						<?php } ?>
					</td>
					<td class="right"><a onclick="$('#seo_filter-row<?php echo $seo_filter_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
				</tr>
				</tbody>
				<?php $seo_filter_row++; ?>
				<?php } ?>
				<tfoot>
				<tr>
					<td colspan="7"></td>
					<td class="left"><a onclick="addseo_filter();" class="button"><span><?php echo $button_add_seo_filter; ?></span></a></td>
				</tr>
				</tfoot>
			</table>
	</div>
<div id="tab-additional">
	<span style="color:#888"><?php echo $text_additional_info; ?></span><br/><br/>
	<table class="list" style="width: 600px;">
		<tr>
			<td><?php echo $text_attr_delimeter?></td>
			<td><input id="attr_delimeter" type="text" name="filterpro_setting[attr_delimeter]" size="3"
					   value="<?php if(isset($setting['attr_delimeter'])) echo $setting['attr_delimeter']; else echo ':'; ?>">
			</td>
		</tr>
		<tr>
			<td><?php echo $text_tax?></td>
			<td><input id="tax" type="text" name="filterpro_setting[tax]" size="3"
					   value="<?php if(isset($setting['tax'])) echo $setting['tax']; else echo '0'; ?>">
			</td>
		</tr>

		<tr>
			<td><?php echo $text_attribute_value_mode?></td>
			<td><input id="attribute_value_mode_or" type="radio" name="filterpro_setting[attribute_value_mode]"
					   value="or" <?php if(!isset($setting['attribute_value_mode']) || $setting['attribute_value_mode'] == 'or') echo " checked='checked'"?>>
				<label for="attribute_value_mode_or"><?php echo $text_mode_or?></label>
				<input id="attribute_value_mode_and" type="radio" name="filterpro_setting[attribute_value_mode]"
					   value="and" <?php if(isset($setting['attribute_value_mode']) && $setting['attribute_value_mode'] == 'and') echo " checked='checked'"?>>
				<label for="attribute_value_mode_and"><?php echo $text_mode_and?></label>
			</td>
		</tr>
		<tr>
			<td><?php echo $text_attr_group?></td>
			<td><input id="text_attr_group_off" type="radio" name="filterpro_setting[attr_group]"
					   value="0" <?php if(!isset($setting['attr_group']) || $setting['attr_group'] == '0') echo " checked='checked'"?>>
				<label for="text_attr_group_off"><?php echo $text_no?></label>
				<input id="text_attr_group_on" type="radio" name="filterpro_setting[attr_group]"
					   value="1" <?php if(isset($setting['attr_group']) && $setting['attr_group'] == '1') echo " checked='checked'"?>>
				<label for="text_attr_group_on"><?php echo $text_yes?></label>
			</td>
		</tr>
		<tr>
			<td><label for="theme_mega"><?php echo $text_theme_mega?></label></td>
			<td><input type="checkbox" name="filterpro_setting[theme_mega]" id="theme_mega" <?php if(isset($setting['theme_mega'])) echo " checked='checked'"; ?>>
			</td>
		</tr>
		<tr>
			<td><label for="jscrollpane"><?php echo $text_jscrollpane?></label></td>
			<td><input type="checkbox" name="filterpro_setting[jscrollpane]" id="jscrollpane" <?php if(isset($setting['jscrollpane'])) echo " checked='checked'"; ?>>
			</td>
		</tr>
		<tr>
			<td><label for="subcategories"><?php echo $text_subcategories?></label></td>
			<td><input type="checkbox" name="filterpro_setting[subcategories]" id="subcategories" <?php if(isset($setting['subcategories'])) echo " checked='checked'"; ?>>
			</td>
		</tr>
		 <tr>
			<td><label for="attr_total_simple"><?php echo $text_attr_total_simple?></label></td>
			<td><input type="checkbox" name="filterpro_setting[attr_total_simple]" id="attr_total_simple" <?php if(isset($setting['attr_total_simple'])) echo " checked='checked'"; ?>>
			</td>
		</tr>
		<tr>
		   <td><label for="option_total_simple"><?php echo $text_option_total_simple?></label></td>
		   <td><input type="checkbox" name="filterpro_setting[option_total_simple]" id="option_total_simple" <?php if(isset($setting['option_total_simple'])) echo " checked='checked'"; ?>>
		   </td>
	   </tr>
		<tr>
		   <td><label for="option_main_checkbox"><?php echo $text_option_main_checkbox?></label></td>
		   <td><input type="checkbox" name="filterpro_setting[option_main_checkbox]" id="option_main_checkbox" <?php if(isset($setting['option_main_checkbox'])) echo " checked='checked'"; ?>>
		   </td>
	   </tr>
		<tr>
			<td><?php echo $text_filterpro_container?></td>
			<td>
				<input type="text" name="filterpro_setting[filterpro_container]" value="<?php echo $setting['filterpro_container']?>">
			</td>
		</tr>
		<tr>
			<td><?php echo $text_filterpro_name?></td>
			<td>
				<input type="text" name="filterpro_setting[filterpro_name]" value="<?php echo isset($setting['filterpro_name']) ? $setting['filterpro_name'] : "FilterPro";?>">
			</td>
		</tr>
		<tr>
			<td><?php echo $text_filterpro_afterload?></td>
			<td>
				<textarea type="text" name="filterpro_setting[filterpro_afterload]" style="height: 350px; width: 300px;"><?php echo $setting['filterpro_afterload']?></textarea>
			</td>
		</tr>
	</table>
	<?php echo $text_product_container?>

	<table id="module_container" class="list">
			<thead>
			  <tr>
				<td class="left"><?php echo $entry_layout; ?></td>
				<td class="left"><?php echo $entry_status; ?></td>
				<td class="right"><?php echo $entry_sort_order; ?></td>
				<td></td>
			  </tr>
			</thead>

			<?php foreach ($modules as $module) { ?>
					  <?php if($module['type'] !=1){continue;}?>
			<tbody id="modulec-row<?php echo $module_row; ?>">
			  <tr>
				<td class="left">
					<input type="hidden" name="filterpro_module[<?php echo $module_row; ?>][type]" value="1">
					<input type="hidden" name="filterpro_module[<?php echo $module_row; ?>][position]" value="content_top">
					<select name="filterpro_module[<?php echo $module_row; ?>][layout_id]">
					<?php foreach ($container_layouts as $layout) { ?>
					<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
					<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>
				<td class="left"><select name="filterpro_module[<?php echo $module_row; ?>][status]">
					<?php if ($module['status']) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select></td>
				<td class="right"><input type="text" name="filterpro_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
				<td class="left"><a onclick="$('#modulec-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
			  </tr>
			</tbody>
			<?php $module_row++; ?>
			<?php } ?>
			  <tfoot>
				<tr>
				  <td colspan="3"></td>
				  <td class="left"><a onclick="addModuleContainer();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
				</tr>
			  </tfoot>
		  </table>

</div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--

$('textarea.seo-description').live('dblclick', function() {
	$(this).ckeditor({
		width: "325px", height: "75px", autoParagraph: false,
		resize_enabled: true, toolbarStartupExpanded: false,
		filebrowserBrowseUrl:      'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl:      'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});
});


var module_row = <?php echo $module_row; ?>;

function addModule() {
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="hidden" name="filterpro_module[' + module_row + '][type]" value="0">'+
		'<select name="filterpro_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="filterpro_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="filterpro_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="filterpro_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#module tfoot').before(html);

	module_row++;
}

function addModuleContainer() {
	html  = '<tbody id="modulec-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="hidden" name="filterpro_module[' + module_row + '][type]" value="1">'+
			'<input type="hidden" name="filterpro_module[' + module_row + '][position]" value="content_top">'+
			'<select name="filterpro_module[' + module_row + '][layout_id]">';
	<?php foreach ($container_layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="filterpro_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="filterpro_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#modulec-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#module_container tfoot').before(html);

	module_row++;
}

function expand_all(checked){
	$('#settings input.expanded:checkbox').attr('checked', checked);
}

function apply(){
	$(".success").remove();
	$.post($("#form").attr('action'), $("#form").serialize(), function(html) {
		var $success = $(html).find(".success");
		if ($success.length > 0) {
			$(".breadcrumb").after($success);
		}
   });
}

var seo_filter_row = <?php echo $seo_filter_row; ?>;

function addseo_filter() {
	html = '<tbody id="seo_filter-row' + seo_filter_row + '">';
	html += '  <tr>';

	html += '    <td class="left"><input type="text" name="filterpro_seo[' + seo_filter_row + '][seo]" value="" /></td>';
	html += '    <td class="left"><input type="text" name="filterpro_seo[' + seo_filter_row + '][url]" value="" /></td>';
	html += '    <td class="left">';
<?php foreach($languages as $language) { ?>
	html +=         '<input type="text" name="filterpro_seo[' + seo_filter_row + '][lang][<?php echo $language['language_id']; ?>][h1]" value="" />';
	html +=         '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>';
<?php } ?>
	html +=     '</td>';
	html += '    <td class="left">';
<?php foreach($languages as $language) { ?>
	html +=         '<input type="text" name="filterpro_seo[' + seo_filter_row + '][lang][<?php echo $language['language_id']; ?>][title]" value="" />';
	html +=         '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>';
<?php } ?>
	html +=     '</td>';
	html += '    <td class="left">';
<?php foreach($languages as $language) { ?>
	html +=         '<input type="text" name="filterpro_seo[' + seo_filter_row + '][lang][<?php echo $language['language_id']; ?>][meta_description]" value="" />';
	html +=         '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>';
<?php } ?>
	html +=     '</td>';
	html += '    <td class="left">';
<?php foreach($languages as $language) { ?>
	html +=         '<input type="text" name="filterpro_seo[' + seo_filter_row + '][lang][<?php echo $language['language_id']; ?>][meta_keywords]" value="" />';
	html +=         '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>';
<?php } ?>
	html +=     '</td>';
	html += '    <td class="left">';
<?php foreach($languages as $language) { ?>
	html +=         '<textarea  class="seo-description" rows="10" cols="10" name="filterpro_seo[' + seo_filter_row + '][lang][<?php echo $language['language_id']; ?>][description]" value="" ></textarea>';
	html +=         '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/><br/>';
<?php } ?>
	html +=     '</td>';
	html += '    <td class="right"><a onclick="$(\'#seo_filter-row' + seo_filter_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#seo_filter tfoot').before(html);

	seo_filter_row++;
}

//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>

copyright &copy; <a href="mailto:ruslan.shv@gmail.com?Subject=Filterpro%20support">Ruslan Shvarev</a>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="view/javascript/ckeditor/adapters/jquery.js"></script>
<?php echo $footer; ?>
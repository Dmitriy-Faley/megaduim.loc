<?php
class ModelExtensionModuleMtExportImport extends Model
{
	// Запись настроек в базу данных
	public function SaveSettings($profile_key, $profile_import_key)
	{
		$this->load->model('setting/setting');
		if (isset($this->request->post['module_mt_export_import_category'])) {
			if (isset($this->request->post['module_mt_export_import_all_category']) && (isset($this->request->post['module_mt_export_import_all_category']) ? $this->request->post['module_mt_export_import_all_category'] : 0) == 'on') {
				$this->request->post['module_mt_export_import_category'] = 'all';
			}
			else {
				$this->request->post['module_mt_export_import_category'] = json_encode($this->request->post['module_mt_export_import_category']);
			}
		}
		else {
			$this->request->post['module_mt_export_import_category'] = 'all';
		}
		if (isset($this->request->post['module_mt_export_import_manufacturer'])) {
			$this->request->post['module_mt_export_import_manufacturer'] = json_encode($this->request->post['module_mt_export_import_manufacturer']);
		}
		if (isset($this->request->post['module_mt_export_import_filter'])) {
			$this->request->post['module_mt_export_import_filter'] = json_encode($this->request->post['module_mt_export_import_filter']);
		}
		if (isset($this->request->post['module_mt_export_import_attribute'])) {
			$this->request->post['module_mt_export_import_attribute'] = json_encode($this->request->post['module_mt_export_import_attribute']);
		}
		if (isset($this->request->post['module_mt_export_import_option'])) {
			$this->request->post['module_mt_export_import_option'] = json_encode($this->request->post['module_mt_export_import_option']);
		}
		if (isset($this->request->post['module_mt_export_import_language'])) {
			$this->request->post['module_mt_export_import_language'] = json_encode($this->request->post['module_mt_export_import_language']);
		}
		if (isset($this->request->post['module_mt_export_import_import_title'])) {
			$this->request->post['module_mt_export_import_import_title'] = json_encode($this->request->post['module_mt_export_import_import_title']);
		}
		if (isset($this->request->post['module_mt_export_import_import_db'])) {
			$this->request->post['module_mt_export_import_import_db'] = json_encode($this->request->post['module_mt_export_import_import_db']);
		}
		if (isset($this->request->post['module_mt_export_import_import_title_disable'])) {
			$this->request->post['module_mt_export_import_import_title_disable'] = json_encode($this->request->post['module_mt_export_import_import_title_disable']);
		}
		if (isset($this->request->post['module_mt_export_import_import_attribute'])) {
			$this->request->post['module_mt_export_import_import_attribute'] = json_encode($this->request->post['module_mt_export_import_import_attribute']);
		}
		$status = $this->request->post['module_mt_export_import_status'];
		$key = 1; // profile key

		if (!isset($this->request->post['remove_profile']) && !isset($this->request->post['remove_profile_import'])) {
			//export
			if (isset($this->request->post['create_profile']) && isset($this->request->post['profile_name']) && $this->request->post['profile_name'] != '') {
				$profiles = $this->LoadProfiles();
				if (!$profiles) $profiles = [];
				if (is_array($profiles)) {
					foreach ($profiles as $index => $p) {
						if ($index != $profile_key) {
							$this->request->post['module_mt_export_import_profile' . $index] = $this->config->get('module_mt_export_import_profile' . $index);
						}
					}
					$key = 0;
					$keys = array_keys($profiles);
					if ($keys) {
						$key = max($keys);
					}
					$key = $key + 1;
					if (isset($this->request->post['profile_name'])) $profiles[$key] = $this->request->post['profile_name'];
					else $profiles[$key] = "Профиль: $key";
					$import_settings = array(
						'module_mt_export_import_file_name' => (isset($this->request->post['module_mt_export_import_file_name']) ? $this->request->post['module_mt_export_import_file_name'] : ''),
						'module_mt_export_import_category' => (isset($this->request->post['module_mt_export_import_category']) ? $this->request->post['module_mt_export_import_category'] : ''),
						'module_mt_export_import_export_categories' => (isset($this->request->post['module_mt_export_import_export_categories']) ? $this->request->post['module_mt_export_import_export_categories'] : ''),
						'module_mt_export_import_all_category' => (isset($this->request->post['module_mt_export_import_all_category']) ? $this->request->post['module_mt_export_import_all_category'] : ''),
						'module_mt_export_import_export_attributes' => (isset($this->request->post['module_mt_export_import_export_attributes']) ? $this->request->post['module_mt_export_import_export_attributes'] : ''),
						'module_mt_export_import_export_options' => (isset($this->request->post['module_mt_export_import_export_options']) ? $this->request->post['module_mt_export_import_export_options'] : ''),
						'module_mt_export_import_export_special' => (isset($this->request->post['module_mt_export_import_export_special']) ? $this->request->post['module_mt_export_import_export_special'] : ''),
						'module_mt_export_import_export_discount' => (isset($this->request->post['module_mt_export_import_export_discount']) ? $this->request->post['module_mt_export_import_export_discount'] : ''),
						'module_mt_export_import_export_seo' => (isset($this->request->post['module_mt_export_import_export_seo']) ? $this->request->post['module_mt_export_import_export_seo'] : ''),
						'module_mt_export_import_export_meta_tags' => (isset($this->request->post['module_mt_export_import_export_meta_tags']) ? $this->request->post['module_mt_export_import_export_meta_tags'] : ''),
						'module_mt_export_import_export_description_html' => (isset($this->request->post['module_mt_export_import_export_description_html']) ? $this->request->post['module_mt_export_import_export_description_html'] : ''),
						'module_mt_export_import_child_category' => (isset($this->request->post['module_mt_export_import_child_category']) ? $this->request->post['module_mt_export_import_child_category'] : ''),
						'module_mt_export_import_manufacturer_name' => (isset($this->request->post['module_mt_export_import_manufacturer_name']) ? $this->request->post['module_mt_export_import_manufacturer_name'] : ''),
						'module_mt_export_import_id_name' => (isset($this->request->post['module_mt_export_import_id_name']) ? $this->request->post['module_mt_export_import_id_name'] : ''),
						'module_mt_export_import_only_main_category' => (isset($this->request->post['module_mt_export_import_only_main_category']) ? $this->request->post['module_mt_export_import_only_main_category'] : ''),
						'module_mt_export_import_type_file' => (isset($this->request->post['module_mt_export_import_type_file']) ? $this->request->post['module_mt_export_import_type_file'] : ''),
						'module_mt_export_import_filter_enable' => (isset($this->request->post['module_mt_export_import_filter_enable']) ? $this->request->post['module_mt_export_import_filter_enable'] : ''),
						'module_mt_export_import_manufacturer' => (isset($this->request->post['module_mt_export_import_manufacturer']) ? $this->request->post['module_mt_export_import_manufacturer'] : ''),
						'module_mt_export_import_filter' => (isset($this->request->post['module_mt_export_import_filter']) ? $this->request->post['module_mt_export_import_filter'] : ''),
						'module_mt_export_import_attribute' => (isset($this->request->post['module_mt_export_import_attribute']) ? $this->request->post['module_mt_export_import_attribute'] : ''),
						'module_mt_export_import_option' => (isset($this->request->post['module_mt_export_import_option']) ? $this->request->post['module_mt_export_import_option'] : ''),
						'module_mt_export_import_language' => (isset($this->request->post['module_mt_export_import_language']) ? $this->request->post['module_mt_export_import_language'] : ''),
						'module_mt_export_import_price_start' => (isset($this->request->post['module_mt_export_import_price_start']) ? $this->request->post['module_mt_export_import_price_start'] : ''),
						'module_mt_export_import_special' => (isset($this->request->post['module_mt_export_import_special']) ? $this->request->post['module_mt_export_import_special'] : ''),
						'module_mt_export_import_export_params' => (isset($this->request->post['module_mt_export_import_export_params']) ? $this->request->post['module_mt_export_import_export_params'] : ''),
						'module_mt_export_import_columns' => (isset($this->request->post['module_mt_export_import_columns']) ? $this->request->post['module_mt_export_import_columns'] : ''),
						'module_mt_export_import_column_name' => (isset($this->request->post['module_mt_export_import_column_name']) ? $this->request->post['module_mt_export_import_column_name'] : ''),
					);
					$this->request->post['module_mt_export_import_profile' . $key] = $import_settings;
					foreach ($profiles as $index => $p) {
						if ($index != $key) {
							$this->request->post['module_mt_export_import_profile' . $index] = $this->config->get('module_mt_export_import_profile' . $index);
						}
					}
					$profile_key = $key;
					$this->request->post['module_mt_export_import_profiles'] = $profiles;
				}
			} else {
				$profiles = $this->LoadProfiles();
				if (!is_array($profiles)) $profiles = [];

				$key = 0;
				$title = "Профиль: 1";
				if (isset($this->request->post['module_mt_export_import_profile']) && isset($profiles[$this->request->post['module_mt_export_import_profile']])) {
					$key = $this->request->post['module_mt_export_import_profile'];
				} else if (is_array($profiles) && count($profiles) == 0) {
					if (in_array($title, $profiles)) {
						$key = array_search($title, $profiles);
					} else {
						$keys = array_keys($profiles);
						if ($keys) {
							$key = max($keys);
						}
						$key = $key + 1;
						$profiles[$key] = $title;
					}
				}
				if ($key != -1) {
					$import_settings = array(
						'module_mt_export_import_file_name' => (isset($this->request->post['module_mt_export_import_file_name']) ? $this->request->post['module_mt_export_import_file_name'] : ''),
						'module_mt_export_import_category' => (isset($this->request->post['module_mt_export_import_category']) ? $this->request->post['module_mt_export_import_category'] : ''),
						'module_mt_export_import_export_categories' => (isset($this->request->post['module_mt_export_import_export_categories']) ? $this->request->post['module_mt_export_import_export_categories'] : ''),
						'module_mt_export_import_all_category' => (isset($this->request->post['module_mt_export_import_all_category']) ? $this->request->post['module_mt_export_import_all_category'] : ''),
						'module_mt_export_import_export_attributes' => (isset($this->request->post['module_mt_export_import_export_attributes']) ? $this->request->post['module_mt_export_import_export_attributes'] : ''),
						'module_mt_export_import_export_options' => (isset($this->request->post['module_mt_export_import_export_options']) ? $this->request->post['module_mt_export_import_export_options'] : ''),
						'module_mt_export_import_export_special' => (isset($this->request->post['module_mt_export_import_export_special']) ? $this->request->post['module_mt_export_import_export_special'] : ''),
						'module_mt_export_import_export_discount' => (isset($this->request->post['module_mt_export_import_export_discount']) ? $this->request->post['module_mt_export_import_export_discount'] : ''),
						'module_mt_export_import_export_seo' => (isset($this->request->post['module_mt_export_import_export_seo']) ? $this->request->post['module_mt_export_import_export_seo'] : ''),
						'module_mt_export_import_export_meta_tags' => (isset($this->request->post['module_mt_export_import_export_meta_tags']) ? $this->request->post['module_mt_export_import_export_meta_tags'] : ''),
						'module_mt_export_import_export_description_html' => (isset($this->request->post['module_mt_export_import_export_description_html']) ? $this->request->post['module_mt_export_import_export_description_html'] : ''),
						'module_mt_export_import_child_category' => (isset($this->request->post['module_mt_export_import_child_category']) ? $this->request->post['module_mt_export_import_child_category'] : ''),
						'module_mt_export_import_manufacturer_name' => (isset($this->request->post['module_mt_export_import_manufacturer_name']) ? $this->request->post['module_mt_export_import_manufacturer_name'] : ''),
						'module_mt_export_import_id_name' => (isset($this->request->post['module_mt_export_import_id_name']) ? $this->request->post['module_mt_export_import_id_name'] : ''),
						'module_mt_export_import_only_main_category' => (isset($this->request->post['module_mt_export_import_only_main_category']) ? $this->request->post['module_mt_export_import_only_main_category'] : ''),
						'module_mt_export_import_type_file' => (isset($this->request->post['module_mt_export_import_type_file']) ? $this->request->post['module_mt_export_import_type_file'] : ''),
						'module_mt_export_import_filter_enable' => (isset($this->request->post['module_mt_export_import_filter_enable']) ? $this->request->post['module_mt_export_import_filter_enable'] : ''),
						'module_mt_export_import_manufacturer' => (isset($this->request->post['module_mt_export_import_manufacturer']) ? $this->request->post['module_mt_export_import_manufacturer'] : ''),
						'module_mt_export_import_filter' => (isset($this->request->post['module_mt_export_import_filter']) ? $this->request->post['module_mt_export_import_filter'] : ''),
						'module_mt_export_import_attribute' => (isset($this->request->post['module_mt_export_import_attribute']) ? $this->request->post['module_mt_export_import_attribute'] : ''),
						'module_mt_export_import_option' => (isset($this->request->post['module_mt_export_import_option']) ? $this->request->post['module_mt_export_import_option'] : ''),
						'module_mt_export_import_language' => (isset($this->request->post['module_mt_export_import_language']) ? $this->request->post['module_mt_export_import_language'] : ''),
						'module_mt_export_import_price_start' => (isset($this->request->post['module_mt_export_import_price_start']) ? $this->request->post['module_mt_export_import_price_start'] : ''),
						'module_mt_export_import_special' => (isset($this->request->post['module_mt_export_import_special']) ? $this->request->post['module_mt_export_import_special'] : ''),
						'module_mt_export_import_export_params' => (isset($this->request->post['module_mt_export_import_export_params']) ? $this->request->post['module_mt_export_import_export_params'] : ''),
						'module_mt_export_import_columns' => (isset($this->request->post['module_mt_export_import_columns']) ? $this->request->post['module_mt_export_import_columns'] : ''),
						'module_mt_export_import_column_name' => (isset($this->request->post['module_mt_export_import_column_name']) ? $this->request->post['module_mt_export_import_column_name'] : ''),
					);
					$this->request->post['module_mt_export_import_profile' . $key] = $import_settings;
					// $this->request->post['module_mt_export_import_profile' . $key] = $this->request->post;
					$profile_key = $key;
				}
				$this->request->post['module_mt_export_import_profiles'] = $profiles;
				foreach ($profiles as $index => $p) {
					if ($index != $key) {
						$this->request->post['module_mt_export_import_profile' . $index] = $this->config->get('module_mt_export_import_profile' . $index);
					}
				}
			}
			//import
			if (isset($this->request->post['create_import_profile']) && isset($this->request->post['profile_name_import']) && $this->request->post['profile_name_import'] != '') {
				$profiles = $this->LoadProfilesImport();
				if (!$profiles) $profiles = [];
				if (is_array($profiles)) {
					foreach ($profiles as $index => $p) {
						if ($index != $profile_import_key) {
							$this->request->post['module_mt_export_import_profile_import' . $index] = $this->config->get('module_mt_export_import_profile_import' . $index);
						}
					}
					$key = 0;
					$keys = array_keys($profiles);
					if ($keys) {
						$key = max($keys);
					}
					$key = $key + 1;
					if (isset($this->request->post['profile_name_import'])) $profiles[$key] = $this->request->post['profile_name_import'];
					else $profiles[$key] = "Профиль: $key";
					$import_settings = array(
						'module_mt_export_import_export_replace_search_column' => (isset($this->request->post['module_mt_export_import_export_replace_search_column']) ? $this->request->post['module_mt_export_import_export_replace_search_column'] : ''),
						'module_mt_export_import_export_search_column' => (isset($this->request->post['module_mt_export_import_export_search_column']) ? $this->request->post['module_mt_export_import_export_search_column'] : ''),
						'module_mt_export_import_import_params' => (isset($this->request->post['module_mt_export_import_import_params']) ? $this->request->post['module_mt_export_import_import_params'] : ''),
						'module_mt_export_import_export_replace' => (isset($this->request->post['module_mt_export_import_export_replace']) ? $this->request->post['module_mt_export_import_export_replace'] : ''),
						'module_mt_export_import_export_create_new' => (isset($this->request->post['module_mt_export_import_export_create_new']) ? $this->request->post['module_mt_export_import_export_create_new'] : ''),
						'module_mt_export_import_export_replace_delimiter' => (isset($this->request->post['module_mt_export_import_export_replace_delimiter']) ? $this->request->post['module_mt_export_import_export_replace_delimiter'] : ''),
						'module_mt_export_import_export_delimiter' => (isset($this->request->post['module_mt_export_import_export_delimiter']) ? $this->request->post['module_mt_export_import_export_delimiter'] : ''),
						'module_mt_export_import_export_replace_delimiter_attribute' => (isset($this->request->post['module_mt_export_import_export_replace_delimiter_attribute']) ? $this->request->post['module_mt_export_import_export_replace_delimiter_attribute'] : ''),
						'module_mt_export_import_export_delimiter_attribute' => (isset($this->request->post['module_mt_export_import_export_delimiter_attribute']) ? $this->request->post['module_mt_export_import_export_delimiter_attribute'] : ''),
						'module_mt_export_import_import_db' => (isset($this->request->post['module_mt_export_import_import_db']) ? $this->request->post['module_mt_export_import_import_db'] : ''),
						'module_mt_export_import_import_title' => (isset($this->request->post['module_mt_export_import_import_title']) ? $this->request->post['module_mt_export_import_import_title'] : ''),
						'module_mt_export_import_import_title_disable' => (isset($this->request->post['module_mt_export_import_import_title_disable']) ? $this->request->post['module_mt_export_import_import_title_disable'] : ''),
						'module_mt_export_import_import_attribute' => (isset($this->request->post['module_mt_export_import_import_attribute']) ? $this->request->post['module_mt_export_import_import_attribute'] : ''),
						
					);
					$this->request->post['module_mt_export_import_profile_import' . $key] = $import_settings;
					foreach ($profiles as $index => $p) {
						if ($index != $key) {
							$this->request->post['module_mt_export_import_profile_import' . $index] = $this->config->get('module_mt_export_import_profile_import' . $index);
						}
					}
					$profile_import_key = $key;
					$this->request->post['module_mt_export_import_profiles_import'] = $profiles;
				}
			} else {
				$profiles = $this->LoadProfilesImport();
				if (!is_array($profiles)) $profiles = [];

				$key = 0;
				$title = "Профиль: 1";
				if (isset($this->request->post['module_mt_export_import_profile_import']) && isset($profiles[$this->request->post['module_mt_export_import_profile_import']])) {
					$key = $this->request->post['module_mt_export_import_profile_import'];
				} else if (is_array($profiles) && count($profiles) == 0) {
					if (in_array($title, $profiles)) {
						$key = array_search($title, $profiles);
					} else {
						$keys = array_keys($profiles);
						if ($keys) {
							$key = max($keys);
						}
						$key = $key + 1;
						$profiles[$key] = $title;
					}
				}
				if ($key != -1) {
					$import_settings = array(
						'module_mt_export_import_export_replace_search_column' => (isset($this->request->post['module_mt_export_import_export_replace_search_column']) ? $this->request->post['module_mt_export_import_export_replace_search_column'] : ''),
						'module_mt_export_import_export_search_column' => (isset($this->request->post['module_mt_export_import_export_search_column']) ? $this->request->post['module_mt_export_import_export_search_column'] : ''),
						'module_mt_export_import_import_params' => (isset($this->request->post['module_mt_export_import_import_params']) ? $this->request->post['module_mt_export_import_import_params'] : ''),
						'module_mt_export_import_export_replace' => (isset($this->request->post['module_mt_export_import_export_replace']) ? $this->request->post['module_mt_export_import_export_replace'] : ''),
						'module_mt_export_import_export_create_new' => (isset($this->request->post['module_mt_export_import_export_create_new']) ? $this->request->post['module_mt_export_import_export_create_new'] : ''),
						'module_mt_export_import_export_replace_delimiter' => (isset($this->request->post['module_mt_export_import_export_replace_delimiter']) ? $this->request->post['module_mt_export_import_export_replace_delimiter'] : ''),
						'module_mt_export_import_export_delimiter' => (isset($this->request->post['module_mt_export_import_export_delimiter']) ? $this->request->post['module_mt_export_import_export_delimiter'] : ''),
						'module_mt_export_import_export_replace_delimiter_attribute' => (isset($this->request->post['module_mt_export_import_export_replace_delimiter_attribute']) ? $this->request->post['module_mt_export_import_export_replace_delimiter_attribute'] : ''),
						'module_mt_export_import_export_delimiter_attribute' => (isset($this->request->post['module_mt_export_import_export_delimiter_attribute']) ? $this->request->post['module_mt_export_import_export_delimiter_attribute'] : ''),
						'module_mt_export_import_import_db' => (isset($this->request->post['module_mt_export_import_import_db']) ? $this->request->post['module_mt_export_import_import_db'] : ''),
						'module_mt_export_import_import_title' => (isset($this->request->post['module_mt_export_import_import_title']) ? $this->request->post['module_mt_export_import_import_title'] : ''),
						'module_mt_export_import_import_title_disable' => (isset($this->request->post['module_mt_export_import_import_title_disable']) ? $this->request->post['module_mt_export_import_import_title_disable'] : ''),
						'module_mt_export_import_import_attribute' => (isset($this->request->post['module_mt_export_import_import_attribute']) ? $this->request->post['module_mt_export_import_import_attribute'] : ''),
					);
					$this->request->post['module_mt_export_import_profile_import' . $key] = $import_settings;
					$profile_import_key = $key;
				}
				$this->request->post['module_mt_export_import_profiles_import'] = $profiles;
				foreach ($profiles as $index => $p) {
					if ($index != $key) {
						$this->request->post['module_mt_export_import_profile_import' . $index] = $this->config->get('module_mt_export_import_profile_import' . $index);
					}
				}
			}
		} else if (isset($this->request->post['remove_profile'])) {
			$profiles = $this->LoadProfiles();
			// $this->request->post['module_mt_export_import_profiles'] = $profiles;
			unset($profiles[$profile_import_key]);
			$keys = array_keys($profiles);
			if (isset($keys[0])) {
				$key = $keys[0];
			} else {
				$key = 0;
				$title = "Профиль: 1";
				$keys = array_keys($profiles);
				if ($keys) {
					$key = max($keys);
				}
				$key = $key + 1;
				$profiles[$key] = $title;
				if ($key != -1) {
					$profile_import_key = $key;
					// $this->request->post['module_mt_export_import_profile'.$key] = $this->request->post;
				}
			}
			$this->request->post['module_mt_export_import_profiles'] = $profiles;
			foreach ($profiles as $index => $p) {
				$this->request->post['module_mt_export_import_profiles' . $index] = $this->config->get('module_mt_export_import_profiles' . $index);
			}
		} else if (isset($this->request->post['remove_profile_import'])) {
			$profiles = $this->LoadProfilesImport();
			// $this->request->post['module_mt_export_import_profiles'] = $profiles;
			unset($profiles[$profile_import_key]);
			$keys = array_keys($profiles);
			if (isset($keys[0])) {
				$key = $keys[0];
			} else {
				$key = 0;
				$title = "Профиль: 1";
				$keys = array_keys($profiles);
				if ($keys) {
					$key = max($keys);
				}
				$key = $key + 1;
				$profiles[$key] = $title;
				if ($key != -1) {
					$profile_import_key = $key;
					// $this->request->post['module_mt_export_import_profile'.$key] = $this->request->post;
				}
			}
			$this->request->post['module_mt_export_import_profiles_import'] = $profiles;
			foreach ($profiles as $index => $p) {
				$this->request->post['module_mt_export_import_profile_import' . $index] = $this->config->get('module_mt_export_import_profile_import' . $index);
			}
		}
		// exit;

		$this->request->post['module_mt_export_import_status'] = $status;
		$this->model_setting_setting->editSetting('module_mt_export_import', $this->request->post);
		return array(
			'profile' => $profile_key,
			'profile_import' => $profile_import_key,
		);
	}

	public function LoadProfiles()
	{
		return $this->config->get('module_mt_export_import_profiles');
	}

	public function LoadProfilesImport()
	{
		return $this->config->get('module_mt_export_import_profiles_import');
	}

	// Загрузка настроек из базы данных
	public function LoadStatus()
	{
		return $this->config->get('module_mt_export_import_status');
	}

	public function LoadKey()
	{
		return $this->config->get('module_mt_export_import_lic_key');
	}

	public function LoadChildCategory($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_child_category'])) {
			return $profile['module_mt_export_import_child_category'];
		}
		return null;
	}

	public function LoadCategories($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_category'])) {
			return $profile['module_mt_export_import_category'];
		}
		return null;
	}

	public function LoadFileName($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_file_name'])) {
			return $profile['module_mt_export_import_file_name'];
		}
		return null;
	}

	public function LoadAllCategory($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_all_category'])) {
			return $profile['module_mt_export_import_all_category'];
		}
		return null;
	}

	public function LoadExportAttributes($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_attributes'])) {
			return $profile['module_mt_export_import_export_attributes'];
		}
		return null;
	}

	public function LoadExportCategories($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_categories'])) {
			return $profile['module_mt_export_import_export_categories'];
		}
		return null;
	}

	public function LoadExportOptions($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_options'])) {
			return $profile['module_mt_export_import_export_options'];
		}
		return null;
	}
	public function LoadExportSpecial($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_special'])) {
			return $profile['module_mt_export_import_export_special'];
		}
		return null;
	}
	public function LoadExportDiscount($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_discount'])) {
			return $profile['module_mt_export_import_export_discount'];
		}
		return null;
	}

	public function LoadExportSeo($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_seo'])) {
			return $profile['module_mt_export_import_export_seo'];
		}
		return null;
	}

	public function LoadExportMetaTags($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_meta_tags'])) {
			return $profile['module_mt_export_import_export_meta_tags'];
		}
		return null;
	}

	public function LoadExportHtml($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_description_html'])) {
			return $profile['module_mt_export_import_export_description_html'];
		}
		return null;
	}

	public function LoadFilterEnable($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_filter_enable'])) {
			return $profile['module_mt_export_import_filter_enable'];
		}
		return null;
	}

	public function LoadSpecial($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_special'])) {
			return $profile['module_mt_export_import_special'];
		}
		return null;
	}

	public function LoadExportParams($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_params'])) {
			return $profile['module_mt_export_import_export_params'];
		}
		return null;
	}

	public function LoadColumns($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_columns'])) {
			return $profile['module_mt_export_import_columns'];
		}
		return null;
	}

	public function LoadColumnName($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_column_name'])) {
			return $profile['module_mt_export_import_column_name'];
		}
		return null;
	}

	public function LoadMultiLang($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_multi_language'])) {
			return $profile['module_mt_export_import_multi_language'];
		}
		return null;
	}

	public function LoadIdName($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_id_name'])) {
			return $profile['module_mt_export_import_id_name'];
		}
		return null;
	}

	public function LoadTypeFile($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_type_file'])) {
			return $profile['module_mt_export_import_type_file'];
		}
		return null;
	}

	public function LoadImportParams($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_import_params'])) {
			return $profile['module_mt_export_import_import_params'];
		}
		return null;
	}
	public function LoadReplaceSearchColumn($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_replace_search_column'])) {
			return $profile['module_mt_export_import_export_replace_search_column'];
		}
		return null;
	}
	public function LoadSearchColumn($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_search_column'])) {
			return $profile['module_mt_export_import_export_search_column'];
		}
		return null;
	}
	public function LoadImportReplace($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_replace'])) {
			return $profile['module_mt_export_import_export_replace'];
		}
		return null;
	}
	public function LoadImportCreateNew($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_create_new'])) {
			return $profile['module_mt_export_import_export_create_new'];
		}
		return null;
	}
	public function LoadImportReplaceDelimiter($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_replace_delimiter'])) {
			return $profile['module_mt_export_import_export_replace_delimiter'];
		}
		return null;
	}
	public function LoadImportDelimiter($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_delimiter'])) {
			return $profile['module_mt_export_import_export_delimiter'];
		}
		return null;
	}
	public function LoadImportReplaceDelimiterAttribute($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_replace_delimiter_attribute'])) {
			return $profile['module_mt_export_import_export_replace_delimiter_attribute'];
		}
		return null;
	}
	public function LoadImportDelimiterAttribute($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_export_delimiter_attribute'])) {
			return $profile['module_mt_export_import_export_delimiter_attribute'];
		}
		return null;
	}
	public function LoadImportEnablePreview($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_enable_preview'])) {
			return $profile['module_mt_export_import_enable_preview'];
		}
		return null;
	}
	public function LoadImportUrl($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile_import' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_url'])) {
			return $profile['module_mt_export_import_url'];
		}
		return null;
	}
	public function LoadOnlyMainCategory($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_only_main_category'])) {
			return $profile['module_mt_export_import_only_main_category'];
		}
		return null;
	}
	public function LoadManufacturerName($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_manufacturer_name'])) {
			return $profile['module_mt_export_import_manufacturer_name'];
		}
		return null;
	}

	public function LoadProducts($categories, $manufacturer, $filters, $attributes, $options, $languages, $profile_key, $product_limit)
	{
		$sql = "SELECT DISTINCT pd.name, pd.language_id, pd.description,"
		. ($this->LoadExportMetaTags($profile_key) ? " meta_title, pd.meta_description, pd.meta_keyword,	" : "")
		. ($this->LoadManufacturerName($profile_key) ? " m.name as 'manufacturer'," : "");
		// if ($this->LoadMultiLang($profile_key)) {
		// 	$sql = "SELECT DISTINCT pd.name, pd.language_id, pd.description,";
		// } else {
		// 	$sql = "SELECT DISTINCT pd.name, pd.description,";
		// }
		// if ((is_array($manufacturer) && count($manufacturer) > 0) || $this->LoadManufacturerName($profile_key)) {
		// 	$sql .= " m.name as 'manufacturer',";
		// }
		$sql .= "p.*, '' as 'images' "
		// .($this->LoadExportParams($profile_key) && $this->LoadExportSeo($profile_key) ? ",'' as 'seo_url'" : "").""
		// .($this->LoadExportParams($profile_key) && $this->LoadExportCategories($profile_key) ? ",'' as 'category_id'" : "")
		." FROM " . DB_PREFIX . "product as p 
		INNER JOIN " . DB_PREFIX . "product_description as pd ON p.product_id = pd.product_id 
		INNER JOIN " . DB_PREFIX . "language as l ON l.language_id = pd.language_id";
		if (is_array($categories) && count($categories) > 0) {
			$sql .= " INNER JOIN " . DB_PREFIX . "product_to_category as pc ON p.product_id = pc.product_id";
			if ($this->LoadOnlyMainCategory($profile_key)) {
				$sql .= " INNER JOIN " . DB_PREFIX . "category as c ON c.category_id = pc.category_id";
			}
		}
		if ($this->LoadFilterEnable($profile_key) || $this->LoadManufacturerName($profile_key)) {
			if ((is_array($manufacturer) && count($manufacturer) > 0) || $this->LoadManufacturerName($profile_key)) {
				$sql .= " INNER JOIN " . DB_PREFIX . "manufacturer as m ON p.manufacturer_id = m.manufacturer_id";
			}
			if (is_array($filters) && count($filters) > 0) {
				$sql .= " INNER JOIN " . DB_PREFIX . "product_filter as pf ON p.product_id = pf.product_id";
			}
			if (is_array($attributes) && count($attributes) > 0) {
				$sql .= " INNER JOIN " . DB_PREFIX . "product_attribute as pa ON p.product_id = pa.product_id";
			}
			if (is_array($options) && count($options) > 0) {
				$sql .= " INNER JOIN " . DB_PREFIX . "product_option as po ON p.product_id = po.product_id";
			}
			if ($this->config->get('module_mt_export_import_special')) {
				$sql .= " INNER JOIN " . DB_PREFIX . "product_special as ps ON p.product_id = ps.product_id";
			}
		}
		if (is_array($categories) && count($categories) > 0) {
			$sql .= " WHERE pc.category_id IN (" . implode(',', $categories) . ")";
			if ($this->LoadOnlyMainCategory($profile_key)) {
				$sql .= " AND c.top = 1";
			}
		}
		if ($this->LoadFilterEnable($profile_key)) {
			if (is_array($manufacturer) && count($manufacturer) > 0) {
				if (strstr($sql, "WHERE")) {
					$sql .= " AND p.manufacturer_id IN (" . implode(',', $manufacturer) . ")";
				} else {
					$sql .= " WHERE p.manufacturer_id IN (" . implode(',', $manufacturer) . ")";
				}
			}
			if (is_array($filters) && count($filters) > 0) {
				if (strstr($sql, "WHERE")) {
					$sql .= " AND pf.filter_id IN (" . implode(',', $filters) . ")";
				} else {
					$sql .= " WHERE pf.filter_id IN (" . implode(',', $filters) . ")";
				}
			}
			if (is_array($attributes) && count($attributes) > 0) {
				if (strstr($sql, "WHERE")) {
					$sql .= " AND pa.attribute_id IN (" . implode(',', $attributes) . ")";
				} else {
					$sql .= " WHERE pa.attribute_id IN (" . implode(',', $attributes) . ")";
				}
			}
			if (is_array($options) && count($options) > 0) {
				if (strstr($sql, "WHERE")) {
					$sql .= " AND po.option_id IN (" . implode(',', $options) . ")";
				} else {
					$sql .= " WHERE po.option_id IN (" . implode(',', $options) . ")";
				}
			}
			if (is_array($languages) && count($languages) > 0) {
				if (strstr($sql, "WHERE")) {
					$sql .= " AND pd.language_id IN (" . implode(',', $languages) . ")";
				} else {
					$sql .= " WHERE pd.language_id IN (" . implode(',', $languages) . ")";
				}
			}
			$price_start = $this->LoadPriceStart($profile_key);
			$price_end = $this->LoadPriceEnd($profile_key);
			if ($price_start) {
				if (strstr($sql, "WHERE")) {
					$sql .= " AND p.price >= $price_start";
				} else {
					$sql .= " WHERE p.price >= $price_start";
				}
			}
			if ($price_end) {
				if (strstr($sql, "WHERE")) {
					$sql .= " AND p.price <= $price_end";
				} else {
					$sql .= " WHERE p.price <= $price_end";
				}
			}
		}
		if (is_array($languages) && count($languages) == 0) {
			$lang = array();
			$languages = $this->model_extension_module_mt_export_import->LoadAllLanguages();
			foreach ($languages as $r) {
				$lang[] = $r['language_id'];
			}
			if (strstr($sql, "WHERE")) {
				$sql .= " AND pd.language_id IN (" . implode(',', $lang) . ")";
			} else {
				$sql .= " WHERE pd.language_id IN (" . implode(',', $lang) . ")";
			}
		}
		// $sql .= " LIMIT $product_limit";
		$main_image = $this->LoadMainImage($profile_key);
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadProductSpecial($product_id)
	{
		$sql = "SELECT * FROM `oc_product_special` WHERE product_id = $product_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadProductSpecialColumn()
	{
		$sql = "SHOW COLUMNS FROM `oc_product_special`";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadProductDiscount($product_id)
	{
		$sql = "SELECT * FROM `oc_product_discount` WHERE product_id = $product_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadProductDiscountColumn()
	{
		$sql = "SHOW COLUMNS FROM `oc_product_discount`";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadImages($product_id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product_image WHERE product_id = " . $product_id);
		return $query->rows;
	}
	public function LoadProductMainImage($product_id)
	{
		$query = $this->db->query("SELECT DISTINCT image FROM " . DB_PREFIX . "product WHERE product_id = " . $product_id);
		return $query->rows;
	}
	public function LoadCategory($id)
	{
		$query = $this->db->query("SELECT DISTINCT cd.*, cc.parent_id FROM " . DB_PREFIX . "category_description as cd INNER JOIN " . DB_PREFIX . "category as cc ON cc.category_id = cd.category_id WHERE cd.`category_id` = $id AND cd.`language_id` = " . (int)$this->config->get('config_language_id'));
		return $query->rows;
	}
	public function LoadMainCategory($id)
	{
		$query = $this->db->query("SELECT DISTINCT cd.*, cc.parent_id FROM " . DB_PREFIX . "category_description as cd INNER JOIN " . DB_PREFIX . "category as cc ON cc.category_id = cd.category_id WHERE cd.`category_id` = $id AND cc.top = 1 AND cd.`language_id` = " . (int)$this->config->get('config_language_id'));
		return $query->rows;
	}
	// public function LoadProductMainCategory($product_id)
	// {
	// 	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product_to_category as ptc WHERE ptc.main_category = 1 AND ptc.product_id = " . $product_id);
	// 	return $query->rows;
	// }
	public function LoadProductCategories($product_id, $language_id)
	{
		$query = $this->db->query("SELECT DISTINCT cd.name, cd.category_id FROM " . DB_PREFIX . "product_to_category as ptc 
		INNER JOIN " . DB_PREFIX . "category_description as cd on cd.category_id = ptc.category_id 
		WHERE ptc.product_id = $product_id AND cd.language_id = $language_id");
		return $query->rows;
	}
	public function LoadProductMainCategory($product_id, $language_id)
	{
		try {
			$query = $this->db->query("SELECT DISTINCT cd.name, cd.category_id FROM " . DB_PREFIX . "product_to_category as ptc 
			INNER JOIN " . DB_PREFIX . "category_description as cd on cd.category_id = ptc.category_id 
			WHERE ptc.product_id = $product_id AND cd.language_id = $language_id AND ptc.`main_category` = 1");
		}
		catch (Exception $ex) {
			$query = $this->db->query("SELECT DISTINCT cd.name, cd.category_id FROM " . DB_PREFIX . "product_to_category as ptc 
				INNER JOIN " . DB_PREFIX . "category_description as cd on cd.category_id = ptc.category_id 
				WHERE ptc.product_id = $product_id AND cd.language_id = $language_id LIMIT 1");
		}
		return $query->rows;
	}
	public function LoadParentCategory($id)
	{
		$query = $this->db->query("SELECT DISTINCT cd.*, cc.parent_id FROM " . DB_PREFIX . "category_description as cd INNER JOIN " . DB_PREFIX . "category as cc ON cc.category_id = cd.category_id WHERE cc.`parent_id` = $id");
		return $query->rows;
	}

	public function LoadModuleManufacturer($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_manufacturer'])) {
			return $profile['module_mt_export_import_manufacturer'];
		}
		return null;
	}
	public function LoadManufacturer($id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "manufacturer WHERE `manufacturer_id` = $id");
		return $query->rows;
	}
	public function LoadModuleLanguages($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_language'])) {
			return $profile['module_mt_export_import_language'];
		}
		return null;
	}
	public function LoadLanguage($id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE status = 1 AND language_id = " . $id);
		return $query->rows;
	}
	public function LoadLanguageByName($name)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE status = 1 AND name = '" . $name ."'");
		return $query->rows;
	}
	public function LoadAllLanguages()
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE status = 1");
		return $query->rows;
	}
	public function LoadCurrency()
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE status = 1");
		return $query->rows;
	}
	public function LoadCurrencyId($id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE status = 1 AND currency_id = $id");
		return $query->rows;
	}

	public function LoadAllCategories()
	{
		$query = $this->db->query("SELECT DISTINCT c.*, cd.name, cd.description FROM oc_category as c INNER JOIN oc_category_description as cd on c.category_id = cd.category_id WHERE c.status = 1 AND cd.language_id = " . (int)$this->config->get('config_language_id'));
		return $query->rows;
	}

	public function LoadModuleFilters($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_filter'])) {
			return $profile['module_mt_export_import_filter'];
		}
		return null;
	}
	public function LoadGetNameFromId($column, $id, $language_id)
	{
		$sql = '';
		if ($column == 'stock_status_id') {
			$sql = "SELECT * FROM `oc_stock_status` WHERE stock_status_id = $id AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['name'];
			}
			else {
				$id = '';
			}
		}
		else if ($column == 'manufacturer_id') {
			$sql = "SELECT * FROM `oc_manufacturer` WHERE manufacturer_id = $id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['name'];
			}
			else {
				$id = '';
			}
		}
		else if ($column == 'tax_class_id') {
			$sql = "SELECT * FROM `oc_tax_class` WHERE tax_class_id = $id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['title'];
			}
			else {
				$id = '';
			}
		}
		else if ($column == 'weight_class_id') {
			$sql = "SELECT * FROM `oc_weight_class_description` WHERE weight_class_id = $id AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['title'];
			}
			else {
				$id = '';
			}
		}
		else if ($column == 'length_class_id') {
			$sql = "SELECT * FROM `oc_length_class_description` WHERE length_class_id = $id AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['title'];
			}
			else {
				$id = '';
			}
		}
		else if ($column == 'length_class_id') {
			$sql = "SELECT * FROM `oc_length_class_description` WHERE length_class_id = $id AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['title'];
			}
			else {
				$id = '';
			}
		}
		else if ($column == 'subtract') {
			if ($id == 1) $id = 'Да';
			else $id = 'Нет';
		}
		else if ($column == 'status') {
			if ($id == 1) $id = 'Включено';
			else $id = 'Отключено';
		}
		else if ($column == 'customer_group_id') {
			$sql = "SELECT * FROM `oc_customer_group_description` WHERE customer_group_id = $id AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['name'];
			}
			else {
				$id = '';
			}
		}
		else if ($column == 'language_id') {
			$sql = "SELECT * FROM `oc_language` WHERE language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['name'];
			}
			else {
				$id = '';
			}
		}
		return $id;
	}
	public function LoadGetIdFromName($column, $id, $language_id, $attr_del)
	{
		if ($id == '') return $id;
		$sql = '';
		if ($column == 'stock_status_id') {
			$sql = "SELECT * FROM `oc_stock_status` WHERE name = '$id' AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['stock_status_id'];
			}
		}
		else if ($column == 'manufacturer_id') {
			$sql = "SELECT * FROM `oc_manufacturer` WHERE name = '$id'";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['manufacturer_id'];
			}
		}
		else if ($column == 'tax_class_id') {
			$sql = "SELECT * FROM `oc_tax_class` WHERE title = '$id'";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['tax_class_id'];
			}
		}
		else if ($column == 'weight_class_id') {
			$sql = "SELECT * FROM `oc_weight_class_description` WHERE title = '$id' AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['weight_class_id'];
			}
		}
		else if ($column == 'length_class_id') {
			$sql = "SELECT * FROM `oc_length_class_description` WHERE title = '$id' AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['length_class_id'];
			}
		}
		else if ($column == 'subtract') {
			if ($id == 'Да' || $id == 1) $id = 1;
			else $id = 0;
		}
		else if ($column == 'status') {
			if ($id == 'Включено' || $id == 1) $id = 1;
			else $id = 0;
		}
		else if ($column == 'customer_group_id') {
			$sql = "SELECT * FROM `oc_customer_group_description` WHERE name = '$id' AND language_id = $language_id";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['customer_group_id'];
			}
		}
		else if ($column == 'language_id') {
			$sql = "SELECT * FROM `oc_language` WHERE name = '$id'";
			$query = $this->db->query($sql);
			if ($query->rows && isset($query->rows[0])) {
				$id = $query->rows[0]['language_id'];
			}
		}
		return $id;
	}
	public function LoadFilter($id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "filter_description WHERE `filter_id` = $id AND `language_id` = " . (int)$this->config->get('config_language_id'));
		return $query->rows;
	}
	public function LoadFilterParent($id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "filter_group_description WHERE `filter_group_id` = $id AND `language_id` = " . (int)$this->config->get('config_language_id'));
		return $query->rows;
	}

	public function LoadModuleAttribute($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_attribute'])) {
			return $profile['module_mt_export_import_attribute'];
		}
		return null;
	}
	public function LoadAttribute($id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "attribute_description WHERE `attribute_id` = $id AND `language_id` = " . (int)$this->config->get('config_language_id'));
		return $query->rows;
	}
	public function LoadModuleOption($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_option'])) {
			return $profile['module_mt_export_import_option'];
		}
		return null;
	}
	public function LoadOption($id)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "option_description WHERE `option_id` = $id AND `language_id` = " . (int)$this->config->get('config_language_id'));
		return $query->rows;
	}
	public function LoadPriceStart($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_price_start'])) {
			return $profile['module_mt_export_import_price_start'];
		}
		return null;
	}
	public function LoadPriceEnd($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_price_end'])) {
			return $profile['module_mt_export_import_price_end'];
		}
		return null;
	}

	public function LoadMainImage($profile_key)
	{
		$profile = $this->config->get('module_mt_export_import_profile' . $profile_key);
		if ($profile && isset($profile['module_mt_export_import_main_image'])) {
			return $profile['module_mt_export_import_main_image'];
		}
		return null;
	}
	public function LoadTableColumns($profile_key)
	{
		$sql = "SELECT DISTINCT pdesc.name, pdesc.language_id, pdesc.description," 
		. ($this->LoadExportMetaTags($profile_key) ? " pdesc.meta_title, pdesc.meta_description, pdesc.meta_keyword," : "")
		. ($this->LoadManufacturerName($profile_key) ? " m.name as 'manufacturer'," : "")."p.*, '' as 'images'";
		// if ($this->LoadExportSpecial($profile_key)) {
		// 	$sql .= ",ps.`product_special_id` as 'special_product_special_id', ps.`customer_group_id` as 'special_customer_group_id', ps.`priority` as 'special_priority', ps.`price` as 'special_price', ps.`date_start` as 'special_date_start', ps.`date_end` as 'special_date_end'";
		// }
		// if ($this->LoadExportDiscount($profile_key)) {
		// 	$sql .= ",pd.`product_discount_id` as 'discount_product_discount_id', pd.`customer_group_id` as 'discount_customer_group_id', pd.`quantity` as 'discount_quantity', pd.`priority` as 'discount_priority', pd.`price` as 'discount_price', pd.`date_start` as 'discount_date_start', pd.`date_end` as 'discount_date_end'";
		// }
		$sql .= " FROM " . DB_PREFIX . "product as p";
		$sql .= " INNER JOIN " . DB_PREFIX . "product_description as pdesc ON p.product_id = pdesc.product_id ";
		$sql .= ($this->LoadManufacturerName($profile_key) ? " INNER JOIN " . DB_PREFIX . "manufacturer as m ON p.manufacturer_id = m.manufacturer_id" : "");
		// $sql = "SELECT DISTINCT pdesc.name, pdesc.description," . ($this->LoadManufacturerName($profile_key) ? " m.name as 'manufacturer'," : "") . "
		// p.*, '' as 'images', ps.`product_special_id` as 'special_product_special_id', ps.`customer_group_id` as 'special_customer_group_id', ps.`priority` as 'special_priority', ps.`price` as 'special_price', ps.`date_start` as 'special_date_start', ps.`date_end` as 'special_date_end',
		// pd.`product_discount_id` as 'discount_product_discount_id', pd.`customer_group_id` as 'discount_customer_group_id', pd.`quantity` as 'discount_quantity', pd.`priority` as 'discount_priority', pd.`price` as 'discount_price', pd.`date_start` as 'discount_date_start', pd.`date_end` as 'discount_date_end'
		// FROM " . DB_PREFIX . "product as p INNER JOIN " . DB_PREFIX . "product_description as pdesc ON p.product_id = pdesc.product_id 
		// INNER JOIN " . DB_PREFIX . "manufacturer as m ON p.manufacturer_id = m.manufacturer_id";
		// if ($this->LoadMultiLang($profile_key)) {
		// 	$sql = "SELECT DISTINCT pdesc.name, pdesc.language_id, pdesc.description," . ($this->LoadManufacturerName($profile_key) ? " m.name as 'manufacturer'," : "") . "
		// 	p.*, '' as 'images', ps.`product_special_id` as 'special_product_special_id', ps.`customer_group_id` as 'special_customer_group_id', ps.`priority` as 'special_priority', ps.`price` as 'special_price', ps.`date_start` as 'special_date_start', ps.`date_end` as 'special_date_end',
		// 	pd.`product_discount_id` as 'discount_product_discount_id', pd.`customer_group_id` as 'discount_customer_group_id', pd.`quantity` as 'discount_quantity', pd.`priority` as 'discount_priority', pd.`price` as 'discount_price', pd.`date_start` as 'discount_date_start', pd.`date_end` as 'discount_date_end'
		// 	FROM " . DB_PREFIX . "product as p INNER JOIN " . DB_PREFIX . "product_description as pdesc ON p.product_id = pdesc.product_id 
		// 	INNER JOIN " . DB_PREFIX . "manufacturer as m ON p.manufacturer_id = m.manufacturer_id";
		// }
		if ($this->LoadExportSpecial($profile_key)) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_special as ps on ps.product_id = p.product_id";
		}
		if ($this->LoadExportDiscount($profile_key)) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_discount as pd on pd.product_id = p.product_id";
		}
		$sql .= " LIMIT 1";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadAttributesColumns()
	{
		$sql = "SELECT DISTINCT ad.name, ad.attribute_id FROM `oc_attribute_description`  as ad
		INNER JOIN `oc_product_attribute` as pa on pa.attribute_id = ad.attribute_id
		WHERE ad.language_id = ".$this->config->get('config_language_id');
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadAttributeValue($product_id, $attribute_id, $language_id)
	{
		$sql = "SELECT DISTINCT text FROM `oc_product_attribute` WHERE product_id = $product_id AND attribute_id = $attribute_id AND language_id = $language_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadOptionsColumns()
	{
		$sql = "SELECT pov.product_option_value_id, po.*, pov.`option_value_id`, pov.`quantity`, pov.`subtract`,
		pov.`price`, pov.`price_prefix`, pov.`points`, pov.`points_prefix`, pov.`weight`, pov.`weight_prefix`, o.type, o.sort_order, od.language_id, od.name 
		FROM `oc_option_description` as od 
	   INNER JOIN `oc_option` as o on o.option_id = od.option_id 
	   INNER JOIN `oc_product_option` as po on po.option_id = o.option_id 
	   LEFT JOIN `oc_product_option_value` as pov on pov.product_id = po.product_id LIMIT 1";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadOptionsValue($product_id, $language_id)
	{
		$sql = "SELECT pov.product_option_value_id, po.*, pov.`option_value_id`, pov.`quantity`, pov.`subtract`,
		 pov.`price`, pov.`price_prefix`, pov.`points`, pov.`points_prefix`, pov.`weight`, pov.`weight_prefix`, o.type, o.sort_order, od.language_id, od.name 
		 FROM `oc_option_description` as od 
		INNER JOIN `oc_option` as o on o.option_id = od.option_id 
		INNER JOIN `oc_product_option` as po on po.option_id = o.option_id 
		LEFT JOIN `oc_product_option_value` as pov on pov.product_id = po.product_id 
		WHERE po.product_id = $product_id AND language_id = $language_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadProductOptionColumn()
	{
		$sql = "SELECT 1 as product_option_value_id, 1 as product_option_id, 1 as product_id, 1 as option_id, 1 as value, 1 as required, 1 as `option_value_id`, 1 as `quantity`, 1 as `subtract`, 1 as `price`, 1 as `price_prefix`, 1 as `points`, 1 as `points_prefix`, 1 as `weight`, 1 as `weight_prefix`, 1 as type, 1 as sort_order, 1 as language_id, 1 as name";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadOptionsShortColumns()
	{
		$sql = "SELECT DISTINCT od.name FROM `oc_option_description` as od LEFT JOIN `oc_product_option_value` as pov on od.option_id = pov.option_id  RIGHT JOIN `oc_product_option` as po on po.option_id = od.option_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}public function LoadOptionsValueShortColumns($product_id, $language_id)
	{
		$sql = "SELECT DISTINCT od.name as 'option_name', pov.price, pov.price_prefix, po.value, ovd.name, ovd.language_id 
		FROM `oc_product_option` as po 
		LEFT JOIN `oc_option_value_description` as ovd on ovd.option_id = po.option_id 
		LEFT JOIN `oc_product_option_value` as pov on ovd.option_value_id = pov.option_value_id 
		INNER JOIN `oc_option_description` as od on od.option_id = po.option_id 
		WHERE (ovd.language_id = $language_id OR ovd.language_id IS NULL) 
		AND ((price IS NULL AND value <> '' AND po.product_id = $product_id) OR (price IS NOT NULL AND pov.product_id = $product_id))";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function LoadSeoValue($product_id, $language_id)
	{
		$sql = "SELECT DISTINCT keyword FROM `oc_seo_url` WHERE query = 'product_id=$product_id' AND language_id = $language_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}
}
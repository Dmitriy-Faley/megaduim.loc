<?php
class ModelCatalogFaq extends Model {

	public function addFaq($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "faq SET sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "'");

		$faq_id = $this->db->getLastId();

		foreach ($data['faq_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET faq_id = '" . (int) $faq_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if (isset($data['faq_store'])) {
			foreach ($data['faq_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "faq_to_store SET faq_id = '" . (int) $faq_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		if (isset($data['faq_layout'])) {
			foreach ($data['faq_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "faq_to_layout SET faq_id = '" . (int) $faq_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
			}
		}

		return $faq_id;
	}

	public function editFaq($faq_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "faq SET sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "' WHERE faq_id = '" . (int) $faq_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int) $faq_id . "'");

		foreach ($data['faq_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET faq_id = '" . (int) $faq_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "faq_to_store WHERE faq_id = '" . (int) $faq_id . "'");

		if (isset($data['faq_store'])) {
			foreach ($data['faq_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "faq_to_store SET faq_id = '" . (int) $faq_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "faq_to_layout` WHERE faq_id = '" . (int) $faq_id . "'");

		if (isset($data['faq_layout'])) {
			foreach ($data['faq_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "faq_to_layout` SET faq_id = '" . (int) $faq_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
			}
		}
	}

	public function deleteFaq($faq_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "faq` WHERE faq_id = '" . (int) $faq_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "faq_description` WHERE faq_id = '" . (int) $faq_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "faq_to_store` WHERE faq_id = '" . (int) $faq_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "faq_to_layout` WHERE faq_id = '" . (int) $faq_id . "'");
	}

	public function getFaqItem($faq_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "faq WHERE faq_id = '" . (int) $faq_id . "'");

		return $query->row;
	}

	public function getFaq($data = array()) {
		$faq_data = array();

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "faq i LEFT JOIN " . DB_PREFIX . "faq_description id ON (i.faq_id = id.faq_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "'";

			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
			}

			$query = $this->db->query($sql);

			$faq_data = $query->rows;
		}

		return $faq_data;
	}

	public function getFaqDescriptions($faq_id) {
		$faq_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int) $faq_id . "'");

		foreach ($query->rows as $result) {
			$faq_description_data[$result['language_id']] = array(
				'title' => $result['title'],
				'description' => $result['description']
			);
		}

		return $faq_description_data;
	}

	public function getFaqStores($faq_id) {
		$faq_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_to_store WHERE faq_id = '" . (int) $faq_id . "'");

		foreach ($query->rows as $result) {
			$faq_store_data[] = $result['store_id'];
		}

		return $faq_store_data;
	}

	public function getFaqLayouts($faq_id) {
		$faq_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_to_layout WHERE faq_id = '" . (int) $faq_id . "'");

		foreach ($query->rows as $result) {
			$faq_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $faq_layout_data;
	}

	public function getTotalFaq() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faq");

		return $query->row['total'];
	}

	public function getTotalFaqByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faq_to_layout WHERE layout_id = '" . (int) $layout_id . "'");

		return $query->row['total'];
	}

}

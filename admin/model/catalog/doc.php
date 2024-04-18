<?php
class ModelCatalogDoc extends Model {

	public function addDoc($data) {
		//$this->db->query("INSERT INTO " . DB_PREFIX . "doc SET sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "'");


		$this->db->query("INSERT INTO " . DB_PREFIX . "doc SET sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', filename = '" . $this->db->escape($data['filename']) . "'");

		$doc_id = $this->db->getLastId();

		foreach ($data['doc_description'] as $language_id => $value) {
			//$this->db->query("INSERT INTO " . DB_PREFIX . "doc_description SET doc_id = '" . (int) $doc_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "doc_description SET doc_id = '" . (int) $doc_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', filename = '" . $this->db->escape($data['filename']) . "'");
		}

		if (isset($data['doc_store'])) {
			foreach ($data['doc_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "doc_to_store SET doc_id = '" . (int) $doc_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		if (isset($data['doc_layout'])) {
			foreach ($data['doc_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "doc_to_layout SET doc_id = '" . (int) $doc_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
			}
		}

		return $doc_id; 
	}

	public function editDoc($doc_id, $data) {
		//$this->db->query("UPDATE " . DB_PREFIX . "doc SET sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "' WHERE doc_id = '" . (int) $doc_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "doc SET sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', filename = '" . $this->db->escape($data['filename']) . "' WHERE doc_id = '" . (int) $doc_id . "'");


		//$this->db->query("UPDATE " . DB_PREFIX . "doc_description SET doc_id = '" . (int) $doc_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', filename = '" . $this->db->escape($data['filename']) . "' WHERE doc_id = '" . (int) $doc_id . "'");



		$this->db->query("DELETE FROM " . DB_PREFIX . "doc_description WHERE doc_id = '" . (int) $doc_id . "'");

		foreach ($data['doc_description'] as $language_id => $value) {
			//$this->db->query("INSERT INTO " . DB_PREFIX . "doc_description SET doc_id = '" . (int) $doc_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "doc_description SET doc_id = '" . (int) $doc_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', filename = '" . $this->db->escape($data['filename']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "doc_to_store WHERE doc_id = '" . (int) $doc_id . "'");

		if (isset($data['doc_store'])) {
			foreach ($data['doc_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "doc_to_store SET doc_id = '" . (int) $doc_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "doc_to_layout` WHERE doc_id = '" . (int) $doc_id . "'");

		if (isset($data['doc_layout'])) {
			foreach ($data['doc_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "doc_to_layout` SET doc_id = '" . (int) $doc_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
			}
		}
	}

	public function deleteDoc($doc_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "doc` WHERE doc_id = '" . (int) $doc_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "doc_description` WHERE doc_id = '" . (int) $doc_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "doc_to_store` WHERE doc_id = '" . (int) $doc_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "doc_to_layout` WHERE doc_id = '" . (int) $doc_id . "'");
	}

	public function getDocItem($doc_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "doc WHERE doc_id = '" . (int) $doc_id . "'");

		return $query->row;
	}

	public function getDoc($data = array()) {
		$doc_data = array();

		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "doc i LEFT JOIN " . DB_PREFIX . "doc_description id ON (i.doc_id = id.doc_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "'";

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

			$doc_data = $query->rows;
		}
		return $doc_data;
	}

	public function getDocDescriptions($doc_id) {
		$doc_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "doc_description WHERE doc_id = '" . (int) $doc_id . "'");

		foreach ($query->rows as $result) {
			$doc_description_data[$result['language_id']] = array(
				'title' => $result['title'],
				'description' => $result['description']
			);
		}

		return $doc_description_data;
	}

	public function getDocStores($doc_id) {
		$doc_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "doc_to_store WHERE doc_id = '" . (int) $doc_id . "'");

		foreach ($query->rows as $result) {
			$doc_store_data[] = $result['store_id'];
		}

		return $doc_store_data;
	}

	public function getDocLayouts($doc_id) {
		$doc_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "doc_to_layout WHERE doc_id = '" . (int) $doc_id . "'");

		foreach ($query->rows as $result) {
			$doc_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $doc_layout_data;
	}

	public function getTotalDoc() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "doc");

		return $query->row['total'];
	}

	public function getTotalDocByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "doc_to_layout WHERE layout_id = '" . (int) $layout_id . "'");

		return $query->row['total'];
	}

}

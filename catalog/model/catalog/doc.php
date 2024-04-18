<?php
class ModelCatalogDoc extends Model {
	public function getDocs() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "doc n LEFT JOIN " . DB_PREFIX . "doc_description nd ON (n.doc_id = nd.doc_id) LEFT JOIN " . DB_PREFIX . "doc_to_store n2s ON (n.doc_id = n2s.doc_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.title) ASC");

		return $query->rows;
		
	}

	public function getDocsCustom() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "doc n LEFT JOIN " . DB_PREFIX . "doc_description nd ON (n.doc_id = nd.doc_id) LEFT JOIN " . DB_PREFIX . "doc_to_store n2s ON (n.doc_id = n2s.doc_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.title) ASC");

		return $query->rows;
	}

	public function getDocLayoutId($doc_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "doc_to_layout WHERE doc_id = '" . (int)$doc_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}
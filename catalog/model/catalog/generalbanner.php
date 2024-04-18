<?php
class ModelCatalogGeneralbanner extends Model {
	public function getNewsItem($gbanner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "general_banner n LEFT JOIN " . DB_PREFIX . "general_banner_description nd ON (n.gbanner_id = nd.gbanner_id) LEFT JOIN " . DB_PREFIX . "general_banner_to_store n2s ON (n.gbanner_id = n2s.gbanner_id) WHERE n.gbanner_id = '" . (int)$gbanner_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1'");

		return $query->row;
	}

	public function getNews() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "general_banner n LEFT JOIN " . DB_PREFIX . "general_banner_description nd ON (n.gbanner_id = nd.gbanner_id) LEFT JOIN " . DB_PREFIX . "general_banner_to_store n2s ON (n.gbanner_id = n2s.gbanner_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.title) ASC");

		return $query->rows;
	}

	public function getNewsToModule() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "general_banner n LEFT JOIN " . DB_PREFIX . "general_banner_description nd ON (n.gbanner_id = nd.gbanner_id) LEFT JOIN " . DB_PREFIX . "general_banner_to_store n2s ON (n.gbanner_id = n2s.gbanner_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.title) ASC LIMIT 3");

		return $query->rows;
	}

	public function getNewsLayoutId($gbanner_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oc_general_banner_layout WHERE gbanner_id = '" . (int)$gbanner_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}
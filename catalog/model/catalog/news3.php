<?php
class ModelCatalogNews3 extends Model {
	public function getNewsItem($news3_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news3 n LEFT JOIN " . DB_PREFIX . "news_description3 nd ON (n.news3_id = nd.news3_id) LEFT JOIN " . DB_PREFIX . "news_to_store3 n2s ON (n.news3_id = n2s.news3_id) WHERE n.news3_id = '" . (int)$news3_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1'");

		return $query->row;
	}

	public function getNews() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news3 n 
		LEFT JOIN " . DB_PREFIX . "news_description3 nd ON (n.news3_id = nd.news3_id) 
		LEFT JOIN " . DB_PREFIX . "news_to_store3 n2s ON (n.news3_id = n2s.news3_id) 
		WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		AND n.status = '1' 
		ORDER BY n.date_start DESC, 
		LCASE(nd.title) ASC");

		return $query->rows;
	}

	public function getNewsToModule() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news3 n LEFT JOIN " . DB_PREFIX . "news_description3 nd ON (n.news3_id = nd.news3_id) LEFT JOIN " . DB_PREFIX . "news_to_store3 n2s ON (n.news3_id = n2s.news3_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.title) ASC LIMIT 3");

		return $query->rows;
	}

	public function getNewsLayoutId($news3_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_layout3 WHERE news3_id = '" . (int)$news3_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}
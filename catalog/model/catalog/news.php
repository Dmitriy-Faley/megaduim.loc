<?php
class ModelCatalogNews extends Model {
	public function getNewsItem($news_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store n2s ON (n.news_id = n2s.news_id) WHERE n.news_id = '" . (int)$news_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1'");

		return $query->row;
	}

	public function getNews() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n 
		LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) 
		LEFT JOIN " . DB_PREFIX . "news_to_store n2s ON (n.news_id = n2s.news_id) 
		WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		AND n.status = '1' 
		ORDER BY n.date_start DESC, 
		LCASE(nd.title) ASC");

		return $query->rows;
	}

	public function getNewsToModule() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store n2s ON (n.news_id = n2s.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.title) ASC LIMIT 3");

		return $query->rows;
	}

	public function getNewsLayoutId($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_layout WHERE news_id = '" . (int)$news_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}
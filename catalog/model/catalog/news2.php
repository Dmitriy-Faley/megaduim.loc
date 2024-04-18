<?php
class ModelCatalogNews2 extends Model {
	public function getNewsItem($news2_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news2 n 
		LEFT JOIN " . DB_PREFIX . "news_description2 nd ON (n.news2_id = nd.news2_id) 
		LEFT JOIN " . DB_PREFIX . "news_to_store2 n2s ON (n.news2_id = n2s.news2_id) 
		WHERE n.news2_id = '" . (int)$news2_id . "' 
		AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		AND n.status = '1' 
		ORDER BY n.date_start DESC");

		return $query->row;
	}
	public function getNews() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news2 n 
		LEFT JOIN " . DB_PREFIX . "news_description2 nd ON (n.news2_id = nd.news2_id) 
		LEFT JOIN " . DB_PREFIX . "news_to_store2 n2s ON (n.news2_id = n2s.news2_id) 
		WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		AND n.status = '1' 
		ORDER BY n.date_start DESC, 
		LCASE(nd.title) ASC");

		return $query->rows;
	}

	public function getNewsToModule() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news2 n LEFT JOIN " . DB_PREFIX . "news_description2 nd ON (n.news2_id = nd.news2_id) LEFT JOIN " . DB_PREFIX . "news_to_store2 n2s ON (n.news2_id = n2s.news2_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.title) ASC LIMIT 3");

		return $query->rows;
	}

	public function getNewsLayoutId($news2_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_layout2 WHERE news2_id = '" . (int)$news2_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	/*public function getArticlesByDate() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news2 ORDER BY date_start DESC");

		return $query->rows;
	}*/
}
<?php
class ModelCatalogFaq extends Model {
	public function getFaqs() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq n LEFT JOIN " . DB_PREFIX . "faq_description nd ON (n.faq_id = nd.faq_id) LEFT JOIN " . DB_PREFIX . "faq_to_store n2s ON (n.faq_id = n2s.faq_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.title) ASC");

		return $query->rows;
	}

	public function getFaqLayoutId($faq_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_to_layout WHERE faq_id = '" . (int)$faq_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}
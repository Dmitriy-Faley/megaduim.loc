<?php

class ModelCatalogGeneralbanner extends Model { 

    public function addNews($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "general_banner SET sort_order = '" . (int) $data['sort_order'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', status = '" . (int) $data['status'] . "'");
        
        $gbanner_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "general_banner SET image = '" . $this->db->escape($data['image']) . "' WHERE gbanner_id = '" . (int) $gbanner_id . "'");
        }

        if (isset($data['image_adaptive'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "general_banner SET image_adaptive = '" . $this->db->escape($data['image_adaptive']) . "' WHERE gbanner_id = '" . (int) $gbanner_id . "'");
        }

        foreach ($data['news_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "general_banner_description SET gbanner_id = '" . (int) $gbanner_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['news_store'])) {
            foreach ($data['news_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "general_banner_to_store SET gbanner_id = '" . (int) $gbanner_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        if (isset($data['news_layout'])) {
            foreach ($data['news_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "general_banner_layout SET gbanner_id = '" . (int) $gbanner_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
            }
        }

        //$this->seogen->genSeoUrl($gbanner_id, $data);

        $this->cache->delete('news');

        return $gbanner_id;
    }

    public function editNews($gbanner_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "general_banner SET sort_order = '" . (int) $data['sort_order'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', status = '" . (int) $data['status'] . "' WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "general_banner_description WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "general_banner SET image = '" . $this->db->escape($data['image']) . "' WHERE gbanner_id = '" . (int) $gbanner_id . "'");
        }
        
        if (isset($data['image_adaptive'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "general_banner SET image_adaptive = '" . $this->db->escape($data['image_adaptive']) . "' WHERE gbanner_id = '" . (int) $gbanner_id . "'");
        }
        
        foreach ($data['news_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "general_banner_description SET gbanner_id = '" . (int) $gbanner_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "general_banner_to_store WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        if (isset($data['news_store'])) {
            foreach ($data['news_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "general_banner_to_store SET gbanner_id = '" . (int) $gbanner_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'gbanner_id=" . (int) $gbanner_id . "'");

        if (isset($data['general_banner_seo_url'])) {
            foreach ($data['general_banner_seo_url'] as $store_id => $language) {
                foreach ($language as $language_id => $keyword) {
                    if (trim($keyword)) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET store_id = '" . (int) $store_id . "', language_id = '" . (int) $language_id . "', query = 'gbanner_id=" . (int) $gbanner_id . "', keyword = '" . $this->db->escape($keyword) . "'");
                    }
                }
            }
        }
 
        $this->db->query("DELETE FROM `" . DB_PREFIX . "general_banner_layout` WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        if (isset($data['news_layout'])) {
            foreach ($data['news_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "general_banner_layout` SET gbanner_id = '" . (int) $gbanner_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
            }
        }

        //$this->seogen->genSeoUrl($gbanner_id, $data);

        $this->cache->delete('news');
    }

    public function deleteNews($gbanner_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "general_banner` WHERE gbanner_id = '" . (int) $gbanner_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "general_banner_description` WHERE gbanner_id = '" . (int) $gbanner_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "general_banner_to_store` WHERE gbanner_id = '" . (int) $gbanner_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "general_banner_layout` WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        $this->cache->delete('news');
    }
    
 
    public function getNewsItem($gbanner_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "general_banner WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        return $query->row;
    }

    public function getNews($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "general_banner i LEFT JOIN " . DB_PREFIX . "general_banner_description id ON (i.gbanner_id = id.gbanner_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "'";

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

            return $query->rows;
        } else {
            $news_data = $this->cache->get('news.' . (int) $this->config->get('config_language_id'));

            if (!$news_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "general_banner i LEFT JOIN " . DB_PREFIX . "general_banner_description id ON (i.gbanner_id = id.gbanner_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY id.title");

                $news_data = $query->rows;

                $this->cache->set('news.' . (int) $this->config->get('config_language_id'), $news_data);
            }

            return $news_data;
        }
    }

    public function getNewsDescriptions($gbanner_id) {
        $news_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "general_banner_description WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        foreach ($query->rows as $result) {
            $news_description_data[$result['language_id']] = array(
                'title' => $result['title'],
                'description' => $result['description'],
                'meta_keyword' => $result['meta_keyword']
            );
        }

        return $news_description_data;
    }

    public function getNewsStores($gbanner_id) {
        $news_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "general_banner_to_store WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        foreach ($query->rows as $result) {
            $news_store_data[] = $result['store_id'];
        }

        return $news_store_data;
    }

    public function getNewsSeoUrls($gbanner_id) {
        $general_banner_seo_url_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'gbanner_id=" . (int) $gbanner_id . "'");

        foreach ($query->rows as $result) {
            $general_banner_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
        }

        return $general_banner_seo_url_data;
    }

    public function getNewsLayouts($gbanner_id) {
        $news_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "general_banner_layout WHERE gbanner_id = '" . (int) $gbanner_id . "'");

        foreach ($query->rows as $result) {
            $news_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $news_layout_data;
    }

    public function getTotalNews() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "general_banner");

        return $query->row['total'];
    }

    public function getTotalNewsByLayoutId($layout_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "general_banner_layout WHERE layout_id = '" . (int) $layout_id . "'");

        return $query->row['total'];
    }

}


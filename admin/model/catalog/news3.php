<?php

class ModelCatalogNews3 extends Model { 

    public function addNews($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "news3 SET sort_order = '" . (int) $data['sort_order'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', status = '" . (int) $data['status'] . "'");

        $news3_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "news3 SET image = '" . $this->db->escape($data['image']) . "' WHERE news3_id = '" . (int) $news3_id . "'");
        }

        if (isset($data['image_adaptive'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "news3 SET image_adaptive = '" . $this->db->escape($data['image_adaptive']) . "' WHERE news3_id = '" . (int) $news3_id . "'");
        }

        if (isset($data['image_inner'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "news3 SET image_inner = '" . $this->db->escape($data['image_inner']) . "' WHERE news3_id = '" . (int) $news3_id . "'");
        }

        if (isset($data['image_inner_adaptive'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "news3 SET image_inner_adaptive = '" . $this->db->escape($data['image_inner_adaptive']) . "' WHERE news3_id = '" . (int) $news3_id . "'");
        }

        foreach ($data['news_description'] as $language_id => $value) {
            $meta_title = '';

            if (!empty($value['meta_title'])) {
                $meta_title = $value['meta_title'];
            } else {
                $meta_title = $value['title'];
            }

            $this->db->query("INSERT INTO " . DB_PREFIX . "news_description3 SET news3_id = '" . (int) $news3_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', intro = '" . $this->db->escape($value['intro']) . "', meta_title = '" . $this->db->escape($meta_title) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['news_store'])) {
            foreach ($data['news_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store3 SET news3_id = '" . (int) $news3_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        // SEO URL
        if (isset($data['news3_seo_url'])) {
            foreach ($data['news3_seo_url'] as $store_id => $language) {
                foreach ($language as $language_id => $keyword) {
                    if (!empty($keyword)) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int) $store_id . "', language_id = '" . (int) $language_id . "', query = 'news3_id=" . (int) $news3_id . "', keyword = '" . $this->db->escape($keyword) . "'");
                    }
                }
            }
        }

        if (isset($data['news_layout'])) {
            foreach ($data['news_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "news_to_layout3 SET news3_id = '" . (int) $news3_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
            }
        }

        //$this->seogen->genSeoUrl($news3_id, $data);

        $this->cache->delete('news');

        return $news3_id;
    }

    public function editNews($news3_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "news3 SET sort_order = '" . (int) $data['sort_order'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', status = '" . (int) $data['status'] . "' WHERE news3_id = '" . (int) $news3_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "news_description3 WHERE news3_id = '" . (int) $news3_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "news3 SET image = '" . $this->db->escape($data['image']) . "' WHERE news3_id = '" . (int) $news3_id . "'");
        }
        
        if (isset($data['image_adaptive'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "news3 SET image_adaptive = '" . $this->db->escape($data['image_adaptive']) . "' WHERE news3_id = '" . (int) $news3_id . "'");
        }
        if (isset($data['image_inner'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "news3 SET image_inner = '" . $this->db->escape($data['image_inner']) . "' WHERE news3_id = '" . (int) $news3_id . "'");
        }
        if (isset($data['image_inner_adaptive'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "news3 SET image_inner_adaptive = '" . $this->db->escape($data['image_inner_adaptive']) . "' WHERE news3_id = '" . (int) $news3_id . "'");
        }
        
        foreach ($data['news_description'] as $language_id => $value) {
            $meta_title = '';

            if (!empty($value['meta_title'])) {
                $meta_title = $value['meta_title'];
            } else {
                $meta_title = $value['title']; 
            }

            $this->db->query("INSERT INTO " . DB_PREFIX . "news_description3 SET news3_id = '" . (int) $news3_id . "', language_id = '" . (int) $language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', intro = '" . $this->db->escape($value['intro']) . "', meta_title = '" . $this->db->escape($meta_title) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store3 WHERE news3_id = '" . (int) $news3_id . "'");

        if (isset($data['news_store'])) {
            foreach ($data['news_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store3 SET news3_id = '" . (int) $news3_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'news3_id=" . (int) $news3_id . "'");

        if (isset($data['news3_seo_url'])) {
            foreach ($data['news3_seo_url'] as $store_id => $language) {
                foreach ($language as $language_id => $keyword) {
                    if (trim($keyword)) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET store_id = '" . (int) $store_id . "', language_id = '" . (int) $language_id . "', query = 'news3_id=" . (int) $news3_id . "', keyword = '" . $this->db->escape($keyword) . "'");
                    }
                }
            }
        }

        $this->db->query("DELETE FROM `" . DB_PREFIX . "news_to_layout3` WHERE news3_id = '" . (int) $news3_id . "'");

        if (isset($data['news_layout'])) {
            foreach ($data['news_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "news_to_layout3` SET news3_id = '" . (int) $news3_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
            }
        }

        //$this->seogen->genSeoUrl($news3_id, $data);

        $this->cache->delete('news');
    }

    public function deleteNews($news3_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "news3` WHERE news3_id = '" . (int) $news3_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "news_description3` WHERE news3_id = '" . (int) $news3_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "news_to_store3` WHERE news3_id = '" . (int) $news3_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "news_to_layout3` WHERE news3_id = '" . (int) $news3_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'news3_id=" . (int) $news3_id . "'");

        $this->cache->delete('news');
    }

    public function getNewsItem($news3_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news3 WHERE news3_id = '" . (int) $news3_id . "'");

        return $query->row;
    }

    public function getNews($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "news3 i LEFT JOIN " . DB_PREFIX . "news_description3 id ON (i.news3_id = id.news3_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "'";

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
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news3 i LEFT JOIN " . DB_PREFIX . "news_description3 id ON (i.news3_id = id.news3_id) WHERE id.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY id.title");

                $news_data = $query->rows;

                $this->cache->set('news.' . (int) $this->config->get('config_language_id'), $news_data);
            }

            return $news_data;
        }
    }

    public function getNewsDescriptions($news3_id) {
        $news_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_description3 WHERE news3_id = '" . (int) $news3_id . "'");

        foreach ($query->rows as $result) {
            $news_description_data[$result['language_id']] = array(
                'title' => $result['title'],
                'description' => $result['description'],
                'intro' => $result['intro'],
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword']
            );
        }

        return $news_description_data;
    }

    public function getNewsStores($news3_id) {
        $news_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_store3 WHERE news3_id = '" . (int) $news3_id . "'");

        foreach ($query->rows as $result) {
            $news_store_data[] = $result['store_id'];
        }

        return $news_store_data;
    }

    public function getNewsSeoUrls($news3_id) {
        $news3_seo_url_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'news3_id=" . (int) $news3_id . "'");

        foreach ($query->rows as $result) {
            $news3_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
        }

        return $news3_seo_url_data;
    }

    public function getNewsLayouts($news3_id) {
        $news_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_layout3 WHERE news3_id = '" . (int) $news3_id . "'");

        foreach ($query->rows as $result) {
            $news_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $news_layout_data;
    }

    public function getTotalNews() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news3");

        return $query->row['total'];
    }

    public function getTotalNewsByLayoutId($layout_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news_to_layout3 WHERE layout_id = '" . (int) $layout_id . "'");

        return $query->row['total'];
    }

}

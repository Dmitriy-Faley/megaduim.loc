<?php
class ControllerMTCommonFooter extends Controller {
	public function index() {
		$data = $this->cache->get('footer');
		if ($data) return $data;

		$this->load->model('catalog/information');

		$data = [];

		$data['checkout'] = $this->url->link('checkout/cart', '', true);

		$this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');
		foreach ($data as $index => $d) {
			if (is_string($d))
				$data[$index] = html_entity_decode($d, ENT_QUOTES, 'UTF-8');
		}

		$informations = $this->model_catalog_information->getInformations();
		$data['informations'] = [];
		foreach ($informations as $index => $information) {
			if (isset($information['header']) && $information['header']) {
				$data['informations'][$index] = $information;
				$data['informations'][$index]['url'] = $this->url->link('information/information', 'information_id=' . $information['information_id']);
			}
		}

		$data['theme_mt_footer_copy'] = str_replace('<year>', date('Y'), $data['theme_mt_footer_copy']);

		if (isset($data['theme_mt_footer_payments']) && is_array($data['theme_mt_footer_payments'])) {
			usort($data['theme_mt_footer_payments'], function($a, $b) {
				if (!$a['sort_order']) $a['sort_order'] = 0;
				if (!$b['sort_order']) $b['sort_order'] = 0;
				return $a['sort_order'] - $b['sort_order'];
			});
		}

		$data['main_contact'] = null;
		if (isset($data['theme_mt_contacts']) && is_array($data['theme_mt_contacts'])) {
			usort($data['theme_mt_contacts'], function($a, $b) {
				if (!$a['sort_order']) $a['sort_order'] = 0;
				if (!$b['sort_order']) $b['sort_order'] = 0;
				return $a['sort_order'] - $b['sort_order'];
			});
			foreach ($data['theme_mt_contacts'] as $index => $contact) {
				if (!$contact['status']) {
					unset($data['theme_mt_contacts'][$index]);
					continue;
				}

				$data['theme_mt_contacts'][$index]['text_before_mobile'] = html_entity_decode($contact['text_before_mobile']);
				$data['theme_mt_contacts'][$index]['text_after_mobile'] = html_entity_decode($contact['text_after_mobile']);
				if (isset($contact['phones'])) {
					foreach ($contact['phones'] as $phone_index => $p) {
						$contact['phones'][$phone_index]['text'] = html_entity_decode($p['text']);
					}
					$phones = $contact['phones'];
					usort($phones, function($a, $b) {
						if (!$a['sort_order']) $a['sort_order'] = 0;
						if (!$b['sort_order']) $b['sort_order'] = 0;
						return $a['sort_order'] - $b['sort_order'];
					});
					$data['theme_mt_contacts'][$index]['phones'] = $phones;
				}
				if (isset($contact['messengers'])) {
					$messengers = $contact['messengers'];
					usort($messengers, function($a, $b) {
						if (!$a['sort_order']) $a['sort_order'] = 0;
						if (!$b['sort_order']) $b['sort_order'] = 0;
						return $a['sort_order'] - $b['sort_order'];
					});
					$data['theme_mt_contacts'][$index]['messengers'] = $messengers;
				}
			}
			
			$data['main_contact'] = $data['theme_mt_contacts'];
		}

		if (isset($_REQUEST['route'])) $data['route'] = $_REQUEST['route'];
		if (isset($_REQUEST['product_id'])) $data['product_id'] = $_REQUEST['product_id'];
		
		$this->cache->set('footer', $data);
		return $data;
	}
}

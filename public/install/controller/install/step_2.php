<?php
class ControllerInstallStep2 extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('install/step_2');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->response->redirect($this->url->link('install/step_3'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_step_2'] = $this->language->get('text_step_2');
		$data['text_install_php'] = $this->language->get('text_install_php');
		$data['text_install_extension'] = $this->language->get('text_install_extension');
		$data['text_install_file'] = $this->language->get('text_install_file');
		$data['text_install_directory'] = $this->language->get('text_install_directory');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_current'] = $this->language->get('text_current');
		$data['text_required'] = $this->language->get('text_required');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_file'] = $this->language->get('text_file');
		$data['text_directory'] = $this->language->get('text_directory');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_writable'] = $this->language->get('text_writable');
		$data['text_unwritable'] = $this->language->get('text_unwritable');
		$data['text_version'] = $this->language->get('text_version');
		$data['text_global'] = $this->language->get('text_global');
		$data['text_magic'] = $this->language->get('text_magic');
		$data['text_file_upload'] = $this->language->get('text_file_upload');
		$data['text_session'] = $this->language->get('text_session');
		$data['text_db'] = $this->language->get('text_db');
		$data['text_gd'] = $this->language->get('text_gd');
		$data['text_curl'] = $this->language->get('text_curl');
		$data['text_openssl'] = $this->language->get('text_openssl');
		$data['text_zlib'] = $this->language->get('text_zlib');
		$data['text_zip'] = $this->language->get('text_zip');
		$data['text_mbstring'] = $this->language->get('text_mbstring');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('install/step_2');

		// catalog config
		if (!is_file(DIR_OPENCART . 'config.php')) {
			$data['error_catalog_config'] = $this->language->get('error_missing');
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$data['error_catalog_config'] = $this->language->get('error_unwritable');
		} else {
			$data['error_catalog_config'] = '';
		}

		// admin configs
		if (!is_file(DIR_OPENCART . 'admin/config.php')) {
			$data['error_admin_config'] = $this->language->get('error_missing');
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$data['error_admin_config'] = $this->language->get('error_unwritable');
		} else {
			$data['error_admin_config'] = '';
		}
 
		if (!is_writable(DIR_OPENCART . 'image/')) {
			$data['error_image'] = $this->language->get('error_unwritable');
		} else {
			$data['error_image'] = '';
		}
		
		if (!is_writable(DIR_OPENCART . 'image/cache/')) {
			$data['error_image_cache'] = $this->language->get('error_unwritable');
		} else {
			$data['error_image_cache'] = '';
		}

		if (!is_writable(DIR_OPENCART . 'image/catalog/')) {
			$data['error_image_catalog'] = $this->language->get('error_unwritable');
		} else {
			$data['error_image_catalog'] = '';
		}

		if (!is_writable(DIR_STORAGE . 'cache/')) {
			$data['error_cache'] = $this->language->get('error_unwritable');
		} else {
			$data['error_cache'] = '';
		}

		if (!is_writable(DIR_STORAGE . 'logs/')) {
			$data['error_logs'] = $this->language->get('error_unwritable');
		} else {
			$data['error_logs'] = '';
		}

		if (!is_writable(DIR_STORAGE . 'download/')) {
			$data['error_download'] = $this->language->get('error_unwritable');
		} else {
			$data['error_download'] = '';
		}

		if (!is_writable(DIR_STORAGE . 'upload/')) {
			$data['error_upload'] = $this->language->get('error_unwritable');
		} else {
			$data['error_upload'] = '';
		}

		if (!is_writable(DIR_STORAGE . 'modification/')) {
			$data['error_modification'] = $this->language->get('error_unwritable');
		} else {
			$data['error_modification'] = '';
		}

		$data['php_version'] = phpversion();
		$data['register_globals'] = ini_get('register_globals');
		$data['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
		$data['file_uploads'] = ini_get('file_uploads');
		$data['session_auto_start'] = ini_get('session_auto_start');

		$db = array(
			'mysqli',
			'pgsql',
			'pdo'
		);

		if (!array_filter($db, 'extension_loaded')) {
			$data['db'] = false;
		} else {
			$data['db'] = true;
		}

		$data['gd'] = extension_loaded('gd');
		$data['curl'] = extension_loaded('curl');
		$data['openssl'] = function_exists('openssl_encrypt');
		$data['zlib'] = extension_loaded('zlib');
		$data['zip'] = extension_loaded('zip');
		$data['iconv'] = function_exists('iconv');
		$data['mbstring'] = extension_loaded('mbstring');

		$data['catalog_config'] = DIR_OPENCART . 'config.php';
		$data['admin_config'] = DIR_OPENCART . 'admin/config.php';
		$data['image'] = DIR_OPENCART . 'image';
		$data['image_cache'] = DIR_OPENCART . 'image/cache';
		$data['image_catalog'] = DIR_OPENCART . 'image/catalog';
		$data['cache'] = DIR_STORAGE . 'cache';
		$data['logs'] = DIR_STORAGE . 'logs';
		$data['download'] = DIR_STORAGE . 'download';
		$data['upload'] = DIR_STORAGE . 'upload';
		$data['modification'] = DIR_STORAGE . 'modification';

		$data['back'] = $this->url->link('install/step_1');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('install/step_2', $data));
	}

	private function validate() {
		if (phpversion() < '8.2') {
			$this->error['warning'] = $this->language->get('error_version');
		}

		if (!ini_get('file_uploads')) {
			$this->error['warning'] = $this->language->get('error_file_upload');
		}

		if (ini_get('session.auto_start')) {
			$this->error['warning'] = $this->language->get('error_session');
		}

		$db = array(
			'mysqli', 
			'pdo', 
			'pgsql'
		);

		if (!array_filter($db, 'extension_loaded')) {
			$this->error['warning'] = $this->language->get('error_db');
		}

		if (!extension_loaded('gd')) {
			$this->error['warning'] = $this->language->get('error_gd');
		}

		if (!extension_loaded('curl')) {
			$this->error['warning'] = $this->language->get('error_curl');
		}

		if (!function_exists('openssl_encrypt')) {
			$this->error['warning'] = $this->language->get('error_openssl');
		}

		if (!extension_loaded('zlib')) {
			$this->error['warning'] = $this->language->get('error_zlib');
		}

		if (!extension_loaded('zip')) {
			$this->error['warning'] = $this->language->get('error_zip');
		}

		if (!function_exists('iconv') && !extension_loaded('mbstring')) {
			$this->error['warning'] = $this->language->get('error_mbstring');
		}

		if (!file_exists(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = $this->language->get('error_catalog_exist');
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = $this->language->get('error_catalog_writable');
		}

		if (!file_exists(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = $this->language->get('error_admin_exist');
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = $this->language->get('error_admin_writable');
		}

		if (!is_writable(DIR_OPENCART . 'image')) {
			$this->error['warning'] = $this->language->get('error_image');
		}

		if (!is_writable(DIR_OPENCART . 'image/cache')) {
			$this->error['warning'] = $this->language->get('error_image_cache');
		}

		if (!is_writable(DIR_OPENCART . 'image/catalog')) {
			$this->error['warning'] = $this->language->get('error_image_catalog');
		}

		if (!is_writable(DIR_STORAGE . 'cache')) {
			$this->error['warning'] = $this->language->get('error_cache');
		}

		if (!is_writable(DIR_STORAGE . 'logs')) {
			$this->error['warning'] = $this->language->get('error_log');
		}

		if (!is_writable(DIR_STORAGE . 'download')) {
			$this->error['warning'] = $this->language->get('error_download');
		}

		if (!is_writable(DIR_STORAGE . 'upload')) {
			$this->error['warning'] = $this->language->get('error_upload');
		}

		if (!is_writable(DIR_STORAGE . 'modification')) {
			$this->error['warning'] = $this->language->get('error_modification');
		}

		return !$this->error;
	}
}

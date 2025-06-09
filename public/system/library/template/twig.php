<?php

namespace Template;
final class Twig {
	private $twig;
	private $data = array();
	
	public function __construct() {
		// include and register Twig auto-loader
		/* include_once(DIR_SYSTEM . 'library/template/Twig/Autoloader.php');
		
		\Twig_Autoloader::register(); */
	}
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($template, $cache = false) {
		// specify where to look for templates
		$loader = new \Twig\Loader\FilesystemLoader(DIR_TEMPLATE);
		
		$config = array('autoescape' => false);

		if ($cache) {
			$config['cache'] = DIR_CACHE;
		}
		$twig = new \Twig\Environment($loader, $config);
		
		if(is_file(DIR_TEMPLATE . $template . '.twig')){
			return $twig->render($template . '.twig', $this->data);
		} else {
			trigger_error('Error: Could not load template ' . $template . '!');
			exit();	
		}
	}	
}

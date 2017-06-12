<?php
if (!defined('MODX_BASE_PATH')) { die('What are you doing? Get out of here!'); }

class shareSnippet
{
	public $basePath        = '';
	public $baseUrl         = '';
	public $config          = array();
	public $placeholders    = array();
	public $templates       = array();
	public $lang            = array();
	public $style           = array();
	
	public function __construct($config)
	{
		$this->basePath = MODX_BASE_PATH.'assets/snippets/shareSnippet/';
		$this->baseUrl  = MODX_BASE_URL .'assets/snippets/shareSnippet/';
		
		// Prepare config
		$this->config   = array(
			'get'               => $config['get'],
			'platforms'         => explode(',', $config['platforms']),
			'twitter_handle'    => $config['twitter_handle'],
			
			'autoSummary'       => $config['autoSummary'],
			'summaryLength'     => $config['summaryLength'],
			
			'style'             => $config['style'],
			'outerTpl'          => $config['outerTpl'],
			'rowTpl'            => $config['rowTpl'],
			
			'lang'              => $config['lang'],
		);

		// Convert placeholders
		$this->placeholders = array(
			'title'         => urlencode($config['title']),
			'url'           => urlencode($config['url']),
			'summary'       => urlencode($config['summary']),
			'image'         => urlencode($config['image']),
			'description'   => urlencode($config['description']),
			'target'        => $config['target']
		);

		$this->getLanguage($this->config['lang']);
		$this->getStyle($this->config['style']);
	}

	public function output()
	{
		switch($this->config['get']) {
			case 'button':
				return $this->renderShareButton(reset($this->config['platforms']));
			case 'list':
				return $this->renderShareButtonsList();
			default:
				return 'Get-Parameter unknown: '. $this->config['get'];
		}
	}

	public function renderShareButtonsList()
	{
		$outerTpl = $this->getTemplate($this->config['outerTpl']);
		$rowTpl   = $this->getTemplate($this->config['rowTpl']);
		
		$wrapper = '';
		foreach($this->config['platforms'] as $platform) {
			
			$button = $this->renderShareButton($platform);
			
			// Wrap button inside rowTpl
			$wrapper  .= str_replace('[+wrapper+]', $button, $rowTpl);
		}

		// Wrap rows inside outerTpl
		return str_replace('[+wrapper+]', $wrapper, $outerTpl);
	}
	
	public function renderShareButton($platform)
	{
		$button   = $this->getTemplate('share.'.$platform);
		
		$phs = $this->placeholders;
		$phs['class'] = isset($this->style[$platform]) ? 'class="'.$this->style[$platform].'" ' : ''; 
			
		$button   = $this->parsePlaceholders($button, $phs);
		$button   = $this->parseLanguage($button);
		return $button;
	}
	
	public function renderOpenGraph() { 
		return 'renderOpenGraph() not ready';
	}
	
	public function renderRichSnippet() {
		return 'renderRichSnippet() not ready';
	}

	public function getTemplate($name) {
		global $modx;
		
		if(!isset($this->templates[$name])) {
			// Try to load Chunk first
			$tpl = $modx->getChunk($name);
			if(!empty($tpl)) {
				$this->templates[$name] = $tpl;
			
			// No Chunk found, try to load from shareSnippet/tpl/custom/$name.tpl  and shareSnippet/tpl/$name.tpl
			} else {
				if (is_readable($this->basePath . 'tpl/custom/' . $name . '.tpl')) {
					$this->templates[$name] = file_get_contents($this->basePath . 'tpl/custom/' . $name . '.tpl');
				} else if (is_readable($this->basePath . 'tpl/' . $name . '.tpl')) {
					$this->templates[$name] = file_get_contents($this->basePath . 'tpl/' . $name . '.tpl');
				} else {
					$this->templates[$name] = 'Template/Chunk not readable: ' . $name;
				}
			}
		}
		
		return $this->templates[$name];
	}

	public function getLanguage($name)
	{
		$lang = array();
		
		// Load english as fallback
		if(file_exists($this->basePath . 'lang/custom/english.inc.php')) require($this->basePath . 'lang/custom/english.inc.php');
		else require($this->basePath . 'lang/english.inc.php');
		
		// Now overwrite custom language
		if($name != 'english') {
			if (file_exists($this->basePath . 'lang/custom/' . $name . '.inc.php')) {
				include($this->basePath . 'lang/custom/' . $name . '.inc.php');
			} else if (file_exists($this->basePath . 'lang/' . $name . '.inc.php')) {
				include($this->basePath . 'lang/' . $name . '.inc.php');
			}
		}
		
		$this->lang = $lang;
	}

	public function getStyle($style)
	{
		$buttons = array();
		if(file_exists($this->basePath . 'style/style.'.$style.'.inc.php')) include($this->basePath . 'style/style.'.$style.'.inc.php');
		$this->style = $buttons;
	}
	
	public function parsePlaceholders($string, $phs)
	{
		foreach($phs as $key=>$val) {
			$string = str_replace('[+'. $key .'+]', $val, $string);
		}
		return $string;
	}

	public function parseLanguage($string)
	{
		foreach($this->lang as $key=>$val) {
			$string = str_replace('[%'. $key .'%]', $val, $string);
		}
		return $string;
	}
}
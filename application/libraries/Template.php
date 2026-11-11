<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {

	/** @var array */
	public $template_data = array();

	/** @var CI_Controller dideklarasikan agar tidak menjadi dynamic property (PHP 8.2+) */
	protected $CI;

	public function set($name, $value)
	{
		$this->template_data[$name] = $value;
	}

	public function load($template = '', $view = '', $view_data = array(), $return = FALSE)
	{
		$this->CI =& get_instance();

		// ambil konten yang sudah di-echo controller (modal, dsb.) untuk dicetak di dalam layout
		$pre = '';
		if ( ! empty($this->CI->top_ob_level) && ob_get_level() >= $this->CI->top_ob_level && ob_get_length()) {
			$pre = ob_get_contents();
			ob_clean();
		}
		$this->set('pre_content', $pre);
		$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
		return $this->CI->load->view($template, $this->template_data, $return);
	}
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */

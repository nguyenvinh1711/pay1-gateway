<?php
/**
 * 
 * 
 * @author vinhsmile
 * @since  1.0.0
 */

namespace pay1;

class Page
{
	
	protected $args;

	public function __construct($args)
	{
		
		$this->args = $args;
		$this->createPage();
	}

	public function createPage()
	{
		return wp_insert_post($this->args);
	}
}
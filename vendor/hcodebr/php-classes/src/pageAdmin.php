<?php 

namespace Hcode;
use Rain\Tpl;

/**
 * 
 */
class PageAdmin extends Page
{
	public function __construct($opts = array(), $tlp_dir = "/views/admin/"){

		parent::__construct($opts, $tlp_dir);
	}
}
?>
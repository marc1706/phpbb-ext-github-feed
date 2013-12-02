<?php
/**
*
* @package Github Feed v0.1.0
* @copyright (c) 2013 Marc Alexander ( www.m-a-styles.de )
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace marc\githubfeed\controller;

class main
{
	public function __construct($template)
	{
		$this->template = $template;
	}

	public function handle()
	{
		$curl_handle = curl_init('https://api.github.com/repos/board3/Board3-Portal/commits');
		$options = array(
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HTTPHEADER	=> array('User-Agent: Github Feed'),
		);

		// Setting curl options
		curl_setopt_array($curl_handle, $options);

		$result = curl_exec($curl_handle);
		$this->template->assign_vars(array(
			'GITHUB_FEED_DATA'	=> print_r(json_decode($result), true),
		));
		curl_close($curl_handle);

		page_header('Github Feed');

		// foobar_body.html is in ./ext/foobar/example/styles/prosilver/template/foobar_body.html
		$this->template->set_filenames(array(
			'body' => 'github_feed_body.html'
		));

		page_footer();
	}
}

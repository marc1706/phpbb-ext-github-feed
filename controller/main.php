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
			CURLOPT_CONNECTTIMEOUT	=> 5,
		);

		// Setting curl options
		curl_setopt_array($curl_handle, $options);

		$result = curl_exec($curl_handle);
		$commit_data = json_decode($result);
		curl_close($curl_handle);
		foreach ($commit_data as $commit)
		{
			if (strpos($commit->commit->message, "\n") !== false)
			{
				$message = explode("\n", $commit->commit->message);
				$commit_title = array_shift($message);
				$commit_message = implode($message, '<br />');
			}
			else
			{
				$commit_title = $commit->commit->message;
				$commit_message = '';
			}

			$this->template->assign_block_vars('github_feed_row', array(
				'AUTHOR'		=> $commit->commit->author->name,
				'AUTHOR_AVATAR'		=> $commit->author->avatar_url,
				'AUTHOR_LINK'		=> $commit->author->html_url,
				'COMMIT_TITLE'		=> $commit_title,
				'COMMIT_MESSAGE'	=> $commit_message,
				'COMMIT_URL'		=> $commit->html_url,
			));
		}

		page_header('Github Feed');

		// foobar_body.html is in ./ext/foobar/example/styles/prosilver/template/foobar_body.html
		$this->template->set_filenames(array(
			'body' => 'github_feed_body.html'
		));

		page_footer();
	}
}

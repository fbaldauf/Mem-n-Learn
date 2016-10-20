<?php

// Quelle: http://www.java4less.com/apache/HTTPPost.txt

class HTTPPost {

	function post_request($host, $port, $template, $data, $referer = '') {

		// open a socket connection on port 80 - timeout: 30 sec
		$fp = fsockopen($host, $port, $errno, $errstr, 30);

		if ($fp) {

			// send the request headers:
			fputs($fp, "POST /J4LFOPServer/servlet?TEMPLATE=$template HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");

			if ($referer != '')
				fputs($fp, "Referer: $referer\r\n");

			fputs($fp, "Content-type: text/xml\r\n");
			fputs($fp, "Content-length: " . strlen($data) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $data);

			$result = '';
			while (!feof($fp)) {
				// receive the results of the request
				$result .= fgets($fp, 128);
			}
		}
		else {
			return array('status' => 'err', 'error' => "$errstr ($errno)");
		}

		// close the socket connection:
		fclose($fp);

		// split the result header from the content
		$result = explode("\r\n\r\n", $result, 2);

		$header = isset($result[0]) ? $result[0] : '';
		$content = isset($result[1]) ? $result[1] : '';

		// return as structured array:
		return $content;

	}

}

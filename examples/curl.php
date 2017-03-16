<?php

/**
 * A basic CURL wrapper.
 * @link http://wiki.secureapi.com.au/index.php?title=CURL_requests
 * @link https://php.net/manual/en/book.curl.php
 *
 * NOTE: If cURL error occurred always throws CurlException and send message to the bug tracker.
 *
 * HOW TO USE BRIEF:
 *      $response = curl::request('GET', 'URL', array('param' => 'value'))->execute();
 *
 *      $response = curl::request('GET', 'URL?param1=value1', array('param2' => 'value2'))->execute();
 *
 *      $response = curl::request('POST', 'URL', array('param' => 'value'))->execute();
 *
 *      $response = curl::request('POST', 'URL', 'RAW DATA')->execute();
 *
 *      $response = curl::request('PUT', 'URL', array('param' => 'value'))->execute();
 *
 *      $response = curl::request('DELETE', 'URL', array('param' => 'value'))->execute();
 *
 *      try {
 *          $response = curl::request('GET', 'get_URL', array('param' => 'value'))->execute();
 *      } catch(CurlException $e) {
 *          $error = $e->getMessage();
 *          $url = $e->getURL();
 *      }
 *
 *      $response = curl::request('GET', $url)
 *          ->set_timeout(60)
 *          ->set_header('Header_Param_Name', 'Header Value')
 *          ->set_parameter(array(
 *              'param_1' => 'value_1',
 *              'param_2' => 'value_2',
 *          ))
 *          ->set_attempts(3)
 *          ->auth('username', 'password', CURLAUTH_DIGEST)
 *          ->execute();
 *
 * 		$curl =  curl::request('GET', $url)
 * 			->set_timeout(60)
 * 			->set_attempts(3);
 * 		$response = $curl->execute();
 * 		$raw_response = $curl->get_raw_response();
 * 		$response_headers = $curl->get_response_headers();
 *
 * 		$xml_rpc_request = xmlrpc_encode_request('method', ['data' => 123456]);
 * 		$xml_rpc_result = curl::request('POST', $url)
 * 			->set_raw_data($xml_rpc_request)
 *			->execute();
 *
 *      curl::request('POST', $url)
 *          ->upload_existing_file('/usr/share/image.jpg')
 *          ->execute();
 *
 *	    curl::request('POST', $url)
 *          ->upload_raw_data_to_file('image.jpg', $raw_data)
 *          ->execute();
 *
 * @use InvalidArgumentException - for development time errors
 * @use BadFunctionCallException - for development time errors
 * @use CurlException - for runtime errors
 **/
require_once(__DIR__ . '/Exception/CurlException.php');

class curl {
	const DEFAULT_TIMEOUT = 30;
	const DEFAULT_MAX_REDIRECTS = 3;
	const MAX_ATTEMPTS = 5; // maximal amount of attempts
	const MAX_DELAY = 10; // maximal amount of the delay between attempts
	const JSON_PATTERN = '~^application/(?:json|vnd\.api\+json)~i';
	const XML_PATTERN = '~^(?:text/|application/(?:atom\+|rss\+)?)xml~i';

	private static $mime_types_extensions = array(
		'js' => 'application/javascript',
		'json' => 'application/json',
		'doc' => 'application/msword',
		'dot' => 'application/msword',
		'pdf' => 'application/pdf',
		'rar' => 'application/rar',
		'rtf' => 'application/rtf',
		'xhtml' => 'application/xhtml+xml',
		'xml' => 'application/xml',
		'zip' => 'application/zip',
		'xls' => 'application/vnd.ms-excel',
		'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pps' => 'application/vnd.ms-powerpoint',
		'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
		'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'7z' => 'application/x-7z-compressed',
		'gif' => 'image/gif',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'jpe' => 'image/jpeg',
		'pcx' => 'image/pcx',
		'png' => 'image/png',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'ico' => 'image/vnd.microsoft.icon',
		'bmp' => 'image/x-ms-bmp',
		'css' => 'text/css',
		'csv' => 'text/csv',
		'html' => 'text/html',
		'htm' => 'text/html',
		'shtml' => 'text/html',
		'asc' => 'text/plain',
		'txt' => 'text/plain',
		'text' => 'text/plain',
	);

	#region Request properties (method, url, options, headers, parameters, attempts, delay)
	/**
	 * Request type:  GET, POST, PUT, DELETE
	 * @var string
	 */
	private $method;

	/**
	 * Base URL, without GET parameters
	 * @var string
	 */
	private $url;

	/**
	 * list of curl options
	 * @var array
	 */
	private $options = array();

	/**
	 * List of request parameters
	 * @var array|string
	 */
	private $parameters = array();

	/**
	 * List of headers
	 * @var array
	 */
	private $headers = array();

	/**
	 * Attempts of sending request if error occurred
	 * @var int
	 */
	private $attempts = 1;

	/**
	 * Delay in seconds between attempts
	 * @var int
	 */
	private $delay = 1;

	/**
	 *  Status - send or not, report of errors
	 * @var boolean
	 */
	private $bug_tracker_enabled = true;
	#endregion

	#region Response properties (response, raw_response, response_headers, content_type)
	/**
	 * Raw response
	 * @var string|null|false
	 */
	private $raw_response;

	/**
	 * Response headers
	 * @var string|null
	 */
	private $response_headers;

	/**
	 * Content type
	 * @var string
	 */
	private $content_type;

	/**
	 * Response HTTP code
	 * @var int
	 */
	private $http_code;

	/**
	 * Response info
	 * @see curl_getinfo
	 * @var array
	 */
	private $response_info;

	/**
	 * Temporary filename for uploads
	 * @var string
	 */
	private $temp_filename;
	#endregion

	/**
	 * Protected constructor for prevent creating instance directly
	 *
	 * @param string $method
	 * @param string $url
	 * @param string|array $parameters
	 */
	protected function __construct($method, $url, $parameters = array()) {
		$this->url = $url;
		$this->method = $method;
		$this->parameters = $parameters;

		$this->options = array(
			CURLOPT_USERAGENT => 'Dreamscape Networks',
			CURLOPT_AUTOREFERER => true, // automatically set the Referer: field in requests where it follows a Location: redirect.
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_FOLLOWLOCATION => true, // to follow any "Location: " header that the server sends as part of the HTTP header
			CURLOPT_MAXREDIRS => self::DEFAULT_MAX_REDIRECTS,
			CURLOPT_TIMEOUT => self::DEFAULT_TIMEOUT,
			CURLINFO_HEADER_OUT => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FAILONERROR => false,
			CURLOPT_HEADER => true,
		);
	}

	/**
	 * Create instance of the class
	 *
	 * @param string $method (GET, POST, PUT or DELETE)
	 * @param string $url
	 * @param string|array $data
	 * 		- string, it will be put as raw POST data (only with POST method)
	 *		- if URL contains GET data, than $data will be add to the URL.
	 * @return self
	 *
	 * @throws InvalidArgumentException
	 * 			- if $url is not a string
	 * 			- if $url is empty string
	 * 			- if $method is not GET, POST, PUT or DELETE or not a string
	 * 			- if method is GET and $url contains parameters in invalid format @see parse_url
	 * 			- if method is not POST and $data is not an array (string allowed only for POST)
	 */
	public static function request($method, $url, $data = array()) {
		// validate method
		if (!is_string($method)) {
			throw new InvalidArgumentException('Method must be a string.');
		}

		$method = strtoupper($method);
		$allow_methods = array('GET', 'POST', 'PUT', 'DELETE', 'PATCH');

		if (!in_array($method, $allow_methods)) {
			throw new InvalidArgumentException('Invalid method: "' . $method . '", allowed: ' . implode(', ', $allow_methods));
		}

		// validate url
		if (!is_string($url) || empty($url)) {
			throw new InvalidArgumentException('URL must be not empty string');
		}

		if ($method == 'GET') {
			if (strpos($url, '?') !== false) {
				// URL already contain parameters, extract parameters from URL
				$url_parsed = parse_url($url);

				if ($url_parsed === false) {
					throw new InvalidArgumentException('URL is invalid: ' . $url);
				}

				$parameters = array();
				parse_str($url_parsed['query'], $parameters);
				// values from $data have higher priority
				$data = array_merge($parameters, $data);
				$url = substr($url, 0, strpos($url, '?'));
			}
		}

		if (!is_array($data) && $method !== 'POST' && $method !== 'PUT' && $method !== 'PATCH' && $method !== 'DELETE') {
			throw new InvalidArgumentException('You can send raw data only for POST, PATCH, DELETE or PUT request.');
		}

		if (!is_array($data)) {
			if (!is_scalar($data)) {
				throw new InvalidArgumentException('Data must be an array or a string.');
			}

			$data = (string)$data;
		}

		$request = new self($method, $url, $data);

		return $request;
	}

	/**
	 * Manually enable bug tracker.
	 *
	 * @return curl
	 */
	public function bug_tracker_enable() {
		$this->bug_tracker_enabled = true;

		return $this;
	}

	/**
	 * Manually disable bug tracker.
	 *
	 * @return curl
	 */
	public function bug_tracker_disable() {
		$this->bug_tracker_enabled = false;

		return $this;
	}

	/**
	 * Returns raw response data
	 *
	 * @return string|null|false
	 * 		- null if request has not been executed
	 * 		- false if request has been failed
	 * 		- string if request has been successful
	 */
	public function get_raw_response() {
		return isset($this->raw_response) ? $this->raw_response : null;
	}

	/**
	 * Returns the parsed response
	 *
	 * @return array|string|int|float|bool|null
	 */
	public function get_parsed_response() {
		return isset($this->raw_response) ? $this->parse_response() : null;
	}

	/**
	 * Returns response headers
	 *
	 * @return string|null
	 * 		- null is request has not been executed or cURL fail
	 *
	 */
	public function get_response_headers() {
		return isset($this->response_headers) ? $this->response_headers : null;
	}

	/**
	 * Returns http code
	 *
	 * @return int|null
	 */
	public function get_http_code() {
		return isset($this->http_code) ? $this->http_code : null;
	}

	/**
	 * Returns response info. If request was not sent yet or error occurred, FALSE will be returned.
	 *
	 * @see curl_getinfo
	 *
	 * @return array|false
	 */
	public function get_response_info() {
		return isset($this->response_info) ? $this->response_info : false;
	}

	/**
	 * Returns full URL of the request or NULL if request has not been executed
	 *
	 * @return string|null
	 */
	public function get_requested_url() {
		return isset($this->options[CURLOPT_URL]) ? $this->options[CURLOPT_URL] : null;
	}

	/**
	 * Collect all data of the request and response, if request was executed
	 *
	 * @return array
	 *		'METHOD' => GET | POST | PUT | DELETE
	 *		'URL' => URL of the request
	 *		'CURL_OPTIONS' => list of curl option names
	 *		'REQUEST_HEADERS' => list of request headers
	 *		'PARAMETERS' => list of request parameters
	 *		'HTTP_CODE' => HTTP Code of the response if request was executed
	 *		'RESPONSE_HEADERS' => Response headers if request was executed
	 *		'RESPONSE_CONTENT_TYPE' => Response header "Content-Type"
	 *		'RAW_RESPONSE' => Raw text of the response
	 */
	public function get_debug_info() {
		// collect all available cURL options
		$constants = get_defined_constants(true);

		$all_options = array();

		foreach ($constants['curl'] as $name => $value) {
			if (strpos($name, 'CURLOPT_') === 0 || strpos($name, 'CURLINFO_') === 0) {
				$all_options[$value] = $name;
			}
		}

		// translate cURL options
		$options = array();

		foreach ($this->options as $option => $value) {
			if (isset($all_options[$option])) {
				$options[$all_options[$option]] = $value;
			} else {
				$options[$option] = $value;
			}
		}

		// collect request and response data
		$data = array(
			'METHOD' => $this->method,
			'URL' => $this->url,
			'CURL_OPTIONS' => $options,
			'REQUEST_HEADERS' => $this->headers,
			'PARAMETERS' => $this->parameters,
			'HTTP_CODE' => $this->http_code,
			'RESPONSE_HEADERS' => $this->response_headers,
			'RESPONSE_CONTENT_TYPE' => $this->content_type,
			'RAW_RESPONSE' => $this->raw_response,
			'CURL_VERSION' => curl_version(),
		);

		return $data;
	}

	/**
	 * Set HTTP Request timeout
	 *
	 * @param int $timeout
	 *
	 * @return self
	 *
	 * @throws InvalidArgumentException
	 *      - timeout less than 1 second
	 */
	public function set_timeout($timeout) {
		if (!is_scalar($timeout)) {
			throw new InvalidArgumentException('Only scalar types allowed as parameters');
		}

		$timeout = (int)$timeout;

		if ($timeout < 1) {
			throw new InvalidArgumentException('Timeout of the request cannot be less than 1 second.');
		}

		$this->options[CURLOPT_TIMEOUT] = $timeout;

		return $this;
	}

	/**
	 * Allow or disallow redirection if response header "Location:" is set
	 *
	 * @param bool $allow
	 * @return self
	 */
	public function allow_redirect($allow = true) {
		$this->set_option(CURLOPT_FOLLOWLOCATION, (bool) $allow);

		return $this;
	}

	/**
	 * Set cURL Option or list of cURL options. http://php.net/manual/en/function.curl-setopt.php
	 *
	 * @param int|array $data
	 * 		- cURL option or array with options in format array('option' => 'value')
	 * @param mixed $value
	 * 		- value of the option or omitted if we pass array of options
	 *
	 * @throws InvalidArgumentException
	 * 			- if $value is not null and $data is an array
	 *			- if $value is null and $data is not an array
	 * 			- if $code less than zero
	 * @return self
	 */
	public function set_option($data, $value = null) {
		if (!is_null($value)) {
			if (is_array($data)) {
				throw new InvalidArgumentException('Option data must be an array or key + value.');
			} else {
				if (!is_scalar($value)) {
					throw new InvalidArgumentException('Option value must be a scalar.');
				}

				$data = array((int)$data => (string)$value);
			}
		} else if (!is_array($data)) {
			throw new InvalidArgumentException('Expected array as data');
		}

		foreach ($data as $key => $value) {
			$key = (int)$key;
			$value = (string)$value;

			if ($key <= 0) {
				throw new InvalidArgumentException('Trying to set wrong cURL option :' . $key);
			}

			$this->options[$key] = $value;
		}

		return $this;
	}

	/**
	 * Set the raw data to the request body
	 *
	 * @param string|int|float|bool $data
	 *      - scalar value to set as the request body
	 *
	 * @return self
	 *
	 * @throws BadMethodCallException
	 * 			- if the method is called for other HTTP methods than POST, PUt or DELETE
	 * @throws InvalidArgumentException
	 * 			- if $data is not scalar
	 */
	public function set_raw_data($data) {
		if ($this->method !== 'POST' && $this->method !== 'PUT' && $this->method !== 'DELETE') {
			throw new BadMethodCallException('Raw data can be only set for POST, PUT and DELETE requests.');
		}

		if (!is_scalar($data)) {
			throw new InvalidArgumentException('The raw data must be a scalar value.');
		}

		$this->parameters = (string) $data;

		return $this;
	}

	/**
	 * Set the request parameter
	 *
	 * @param string|array $data
	 *      - string, name of the request parameter
	 *      - array('key' => 'value')
	 * @param string $value
	 *      value of the request parameter, or omitted
	 *
	 * @return self
	 *
	 * @throws InvalidArgumentException
	 * 			- if $value is not null and $data is an array
	 *			- if $value is null and $data is not an array
	 */
	public function set_parameter($data, $value = null) {
		if (!is_array($this->parameters)) {
			throw new BadMethodCallException('Raw data for POST request is already set and you cannot set named data.');
		}

		if (!is_null($value)) {
			if (is_array($data)) {
				throw new InvalidArgumentException('Parameter data must be an array or key + value.');
			} else {
				if (!is_scalar($value)) {
					throw new InvalidArgumentException('Parameter value must be a scalar.');
				}

				$data = array((string)$data => (string)$value);
			}
		}

		if (!is_array($data)) {
			throw new InvalidArgumentException('Expected array as data');
		}

		foreach ($data as $key => $value) {
			$this->parameters[(string) $key] = (string) $value;
		}

		return $this;
	}

	/**
	 * Set the request header line or list of headers
	 *
	 * @param string|array $data
	 * 		- string, name of the header parameter
	 * 		- array('Header_Name' => 'Header Value',)
	 * @param string $value
	 * 		- value of the header parameter or omitted
	 *
	 * @return self
	 *
	 * @throws InvalidArgumentException
	 * 			- if $value is not null and $data is an array
	 *			- if $value is null and $data is not an array
	 */
	public function set_header($data, $value = null) {
		if (!is_null($value)) {
			if (is_array($data)) {
				throw new InvalidArgumentException('Headers data must be an array or key + value.');
			} else {
				if (!is_scalar($value)) {
					throw new InvalidArgumentException('Header value must be a scalar.');
				}

				$data = array((string)$data => (string)$value);
			}
		}

		if (!is_array($data)) {
			throw new InvalidArgumentException('Expected array as data');
		}

		foreach ($data as $key => $value) {
			$this->headers[(string) $key] = (string) $value;
		}

		return $this;
	}

	/**
	 * Set HTTP username and password and authenticate method
	 *
	 * @param string $username
	 * @param string $password
	 * @param int $type - any of constant: CURLAUTH_BASIC, CURLAUTH_DIGEST, CURLAUTH_GSSNEGOTIATE, CURLAUTH_NTLM, CURLAUTH_ANY, CURLAUTH_ANYSAFE
	 *
	 * @return self
	 *
	 * @throws InvalidArgumentException
	 *          - if username is empty
	 *          - if $type is invalid
	 */
	public function auth($username, $password = '', $type = CURLAUTH_ANY) {
		if (!is_scalar($username) || !is_scalar($password)) {
			throw new InvalidArgumentException('Only scalar types allowed as parameters');
		}

		$username = (string)$username;
		$password = (string)$password;

		if (empty($username)) {
			throw new InvalidArgumentException('Username for cURL auth cannot be empty.');
		}

		if (!in_array($type, array(CURLAUTH_BASIC, CURLAUTH_DIGEST, CURLAUTH_GSSNEGOTIATE, CURLAUTH_NTLM, CURLAUTH_ANY, CURLAUTH_ANYSAFE))) {
			throw new InvalidArgumentException('cURL auth type is invalid.');
		}

		$this->options[CURLOPT_HTTPAUTH] = $type;
		$this->options[CURLOPT_USERPWD] = $username . ':' . $password;

		return $this;
	}

	/**
	 * Set amount of attempts to send request and delay between unsuccessful requests.
	 *
	 * @param int $attempts - amount of attempts if request error occurred, can be 1 to 5.
	 * @param int $delay - delay between attempts in seconds, can be 1 to 10 seconds.
	 *
	 * @return $this
	 *
	 * @throws InvalidArgumentException
	 *      - $attempts less than 1 or more than self::MAX_ATTEMPTS
	 *      - $delay less than 1 or more than self::MAX_DELAY
	 */
	public function set_attempts($attempts, $delay = 1) {
		if (!is_scalar($attempts) || !is_scalar($delay)) {
			throw new InvalidArgumentException('Only scalar types allowed as parameters');
		}

		$attempts = (int)$attempts;
		$delay = (int)$delay;

		if ($attempts < 1 || $attempts > self::MAX_ATTEMPTS) {
			throw new InvalidArgumentException('Wrong amount of attempts, can be from 1 to ' . self::MAX_ATTEMPTS);
		}

		if ($delay < 1 || $delay > self::MAX_DELAY) {
			throw new InvalidArgumentException('Wrong amount of delay between attempts, can be 1 to ' . self::MAX_DELAY . ' seconds.');
		}

		$this->attempts = $attempts;
		$this->delay = $delay;

		return $this;
	}

	/**
	 * Sets the parameters to upload the existing file on the remote server
	 *
	 * @param string $filename - the name (and path) of the existing file on disk to upload
	 * @param string $post_filename - the name of the file when uploaded on the remote server
	 * @param string $content_type - the MIME Type for the file
	 *
	 * @return self
	 */
	public function upload_existing_file($filename, $post_filename = '', $content_type = '') {
		if ($this->method != 'POST') {
			throw new BadMethodCallException('Files must be uploaded using POST method only.');
		}

		if (!is_string($filename) || empty($filename)) {
			throw new InvalidArgumentException('The upload filename cannot be empty.');
		}

		if (!file_exists($filename)) {
			throw new InvalidArgumentException("The file '{$filename}' does not exist.");
		}

		if (empty($post_filename) || !is_string($post_filename)) {
			$post_filename = basename($filename);
		}

		if (empty($content_type) || !is_string($content_type)) {
			$content_type = $this->content_type_by_filename($post_filename);
		}

		// PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
		if (function_exists('curl_file_create')) {
			$data = curl_file_create($filename, $content_type, $post_filename);
		} else {
			$data = "@{$filename};filename={$post_filename};type={$content_type}";
		}

		// allow to use the existing raw data as the file content
		if (!empty($this->parameters) && is_array($this->parameters)) {
			$this->parameters['file'] = $data;
		} else {
			$this->parameters = array('file' => $data);
		}

		$this->temp_filename = $filename;

		return $this;
	}

	/**
	 * Sets the parameters to upload raw data as file on the remote server
	 *
	 * @param string $filename - the filename as it should be on the remote server
	 * @param string $raw_data - the file content, which will be uploaded AS IS
	 * @param string $content_type - the MIME Type of the uploaded file
	 *
	 * @throws InvalidArgumentException
	 * @throws BadMethodCallException
	 * @throws CurlException
	 *
	 * @return self
	 */
	public function upload_raw_data_to_file($filename, $raw_data = '', $content_type = '') {
		if (!is_string($filename) || empty($filename)) {
			throw new InvalidArgumentException('The upload filename cannot be empty.');
		}

		// check if the raw data is provided
		if (empty($raw_data) || !is_string($raw_data)) {
			// use the existing raw data as the file content
			if (!empty($this->parameters) && is_scalar($this->parameters)) {
				$raw_data = (string) $this->parameters;
			} else {
				throw new InvalidArgumentException('The upload data must be a not empty string.');
			}
		}

		if ((!$upload_temp_filename = tempnam(sys_get_temp_dir(), 'upl')) || file_put_contents($upload_temp_filename, $raw_data) === false) {
			$message = "cURL file upload error: cannot write temporary file to '" . sys_get_temp_dir() . "'.";
			$ex = new CurlException($this->method, $this->url, 500, array(), $message);
			$this->log_error($ex);

			throw $ex;
		}

		$this->upload_existing_file($upload_temp_filename, $filename, $content_type);

		return $this;
	}

	/**
	 * Execute Request and Return Result
	 *
	 * @return array|string
	 *
	 * @throws CurlException
	 *          - if at least one curl option is invalid
	 *          - if request failed
	 *          - if parsing the response failed
	 */
	public function execute() {
		// reuse object for sending one more request, clear response data
		if (!is_null($this->raw_response)) {
			unset($this->raw_response, $this->response_headers, $this->content_type, $this->http_code);
		}

		// depending of request type, set specific cURL options, URL of the request, parameters and headers
		$this->init();
		$curl = curl_init();

		if (!curl_setopt_array($curl, $this->options)) {
			$e = new CurlException($this->method, $this->options[CURLOPT_URL], -1, $this->parameters, 'Cannot set cURL options, because one or more options is invalid.');
			$this->log_error($e);

			throw $e;
		}

		$attempts = $this->attempts;

		while ($attempts--) {
			// get response
			try {
				$this->request_attempt($curl);
				curl_close($curl);

				if (isset($this->temp_filename)) {
					if (file_exists($this->temp_filename)) {
						unlink($this->temp_filename);
					}

					unset($this->temp_filename);
				}

				break;
			} catch (CurlException $e) {
				if ($attempts == 0) {
					curl_close($curl);

					if ($this->raw_response === '' || $this->raw_response === false || $e->getHTTPCode() >= 500) {
						// log error and bubble up the exception
						$this->log_error($e);
					}

					throw $e;
				}
			}

			// TODO: maybe increase delay after every attempt on, for example, 30% (copy delay in local var and increase it after every attempt)
			sleep($this->delay);
		}

		// try parse response
		try {
			return $this->parse_response();
		} catch (CurlException $e) {
			// log error and bubble up the exception
			$this->log_error($e);

			throw $e;
		}
	}

	/**
	 * Execute request and check the answer
	 *
	 * @param resource $curl
	 *
	 * @throws CurlException
	 *      - cannot connect to the remote sever
	 *      - response HTTP code is not 200
	 */
	private function request_attempt($curl) {
		$this->raw_response = curl_exec($curl);
		$this->response_info = curl_getinfo($curl);
		$this->http_code = empty($this->response_info['http_code']) ? -1 : (int)$this->response_info['http_code'];

		if ($this->raw_response === false) {
			$error = 'Request failed: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl);

			throw new CurlException($this->method, $this->options[CURLOPT_URL], $this->http_code, $this->parameters, $error);
		}

		// split body and header
		$this->response_headers = substr($this->raw_response, 0, $this->response_info['header_size']);
		$this->response_headers = $this->http_parse_headers($this->response_headers);

		$this->raw_response = trim(substr($this->raw_response, $this->response_info['header_size']));

		//checking of Accept header, because not all API returned actual Content Type
		$this->content_type = isset($this->headers['Accept']) ? $this->headers['Accept'] : '';

		if (empty($this->content_type)) {
			$this->content_type = isset($this->response_info['content_type']) ? $this->response_info['content_type'] : '';
		}

		if ($this->http_code < 200 || $this->http_code >= 400) {
			throw new CurlException($this->method, $this->options[CURLOPT_URL], $this->http_code, $this->parameters);
		}
	}

	/**
	 * Prepare request
	 */
	private function init() {
		switch ($this->method) {
			case 'POST':
			case 'PUT':
			case 'PATCH':
				if ($this->method == 'PUT') {
					$this->options[CURLOPT_CUSTOMREQUEST] = 'PUT';
					// Overrides $_POST with PUT data
					$this->headers['X-HTTP-Method-Override'] = 'PUT';
				} else if ($this->method == 'PATCH') {
					$this->options[CURLOPT_CUSTOMREQUEST] = 'PATCH';
					// Overrides $_POST with PUT data
					$this->headers['X-HTTP-Method-Override'] = 'PATCH';
				} else {
					$this->options[CURLOPT_POST] = true;
				}

				if (is_array($this->parameters) && isset($this->headers['Content-Type']) && $this->headers['Content-Type'] == 'application/json') {
					// request with JSON data. Encode JSON data to string.
					$post_data = json_encode($this->parameters);
				} else {
					$post_data = $this->parameters;
				}

				if (is_array($post_data)) {
					if (!isset($post_data['file'])) {
						$post_data = http_build_query($post_data, null, '&');
					}
				}

				if (!isset($post_data['file'])) {
					$this->headers['Content-Length'] = strlen($post_data);
				}

				$this->options[CURLOPT_POSTFIELDS] = $post_data;
				$this->options[CURLOPT_URL] = $this->url;

				break;
			case 'GET':
				$this->options[CURLOPT_CUSTOMREQUEST] = 'GET';

				if (!empty($this->parameters)) {
					$url = $this->url . '?' . http_build_query($this->parameters, null, '&');
				} else {
					$url = $this->url;
				}

				$this->options[CURLOPT_URL] = $url;

				break;
			case 'DELETE':
				$this->options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
				$this->options[CURLOPT_POSTFIELDS] = is_array($this->parameters) ? http_build_query($this->parameters, null, '&') : $this->parameters;
				$this->options[CURLOPT_URL] = $this->url;

				break;
			default:
				// you will never see this
				throw new InvalidArgumentException('Request is not supported');
		}

		if (!empty($this->headers)) {
			$headers = array();

			foreach ($this->headers as $key => $value) {
				$headers[] = $key . ': ' . $value;
			}

			$this->options[CURLOPT_HTTPHEADER] = $headers;
		}
	}

	/**
	 * Logging error
	 *
	 * @param CurlException $exception
	 */
	private function log_error(CurlException $exception) {
		if ($this->bug_tracker_enabled) {
			//bugtracker::network_error($this, $exception->getMessage());
		}
	}

	/**
	 * @param $xmlObject
	 *
	 * @return array
	 */
	private static function xml2array($xmlObject) {
		$out = array();

		foreach ((array)$xmlObject as $index => $node) {
			if ($index === '@attributes') {
				$result = array();

				foreach ($node as $key => $value ) {
					$result["@$key"] = $value;
				}

				$out += $result;
			} else {
				if (is_object($node)) {
					$result = self::xml2array($node);
				} else if (is_array($node)) {
					$result = array();

					foreach ($node as $key => $value ) {
						$result[$key] = (is_object($value)) ? self::xml2array($value) : $value;
					}
				} else {
					$result = $node;
				}

				$out[$index] = $result;
			}
		}

		return $out;
	}

	/**
	 * Parse Content-Type response header and detect json and xml data type
	 *
	 * @return string
	 */
	private function get_content_type() {
		if (!$this->content_type) {
			return '';
		}

		if (preg_match(self::JSON_PATTERN, $this->content_type)) {
			return 'json';
		} else if (preg_match(self::XML_PATTERN, $this->content_type)) {
			return 'xml';
		}

		return '';
	}

	/**
	 * Returns the content type as set for the filename extension
	 * If the extension is unknown, returns 'application/octet-stream'
	 *
	 * @param $filename - file name for which the content type should be returned
	 * @return string
	 */
	private function content_type_by_filename($filename) {
		$file = new SplFileInfo($filename);
		$ext  = $file->getExtension();

		if (isset(self::$mime_types_extensions[$ext])) {
			return self::$mime_types_extensions[$ext];
		}

		return 'application/octet-stream';
	}

	/**
	 * Parse JSON or XML answer to array.
	 * $this->error will be set if parsing error occurred
	 *
	 * @return mixed
	 *
	 * @throws CurlException
	 *          - if json decode error
	 */
	private function parse_response() {
		$error = 'Undefined response parsing error.';

		switch ($this->get_content_type()) {
			case 'json':
				$response = json_decode($this->raw_response, true);

				if (function_exists('json_last_error')) {
					switch (json_last_error()) {
						case JSON_ERROR_DEPTH:
							$error = 'JSON:The maximum stack depth has been exceeded.';
							break;
						case JSON_ERROR_STATE_MISMATCH:
							$error = 'JSON:Invalid or malformed';
							break;
						case JSON_ERROR_CTRL_CHAR:
							$error = 'JSON:Control character error, possibly incorrectly encoded.';
							break;
						case JSON_ERROR_SYNTAX:
							$error = 'JSON:Syntax error';
							break;
						case JSON_ERROR_UTF8:
							$error = 'JSON:Malformed UTF-8 characters, possibly incorrectly encoded.';
							break;
						case JSON_ERROR_NONE:
						default:
							// success parsed JSON request
							return $response;
					}
				} else if (is_null($response)) {
					// PHP version < 5.3
					$error = 'Error decoding JSON response.';
				}

				break;
			case 'xml':
				$xml_obj = @simplexml_load_string($this->raw_response);

				if ($xml_obj !== false) {
					if ($xml_obj->getName() === 'methodResponse' || $xml_obj->xpath('param/value')) {
						$response = xmlrpc_decode($this->raw_response);

						if (!is_null($response)) {
							if (is_array($response) && xmlrpc_is_fault($response)) {
								$error = 'XML RPC Fail: ' . (!empty($response['faultString']) ? $response['faultString'] : 'faultString is not set.');
							} else {
								// success parsed XML-RPC response
								return $response;
							}
						} else {
							$error = 'Error parsing XML RPC response.';
						}
					} else {
						// success parsed XML response
						return self::xml2array($xml_obj);
					}
				} else {
					$error = 'Error parsing XML response.';
				}

				break;
			default:
				// response is just a string
				return $this->raw_response;
		}

		throw new CurlException($this->method, $this->options[CURLOPT_URL], 200, $this->parameters, $error);
	}

	/**
	 * Parse headers to array
	 *
	 * @param $headers_string
	 *
	 * @return array
	 */
	private function http_parse_headers($headers_string) {
		$headers = array();
		$fields = explode("\r\n", preg_replace('/\r\n[\t ]+/', ' ', $headers_string));

		foreach ($fields as $field) {
			if (preg_match('/([^:]+): (.+)/m', $field, $match)) {
				$match[1] = preg_replace_callback('/(?<=^|[\t -])./', function($matches) { return strtoupper($matches[0]); }, strtolower(trim($match[1])));

				if (isset($headers[$match[1]])) {
					if (is_array($headers[$match[1]])) {
						$i = count($headers[$match[1]]);
						$headers[$match[1]][$i] = $match[2];
					} else {
						$headers[$match[1]] = array($headers[$match[1]], $match[2]);
					}
				} else {
					$headers[$match[1]] = trim($match[2]);
				}
			}
		}

		return $headers;
	}
}

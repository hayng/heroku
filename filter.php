<?php
	class IPFilter{
		public $ip;
		public $range;
		public $valid = true;
		public $filter_type;
		private $headers = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
		
		public function IPFilter(){
			foreach ($this->headers as $header) {
				if(isset($_SERVER[$header]) && !empty($_SERVER[$header])){
					$this->ip = $_SERVER[$header];
				}
			}
		}

		private function setCookie(){
			$host_name = $_SERVER['HTTP_HOST'];
			$dots = explode('.', $host_name);
			$host_name = $dots[count($dots)-2].'.'.$dots[count($dots)-1];
			setcookie('filter', $this->ip, time() + 60*60*24*30, '/', '.'.$host_name, false);
		}

		public function isValid(){
			if(filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false){
				$this->valid = false;
				$this->filter_type = 'validator';
				$this->setCookie();
			}else if(isset($_COOKIE['filter']) && $_COOKIE['filter'] == $this->ip){
				$this->valid = false;
				$this->filter_type = 'cookie';
			}else{
				$this->range = explode('.', $this->ip);
        		$this->range = $this->range[0].'.'.$this->range[1].'.'.$this->range[2];
				$cf = fopen(__DIR__ . '/ranges', 'r');
				$filters = fread($cf, filesize(__DIR__ . '/ranges'));
				$filters = json_decode($filters, true);
				fclose($cf);
				if(isset($filters[$this->range])){
					$this->valid = false;
					$this->filter_type = 'db';
					$this->setCookie();
				}
			}
			return $this->valid;
		}

		public function getORG(){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://ipinfo.io/org');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 1);
			$org = curl_exec($ch);
			curl_close($ch);
			return $org;
		}
	}
?>
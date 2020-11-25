<?
//유틸성 함수 모움

if (!function_exists('getallheaders'))
{
	function getallheaders()  //아파치가 아닌경우 이 함수가 없다.
	{
		foreach ($_SERVER as $name => $value)
		{
			if (substr($name, 0, 5) == 'HTTP_')
			{
				$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
				$headers[$name] = $value;
			} else if ($name == "CONTENT_TYPE") {
				$headers["Content-Type"] = $value;
			} else if ($name == "CONTENT_LENGTH") {
				$headers["Content-Length"] = $value;
			}
		}
		return $headers;
	}
}

if (!function_exists('gzdecode') && function_exists('gzinflate'))
{
	function gzdecode($data)
	{
	   return gzinflate(substr($data,10,-8));
	}
}

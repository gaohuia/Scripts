<?php

/** The file you wish to be uploaded **/
$file = "/Users/tom/images/time9.bmp";

/** The target URL this form should be posted to **/
$uri = "/server.php";

/** The ip address of the server */
$serverAddress = "127.0.0.1:8080";
$host = "www.test.com";

$boundary = "----WebKitFormBoundary0mrxZMmUvxkrafHd";
$contentType = "multipart/form-data; boundary=" . $boundary;

$post = [
  "--$boundary",
  'Content-Disposition: form-data; name="image"; filename="1.jpg"',
  'Content-Type: image/jpg',
  '',
  file_get_contents($file),
  "--{$boundary}--",
  ''
];

$data = implode("\r\n", $post);
$contentLength = strlen($data);

$headers = [
  "POST {$uri} HTTP/1.1",
  "Host: {$host}",
  // 'Accept: */*',
  // 'Cookie: SESSID=xxxxxxxxxxxx',
  // 'Referer: http://test.com/',
  // 'Origin: http://test.com',
  'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36',
  "Content-Type: {$contentType}",
  "Content-Length: {$contentLength}",
  "Connection: close",
];

$out = implode("\r\n", $headers);
$out .= "\r\n\r\n";
$out .= $data;

echo $out;

$socket = stream_socket_client($serverAddress);
if (!is_resource($socket)) {
  die("Failed to connect to target: " . $serverAddress);
}

fwrite($socket, $out);
while (!feof($socket)) {
  $data = fread($socket, 1024);
  echo $data;
}

fclose($socket);


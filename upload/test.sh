#!/bin/sh

php -S 127.0.0.1:8080 > /dev/null 2>&1 &
pid=$!
echo "server pid: " $pid

php multipart_file_upload_with_raw_socket.php

kill ${pid}


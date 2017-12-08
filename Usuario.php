<?php
	error_reporting(E_ALL);

	set_time_limit(0);
	ob_implicit_flush();
	$address = '192.168.0.105';
	$port = 8880;
    // Avisos na interação
	if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) 
	{
    	echo "Falha socket_create" . socket_strerror(socket_last_error()) . "\n";
	}
	if (socket_bind($sock, $address, $port) === false) 
	{
    	echo "Falha socket_bind" . socket_strerror(socket_last_error($sock)) . "\n";
	}
	if (socket_listen($sock, 5) === false) 
	{
    	echo "Falha socket_listen" . socket_strerror(socket_last_error($sock)) . "\n";
	}
	do {
    	if (($msgsock = socket_accept($sock)) === false) 
    	{
        	echo "Falha socket_accept" . socket_strerror(socket_last_error($sock)) . "\n";
        	break;
    	}
    	$msg = "\Lerdones\n"; 
    	socket_write($msgsock, $msg, strlen($msg));
    	do 
    	{
        	if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) 
        	{
            	echo "Falha socket_read" . socket_strerror(socket_last_error($msgsock)) . "\n";
            	break 2;
        	}
        	$values = "'".implode("', '", explode('|', $buf))."'";
        	$conn = mysqli_connect(localhost, root, null, sockets) or die(mysqli_connect_error());
        	$query = "INSERT INTO user (login, password) VALUES ({$values})";
        	$result =  mysqli_query($conn, $query) or die(mysqli_error($link));

        	if($result === false)
        	{
        		$talkback = "Falha na conexão";
        	}
        	else
        	{
        		$talkback = "Usuario registrado"
        	}
        	socket_write($msgsock, $talkback, strlen($talkback));
    	} while (true);
        socket_close($msgsock);
	   } while (true);
	   socket_close($sock);
?>
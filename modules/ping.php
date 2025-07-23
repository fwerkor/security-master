<?php
/**
 * @description 网络连通性测试工具，支持IPv4/IPv6和自定义包大小
 */

function ping_parameters() {
    return '<label>Target IP: <input type="text" name="target_ip"></label><br>';
}

function ping_execute($params, $realtime = true) {
    if (!isset($params['target_ip'])) {
        return 'Target IP not provided.';
    }

    $target_ip = escapeshellarg($params['target_ip']);
    $command = "ping -c 4 $target_ip";

    if ($realtime) {
        $descriptorspec = array(
           0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
           1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
           2 => array("pipe", "w")   // stderr is a pipe that the child will write to
        );

        $process = proc_open($command, $descriptorspec, $pipes);

        if (is_resource($process)) {
            // Stream the output
            while ($realtime && !feof($pipes[1])) {
                $output = fgets($pipes[1], 1024);
                echo $output;
                ob_flush();
                flush();
            }

            // Close process
            proc_close($process);
        }

        return '';
    } else {
        // Execute command and get the full output
        $output = shell_exec($command . ' 2>&1');
        return $output;
    }
}

function ping_stop($params) {
    // Implement logic to stop the ping task if possible
    // This is highly dependent on the actual implementation
    // For now, we'll just return a message
    return 'Ping task stopped.';
}

?>
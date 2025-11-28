<?php
function success($data, $message = "Success") {
    return json_encode([
        "status" => "success",
        "message" => $message,
        "data" => $data
    ]);
}

function error($message) {
    return json_encode([
        "status" => "error",
        "message" => $message
    ]);
}
?>

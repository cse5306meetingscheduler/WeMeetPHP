{"changed":true,"filter":false,"title":"create_meeting.php","tooltip":"/create_meeting.php","value":"<?php\n\n$servername = getenv('IP');\n$username = getenv('C9_USER');\n$password = \"\";\n$database = \"c9\";\n/*\n$servername = 'https://omega.uta.edu/myadmin';\n$username = 'sxa6933';\n$password = \"B88KMc5T\";\n$database = \"sxa6933\";\n*/\ntry {\n    echo \"In start\";\n    $conn = new PDO(\"mysql:host=$servername;dbname=$database\", $username, $password);\n    // set the PDO error mode to exception\n    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n    if (isset($_POST['max_ppl']) && isset($_POST['username']) && isset($_POST['meeting_time']) && isset($_POST['meeting_date']) && isset($_POST['location'])) {\n        echo \"in create meeting\";\n        $max_ppl = $_POST['max_ppl'];\n        $meeting_time = $_POST['meeting_time'];\n        $meeting_date = $_POST['meeting_date'];\n        $location = $_POST['location'];\n        //$conn->beginTransaction();\n\t\t$stmt = $conn->prepare(\"select user_id from users where username = '\" . $username . \"'\");\n\t\t$stmt->execute();\n\t\twhile ($row = $stmt->fetch()) {\n            $user_id = $row['user_id'];\n        }\n        echo \"User id = \" . $user_id;\n        $query = \"INSERT INTO group_details (host_id, max_ppl, meeting_date, meeting_time) VALUES ('$user_id', '$max_ppl', '$meeting_date', '$meeting_time')\";\n        $conn->beginTransaction();\n        $result_group = $conn->exec($query);\n        $conn->commit();\n        //$conn->close();\n        echo \"Insterted into group_details\";\n        //$conn->beginTransaction();\n\t\t$stmt = $conn->prepare(\"select group_id from group_details where host_id = '\" . $user_id . \"' and max_ppl = '\" . $max_ppl . \"' and meeting_date = '\" . $meeting_date . \"' and meeting_time = '\" . $meeting_time . \"'\");\n\t\t$stmt->execute();\n\t\t\n\t\twhile ($row = $stmt->fetch()) {\n            $group_id = $row['group_id'];\n        }\n        //$conn->close();\n        echo \"Group id = \" . $group_id;\n        $query = \"INSERT INTO user_group_details (user_id, group_id, location) VALUES ('$user_id', '$group_id', '$location')\";\n        $conn->beginTransaction();\n        $result_user_group = $conn->exec($query);\n        $conn->commit();\n        //$conn->close();\n        if (isset($user_id) and isset($group_id)) {\n            // successfully inserted into database\n        \n            $response[\"success\"] = 1;\n            $response[\"message\"] = \"Your meeting group ID is \" . $group_id;\n \n            // echoing JSON response\n            echo json_encode($response);\n        } else {\n        // failed to insert row\n            $response[\"success\"] = 0;\n            $response[\"message\"] = \"Oops! An error occurred. Meeting was not created.\";\n \n        // echoing JSON response\n            echo json_encode($response);\n        }\n    \n    }\n        \n}\ncatch(PDOException $e)\n    {\n    echo \"Connection failed: \" . $e->getMessage();\n    }\n?>","undoManager":{"mark":99,"position":100,"stack":[[{"group":"doc","deltas":[{"start":{"row":27,"column":24},"end":{"row":27,"column":25},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":21},"end":{"row":27,"column":22},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":22},"end":{"row":27,"column":23},"action":"insert","lines":["="]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":23},"end":{"row":27,"column":24},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":28,"column":9},"end":{"row":29,"column":0},"action":"insert","lines":["",""]},{"start":{"row":29,"column":0},"end":{"row":29,"column":8},"action":"insert","lines":["        "]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":8},"end":{"row":29,"column":9},"action":"insert","lines":["$"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":9},"end":{"row":29,"column":10},"action":"insert","lines":["c"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":10},"end":{"row":29,"column":11},"action":"insert","lines":["o"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":11},"end":{"row":29,"column":12},"action":"insert","lines":["n"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":12},"end":{"row":29,"column":13},"action":"insert","lines":["n"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":13},"end":{"row":29,"column":14},"action":"insert","lines":["-"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":14},"end":{"row":29,"column":15},"action":"insert","lines":[">"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":15},"end":{"row":29,"column":16},"action":"insert","lines":["c"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":16},"end":{"row":29,"column":17},"action":"insert","lines":["l"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":17},"end":{"row":29,"column":18},"action":"insert","lines":["o"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":18},"end":{"row":29,"column":19},"action":"insert","lines":["s"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":19},"end":{"row":29,"column":20},"action":"insert","lines":["e"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":20},"end":{"row":29,"column":22},"action":"insert","lines":["()"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":22},"end":{"row":29,"column":23},"action":"insert","lines":[";"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":32},"end":{"row":27,"column":34},"action":"insert","lines":["[]"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":33},"end":{"row":27,"column":35},"action":"insert","lines":["''"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":34},"end":{"row":27,"column":35},"action":"insert","lines":["u"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":35},"end":{"row":27,"column":36},"action":"insert","lines":["s"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":36},"end":{"row":27,"column":37},"action":"insert","lines":["e"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":37},"end":{"row":27,"column":38},"action":"insert","lines":["r"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":38},"end":{"row":27,"column":39},"action":"insert","lines":["_"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":39},"end":{"row":27,"column":40},"action":"insert","lines":["i"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":40},"end":{"row":27,"column":41},"action":"insert","lines":["d"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":12},"end":{"row":27,"column":28},"action":"remove","lines":["echo \"Row = \" . "]},{"start":{"row":27,"column":12},"end":{"row":27,"column":13},"action":"insert","lines":["$"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":13},"end":{"row":27,"column":14},"action":"insert","lines":["u"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":14},"end":{"row":27,"column":15},"action":"insert","lines":["s"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":15},"end":{"row":27,"column":16},"action":"insert","lines":["e"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":16},"end":{"row":27,"column":17},"action":"insert","lines":["r"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":17},"end":{"row":27,"column":18},"action":"insert","lines":["_"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":18},"end":{"row":27,"column":19},"action":"insert","lines":["i"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":19},"end":{"row":27,"column":20},"action":"insert","lines":["d"]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":20},"end":{"row":27,"column":21},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":21},"end":{"row":27,"column":22},"action":"insert","lines":["="]}]}],[{"group":"doc","deltas":[{"start":{"row":27,"column":22},"end":{"row":27,"column":23},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":39,"column":24},"end":{"row":40,"column":0},"action":"insert","lines":["",""]},{"start":{"row":40,"column":0},"end":{"row":40,"column":8},"action":"insert","lines":["        "]}]}],[{"group":"doc","deltas":[{"start":{"row":40,"column":8},"end":{"row":40,"column":23},"action":"insert","lines":["$conn->close();"]}]}],[{"group":"doc","deltas":[{"start":{"row":44,"column":19},"end":{"row":45,"column":0},"action":"insert","lines":["",""]},{"start":{"row":45,"column":0},"end":{"row":45,"column":2},"action":"insert","lines":["\t\t"]}]}],[{"group":"doc","deltas":[{"start":{"row":45,"column":2},"end":{"row":45,"column":17},"action":"insert","lines":["$conn->close();"]}]}],[{"group":"doc","deltas":[{"start":{"row":45,"column":2},"end":{"row":45,"column":17},"action":"remove","lines":["$conn->close();"]}]}],[{"group":"doc","deltas":[{"start":{"row":49,"column":9},"end":{"row":50,"column":0},"action":"insert","lines":["",""]},{"start":{"row":50,"column":0},"end":{"row":50,"column":8},"action":"insert","lines":["        "]}]}],[{"group":"doc","deltas":[{"start":{"row":50,"column":8},"end":{"row":50,"column":23},"action":"insert","lines":["$conn->close();"]}]}],[{"group":"doc","deltas":[{"start":{"row":46,"column":2},"end":{"row":49,"column":9},"action":"remove","lines":["$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); ","        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $key=>$value) { ","            $group_id = $value;","        }"]},{"start":{"row":46,"column":2},"end":{"row":48,"column":9},"action":"insert","lines":["while ($row = $stmt->fetch()) {","            $user_id = $row['user_id'];","        }"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":13},"end":{"row":47,"column":14},"action":"remove","lines":["u"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":13},"end":{"row":47,"column":14},"action":"remove","lines":["s"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":13},"end":{"row":47,"column":14},"action":"remove","lines":["e"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":13},"end":{"row":47,"column":14},"action":"remove","lines":["r"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":13},"end":{"row":47,"column":14},"action":"insert","lines":["g"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":14},"end":{"row":47,"column":15},"action":"insert","lines":["r"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":15},"end":{"row":47,"column":16},"action":"insert","lines":["o"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":16},"end":{"row":47,"column":17},"action":"insert","lines":["u"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":17},"end":{"row":47,"column":18},"action":"insert","lines":["p"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":30},"end":{"row":47,"column":31},"action":"remove","lines":["u"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":30},"end":{"row":47,"column":31},"action":"remove","lines":["s"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":30},"end":{"row":47,"column":31},"action":"remove","lines":["e"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":30},"end":{"row":47,"column":31},"action":"remove","lines":["r"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":30},"end":{"row":47,"column":31},"action":"insert","lines":["g"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":31},"end":{"row":47,"column":32},"action":"insert","lines":["r"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":32},"end":{"row":47,"column":33},"action":"insert","lines":["o"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":33},"end":{"row":47,"column":34},"action":"insert","lines":["u"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":34},"end":{"row":47,"column":35},"action":"insert","lines":["p"]}]}],[{"group":"doc","deltas":[{"start":{"row":47,"column":30},"end":{"row":47,"column":35},"action":"remove","lines":["group"]},{"start":{"row":47,"column":30},"end":{"row":47,"column":35},"action":"insert","lines":["group"]}]}],[{"group":"doc","deltas":[{"start":{"row":54,"column":24},"end":{"row":55,"column":0},"action":"insert","lines":["",""]},{"start":{"row":55,"column":0},"end":{"row":55,"column":8},"action":"insert","lines":["        "]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":8},"end":{"row":55,"column":9},"action":"insert","lines":["$"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":9},"end":{"row":55,"column":10},"action":"insert","lines":["c"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":10},"end":{"row":55,"column":11},"action":"insert","lines":["o"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":11},"end":{"row":55,"column":12},"action":"insert","lines":["n"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":12},"end":{"row":55,"column":13},"action":"insert","lines":["n"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":13},"end":{"row":55,"column":14},"action":"insert","lines":["-"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":14},"end":{"row":55,"column":15},"action":"insert","lines":[">"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":15},"end":{"row":55,"column":16},"action":"insert","lines":["c"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":16},"end":{"row":55,"column":17},"action":"insert","lines":["l"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":17},"end":{"row":55,"column":18},"action":"insert","lines":["o"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":18},"end":{"row":55,"column":19},"action":"insert","lines":["s"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":19},"end":{"row":55,"column":20},"action":"insert","lines":["e"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":20},"end":{"row":55,"column":22},"action":"insert","lines":["()"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":22},"end":{"row":55,"column":23},"action":"insert","lines":[";"]}]}],[{"group":"doc","deltas":[{"start":{"row":31,"column":2},"end":{"row":31,"column":3},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":31,"column":3},"end":{"row":31,"column":4},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":23,"column":8},"end":{"row":23,"column":9},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":23,"column":9},"end":{"row":23,"column":10},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":8},"end":{"row":29,"column":9},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":9},"end":{"row":29,"column":10},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":40,"column":8},"end":{"row":40,"column":9},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":40,"column":9},"end":{"row":40,"column":10},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":42,"column":8},"end":{"row":42,"column":9},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":42,"column":9},"end":{"row":42,"column":10},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":49,"column":8},"end":{"row":49,"column":9},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":49,"column":9},"end":{"row":49,"column":10},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":8},"end":{"row":55,"column":9},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":55,"column":9},"end":{"row":55,"column":10},"action":"insert","lines":["/"]}]}],[{"group":"doc","deltas":[{"start":{"row":43,"column":83},"end":{"row":43,"column":87},"action":"remove","lines":["host"]},{"start":{"row":43,"column":83},"end":{"row":43,"column":84},"action":"insert","lines":["u"]}]}],[{"group":"doc","deltas":[{"start":{"row":43,"column":84},"end":{"row":43,"column":85},"action":"insert","lines":["s"]}]}],[{"group":"doc","deltas":[{"start":{"row":43,"column":85},"end":{"row":43,"column":86},"action":"insert","lines":["e"]}]}],[{"group":"doc","deltas":[{"start":{"row":43,"column":86},"end":{"row":43,"column":87},"action":"insert","lines":["r"]}]}],[{"group":"doc","deltas":[{"start":{"row":43,"column":82},"end":{"row":43,"column":90},"action":"remove","lines":["$user_id"]},{"start":{"row":43,"column":82},"end":{"row":43,"column":90},"action":"insert","lines":["$user_id"]}]}],[{"group":"doc","deltas":[{"start":{"row":29,"column":8},"end":{"row":35,"column":8},"action":"remove","lines":["//$conn->close();","\t\t//$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);","\t\t//echo $result;","        //foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $key=>$value) { ","        //    $user_id = $value;","        //}","        "]}]}]]},"ace":{"folds":[],"scrolltop":60,"scrollleft":0,"selection":{"start":{"row":29,"column":8},"end":{"row":29,"column":8},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":{"row":4,"state":"php-start","mode":"ace/mode/php"}},"timestamp":1428692897274}
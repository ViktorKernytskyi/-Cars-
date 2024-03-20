<?php

//include('config.php');

?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>SELECT</title>
        <style>
            label {
                display: inline-block;
                width: 170px;
            }
            form > div {
                margin-bottom: 5px;
            }
            td:nth-child(5), td:nth-child(6) {
                text-align: center;
            }
            table {
                border-spacing: 0;
                border-collapse: collapse;
            }
            td, th {
                padding: 10px;
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
    <h2>Команда SELECT</h2>
    <form action="DBcars.php" method="POST">

        <div>
            <label for="min">min:</label>
            <input type="text" id="min" name="min">
        </div>
        <div>
            <label for="max">max:</label>
            <input type="text" id="max" name="max">
        </div>
        <div>
            <label for="sedan">sedan:</label>
            <input type="text" id="sedan" name="sedan">
        </div>
        <div>
            <label for="password">passwd:</label>
            <input type="text" id="password" name="users_password">
        </div>
        <div>
            <label for="LIMIT">LIMIT:</label>
            <input type="number" id="LIMIT" name="LIMIT">
        </div>
        <button type="submit" value="action" name="min_max">
            вивести на екран
        </button>
    </form>
    </body>
    <a href="select.php">Повернутися до пошуку</a><br/><br/>
<?php
//$dbhost="localhost";
//$dbport = "5432";
//$dbuser = "postgres";
//$dbpasswd = "root";
//$dbname = "cars";


//$dbconn3 = pg_connect("host=sheep port=5432 dbname=mary user=lamb password=foo");
////подключиться к базе "mary" на хосте "sheep", используя имя пользователя и пароль

//$dbconn = pg_connect("host=127.0.0.1 port=5432 dbname = cars user=postgres password = root ");

/** @var string название базы данных */
const SELECT_BRAND_FROM_CAR_WHERE_YEAR_BETWEEN_FIRST_AND_SECOND = "select brand from car where year between :first and :second";
$dbname = 'cars';
/** @var string имя пользователя */
$user = 'postgres';
/** @var string пароль пользователя */
$password = 'root';
/** @var string адрес базы данных */
$host = '127.0.0.1';
/** @var int порт доступа к базе данных */
$port = 5432;
/** @var array дополнительные опции соединения с базой данных */
//$options = [];
//$charset = 'utf8';charset=".$charset;
/** @var string формируем dsn для подключения */
$dsn = "pgsql:host=".$host.";port=".$port.";dbname=".$dbname;
/** @var PDO подключение к базе данных */
//var_dump('<pre>',$dsn);
//$connectionString = 'pgsql:host=localhost;port=5432;dbname=cars;user=postgres;password=root';
//var_dump('<pre>',$connectionString);
//try{
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$db = new PDO($dsn, $user,$password, $opt);
/**працює*/
$stmt = $db ->query('SELECT brand FROM car');
var_dump('<pre>',$stmt);
while ($row = $stmt->fetch())
{
    echo $row['brand'] . '<br>'."\n";
}
/** @var brand по model $sql */
$sql = $db->prepare('SELECT brand FROM car WHERE model = ?');
$model = trim($_POST['model']);
$sql->bindParam(1, $model, PDO::PARAM_STR);
$sql->execute();
$sql->setFetchMode(PDO::FETCH_ASSOC);
while($row = $sql->fetch()) {
        echo "Car make : " . $row["brand"] . "<br>";
   }
/**  2. Запит на вибірку з використанням «between....and».*/
$sql = $db->prepare("select brand from car where year between :first and :second");
$first = trim($_POST['first']);
$second = trim($_POST['second']);
$sql->execute(array(':first' => $first, ':second' => $second));
$sql->setFetchMode(PDO::FETCH_ASSOC);
while($row = $sql->fetch()) {
    echo "Car make : " . $row["brand"] . "<br>";
}
/** 3. Запит на вибірку з використанням «in»*/
$sql = $db->prepare("SELECT brand,model FROM car WHERE body_type IN (:cuv, :wagon, :sedan )");

$cuv = trim($_POST['cuv']);
$wagon = trim($_POST['wagon']);
$sedan = trim($_POST['sedan']);
$sql->execute(array('cuv' => $cuv, 'wagon'=>$wagon,'sedan' => $sedan));
$sql->setFetchMode(PDO::FETCH_ASSOC);
while($row = $sql->fetch()) {
    echo "Car make : " . $row["brand"] .' '. $row["model"] ."<br>";
}

/** 8. Запит з функцією «min» або «max»*/
$sql = $db->prepare("SELECT MIN(engine) as min, MAX(engine) as max FROM car");

$sql->execute();

$sql->setFetchMode(PDO::FETCH_ASSOC);

while($row = $sql->fetch()) {
    var_dump($row);
}


/** 15. Запит з використанням INNER JOIN.*/

/** 16. Запит з використанням LEFT JOIN.*/

/** 18. Запит з використанням INNER JOIN і умовою. */

/** 19. Запит з використанням INNER JOIN і умовою LIKE. */
/** 22. Запит з використанням підзапита з використанням (=, <,>). */

/** 25. Запит з використанням підзапита з використанням АNY або SOME. */
//    var_dump ('<pre>', $id, name, email, phone);
 $sql = $conn->prepare("SELECT  id =?, name=?, email=?, phone= ? FROM users WHERE LIMIT 25");
$sql->bind_param('ssss', $id,  $users_name, $users_email, $users_phone);

//var_dump ('<pre>', $users_name, $users_email, $users_phone);

 $sql->bind_result($id, $users_name, $users_email, $users_phone);



     $sql = "SELECT id, name, email, phone, password FROM users LIMIT 25";
    $sql = "SELECT * FROM users  WHERE name='$users_name' ";
     $result = $conn->query($sql);

    if ($sql->execute()) {
       // while ($row = $result->fetch_assoc()) {
        while ($row = $sql->fetch()) {
                      echo "id: " . $row["id"] . " - Name: " . $row["name"] . " - Email: " . $row["email"] . "<br>";

        }
    } else {
        echo "0 results";
    }
    //$conn->close();
    $sql->close();
}

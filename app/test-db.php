<?php

$link = mysqli_connect('193.203.175.55:3306', 'u919907044_testf7p', 'bj^OSAy9#', 'u919907044_testf7p');
if (!$link) {
die('Could not connect: ' . mysqli_error());
}
echo 'Connected successfully';
mysqli_close($link);


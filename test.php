<?php
require "settings/init.php";
?>

<?php
if (!empty($_GET["userName"])&&!empty($_GET["userPassword"])) {
$userName = ($_GET['userName']);
$userPassword = ($_GET['userPassword']);

    echo "Received userName: $userName, userPassword: $userPassword<br>";
} else {
    echo "No username or password received.<br>";
    exit; // Stop execution if no parameters are passed
}
$users = $db->sql("SELECT * FROM users WHERE userName = :userName AND userPassword = :userPassword", [":userName" => $userName, ":userPassword" => $userPassword]);
$user = $users[0]; // Access the first (and presumably only) result
echo $user->userName;
echo $user->userId;

?>
<script>
    const valueName = "Jonatan";
    const valuePass = "12345";
    const valueId = 1;
    fetch(`test.php?userName=${valueName}&userPassword=${valuePass}`)
        .then(response => response.text())
        .then(data => {
            console.log("User Name:", data); // Output the user's name
        })
        .catch(error => console.error("Error:", error));
</script>
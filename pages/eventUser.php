<?php
function editName(){
    if (isset($_POST['editName'])) {
        $newName = $_POST['newName'];
        echo 'document.getElementById("name").value = "'.$newName.'";';
}
}
?>

<?php
// Title Functins V1.0
// That Echo The Page Title In Case The Page,
// Has The Variable $pageTitle And Echo Defult Title For Other Pages
function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'CARMACENTER';
    }
}
/*
*Home Redirect function V1.0
// [This Function Accept Parameters]
*$errorsMsg = Echo The Error Message
*$seconds = seconds Before Redirecting
*/
function redirectHome($errorMsg, $seconds = 3)
{
    echo "<div class='alert alert-danger text-centerr'> $errorMsg</div>";
    echo "<div class='alert alert-info' >You Will Be Redirected To Homepage $seconds seconds</div>";
    header("refresh:$seconds ;url='index.php");
    exit();
}
/*
** Check Items Function V1.0
** Function To check Items Database [ Function Accept Parameter ]
** $select = The Item In Select[Example: user , item , Category]
** $from = The Table To Select From [EXample: Users , IteMs , Categorys]
** $value= The Value Of Select [Example : Osama , Box , Electronic]
*/
function checkItems($select, $from, $value)
{
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $statement->execute(array($value));

    $count = $statement->rowCount();

    return $count;
}
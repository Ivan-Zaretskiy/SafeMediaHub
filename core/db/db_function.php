<?php

function mq($query) {
    global $mysqli;
    try {
        $res = $mysqli->query($query);
    } catch (Exception $exception) {
        $res = false;
    }
    return $res;
}

function mres($string){
    global $mysqli;
    return $mysqli->real_escape_string($string);
}

function getValueQuery($query){
    global $mysqli;
    $mysqli->real_query($query);
    $result = $mysqli->use_result();
    if($result){
        $row = $result->fetch_row();
    }else{
        return $result;
    }
    return $row[0];
}

function getRowQuery($query){
    global $mysqli;

    $mysqli->real_query($query);
    $result = $mysqli->use_result();
    if($result){
        return $result->fetch_assoc();
    }else{
        return $result;
    }
}

function getArrayQuery($query){
    global $mysqli;
    $array = array();
    $mysqli->real_query($query);
    $result = $mysqli->use_result();

    if($result){
        while($row = $result->fetch_array(MYSQLI_ASSOC) ){
            $array[] = $row;
        }
    }
    return $array;
}

function get_insert_id(){
    global $mysqli;
    return $mysqli->insert_id;
}

function getParam($index) {
    return @$_GET[$index];
}

function getSqliError(){
    global $mysqli;
    if(mysqli_errno($mysqli)){
        return mysqli_errno($mysqli).": ".mysqli_error($mysqli);
    }else{
        return false;
    }
}

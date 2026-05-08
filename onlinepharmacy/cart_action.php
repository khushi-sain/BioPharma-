<?php
session_start();

if (isset($_GET['id']) && isset($_GET['action'])) {
    $pid = $_GET['id'];
    $action = $_GET['action'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action == 'add') {
        $_SESSION['cart'][$pid] = (isset($_SESSION['cart'][$pid])) ? $_SESSION['cart'][$pid] + 1 : 1;
    } 
    elseif ($action == 'remove') {
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]--;
            if ($_SESSION['cart'][$pid] <= 0) {
                unset($_SESSION['cart'][$pid]);
            }
        }
    }

    if (isset($_GET['ajax'])) {
        echo json_encode([
            'item_qty' => isset($_SESSION['cart'][$pid]) ? $_SESSION['cart'][$pid] : 0,
            'total_cart_count' => array_sum($_SESSION['cart'])
        ]);
        exit;
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
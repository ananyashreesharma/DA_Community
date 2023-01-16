<?php

function escape($string) {
    // prefer to use htmlspecialchars over htmlentities
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
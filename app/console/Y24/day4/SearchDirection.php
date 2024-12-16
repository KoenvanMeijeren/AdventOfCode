<?php
 namespace App\console\Y24\day4;

// $directions = [
//     [0, 1],  # Right
//     [0, -1],  # Left
//     [1, 0],  # Down
//     [-1, 0],  # Up
//     [1, 1],  # Down-Right
//     [-1, -1],  # Up-Left
//     [1, -1],  # Down-Left
//     [-1, 1],  # Up-Right
// ];

enum SearchDirection {
    case Right;
    case Left;
    case Down;
    case Up;
    case DownRight;
    case DownLeft;
    case UpRight;
    case UpLeft;
}
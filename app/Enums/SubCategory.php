<?php

namespace App\Enums;

enum Subcategory: int {
    // Dairy
    case CHEESE = 1;
    case YOGURT = 2;
    case MILK = 3;
    // Handmade
    case FURNITURE = 10;
    case DECORATION = 11;
    case TOOLS = 12;
    // Universal
    case OTHER = 99;
}
<?php

namespace App\Enums;


enum Role: int {
  case ADMIN = 1;
  case SELLER = 2;
  case BUYER = 3;
}
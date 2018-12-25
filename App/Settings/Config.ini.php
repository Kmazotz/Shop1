<?php

  namespace App\Config;

  final class Config
  {
      public static function GetConfig(): array
      {
        return parse_ini_file( "Settings.ini", true, INI_SCANNER_TYPED );
      }
  }

 ?>

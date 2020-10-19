<?php

namespace Joeyfrich\ImageResizer;

class ImageResizer {
  public static function hashImage(&$raw_image) {
    return hash("sha256", $raw_image);
  }
}

<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.4

 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

function mh_read_module_directory($module_directory_current) {
  global $file_extension;
  $directory_array = array();
  if ($dir = @dir($module_directory_current)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory_current . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    $dir->close();
  }
  return $directory_array;
}

function mh_load_submodules($module_directory_current, $class, $all_modules_sortorder, $installed_modules) {
  $path_submodules = $module_directory_current . $class . '/submodules/';
  $directory_submodules_array = mh_read_module_directory($path_submodules);
  for ($j = 0, $m = sizeof($directory_submodules_array); $j < $m; $j++) {
    $offset = '';
    $file_submodule = $directory_submodules_array[$j];
    $class_submodule = substr($file_submodule, 0, strrpos($file_submodule, '.'));
    if (file_exists($path_submodules . $file_submodule)) {
      include_once($path_submodules . $file_submodule);
    }
    if (mh_class_exists($class_submodule)) {
      $sub_module = $class . '/submodules/' . $class_submodule;
      $file_submodule_path = $class . '/submodules/' . $file_submodule;
      $GLOBALS[$sub_module] = new $class_submodule;
      $offset_submodule = '';
      if ($GLOBALS[$sub_module]->check() < 1) {
        // not installed, push to end
        $offset_submodule = '99999999';
      }

      if ($GLOBALS[$sub_module]->hidden) {
        // submodule is hidden and inactive
        continue;
      }

      if ($GLOBALS[$sub_module]->check() > 0 && !( isset($GLOBALS[$sub_module]->stealth) ? $GLOBALS[$sub_module]->stealth : false )) {
        if ($GLOBALS[$sub_module]->sort_order > 0) {
          $installed_modules[($all_modules_sortorder + $GLOBALS[$sub_module]->sort_order) . $sub_module] = $file_submodule_path;
        } else {
          $installed_modules[$all_modules_sortorder . $sub_module] = $file_submodule_path;
        }
      }

      // $all_modules_sortorder
      if ($GLOBALS[$sub_module]->sort_order > 0) {
        $all_submodules[($all_modules_sortorder + $GLOBALS[$sub_module]->sort_order) . $class_submodule] = $file_submodule_path;
      } else {
        $all_submodules[$all_modules_sortorder . $class_submodule] = $file_submodule_path;
      }
    }
  }
  return array($all_submodules, $installed_modules);
}

function mh_get_class_name($module) {
  if (strrpos($module, '.')) {
    $module = substr($module, 0, strrpos($module, '.'));
  }
  $result = preg_split('#/#', $module);
  return array_pop($result);
}



function mh_get_module_name($module) {
  if (strrpos($module, '.')) {
    $module = substr($module, 0, strrpos($module, '.'));
  }
  $result = preg_split('#/#', $module);
  return array_shift($result);
}


function mh_get_class_path($module) {
  return $module;
}

function mh_load_modules_language_files($module_directory_current, $class, $file_extension) {
  //global $module_directory_current_ws;
  if (file_exists($module_directory_current . $class . '/languages/' . $_SESSION['language'] . $file_extension)) {
    // try to load language file
    include_once($module_directory_current . $class . '/languages/' . $_SESSION['language'] . $file_extension);
  } elseif (file_exists($module_directory_current . $class . '/languages/english' . $file_extension)) {
    // .. or english file as default if available
    include_once($module_directory_current . $class . '/languages/english' . $file_extension);
  } else {
    // no language file found!
  }
}

?>
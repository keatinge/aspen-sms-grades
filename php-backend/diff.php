<?php

// Takes input of two different class strings, returns a well formatted
// Ready to text string that outlines all the changes that occoured
//Example output:
//"PHYSICS grade changed from 99.7 A+ to 99.8 A+
//WORLD THEMES added with grade 90.84 A-"

function get_class_difference($string1, $string2)
{
  $classes1 = explode("\n", $string1);
  $classes2 = explode("\n", $string2); //classes2 is the new one

  $classesOneHashMap = array();
  $classesTwoHashMap = array();

  $newItems = array();

  //build classone class => grade array
  foreach ($classes1 as $currentLine)
  {
    $classItems = get_grade_items($currentLine);
    $className = $classItems[1];
    $classGrade = $classItems[2];

    $classesOneHashMap[$className] = $classGrade;
  }

  //build classtwo class=> grade array
  foreach ($classes2 as $currentLine)
  {
    $classItems = get_grade_items($currentLine);
    $className = $classItems[1];
    $classGrade = $classItems[2];

    $classesTwoHashMap[$className] = $classGrade;
  }

  /* Useful for debugging
  echo "<h1>INITIAL VALUES</h1>";
  print_r($classesOneHashMap);
  echo "<br>";
  print_r($classesTwoHashMap);
  echo "<br><hr>";
  */

  $keys1 = array_keys($classesOneHashMap);
  $keys2 = array_keys($classesTwoHashMap);

  //loop through the classes and look for changes
  //all changes get appened to $newItems
  foreach ($keys2 as $currentKey)
  {
    //a new class was added
    if (!in_array($currentKey, $keys1))
    {
      $pushString = $currentKey . " added with grade " . $classesTwoHashMap[$currentKey];
      array_push($newItems, $pushString);
    }
    else
    {
        //look for change in grades
        $newGrade = $classesTwoHashMap[$currentKey];
        $oldGrade = $classesOneHashMap[$currentKey];

        if ($newGrade !== $oldGrade)
        {
          $pushString = $currentKey . " grade changed from " . $classesOneHashMap[$currentKey] . " to " . $classesTwoHashMap[$currentKey];
          array_push($newItems, $pushString);
        }
    }
  }

  $finalString = implode("\n", $newItems);

  /* Also useful for debugging
  echo "<h1> ENDING VALUES </h1>";
  print_r($newItems);
  echo "<h1> DISPLAY STRING </h1>";
  echo $finalString;
  */

  return $finalString;
}



//gets the grade number and the letter for the class in string form
//get_grade_from_str("PRECALCULUS 2 94.33 A-")[1] -> 94.33
//get_grade_from_str("PRECALCULUS 2 94.33 A-")[2] -> A-
function get_grade_from_str($inputStr)
{
  preg_match("/(\d\d.+) (.+)/", $inputStr, $matches);
  return $matches;
}


//splits the string up into classname and then grade
//get_grade_items("PRECALCULUS 2 94.33 A-")[1] -> PRECALCLUS 2
//get_grade_items("PRECALCULUS 2 94.33 A-")[2] -> 94.33 A-
function get_grade_items($inputStr)
{
  preg_match("/(.+?)  (\d\d.+)/", $inputStr, $matches);
  return $matches;
}



 ?>

<?php


$success_message = false;
$error_message   = false;
$upload_url      ='http://localhost/upload/';

if (isset($_POST['save'])) {

  $image = isset($_FILES['image']) ? $_FILES['image'] : false;
  $url = '';
  $image_sql ='';
  if($image && $image['size'] > 0 ){
    $tmp = $image['tmp_name'];
    move_uploaded_file( $tmp, 'upload/' .$image['name']);
    $url = $upload_url . $image['name'];
    $image_sql = "image = '$url', ";
  }

  $name        = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
  $family      = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
  $gender      = isset($_POST['gender']) ? trim($_POST['gender']) : '';
  $description = isset($_POST['description']) ? trim($_POST['description']) : '';
  $user_id     = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
  $newsletter  = intval(isset($_POST['newsletter'])) ;


  if ($name == '' || $family == '') {
    $error_message = 'Data is empty';

  } else {

    if ($user_id) {
      //Update
      $update_sql = " UPDATE users SET 
      first_name  = '$name' ,
      last_name   = '$family',
      description = '$description',
      $image_sql
      gender      = '$gender',
      newsletter  =  $newsletter
        WHERE ID = $user_id";
      $updated    = db()->exec($update_sql);

      if ($updated) {
        $success_message = 'User' . $user_id . ' Updated';
      } else {
        $error_message = 'Update error';
      }

    } else {
      //Insert user
      $inserted = add_user($name, $family , $url , $description, $gender, $newsletter);

      if ($inserted) {
        $success_message = 'User inserted  to database';
      } else {
        $error_message = 'Insertion error';
      }
    }
  }

}
?>
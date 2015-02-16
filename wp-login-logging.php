<?php
/**
* Plugin Name: WP Login Logging
* Description: Write all login attempts to a logfile by replacing default wp_authenticate_username_password functionality
* Author: https://github.com/tarppa/
* Version: 0.5
* License: GPLv2
*/


//a modified wp_authenticate_username_password function with added logging functionality
function authenticate_and_log( $user, $username, $password ) {
  $attempt = '';
  
  // do the authentication here

  if ( is_a( $user, 'WP_User' ) ) {
    $attempt = 'SUCCESS';
    write_to_log($user->name, $attempt);
    return $user;
  }
  
  if ( empty( $username ) || empty( $password ) ) {
    if ( is_wp_error( $user ) ){
      return $user;
  }
  
    $error = new WP_Error();
    
    if ( empty($username) ){
      $error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));
    }
    if ( empty($password) ){
      $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));
    }

    return $error;
  }
  
  $user = get_user_by('login', $username);

  if ( ! $user ){
    $attempt = 'FAILURE';
    write_to_log( $username, $attempt );
    return new WP_Error( 'invalid_username', sprintf( __( '<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password</a>?' ), wp_lostpassword_url() ) );
  }

  $user = apply_filters( 'wp_authenticate_user', $user, $password );

  if ( is_wp_error($user) ){
    return $user;
  }

  if ( ! wp_check_password($password, $user->user_pass, $user->ID) ){
    $attempt = 'FAILURE';
    write_to_log( $username, $attempt );
    return new WP_Error( 'incorrect_password', sprintf( __( '<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s">Lost your password</a>?' ),$username, wp_lostpassword_url() ) );
  }
  
  $attempt = 'SUCCESS';
  write_to_log( $username, $attempt );
  return $user;
}

// write data to log
function write_to_log( $username, $attempt ){
  $timestamp = date( "Y-m-d H:i:s" );
  $log_location = dirname( ini_get( 'error_log' ) );
  $log_line = "{$timestamp} {$username} {$attempt}\n";
  // write to file
  $file_handle = fopen( "$log_location/login.log",'a' );
  fwrite( $file_handle, $log_line );
  fclose( $file_handle );
}

// replace the authentication function with a custom one to optimize the sql queries
remove_filter( 'authenticate', 'wp_authenticate_username_password',  20, 3 );
add_filter( 'authenticate', 'authenticate_and_log',20,3 );

?>

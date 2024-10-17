<?php
function api_usuario_post($request) {
  $email = sanitize_email($request['email']);
  $nome = sanitize_text_field($request['nome']);
  $senha = ($request['senha']);
  $cep = sanitize_text_field($request['cep']);
  $rua = sanitize_text_field($request['rua']);
  $numero = sanitize_text_field($request['numero']);
  
  
  $user_exists = username_exists($email);
  $email_exists = email_exists($email);
  
  if(!$user_exists && !$email_exists && $email && $senha) {
    $user_id = wp_create_user($email, $senha, $email);
    
    $user_data = array(
      'ID' => $user_id,
      'display_name' => $nome,
      'first_name' => $nome,
      'role' => 'subscriber',
    );
    
    wp_update_user($user_data);
    
    update_user_meta($user_id, 'cep', $cep);
    update_user_meta($user_id, 'rua', $rua);
    update_user_meta($user_id, 'numero', $numero);
    
    $response = array(
      'nome' => $nome,
      'email' => $email,
    );
  } else {
    $response = new WP_Error('email', "O email já existe", array('status' => 403));
  }
  
  return rest_ensure_response($response);
}

function registrar_api_usuario_post() {
  register_rest_route('api', '/usuario', array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'api_usuario_post',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_usuario_post');
?>
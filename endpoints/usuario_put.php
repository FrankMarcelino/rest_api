<?php
function api_usuario_put($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {
    $email = sanitize_email($request['email']);
    $nome = sanitize_text_field($request['nome']);
    $senha = sanitize_text_field($request['senha']); // Corrigido
    $cep = sanitize_text_field($request['cep']);
    $rua = sanitize_text_field($request['rua']);
    $numero = sanitize_text_field($request['numero']);

    $email_exists = email_exists($email);

    if(!$email_exists || ($email_exists && $email_exists === $user_id)) {
      $user_data = array(
        'ID' => $user_id,
        'user_email' => $email,
        'user_pass' => $senha,
        'display_name' => $nome,
        'first_name' => $nome,
      );
      wp_update_user($user_data);

      update_user_meta($user_id, 'cep', $cep);
      update_user_meta($user_id, 'rua', $rua);
      update_user_meta($user_id, 'numero', $numero);

      $response = array(
        'nome' => $nome,
        'email' => $email,
        'mensagem' => 'Usuário atualizado com sucesso'
      );
    } else {
      $response = new WP_Error('email', "O email já existe", array('status' => 403));
    }
  } else {
    $response = new WP_Error('permissao', "Usuário não possui permissão", array('status' => 401));
  }

  return rest_ensure_response($response);
}

function registrar_api_usuario_put() {
  register_rest_route('api', '/usuario', array(
    array(
      'methods' => WP_REST_Server::EDITABLE,
      'callback' => 'api_usuario_put',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_usuario_put');
?>
<?php

function api_usuario_post($request) {

  $email = $request['email'];
  $nome = $request['nome'];
  $senha = $request['senha'];
  $rua = $request['rua'];
  $cep = $request['cep'];
  $numero = $request['numero'];
  $bairro = $request['bairro'];
  $cidade = $request['cidade'];
  $estado = $request['estado'];
  
  $response = array(
    'nome' => $nome,
    'email' => $email,
    'rua' => $rua,
    'cep' => $cep,
    'numero' => $numero,
    'bairro' => $bairro,
    'cidade' => $cidade,
    'estado' => $estado

  );

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
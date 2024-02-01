<?php 

function api_usuario_login_post($request) {
    $email = sanitize_text_field($request['email']);
    $senha = $request['senha'];

    $creds = array(
        'user_login'    => $email,
        'user_password' => $senha,
        'remember'      => true,
    );

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        $error_message = $user->get_error_message();
        $response = new WP_Error('login_error', $error_message, array('status' => 403));
    } else {
        // Login bem-sucedido, você pode retornar informações do usuário se necessário
        $response = array(
            'message' => 'Login bem-sucedido',
            'user_id' => $user->ID,
            'display_name' => $user->display_name,
            // Adicione outras informações do usuário conforme necessário
        );
    }

    return rest_ensure_response($response);
}

function registrar_api_usuario_login_post() {
    register_rest_route('api', '/usuario/login', array(
        array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => 'api_usuario_login_post',
        ),
    ));
}

add_action('rest_api_init', 'registrar_api_usuario_login_post');

?>
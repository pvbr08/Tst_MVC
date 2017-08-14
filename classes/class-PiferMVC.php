<?php
/**
 * TutsupMVC - Gerencia Models, Controllers e Views
 *
 * @package TutsupMVC
 * @since 0.1
 */
class PiferQualityMVC
{
        private $controlador;
        private $acao;
        private $parametros;
        private $not_found = '/includes/404.php';
        public function __construct () {


        $this->get_url_data();
        if ( ! $this->controlador ) {
            require_once ABSPATH . '/controllers/home-controller.php';
            $this->controlador = new HomeController();
            $this->controlador->index();
            return;
        }

        if ( ! file_exists( ABSPATH . '/controllers/' . $this->controlador . '.php' ) ) {
            require_once ABSPATH . $this->not_found;
            return;
        }

        require_once ABSPATH . '/controllers/' . $this->controlador . '.php';
        $this->controlador = preg_replace( '/[^a-zA-Z]/i', '', $this->controlador );
        if ( ! class_exists( $this->controlador ) ) {
            require_once ABSPATH . $this->not_found;
            return;
        } // class_exists
        $this->controlador = new $this->controlador( $this->parametros );

        if ( method_exists( $this->controlador, $this->acao ) ) {
            $this->controlador->{$this->acao}( $this->parametros );
            return;
        } // method_exists

        if ( ! $this->acao && method_exists( $this->controlador, 'index' ) ) {
            $this->controlador->index( $this->parametros ); 
            return;
        } // ! $this->acao 

     // Página não encontrada
        require_once ABSPATH . $this->not_found;

     // FIM :)
         return;
    } // __construct

    public function get_url_data () {
        if ( isset( $_GET['path'] ) ) {


            $path = $_GET['path'];
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);
            // Cria um array de parâmetros
            $path = explode('/', $path);

            // Configura as propriedades
            $this->controlador  = chk_array( $path, 0 );
            $this->controlador .= '-controller';
            $this->acao         = chk_array( $path, 1 );

            // Configura os parâmetros
            if ( chk_array( $path, 2 ) ) {
                unset( $path[0] );
                unset( $path[1] );
                $this->parametros = array_values( $path );
            }
        }

    } // get_url_data
 
} // class TutsupMVC

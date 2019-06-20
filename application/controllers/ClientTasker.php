<?php
    class ClientTasker extends CI_Controller {
        public function __construct() {

            parent::__construct();
            $this->load->helper(array('form', 'url'));
            $this->load->helper('array');
            set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . 'third_party/phpseclib');
            include(APPPATH . 'third_party/phpseclib/Net/SSH2.php');
            
        }

        public function index($page = 'ClientTasker') {
            $task = $this->input->post('task');
            $password = $this->input->post('password');
            $clients = $this->input->post('clientCheckbox');
            $server_ip  = $this->input->post('serverIp');
            switch($task) {
                case "reboot":
                $this->reboot($clients, $password);
                    break;
                case "installJcats":
                $this->installJcats($clients, $password, $server_ip);
                    break;
                case "removeJcats":
                $this->removeJcats($clients, $password, $server_ip);
                    break;
                case "changeServer":
                $this->changeServer($clients, $password, $server_ip);
                    break;
                default:
                echo "No recognisible input given";
            }
        }

        public function reboot($clients, $password) {
            foreach($clients as $client) {
                $ssh = new Net_SSH2($client['ip']);
                if (!$ssh->login($client['name'], $password)) {
                    exit('Login Failed');
                }
                $ssh->exec('reboot');
            }

        }

        public function installJcats($client, $password, $server_ip) {
            foreach($clients as $client) {
                $ssh = new Net_SSH2($client['ip']);
                if (!$ssh->login($client['name'], $password)) {
                    exit('Login Failed');
                }
                $ssh->exec('mkdir /home/jcats');
                $ssh->exec('mount '.$server_ip.'://home/jcats');
                $ssh->exec('/home/jcats/jcats14.0/utils/quick_start_client.sh -y');
            }
        }

        public function removeJcats($client, $password) {
            foreach($clients as $client) {
                $ssh = new Net_SSH2($client['ip']);
                if (!$ssh->login($client['name'], $password)) {
                    exit('Login Failed');
                }
                $ssh->exec('/home/jcats/jcats14.0/utils/putback.client -y');
            }
        }

        public function changeServer($client, $password, $server_ip) {
            foreach($clients as $client) {
                $ssh = new Net_SSH2($client['ip']);
                if (!$ssh->login($client['name'], $password)) {
                    exit('Login Failed');
                }
                $ssh->exec('/home/jcats/jcats14.0/utils/change_server.sh ' );
            }
        }
    }
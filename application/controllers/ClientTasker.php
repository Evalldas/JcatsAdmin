<?php
    class ClientTasker extends CI_Controller {
        public function __construct() {

            parent::__construct();
            $this->load->helper(array('form', 'url'));
            $this->load->helper('array');
            $this->load->model('Client_model', 'client_model');
            $this->load->model('Server_model', 'server_model');
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
                //$this->reboot($clients, $password);
                print_r($clients);
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


        public function testReboot(){
            $this->reboot("193.170.9.109", "Dragon01");
        }

        public function reboot() {
            $stations = $this->input->post();
            echo $station;
            foreach ($stations as $station) {
                echo $station;
                if($this->ping($station['ip'])) {
                    $ssh = new Net_SSH2($station['ip']);
                    if (!$ssh->login('root', 'Dragon01')) {
                        exit('Login Failed');
                    }
                    $ssh->exec('reboot');
                    return "success";
                }
                else {
                    return "Could not connect";
                }
            }
        }

         /**
          * updateStationInfo
          *
          * @param  mixed $station_data
          * @param  mixed $password
          *
          * @return void
          */
         public function updateStationInfo($station_data, $password) {
             
             // Variables
             $domain_name = trim($this->getDomainInfo($station_data['ip'], $password));
             $server_data = $this->server_model->getServerByDomainName($domain_name);

             $result = $this->client_model->update([
                'name' => $station_data['name'],
                'ip' => $station_data['ip'],
                'server_id' => $server_data[0]['id']
            ], $station_data['id']);

         }
        
        /**
         * getDomainInfo
         * Gets the name of the stations domain
         *
         * @param  mixed $host IP address of the station
         * @param  mixed $password ssh password
         *
         * @return void
         */
        public function getDomainInfo($host, $password) {
            if($this->ping($host)) {
                $ssh = new Net_SSH2($host);
                if (!$ssh->login('root', $password)) {
                    exit('Login Failed');
                }
                $domain_name = $ssh->exec('domainname');
                return $domain_name;
            }
            else {
                return "Could not connect";
            }
        }
        
        /**
         * ping IP addres to check if the host is up.
         * Returns true if ping is successfull and false if otherwise
         *
         * @param  mixed $host IP addres you want to ping
         *
         * @return void
         * 
         * TODO: change the commad to: #nc -z hostname 22 > /dev/null
         * 
         */
        public function ping($host, $timeout = "0.2") {
            exec("timeout $timeout ping -c1 $host", $output, $result);  // Set the timeout for 0.2 seconds for a quicker ping
            if($result === 0) {
                return true;
            }
            else {
                return false;
            }
        }
    }
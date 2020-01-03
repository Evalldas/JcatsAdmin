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


        /**
         * reboot
         *
         * @return void
         */
        public function reboot() {
            // Get data from the POST
            $stations = $this->input->post('stations'); // Array of stations
            $password = $this->input->post('password'); // A password for SSH
            $counter = 0; // Counter for array loop
            foreach ($stations as $station) {
                // Check the connection first
                if($this->ping($station['ip'])) {
                    $ssh = new Net_SSH2($station['ip']); // Set the IP to SSH
                    if (!$ssh->login('root', $password)) {
                        // If connection is not successful set response record accordingly
                        $response[$counter] = [
                            'station' => $station['name'],
                            'status' => "failed",
                            'message' => "Could not establish the connection, please check the password"
                        ];
                    } else{
                        $ssh->exec('reboot');
                        $response[$counter] = [
                        'station' => $station['name'],
                        'status' => "success",
                        'message' => "Station was rebooted successfully"
                    ];
                    }
                }
                else {
                    // If host does not respond to ping
                    $response[$counter] = [
                        'station' => $station['name'],
                        'status' => "failed",
                        'message' => "There's no network connection to that station"
                    ];
                }
                $counter++;
            }
            // Set output data type to json
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['result' => $response]));
            return null;
        }

        /**
         * 
         *
         * @return void
         */
        public function installJcats() {
            // Get data from the POST
            $stations = $this->input->post('stations'); // Array of stations
            $password = $this->input->post('password'); // A password for SSH
            $server_ip = $this->input->post('server'); // A IP for the directory mounting
            $counter = 0; // Counter for array loop
            // Connect to the server and check JCATS version
            $ssh2 = new Net_SSH2($server_ip);
            $ssh2->login('root', $password);
            $jcats_v = $ssh2->exec('ls /home/jcats/');
            $jcats_v = trim($jcats_v);
            //-----------------------------------------------
            foreach ($stations as $station) {
                // Check the connection first
                if($this->ping($station['ip'])) {
                    $ssh = new Net_SSH2($station['ip']); // Set the IP to SSH
                    if (!$ssh->login('root', $password)) {
                        // If connection is not successful set response record accordingly
                        $response[$counter] = [
                            'station' => $station['name'],
                            'status' => "failed",
                            'message' => "Could not establish the connection, please check the password"
                        ];
                    } else{
                        // Check if station already has jcats on it
                        $check_jcats = $ssh->exec('ls /home/jcats/');
                        if(!$check_jcats)  {
                            // Make JCATS directory
                            $ssh->exec('mkdir /home/jcats');
                            // Mount servers JCATS directory
                            $ssh->exec("mount $server_ip:/home/jcats /home/jcats");
                            // Check the hostname of the station
                            $name = $ssh->exec('hostname');
                            // Execute the installation
                            $output = $ssh->exec("/home/jcats/$jcats_v/sysadm/utils/quick_start_client -d | tee /root/qsc-$name.txt");
                            // Fill in the response
                            $response[$counter] = [
                                'station' => $station['name'],
                                'status' => "success",
                                'message' => "Jcats was installed successfuly"
                            ];
                        } else {
                            $response[$counter] = [
                                'station' => $station['name'],
                                'status' => "failed",
                                'message' => "Jcats is already installed on the system"
                            ];
                        }
                    }
                }
                else {
                    // If host does not respond to ping
                    $response[$counter] = [
                        'station' => $station['name'],
                        'status' => "failed",
                        'message' => "There's no network connection to that station"
                    ];
                }
                $counter++;
            }
            // Set output data type to json
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['result' => $response]));
            return null;
        }

        /**
         * reboot
         *
         * @return void
         */
        public function removeJcats() {
            // Get data from the POST
            $stations = $this->input->post('stations'); // Array of stations
            $password = $this->input->post('password'); // A password for SSH
            $counter = 0; // Counter for array loop
            foreach ($stations as $station) {
                // Check the connection first
                if($this->ping($station['ip'])) {
                    $ssh = new Net_SSH2($station['ip']); // Set the IP to SSH
                    if (!$ssh->login('root', $password)) {
                        // If connection is not successful set response record accordingly
                        $response[$counter] = [
                            'station' => $station['name'],
                            'status' => "failed",
                            'message' => "Could not establish the connection, please check the password"
                        ];
                    } else{
                        // Check the JCATS version on the mashine
                        $jcats_v = $ssh->exec('ls /home/jcats/');
                        if ($jcats_v) {
                            $jcats_v = trim($jcats_v);
                            // Run the putback client script
                            $ssh->exec("echo -ne '\n' | /home/jcats/$jcats_v/sysadm/utils/putback.client");
                            // Reboot the machine after
                            $ssh->exec('reboot');
                            // Fill in the response
                            $response[$counter] = [
                                'station' => $station['name'],
                                'status' => "success",
                                'message' => "Jcats unninstalled successfuly"
                            ];
                        } else {
                            // If station doesn't have a JCATS on it
                            $response[$counter] = [
                                'station' => $station['name'],
                                'status' => "failed",
                                'message' => "Jcats is not installed on the system"
                            ];
                        }
                    }
                }
                else {
                    // If host does not respond to ping
                    $response[$counter] = [
                        'station' => $station['name'],
                        'status' => "failed",
                        'message' => "There's no network connection to that station"
                    ];
                }
                $counter++;
            }
            // Set output data type to json
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['result' => $response]));
            return null;
        }
/**
         * switchServer
         *
         * @return void
         */
        public function switchServer() {
            // Get data from the POST
            $stations = $this->input->post('stations'); // Array of stations
            $password = $this->input->post('password'); // A password for SSH
            $server_ip = $this->input->post('server'); // A IP for the directory mounting
            $new_server_data = $this->server_model->getServerByIpAddress($server_ip);
            $new_server = $new_server_data[0]['name'];
            $new_domain = $new_server_data[0]['domain_name'];
            $counter = 0; // Counter for array loop
            // Connect to the server and check JCATS version
            $ssh2 = new Net_SSH2($server_ip);
            $ssh2->login('root', $password);
            //find out the jcats version
            $jcats_v = $ssh2->exec('ls /home/jcats/');
            $jcats_v = trim($jcats_v);
            //-----------------------------------------------
            foreach ($stations as $station) {
                // Check the connection first
                if($this->ping($station['ip'])) {
                    $ssh = new Net_SSH2($station['ip']); // Set the IP to SSH
                    $domain_name = trim($ssh->exec('domainname'));
                    $old_server_data = $this->server_model->getServerByDomainName($domain_name);
                    $old_server = $old_server_data[0]['name'];
                    if (!$ssh->login('root', $password)) {
                        // If connection is not successful set response record accordingly
                        $response[$counter] = [
                            'station' => $station['name'],
                            'status' => "failed",
                            'message' => "Could not establish the connection, please check the password"
                        ];
                    } else{
                        // Check if station already has jcats on it
                        $check_jcats = $ssh->exec('ls /home/jcats/');
                        if(!$check_jcats)  {
                            $response[$counter] = [
                                'station' => $station['name'],
                                'status' => "failed",
                                'message' => "Jcats is not installed on the system or simulation failed to mount"
                            ];
                        } else {
                            // Stop ypbind & xinetd
                            $ssh->exec('service ypbind stop');
                            $ssh->exec('service xinetd stop');

                            // Unmount the two mountpoints from the old server
                            $ssh->exec('umount -l /home/jcats');
                            $ssh->exec('umount -l /home');

                            // Update the /etc/fstab
                            //$ssh->exec('sed -e "s#'.$old_server.':/home/jcats[[:blank:]]#'.$new_server.':/home/jcats #g" \
                            //-e "s#'.$old_server.':/home[[:blank:]]#'.$new_server.':/home #g" /etc/fstab > /tmp/fstab');

                            $ssh->exec("sed -i -e '16s+.*+$new_server:/home/jcats	/home/jcats	nfs bg,soft,rsize=32767,wsize=32767,timeo=14,nosuid,nodev 0 0+g' /etc/fstab");
                            $ssh->exec("sed -i -e '17s+.*+$new_server:/home	/home	nfs bg,soft,rsize=32767,wsize=32767,timeo=14,nosuid,nodev 0 0+g' /etc/fstab");
                            //$ssh->exec("mv -f /tmp/fstab /etc/fstab");
                            $ssh->exec('chcon system_u:object_r:etc_t:s0 /etc/fstab');

                            // Mount back the directories from the new server
                            $ssh->exec('mount /home');
                            $ssh->exec('mount /home/jcats');

                            // Change the domainname
                            $ssh->exec('domainname '.$new_domain.'');

                            // Update the /etc/sysconfig/network file
                            $ssh->exec('sed -e "/NISDOMAIN/D" /etc/sysconfig/network > /tmp/network');
                            $ssh->exec('chmod 644 /tmp/network');
                            $ssh->exec('chown root:root /tmp/network');
                            $ssh->exec('mv -f /tmp/network /etc/sysconfig/network');
                            $ssh->exec('echo "NISDOMAIN='.$new_domain.'" >> /etc/sysconfig/network');

                            // Start services
                            $ssh->exec('service ypbind start');
                            $ssh->exec('service xinetd start');

                            // Reboot the computer
                            //$ssh->exec('reboot');
                            $response[$counter] = [
                                'station' => $station['name'],
                                'status' => "success",
                                'message' => "Successfuly switched the domain server"
                            ];
                        }
                    }
                }
                else {
                    // If host does not respond to ping
                    $response[$counter] = [
                        'station' => $station['name'],
                        'status' => "failed",
                        'message' => "There's no network connection to that station"
                    ];
                }
                $counter++;
            }
            // Set output data type to json
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['result' => $response]));
            return null;
        }


         /**
          * updateStationInfo
          * Gets info about all stations and updates the database with it.
          * 
          * TODO: Make it skip connection to the daatabae if connection was not successful
          *
          * @param  mixed $station_data
          * @param  mixed $password
          *
          * @return void
          */
         public function updateStationInfo() {
             $station_data = $this->client_model->get();
             $password = "Dragon01";
             foreach ($station_data as $station) {
                 // Variables
                $domain_name = trim($this->getDomainInfo($station['ip'], $password));
                $mtu = trim($this->getMtuInfo($station['ip'], $password));
                $server_data = $this->server_model->getServerByDomainName($domain_name);
                print_r($station['name'] . $domain_name);
                if($server_data && $mtu) {
                    $result = $this->client_model->update([
                   'name' => $station['name'],
                   'ip' => $station['ip'],
                   'server_id' => $server_data[0]['id'],
                   'mtu' => $mtu
               ], $station['id']);
                }
             }

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
                $domain_name = $ssh->exec('nisdomainname');
                return $domain_name;
            }
            else {
                return null;
            }
        }
        
        public function getMtuInfo($host, $password) {
            if($this->ping($host)) {
                $ssh = new Net_SSH2($host);
                if (!$ssh->login('root', $password)) {
                    exit('Login Failed');
                }
                // Find the default network interface used by the mashine
                $default_interface = trim($ssh->exec('route | grep "^default" | grep -o "[^ ]*$" | head -n1'));
                // Find mtu of that interface
                $mtu = $ssh->exec("cat /sys/class/net/$default_interface/mtu");
                return $mtu;
            }
            else {
                return null;
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

        function statusUpdate() {
            $stations = $this->client_model->get();
            foreach ($stations as $station) {
                $host = $station['ip'];
                exec("timeout 0.2 ping -c1 $host", $output, $result);
                if($result === 0) {
                    $this->client_model->update([
                        'status' => 1
                    ], $station['id']);
                } else {
                    $this->client_model->update([
                        'status' => 0
                    ], $station['id']);
                }
            }
        }

        public function changeMtu() {
            $stations = $this->input->post('stations'); // Array of stations
            $password = $this->input->post('password'); // A password for SSH
            $mtu = $this->input->post('mtu');
            $counter = 0; // Counter for array loop
            foreach ($stations as $station) {
                // Check the connection first
                if($this->ping($station['ip'])) {
                    $ssh = new Net_SSH2($station['ip']); // Set the IP to SSH
                    if (!$ssh->login('root', $password)) {
                        // If connection is not successful set response record accordingly
                        $response[$counter] = [
                            'station' => $station['name'],
                            'status' => "failed",
                            'message' => "Could not establish the connection, please check the password"
                        ];
                    } else{
                        $default_interface = trim($ssh->exec('route | grep "^default" | grep -o "[^ ]*$" | head -n1'));
                        $ssh->exec("ifconfig $default_interface mtu $mtu");
                        $response[$counter] = [
                            'station' => $station['name'],
                            'status' => "success",
                            'message' => "Successfully changes MTU size of the station"
                        ];
                    }
                }
                else {
                    // If host does not respond to ping
                    $response[$counter] = [
                        'station' => $station['name'],
                        'status' => "failed",
                        'message' => "There's no network connection to that station"
                    ];
                }
                $counter++;
            }
            // Set output data type to json
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['result' => $response]));
            return null;
        }

    }
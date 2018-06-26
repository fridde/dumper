<?php
	namespace Fridde;

	class Dumper extends \MySQLDump
	{
	    public $conn_settings;
		public $dumped_file_name;
		public $local_connection;

		public function __construct($settings = null){
			$this->setConfiguration($settings);
			$c = $this->conn_settings;
			$this->dumped_file_name =  $c['db_name'] . '_' . date('Y-m-d') . '.sql';
			$this->local_connection = new \mysqli($c['db_host'], $c['db_username'], $c['db_password'], $c['db_name']);
			parent::__construct($this->local_connection);
		}

		private function setConfiguration($settings = null)
		{
			$local_conn_settings = $settings ?? ($GLOBALS['SETTINGS'] ?? false);
			if($local_conn_settings === false){
				throw new \Exception('No settings given or found in the global scope');
			}

            $this->conn_settings = $local_conn_settings['Connection_Details'];
        }

		public function export()
		{
			$this->save('backup/' . $this->dumped_file_name);
		}

		public function import()
		{
			$sql_text = file_get_contents('temp/' . $this->dumped_file_name);
			$result = $this->local_connection->multi_query($sql_text);
			echo $result;
		}

	}

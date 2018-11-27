<?php
	namespace Fridde;

	use Carbon\Carbon;

    class Dumper extends \MySQLDump
	{
	    public $conn_settings;
        public $backup_dir;
		public $dumped_file_name;
		public $local_connection;

		public function __construct(array $settings){
			$this->setConfiguration($settings);
			$c = $this->conn_settings;
			$this->dumped_file_name =  Carbon::today()->toDateString() . '_' . $c['db_name'] . '.sql';
			$this->local_connection = new \mysqli($c['db_host'], $c['db_username'], $c['db_password'], $c['db_name']);
			parent::__construct($this->local_connection);
		}

		private function setConfiguration(array $settings)
		{
		    $this->backup_dir = $settings['backup_dir'];
            $this->conn_settings = $settings['Connection_Details'];
        }

		public function export()
		{
			$this->save($this->backup_dir . '/' . $this->dumped_file_name);
		}

		public function import()
		{
			$sql_text = file_get_contents($this->backup_dir . '/../temp/' . $this->dumped_file_name);
			$result = $this->local_connection->multi_query($sql_text);
			echo $result;
		}

	}

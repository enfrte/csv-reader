<?php 
// https://en.wikipedia.org/wiki/Comma-separated_values#Example
// http://localhost/csv-reader/

class CSVReader 
{
	protected $file;
	protected $separator;
	protected $tables;
	protected $line_number;
	protected $previous_table;
	protected $end_of_file;
	
	public function __construct($file, $separator)
	{
		$this->file = $file;
		$this->separator = $separator;
		$this->tables = new StdClass();
	}

	public function setTable($name, $line_number_start, $line_number_end = false) : self
	{
		$this->tables->$name = new StdClass();
		$this->tables->$name->line_number_start = $line_number_start;
		$this->previous_table = $name;
		if ($line_number_end) {
			$this->tables->$name->line_number_end = $line_number_end;
		}
		else { $this->tables->$name->line_number_end = $line_number_start; }
		return $this;
	}

    public function setTableColumns($columns) : self
    {
		$this->tables->{$this->previous_table}->columns = $columns;
		$this->populateTableData($this->previous_table);
        return $this;
    }

	public function theLastLine()
	{
		return $this->setLastLineOfFileNumber();
	}

	private function setLastLineOfFileNumber()
	{
		$linecount = 0;
		$handle = fopen($this->file, "r");

		while(!feof($handle)){
			$line = fgets($handle);
			$linecount++;
		}

		fclose($handle);
		return $linecount;
	}

	public function populateTableData($table)
	{
		$this->tables->$table->column_data = [];
		$line_number_start = $this->tables->$table->line_number_start;
		$line_number_end = $this->tables->$table->line_number_end;
		$line_number = 1;

		if (($handle = fopen($this->file, "r")) !== FALSE) {
			// Iterate lines
			while (($line_data = fgetcsv($handle, 1000, $this->separator)) !== FALSE) {
				$column_total = count($line_data); // Number of columns per line
				// Find the line range to start to copy data
				if ($line_number >= $line_number_start && $line_number <= $line_number_end) {
					for ($column_index = 0; $column_index < $column_total; $column_index++) {
						$this->tables->$table->column_data[$line_number][$column_index] = $line_data[$column_index];
					}
				}
				$line_number++;
			}
			fclose($handle);
		}
		return;
	}

	public function queryTable($table, $column_names = [])
	{
		$table_obj = $this->tables->$table;
		$result = [];

		foreach ($table_obj->column_data as $cd_key => $cd_value) {
			foreach ($table_obj->columns as $column_key => $column_value) {
				if (in_array($column_key, $column_names)) {
					$result[$cd_key][$column_key] = $cd_value[$column_value - 1];
				}
			}
		}
		return $result;
	}

}

# CSV Reader

Read some CSV data and prepare it for database inserting. 

Example data

	Year,Make,Model,Description,Price
	Title, "My database"
	1997,Ford,E350,"ac, abs, moon",3000.00
	1999,Chevy,"Venture ""Extended Edition""","",4900.00
	1999,Chevy,"Venture ""Extended Edition, Very Large""",,5000.00
	1996,Jeep,Grand Cherokee,"MUST SELL! air, moon roof, loaded",4799.00

Usage example

	$foo = new CSVReader('database.csv', ',');
	$foo->setTable('details', 2)
		->setTableColumns(['title' => 2])
		->setTable('car_data', 3, $foo->theEnd())
		->setTableColumns([
			"Year" => 1, 
			"Make" => 2, 
			"Model" => 3, 
			"Description" => 4, 
			"Price"  => 5
		]);

	// Query the CSV file
	echo '<pre>';
	print_r($foo);
	$bar = $foo->queryTable('details', ['title']);
	$bar = $foo->queryTable('car_data', ['Year', 'Price']);

### To-do

Make it a module.

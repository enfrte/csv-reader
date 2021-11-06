# CSV Reader

Read some CSV data and prepare it for database inserting. 

Example CSV data

	Year,Make,Model,Description,Price
	Title, "My database"
	1997,Ford,E350,"ac, abs, moon",3000.00
	1999,Chevy,"Venture ""Extended Edition""","",4900.00
	1999,Chevy,"Venture ""Extended Edition, Very Large""",,5000.00
	1996,Jeep,Grand Cherokee,"MUST SELL! air, moon roof, loaded",4799.00

Usage example

	<?php 
	$my_database = new CSVReader('database.csv', ','); // Load the DB and set the delimiter
	// Give a name to your table and specify the line in the CSV file where the data begings and ends. If the end line is omitted, it is assumed the start and end line are the same.
	$my_database->setTable('details', 2) 
		->setTableColumns(['title' => 2]) // Give a name to your column and position of where the data is. 
		->setTable('car_data', 3, $my_database->theLastLine()) // Optionally add more tables and columns. 
		->setTableColumns([
			"Year" => 1, // First row (No zero indexing)
			"Make" => 2, 
			"Model" => 3, 
			"Description" => 4, 
			"Price"  => 5
		]);

	// Query the CSV file
	echo '<pre>';
	print_r($my_database); // This will give you an idea how the data set is structured.
	$bar = $my_database->queryTable('details', ['title']);
	$baz = $my_database->queryTable('car_data', ['Year', 'Price']); // Select the first and last columns from the car_data table
	?>

### To-do

Make it a module.

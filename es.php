<?php
	use Elasticsearch\ClientBuilder;
	require 'vendor/autoload.php'; 

	class esClass {

		private $client;

		public function __construct() {
			$this->client = ClientBuilder::create()->build();
		}

		/**
		* insert specific document (row) into specific type (table) in specific index (database)
		*
		* @param string $indexName    	name of index (database)
		* @param string $indexType    	type of index (table)
		* @param int $documentId    	id of document (row)
		* @param array $documentBody  	array with document body (collumn) consist of key (collumn name) and value (collumn value)
		* @param string $queryFor    	searching string
		*
		* @return array 		 		elasticsearch response
		*/
		public function index($indexName, $indexType, $documentId, $documentBody) {
			$params = [
				'index' => $indexName,
				'type' => $indexType,
				'id' => $documentId,
				'body' => $documentBody
			];
			
			return $this->client->index($params);
		}

			/**
		   	* delete specific document (row) from specific type (table) from specific index (database)
		   	*
		   	* @param string $indexName    	name of index (database)
		   	* @param string $indexType    	type of index (table)
		   	* @param int $documentId    	id of document (row)
		   	*
		   	* @return array 		 		elasticsearch response   
		   	*/
			public function delete($indexName, $indexType, $documentId) {
				$params = [
				    'index' => $indexName,
				    'type' => $indexType,
				    'id' => $documentId,
				];

				return $this->client->delete($params);
			}

	    	/**
		   	* search for query in specified index (database)
		   	*
		   	* @param string $indexName    	name of index (database)
		   	* @param string $indexType    	type of index (table)
		   	* @param string $queryType    	type of query (regexp, match, matchall, ...)
		   	* @param string $queryWhere    	name of collumn (collumn)
		   	* @param string $queryFor    	searching string
		   	*
		   	* @return array 		 		elasticsearch response   
		   	*/
			public function search($indexName, $indexType, $queryType, $queryWhere, $queryFor) {
				$params = [
				    'index' => $indexName,
				    'type' => $indexType,
				    'body' => [
				        'query' => [
				            $queryType => [
				                $queryWhere => $queryFor
				            ]
				        ],
				        'size' => 1000,
				    ]
				];

				return $this->client->search($params);			
			}

			/**
		   	* fetch sql database into elasticserach index
		   	*
		   	* @param string $tableName    	name of sql database table
		   	*
		   	* @return array 		 		elasticsearch response   
		   	*/
			public function fetchSqlTable($host, $user, $pass, $database, $tableName) {
				// Create connection
			    $conn = new mysqli($host, $user, $pass, $database);
			    mysqli_set_charset($conn, 'utf-8');

			    // Set correct encoding
			    $sql = "SET NAMES utf8";
			    $result = $conn->query($sql); 

				$sql = "SELECT * FROM " . $tableName;
	  			$result = $conn->query($sql);
	  			$rowCounter = 0;
	  
	  			while($row = $result->fetch_assoc()) {    					  	
				  	$this->index($database, $tableName, $rowCounter, $row);
				  	$rowCounter++;
	  			}
	  		}			
		} 	

?>
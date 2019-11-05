# ElasticSearchSqlIndex
PHP class for indexing SQL tables and basic ES php API operations

$es = new esClass();

// Fetch
$es->fetchSqlTable("database_host", "database_user", "database_pass", "database_name", "database_table");
	  	
// Search
$response = $es->search("index_name", "type_name", "regexp", "document", ".*");
	  	
// Delete
$response = $es->delete("index_name", "type_name", "document_id");
	  	
// Index
$response = $es->index("index_name, "type_name", "document_id", $document_body_array);
	
// Print response result  	
print_r($response);
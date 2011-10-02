<?php
/**
 *	Include the API PHP file
 */
require('neo4j.class.php');

$graphDb = new Neo4j();
$graphDb->setBaseUri('http://localhost:7474/');

$firstNode = $graphDb->createNode();
$secondNode = $graphDb->createNode();
$thirdNode = $graphDb->createNode();




$firstNode->name = "Hello, ";
$firstNode->id = 123;
$firstNode->save();

$firstNode->id = NULL;	// Setting to null removes the property
$firstNode->save();


$secondNode->name = "world!";
$secondNode->id = 345;
$secondNode->save();

$thirdNode->id = 11;
$thirdNode->save();


/**
 *	Create a relationship between some nodes. These can also have attributes.
 *	Note: Relationships also need to be saved before they exist in the DB.
 */
$relationship = $firstNode->createRelationshipTo($secondNode, 'KNOWS');
$relationship->name = "brave Neo4j";
$relationship->id = 222;
$relationship->save();

$relationship->id = NULL; // Setting to NULL removed the property
$relationship->save();

dump_node($firstNode);
dump_node($secondNode);
dump_node($thirdNode);


/**
 *	Perform Cypher Query
 */
$script = 'START a = ('.$secondNode->getId().') MATCH (a)<-->(x) RETURN x';
$res = $graphDb->performCypherQuery($script);

var_dump($res);


/**
 *	A little utility function to display a node
 */
function dump_node($node)
{
	$rels = $node->getRelationships();
	
	echo 'Node '.$node->getId()."\t\t\t\t\t\t\t\t".json_encode($node->getProperties())."\n";
	
	foreach($rels as $rel)
	{
		$start = $rel->getStartNode();
		$end = $rel->getEndNode();
		
		echo 	"  Relationship ".$rel->getId()."  :  Node ".$start->getId()." ---".$rel->getType()."---> Node ".$end->getId(),
				"\t\t\t\t\t\t\t\t".json_encode($rel->getProperties())."\n";
	}
}

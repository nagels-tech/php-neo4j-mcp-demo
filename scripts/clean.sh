#!/bin/bash

# Comprehensive Neo4j database cleanup script
# This removes all nodes, relationships, constraints, and user-created indexes

echo "Cleaning Neo4j database completely..."

# Function to execute Cypher queries
execute_cypher() {
    local query="$1"
    echo "Executing: $query"
    docker compose exec neo4j cypher-shell -u neo4j -p password "$query"
}

# Function to execute Cypher queries and return output
execute_cypher_output() {
    local query="$1"
    docker compose exec neo4j cypher-shell -u neo4j -p password --format plain "$query"
}

# Check if Neo4j is running
if ! docker compose ps neo4j | grep -q "Up"; then
    echo "Starting Neo4j service..."
    docker compose up -d neo4j
    echo "Waiting for Neo4j to be ready..."
    sleep 15
fi

echo "Getting and dropping all constraints..."
# Get constraint names and drop them one by one
constraint_names=$(execute_cypher_output "SHOW CONSTRAINTS YIELD name RETURN name" | grep -v "^name$" | grep -v "^+.*+$" | grep -v "^$" | sed 's/^"\(.*\)"$/\1/' | tr -d ' ')

if [ ! -z "$constraint_names" ]; then
    echo "Found constraints to drop:"
    echo "$constraint_names"
    echo "$constraint_names" | while read -r constraint_name; do
        if [ ! -z "$constraint_name" ]; then
            echo "Dropping constraint: $constraint_name"
            execute_cypher "DROP CONSTRAINT \`$constraint_name\`;"
        fi
    done
else
    echo "No constraints found to drop."
fi

echo "Getting and dropping all user-created indexes..."
# Get index names (excluding system LOOKUP indexes) and drop them
index_names=$(execute_cypher_output "SHOW INDEXES YIELD name, type WHERE type <> 'LOOKUP' RETURN name" | grep -v "^name$" | grep -v "^+.*+$" | grep -v "^$" | sed 's/^"\(.*\)"$/\1/' | tr -d ' ')

if [ ! -z "$index_names" ]; then
    echo "Found indexes to drop:"
    echo "$index_names"
    echo "$index_names" | while read -r index_name; do
        if [ ! -z "$index_name" ]; then
            echo "Dropping index: $index_name"
            execute_cypher "DROP INDEX \`$index_name\`;"
        fi
    done
else
    echo "No user-created indexes found to drop."
fi

echo "Removing all nodes and relationships..."
execute_cypher "MATCH (n) DETACH DELETE n;"

echo ""
echo "Database cleaned successfully!"
echo "- All constraints have been dropped"
echo "- All user-created indexes have been dropped (system indexes preserved)"
echo "- All nodes and relationships have been removed" 
#!/bin/bash

# Script to load Neo4j data from Cypher script
# Assumes Neo4j container is already running

echo "Loading movies data from Cypher script..."

# Load the Cypher script using cypher-shell
echo "Executing movies.cypher script..."
docker compose exec neo4j cypher-shell -u neo4j -p password -f /scripts/movies.cypher -d neo4j

if [ $? -eq 0 ]; then
    echo "✅ Movies data loaded successfully!"
    echo "You can access Neo4j Browser at http://localhost:7474"
    echo "Username: neo4j, Password: password"
    echo ""
    echo "Try running some queries:"
    echo "  MATCH (m:Movie) RETURN m.title LIMIT 10"
    echo "  MATCH (p:Person)-[:ACTED_IN]->(m:Movie) RETURN p.name, m.title LIMIT 10"
else
    echo "❌ Error loading Cypher script. Please check the Neo4j container is running and the script file exists."
fi

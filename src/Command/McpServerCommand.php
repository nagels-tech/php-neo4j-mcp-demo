<?php

namespace App\Command;

use App\Data\Common\Constants;
use App\Data\Content\TextContent;
use App\Data\Initialization\Implementation;
use App\Data\Initialization\ResourcesCapability;
use App\Data\Initialization\ServerCapabilities;
use App\Data\Initialization\ToolsCapability;
use App\Data\Resources\Resource;
use App\Data\Resources\TextResourceContents;
use App\Data\Results\InitializeResult;
use App\Data\Tools\Tool;
use App\Data\Tools\ToolAnnotations;
use Laudis\Neo4j\Contracts\SessionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'mcp:server',
    description: 'Run the Neo4j MCP server using stdio protocol'
)]
class McpServerCommand extends Command
{
    private bool $shouldContinue = true;

    public function __construct(
        private readonly SessionInterface $session,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Disable output buffering to ensure immediate response
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set up signal handlers for graceful shutdown
        $this->setupSignalHandlers();

        $this->logger->info('Starting MCP server');

        // Read from stdin line by line
        while ($this->shouldContinue && ($line = fgets(STDIN)) !== false) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            $data = null;
            try {
                $data = json_decode($line, true, 512, JSON_THROW_ON_ERROR);

                $this->logger->info('Received MCP request', ['data' => $data]);

                $response = $this->handleMcpRequest($data);

                $this->logger->info('Sending MCP response', ['response' => $response]);
                $this->writeResponse($response);
            } catch (\JsonException $e) {
                $this->logger->error('JSON parsing failed', ['error' => $e->getMessage(), 'line' => $line]);
                $this->writeError(
                    null,
                    Constants::INVALID_REQUEST,
                    'Invalid JSON format: ' . $e->getMessage()
                );
            } catch (\Exception $e) {
                $this->logger->error('Request handling failed', [
                    'error' => $e->getMessage(),
                    'id' => $data['id'] ?? null
                ]);
                $this->writeError(
                    $data['id'] ?? null,
                    Constants::INTERNAL_ERROR,
                    $e->getMessage()
                );
            }
        }

        $this->logger->info('MCP server shutting down');
        return Command::SUCCESS;
    }

    private function handleMcpRequest(array $data): array
    {
        $this->logger->debug('Handling MCP request', ['method' => $data['method'] ?? 'unknown', 'id' => $data['id'] ?? null]);

        // Validate JSON-RPC 2.0 structure
        if (!$data || !is_array($data)) {
            $this->logger->error('Invalid request format: not an array');
            throw new \InvalidArgumentException('Invalid request format');
        }

        if (!isset($data['jsonrpc']) || $data['jsonrpc'] !== '2.0') {
            $this->logger->error('Invalid JSON-RPC version', ['version' => $data['jsonrpc'] ?? 'missing']);
            throw new \InvalidArgumentException('Invalid JSON-RPC version');
        }

        if (!isset($data['method']) || !is_string($data['method'])) {
            $this->logger->error('Missing or invalid method', ['method' => $data['method'] ?? 'missing']);
            throw new \InvalidArgumentException('Missing or invalid method');
        }

        // For requests (not notifications), id is required
        if (!isset($data['id'])) {
            $this->logger->error('Missing request id');
            throw new \InvalidArgumentException('Missing request id');
        }

        return match ($data['method']) {
            'initialize' => $this->handleInitialize($data),
            'resources/list' => $this->handleListResources($data),
            'resources/read' => $this->handleReadResource($data),
            'tools/list' => $this->handleListTools($data),
            'tools/call' => $this->handleCallTool($data),
            'ping' => $this->handlePing($data),
            default => throw new \InvalidArgumentException("Method '{$data['method']}' not found")
        };
    }

    private function handleInitialize(array $data): array
    {
        $this->logger->info('Handling initialize request', ['id' => $data['id']]);

        // Validate required parameters
        if (!isset($data['params'])) {
            $this->logger->error('Missing params for initialize request');
            throw new \InvalidArgumentException('Missing params for initialize request');
        }

        $params = $data['params'];
        if (!isset($params['protocolVersion']) || !isset($params['capabilities']) || !isset($params['clientInfo'])) {
            $this->logger->error('Missing required parameters for initialize', [
                'has_protocolVersion' => isset($params['protocolVersion']),
                'has_capabilities' => isset($params['capabilities']),
                'has_clientInfo' => isset($params['clientInfo'])
            ]);
            throw new \InvalidArgumentException('Missing required parameters: protocolVersion, capabilities, or clientInfo');
        }

        $this->logger->info('Initialize request successful', [
            'client_protocol_version' => $params['protocolVersion'],
            'client_info' => $params['clientInfo']
        ]);

        $result = new InitializeResult(
            protocolVersion: Constants::LATEST_PROTOCOL_VERSION,
            capabilities: new ServerCapabilities(
                resources: new ResourcesCapability(subscribe: false, listChanged: false),
                tools: new ToolsCapability(listChanged: false)
            ),
            serverInfo: new Implementation('Neo4j MCP Server', '1.0.0'),
            instructions: 'This server provides access to a Neo4j database. Use the schema resource to understand the database structure, and the query tool to execute Cypher queries.'
        );

        return [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $data['id'],
            'result' => $result->jsonSerialize()
        ];
    }

    private function handleListResources(array $data): array
    {
        $this->logger->debug('Handling list resources request', ['id' => $data['id']]);

        $resources = [
            new Resource(
                uri: 'neo4j://schema',
                name: 'Database Schema',
                description: 'Neo4j database schema including node labels, relationship types, and property keys',
                mimeType: 'application/json'
            )
        ];

        return [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $data['id'],
            'result' => [
                'resources' => array_map(fn($resource) => $resource->jsonSerialize(), $resources)
            ]
        ];
    }

    private function handleReadResource(array $data): array
    {
        $this->logger->debug('Handling read resource request', ['id' => $data['id']]);

        if (!isset($data['params']) || !isset($data['params']['uri'])) {
            $this->logger->error('Missing required parameter: uri');
            throw new \InvalidArgumentException('Missing required parameter: uri');
        }

        $uri = $data['params']['uri'];
        $this->logger->info('Reading resource', ['uri' => $uri]);

        if ($uri !== 'neo4j://schema') {
            $this->logger->error('Resource not found', ['uri' => $uri]);
            throw new \InvalidArgumentException("Resource not found: $uri");
        }

        // Get database schema information
        $this->logger->debug('Retrieving database schema');
        $schema = $this->getDatabaseSchema();

        $content = new TextResourceContents(
            uri: $uri,
            text: json_encode($schema, JSON_PRETTY_PRINT),
            mimeType: 'application/json'
        );

        return [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $data['id'],
            'result' => [
                'contents' => [$content->jsonSerialize()]
            ]
        ];
    }

    private function handleListTools(array $data): array
    {
        $this->logger->debug('Handling list tools request', ['id' => $data['id']]);

        $tools = [
            new Tool(
                name: 'run_cypher_query',
                inputSchema: [
                    'type' => 'object',
                    'properties' => [
                        'query' => [
                            'type' => 'string',
                            'description' => 'The Cypher query to execute'
                        ],
                        'parameters' => [
                            'type' => 'object',
                            'description' => 'Optional parameters for the query',
                            'additionalProperties' => true
                        ]
                    ],
                    'required' => ['query']
                ],
                description: 'Execute a Cypher query against the Neo4j database and return the results',
                annotations: new ToolAnnotations(
                    title: 'Run Cypher Query',
                    readOnlyHint: false,
                    destructiveHint: true,
                    idempotentHint: false
                )
            )
        ];

        return [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $data['id'],
            'result' => [
                'tools' => array_map(fn($tool) => $tool->jsonSerialize(), $tools)
            ]
        ];
    }

    private function handleCallTool(array $data): array
    {
        $this->logger->debug('Handling call tool request', ['id' => $data['id']]);

        if (!isset($data['params']) || !isset($data['params']['name'])) {
            $this->logger->error('Missing required parameter: name');
            throw new \InvalidArgumentException('Missing required parameter: name');
        }

        $toolName = $data['params']['name'];
        $arguments = $data['params']['arguments'] ?? [];

        $this->logger->info('Calling tool', ['tool_name' => $toolName]);

        if ($toolName !== 'run_cypher_query') {
            $this->logger->error('Tool not found', ['tool_name' => $toolName]);
            throw new \InvalidArgumentException("Tool not found: $toolName");
        }

        $query = $arguments['query'] ?? '';
        $parameters = $arguments['parameters'] ?? [];

        $this->logger->info('Executing Cypher query', [
            'query' => $query,
            'has_parameters' => !empty($parameters)
        ]);

        if (empty($query)) {
            $this->logger->warning('Empty query provided');
            return [
                'jsonrpc' => Constants::JSONRPC_VERSION,
                'id' => $data['id'],
                'result' => [
                    'content' => [
                        (new TextContent('Error: Query parameter is required'))->jsonSerialize()
                    ],
                    'isError' => true
                ]
            ];
        }

        try {
            $result = $this->session->run($query, $parameters);
            $resultArray = $result->toRecursiveArray();

            $this->logger->info('Cypher query executed successfully', [
                'query_type' => $result->getSummary()->getQueryType(),
                'result_count' => count($resultArray)
            ]);

            $content = new TextContent(
                text: json_encode([
                    'query' => $query,
                    'parameters' => $parameters,
                    'results' => $resultArray,
                    'summary' => [
                        'query_type' => $result->getSummary()->getQueryType(),
                        'counters' => $result->getSummary()->getCounters()->toArray(),
                        'result_available_after' => $result->getSummary()->getResultAvailableAfter(),
                        'result_consumed_after' => $result->getSummary()->getResultConsumedAfter()
                    ]
                ], JSON_PRETTY_PRINT)
            );

            return [
                'jsonrpc' => Constants::JSONRPC_VERSION,
                'id' => $data['id'],
                'result' => [
                    'content' => [$content->jsonSerialize()],
                    'isError' => false
                ]
            ];
        } catch (\Exception $e) {
            $this->logger->error('Cypher query execution failed', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);

            return [
                'jsonrpc' => Constants::JSONRPC_VERSION,
                'id' => $data['id'],
                'result' => [
                    'content' => [
                        (new TextContent("Query execution failed: " . $e->getMessage()))->jsonSerialize()
                    ],
                    'isError' => true
                ]
            ];
        }
    }

    private function handlePing(array $data): array
    {
        $this->logger->debug('Handling ping request', ['id' => $data['id']]);

        return [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $data['id'],
            'result' => []
        ];
    }

    private function getDatabaseSchema(): array
    {
        $this->logger->debug('Retrieving database schema');

        try {
            // Get node labels
            $labelsResult = $this->session->run('CALL db.labels()');
            $labels = array_map(fn($record) => $record->get('label'), $labelsResult->toArray());

            // Get relationship types
            $relationshipTypesResult = $this->session->run('CALL db.relationshipTypes()');
            $relationshipTypes = array_map(fn($record) => $record->get('relationshipType'), $relationshipTypesResult->toArray());

            // Get property keys
            $propertyKeysResult = $this->session->run('CALL db.propertyKeys()');
            $propertyKeys = array_map(fn($record) => $record->get('propertyKey'), $propertyKeysResult->toArray());

            // Get constraints
            $constraintsResult = $this->session->run('SHOW CONSTRAINTS');
            $constraints = $constraintsResult->toArray();

            // Get indexes
            $indexesResult = $this->session->run('SHOW INDEXES');
            $indexes = $indexesResult->toArray();

            $this->logger->info('Database schema retrieved successfully', [
                'labels_count' => count($labels),
                'relationship_types_count' => count($relationshipTypes),
                'property_keys_count' => count($propertyKeys),
                'constraints_count' => count($constraints),
                'indexes_count' => count($indexes)
            ]);

            return [
                'labels' => $labels,
                'relationshipTypes' => $relationshipTypes,
                'propertyKeys' => $propertyKeys,
                'constraints' => $constraints,
                'indexes' => $indexes,
                'generated_at' => date('c')
            ];
        } catch (\Exception $e) {
            $this->logger->error('Failed to retrieve database schema', ['error' => $e->getMessage()]);

            return [
                'error' => 'Failed to retrieve schema: ' . $e->getMessage(),
                'generated_at' => date('c')
            ];
        }
    }

    private function writeResponse(array $response): void
    {
        echo json_encode($response) . "\n";
        flush();
    }

    private function writeError(?string $id, int $code, string $message): void
    {
        $error = [
            'jsonrpc' => Constants::JSONRPC_VERSION,
            'id' => $id,
            'error' => [
                'code' => $code,
                'message' => $message
            ]
        ];

        echo json_encode($error) . "\n";
        flush();
    }

    private function setupSignalHandlers(): void
    {
        // Check if pcntl extension is available
        if (!extension_loaded('pcntl')) {
            $this->logger->error('pcntl extension not available, cannot start MCP server without signal handling');
            throw new \RuntimeException('pcntl extension is required for signal handling. Please install the pcntl extension.');
        }

        // Set up signal handlers for graceful shutdown
        pcntl_signal(SIGINT, function ($signal) {
            $this->logger->info('Received SIGINT (Ctrl+C), shutting down gracefully');
            $this->shouldContinue = false;
        });

        pcntl_signal(SIGTERM, function ($signal) {
            $this->logger->info('Received SIGTERM, shutting down gracefully');
            $this->shouldContinue = false;
        });

        // Enable signal handling
        pcntl_async_signals(true);

        $this->logger->info('Signal handlers registered for SIGINT and SIGTERM');
    }
}
